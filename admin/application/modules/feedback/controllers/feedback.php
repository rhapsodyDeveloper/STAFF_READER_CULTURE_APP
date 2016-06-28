<?php
/**
 * @name: Feedback Management
 * 
 * @desc: Feedback Controller
 * 
 * @author: Pratyush Dimri
 */
class Feedback extends CI_Controller
{
	private $data = array();
        private $feedbackTable = 'user_feedback';
	
	/**
	 * Feedback constructor
	 *
	 */
	function __construct()
	{
		parent::__construct();
		$this->auth->restrict(3);
		$this->template->set('controller', $this);
		$this->data['module_name'] = $this->router->fetch_module();
		$this->load->model('feedbackmodel');
	}
	
	/**
	 * page list index page for feedback
	 *	 
	 * @return feedback listing
	 */
	function index()
	{
		$this->template->load_partial('admin/admin_master', 'feedback/index', $this->data);
	}
	
	/**
     * Listing of feedback
     * 	 
     * @return json format listing
     */
    function get() {
        $params = $this->uri->uri_to_assoc(3);
        $result = $this->feedbackmodel->get($params);
        echo $result;
        exit;
    }
    
	/**
	 * view form for feedback
	 *
	 * @param int $id
	 */
	function view($id)
	{	
		$this->load->helper('form');			
		$this->data['query'] = $this->feedbackmodel->getById($id);
		$this->template->load_partial('admin/admin_master', 'feedback/form', $this->data);			
	}	
        
        function exportCsv() 
    {    	
		$this->load->dbutil();
		
	$params = $this->uri->uri_to_assoc(3);
        $aColumns = array("$this->feedbackTable.id",
            "$this->feedbackTable.name",
            "$this->feedbackTable.comments",
            "$this->feedbackTable.email");
        $aColumnsAlias = array('id', 'name', 'email', 'comments');
        
         /* Table*/
        $sTable = $this->feedbackTable;

        /* Indexed column (used for fast and accurate table cardinality) */
        $sIndexColumn = "$this->feedbackTable.id";

        /* Where (don't include 'WHERE') */
        $qWhere = "";
        
        /* Join */
        $sJoin = "";
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
        
        
        if($params['year'] != 'null')
        {
        	if($qWhere != '')
        	$qWhere .= " AND ";
        	$qWhere .= "DATE_FORMAT($this->feedbackTable.created,'%Y') = ".$params['year'];        
        }
        if($params['from'] != 'null' && $params['to'] != 'null')
        {
        	if($qWhere != '')
        	$qWhere .= " AND ";
        	$qWhere .= $this->feedbackTable.'.created BETWEEN "'.$params['from'].'" AND "'.$params['to'].'"';        
        }
        
        /* Include query condition*/
        if($qWhere != '' && $sWhere != '')
        $sWhere = $sWhere." AND ".$qWhere;
        elseif ($qWhere != '' && $sWhere == '')
        $sWhere = 'WHERE '.$qWhere;  
//echo $sWhere; exit;
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
        $query = $this->db->query($sQuery);
        header("Content-type: application/ms-excel");
      	header("Content-disposition: csv" . date("d-m-Y") . ".csv");
      	header("Content-disposition: filename=sales-report-".date("d-m-Y").".csv");	
		print $this->dbutil->csv_from_result($query);
		exit;
    }
}