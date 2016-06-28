<?php

/**
 * Mobi App Store Management Model
 *
 */
class Bookmodel extends CI_Model {

    private $bookTable = 'books';
    private $authorTable = 'authors';
    private $planTypeTable = 'plan_types';
    private $orderTable = 'orders';
    private $notificationTimeTable = 'notification_time';

    function __construct() {
        parent::__construct();
    }

    /**
     * get the Mobi App Store listing
     *
     */
    function get() {

        $aColumns = array("$this->bookTable.id",
            "$this->bookTable.title",
            "IF($this->bookTable.price = 0,IF($this->bookTable.combo_price = 0,$this->bookTable.audio_price,$this->bookTable.combo_price ),$this->bookTable.price )",
            "$this->planTypeTable.name",
            "$this->authorTable.name",
            "DATE_FORMAT($this->bookTable.publish_date,'" . DATE_FORMAT_SQL . "')",
            "$this->bookTable.status");
        $aColumnsAlias = array('id', 'title', 'price', 'plan_type','name', 'publish_date', 'status');
        
         /* Table*/
        $sTable = $this->bookTable;

        /* Indexed column (used for fast and accurate table cardinality) */
        $sIndexColumn = "$this->bookTable.id";

        /* Where (don't include 'WHERE') */
        $qWhere = "";
        
        /* Join */
        $sJoin = "LEFT JOIN $this->authorTable ON $this->authorTable.id = $this->bookTable.author_id";
        $sJoin .= " LEFT JOIN $this->planTypeTable ON $this->planTypeTable.id = $this->bookTable.plan_type_id";
        /*

         /* Paging
         */
        $sLimit = "";
        if (isset($_GET['iDisplayStart']) && $_GET['iDisplayLength'] != '-1') {
            $sLimit = "LIMIT " . intval($_GET['iDisplayStart']) . ", " .
                    intval($_GET['iDisplayLength']);
        }

        /*
         * Ordering
         */
        $sOrder = "";
        if (isset($_GET['iSortCol_0'])) {
            $sOrder = "ORDER BY  ";
            for ($i = 0; $i < intval($_GET['iSortingCols']); $i++) {
                if ($_GET['bSortable_' . intval($_GET['iSortCol_' . $i])] == "true") {
                	if($aColumns[intval($_GET['iSortCol_' . $i])] == "DATE_FORMAT($this->bookTable.publish_date,'" . DATE_FORMAT_SQL . "')")
                	$sOrder .= "" . $this->bookTable.".publish_date " .
                            ($_GET['sSortDir_' . $i] === 'asc' ? 'asc' : 'desc') . ", ";                            
                    $sOrder .= "" . $aColumns[intval($_GET['iSortCol_' . $i])] . " " .
                            ($_GET['sSortDir_' . $i] === 'asc' ? 'asc' : 'desc') . ", ";
                }
            }

            $sOrder = substr_replace($sOrder, "", -2);
            if ($sOrder == "ORDER BY") {
                $sOrder = "";
            }
        }

        /*
         * Filtering
         * NOTE this does not match the built-in DataTables filtering which does it
         * word by word on any field. It's possible to do here, but concerned about efficiency
         * on very large tables, and MySQL's regex functionality is very limited
         */
        $sWhere = "";
        if (isset($_GET['sSearch']) && $_GET['sSearch'] != "") {        	
            $sWhere = "WHERE (";
            for ($i = 0; $i < count($aColumns); $i++) {
                if (isset($_GET['bSearchable_' . $i]) && $_GET['bSearchable_' . $i] == "true") {
                    $sWhere .= "" . $aColumns[$i] . " LIKE '%" . mysql_real_escape_string($_GET['sSearch']) . "%' OR ";
                }
            }
            $sWhere = substr_replace($sWhere, "", -3);
            $sWhere .= ')';            
        }
        
        /* Include query condition*/
        if($qWhere != '' && $sWhere != '')
        $sWhere = $sWhere." AND ".$qWhere;
        elseif ($qWhere != '' && $sWhere == '')
        $sWhere = 'WHERE '.$qWhere; 

        /* Individual column filtering */
        for ($i = 0; $i < count($aColumns); $i++) {
            if (isset($_GET['bSearchable_' . $i]) && $_GET['bSearchable_' . $i] == "true" && $_GET['sSearch_' . $i] != '') {
                if ($sWhere == "") {
                    $sWhere = "WHERE ";
                } else {
                    $sWhere .= " AND ";
                }
                $sWhere .= "" . $aColumns[$i] . " LIKE '%" . mysql_real_escape_string($_GET['sSearch_' . $i]) . "%' ";
            }
        }

        /* Final columns with alias */
        foreach ($aColumns as $key => $value) {
            $fColumns[] = $value . ' AS ' . $aColumnsAlias[$key];
        }

        /*
         * SQL queries
         * Get data to display
         */
        $sQuery = "
		SELECT SQL_CALC_FOUND_ROWS " . str_replace(" , ", " ", implode(", ", $fColumns)) . "
		FROM   $sTable
		$sJoin
		$sWhere
		$sOrder
		$sLimit
		";        
        
        $qResult = $this->db->query($sQuery)->result_array();
		
        /* Data set length after filtering */
        $sQuery = "SELECT FOUND_ROWS() AS tot_rows";
        $aResultFilterTotal = $this->db->query($sQuery)->row_array();
        $iFilteredTotal = $aResultFilterTotal['tot_rows'];

        /* Total data set length */
        $sQuery = "SELECT COUNT(" . $sIndexColumn . ") AS tot FROM  $sTable";
        $aResultTotal = $this->db->query($sQuery)->row_array();
        $iTotal = $aResultTotal['tot'];

        /*
         * Output
         */
        $output = array(
            "sEcho" => intval($_GET['sEcho']),
            "iTotalRecords" => $iTotal,
            "iTotalDisplayRecords" => $iFilteredTotal,
            "aaData" => array()
        );

        foreach ($qResult as $aRow) {
            $row = array();
            for ($i = 0; $i < count($aColumnsAlias); $i++) {
                if ($aColumnsAlias[$i] == "version") {
                    /* Special output formatting for 'version' column */
                    $row[] = ($aRow[$aColumnsAlias[$i]] == "0") ? '-' : $aRow[$aColumnsAlias[$i]];
                } else if ($aColumnsAlias[$i] != ' ') {
                    /* General output */
                    $row[] = $aRow[$aColumnsAlias[$i]];
                }
            }
            $output['aaData'][] = $row;
        }

        return json_encode($output);
    }

    /**
     * get Mobi App Store details by its primary id
     *
     * @param int $id
     * @return details of Mobi App Store
     */
    function getById($id) {        
        return $this->db->get_where($this->bookTable, array('id' => $id))->row_array();
    }

    /**
     * add/edit the Mobi App Store details
     *
     * @param int $id
     */
    function save($id='',$filesize) {
    	if($this->input->post('plan_type_id') == '1') { //for Realitios of rhapsody (devotionals)
    		$authorId = 1;
    	} else { 
    		$authorId = $this->input->post('author_id');  //for Other books and holy bible
    	} 
		$combo_price = 0; 
		$audio_price = 0;
		$ebook_price = 0;
		if($this->input->post('content_type') == "ebook"){
		$ebook_price = $this->input->post('price');
		}
		elseif($this->input->post('content_type') == "audio"){
		$audio_price = $this->input->post('price');
		}
		elseif($this->input->post('content_type') == "ebook"){
		$combo_price = $this->input->post('price');
		}
		
		$mb= number_format($filesize/(1024*1024),2);
		
		if($mb == 0){
			
		$data = array(
	            'author_id' => $authorId,
	            'plan_type_id' => $this->input->post('plan_type_id'),
	            'title' => $this->input->post('title'),
	            'apple_product_id' => $this->input->post('apple_product_id'),
	            'google_product_id' => $this->input->post('google_product_id'),            
	            'description' =>mysql_real_escape_string($this->input->post('description')),
	            'price' => $ebook_price,  
                    'audio_price' => $audio_price,  
                    'combo_price' => $combo_price,           
	            'content_type' => $this->input->post('content_type'),
	            'status' => $this->input->post('status'),
	            'publish_date' => date('Y-m-d', strtotime(str_replace('/', '-', $this->input->post('publish_date'))))
	               
	        );
		}else{
	        $data = array(
	            'author_id' => $authorId,
	            'plan_type_id' => $this->input->post('plan_type_id'),
	            'title' => $this->input->post('title'),
	            'apple_product_id' => $this->input->post('apple_product_id'),
	            'google_product_id' => $this->input->post('google_product_id'),            
	            'description' =>mysql_real_escape_string($this->input->post('description')),
	            'price' => $ebook_price,  
                    'audio_price' => $audio_price,  
                    'combo_price' => $combo_price,           
	            'content_type' => $this->input->post('content_type'),
	            'status' => $this->input->post('status'),
	            'publish_date' => date('Y-m-d', strtotime(str_replace('/', '-', $this->input->post('publish_date')))),
	            'filesize'=>$mb         
	        );
		}
        if($id == ''){
            $data['created'] = date('Y-m-d');
        }else{
            $data['modified'] = date('Y-m-d H:i:s');
        }           
        if ($id == '') {
            $this->db->set($data);
            $this->db->insert($this->bookTable);
            return $this->db->insert_id();
        } else {
            $this->db->set($data);
            $this->db->where('id', $id);
            $this->db->update($this->bookTable);
            return $id;
        }
    }
    
    /**
     * update file name(epub,mp3 and img)
     *
     * @param int $id
     * @param int $data
     */
    function updateFile($id,$data)
    {	
    	$this->db->set($data);
        $this->db->where('id', $id);
        $this->db->update($this->bookTable);	
    }

    /**
     * get author
     *
     * @return array of author
     */
    function getAuthor() {
        $this->db->select("$this->authorTable.id, $this->authorTable.name");
        return $this->db->get_where($this->authorTable,array('plan_type_id'=>'2'))->result();
    }
    
    /**
     * get plan type
     *
     * @return array of plan type
     */
    function getPlanType() {
        $this->db->select("$this->planTypeTable.id, $this->planTypeTable.name");
        return $this->db->get($this->planTypeTable)->result();
    }

    /**
     * delete the Mobi App Store
     *
     * @param int $id
     */
    function delete($id) {
        if($this->db->delete($this->bookTable, array('id' => $id)))
        return true;
        else 
        return false;
    }
    
    function getAuthorByPlanType($planTypeId)
    {
    	$this->db->select("$this->authorTable.id, $this->authorTable.name");
    	return $this->db->get_where($this->authorTable, array('plan_type_id' => $planTypeId))->result();	
    }
    
    /**
     * update notification time when admin update epub magazine
     *
     * @param int $bookId
     */
    function updateNotificationTime($bookId)
    {
    	$this->db->select("user_id");
    	$this->db->distinct();
    	$userId = $this->db->get_where($this->orderTable, array('plan_id !=' => 0))->result();
    	foreach ($userId as $value) {
    		$data = array(
				'user_id' => $value->user_id ,
				'book_id' => $bookId
			);
    		$this->db->insert($this->notificationTimeTable, $data); 
    	}
    }
}

?>