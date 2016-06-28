<?php

/**
 * advertisement Management Model
 *
 */
class Advertisementmodel extends CI_Model {

    private $advertisementTable = 'advertisements';    
    private $countryTable = 'countries';    
    private $planTable = 'plans';    

    function __construct() {
        parent::__construct();
    }

    /**
     * get the advertisements listing
     *
     */
    function get() {

        $aColumns = array("$this->advertisementTable.id",
            "$this->advertisementTable.title",
            "$this->planTable.title",
            "$this->advertisementTable.country",
            "$this->advertisementTable.device_type",
            "$this->advertisementTable.status");
        $aColumnsAlias = array('id', 'title', 'plan_title', 'country', 'device_type', 'status');
        
         /* Table*/
        $sTable = $this->advertisementTable;

        /* Indexed column (used for fast and accurate table cardinality) */
        $sIndexColumn = "$this->advertisementTable.id";

        /* Where (don't include 'WHERE') */
        $qWhere = "";
        
        /* Join */
        $sJoin = "LEFT JOIN $this->planTable ON $this->planTable.id = $this->advertisementTable.plan_id ";
        $sJoin .= "LEFT JOIN $this->countryTable ON $this->countryTable.id = $this->advertisementTable.country";
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
     * get advertisement details by its primary id
     *
     * @param int $id
     * @return details of advertisement
     */
    function getById($id) {
        $this->db->select("$this->advertisementTable.id, $this->advertisementTable.title, $this->advertisementTable.image_path, $this->advertisementTable.plan_id, $this->advertisementTable.country, $this->advertisementTable.device_type, $this->advertisementTable.duration, $this->advertisementTable.link,  $this->advertisementTable.status");
        return $this->db->get_where($this->advertisementTable, array('id' => $id))->row();
    }

    /**
     * add/edit the advertisement
     *
     * @param int $id
     */
    function save($id) {   	    	
        $data = array(
            'title' => $this->input->post('title'),
            'image_path' => $this->fileName,
            'plan_id' => $this->input->post('plan_id'),
            'country' => $this->input->post('country'),
            'device_type' => $this->input->post('device_type'),
            'duration' => $this->input->post('duration'),
            'link' => $this->input->post('link'),
            'status' => $this->input->post('status'),
            'modified' => date('Y-m-d')
        );
        
		if(!isset($id))
		$data['created'] = date('Y-m-d');
           

        if ($id == FALSE) {
            $this->db->set($data);
            $this->db->insert($this->advertisementTable);
            return $this->db->insert_id();
        } else {
            $this->db->set($data);
            $this->db->where('id', $id);
            $this->db->update($this->advertisementTable);
            return $id;
        }
    }

    /**
     * delete the advertisement
     *
     * @param int $id
     */
    function delete($id) {
        if($this->db->delete($this->advertisementTable, array('id' => $id)))
        return true;
        else 
        return false;
    }    
}

?>