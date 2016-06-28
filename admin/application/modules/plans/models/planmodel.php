<?php
/**
 * Plans Management Model
 *
 */
class Planmodel extends CI_Model
{
	private $planTable = 'plans';
	private $planTypeTable = 'plan_types';
	
	function __construct()
	{
		parent::__construct();		
	}
	
	/**
     * get the Mobi App Store listing
     *
     */
    function get() {

        $aColumns = array("$this->planTable.id",
            "$this->planTable.title",
            "CONCAT($this->planTable.price,' ',$this->planTable.currency)",
            "$this->planTypeTable.name",
            "$this->planTable.duration_number");
        $aColumnsAlias = array('id', 'title', 'price', 'name', 'duration_number');
        
         /* Table*/
        $sTable = $this->planTable;

        /* Indexed column (used for fast and accurate table cardinality) */
        $sIndexColumn = "$this->planTable.id";

        /* Where (don't include 'WHERE') */
        $qWhere = "$this->planTable.plan_flag = 'book'";;
        
        /* Join */
        $sJoin = "LEFT JOIN $this->planTypeTable ON $this->planTypeTable.id = $this->planTable.plan_type_id";
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
	 * get plan details by its primary id
	 *
	 * @param int $id
	 * @return details of plan
	 */
	function getById($id)
	{
		$this->db->select("$this->planTable.id, $this->planTable.plan_type_id, $this->planTable.title, $this->planTable.price, $this->planTable.currency, $this->planTable.duration_number, $this->planTable.apple_product_id, $this->planTable.google_product_id");
		return $this->db->get_where($this->planTable, array('id' => $id))->row();
	}
	
	/**
	 * add/edit the plan details
	 *
	 * @param int $id
	 */
	function save($id) {
		$data = array(					  
					  'plan_type_id' => $this->input->post('plan_type_id'),
					  'title' => $this->input->post('title'),
					  'price' => $this->input->post('price'),
					  'currency' => $this->input->post('currency'),
					  'duration_number' => $this->input->post('duration_number'),
					  'apple_product_id' => $this->input->post('apple_product_id'),
					  'google_product_id' => $this->input->post('google_product_id'),
					  'duration_type' => 'Month',
					  'Status' => 'Active',
					  'created' => date('Y-m-d'),
					  'modified' => date('Y-m-d'),
					  'plan_flag' => 'book'
					  );
		
		if ($id == FALSE) {
			$this->db->set($data);
			$this->db->insert($this->planTable);
		} else {
			$this->db->set($data);
			$this->db->where('id', $id);
			$this->db->update($this->planTable);
		}
	}
	
	/**
	 * get active plant types
	 *
	 * @return array of plan types
	 */
	function getPlanTypes()
	{
		$this->db->select("$this->planTypeTable.id, $this->planTypeTable.name");
		return $this->db->get_where($this->planTypeTable, array('status' => 'Active'))->result();	
	}
	
	/**
	 * delete the plan
	 *
	 * @param int $id
	 */
	/*function delete($id)
	{
		$this->db->delete($this->planTable, array('id' => $id)); 
	}*/
}
?>