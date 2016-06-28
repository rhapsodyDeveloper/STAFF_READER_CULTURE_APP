<?php
/**
 * quiz Management Model
 *
 */
class Quizmodel extends CI_Model
{
	private $quizTable = 'quiz';
	private $quizQuestionTable = 'quiz_question';
	private $quizAnswerTable = 'quiz_answer';
	private $bookTable = 'books';
	
	function __construct()
	{
		parent::__construct();
	}
	
	/**
	 * get the quiz listing
	 *
	 */
	function get() {

        $aColumns = array("$this->quizTable.id",
            "$this->bookTable.title",            
            "$this->quizTable.status");
        $aColumnsAlias = array('id', 'title', 'status');
        
         /* Table*/
        $sTable = $this->quizTable;

        /* Indexed column (used for fast and accurate table cardinality) */
        $sIndexColumn = "$this->quizTable.id";

        /* Where (don't include 'WHERE') */
        $qWhere = "";
        
        /* Join */        
        $sJoin = "LEFT JOIN $this->bookTable ON $this->bookTable.id = $this->quizTable.book_id ";
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
	 * get quiz by its primary id
	 *
	 * @param int $id
	 * @return details of quiz
	 */
	function getById($id)
	{	
		return $this->db->get_where($this->quizTable, array("$this->quizTable.id" => $id))->row();
	}
	
	/**
	 * add/edit the quiz details
	 *
	 * @param int $id
	 */
	function save($id=FALSE) {
		$data = array(					  
					  'book_id' => $this->input->post('book_id'),
					  'status' => $this->input->post('status'),					 
					  'created' => date('Y-m-d')
					  );
		if($id != '')
        $data['modified'] = date('Y-m-d');					  
		
		if ($id == FALSE) {
			$this->db->set($data);
			$this->db->insert($this->quizTable);
		} else {
			$this->db->set($data);
			$this->db->where('id', $id);
			$this->db->update($this->quizTable);		  
		}
	}
	
	/**
	 * delete the quiz
	 *
	 * @param int $id
	 */
	function delete($id)
	{
		$this->db->delete($this->quizTable, array('id' => $id));  
	}
	
	/**
     * get magazine
     *
     * @return array of magazine
     */
    function getMagazine() {
        $this->db->select("$this->bookTable.id,$this->bookTable.title");
        return $this->db->get_where($this->bookTable,array('plan_type_id'=>'1'))->result();
    }
}
?>