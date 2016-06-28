<?php
/**
 * quiz Management Model
 *
 */
class Quizquestionmodel extends CI_Model
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
	function get($quizId) {

        $aColumns = array("$this->quizQuestionTable.id",
            "$this->quizQuestionTable.question");
        $aColumnsAlias = array('id', 'question');
        
         /* Table*/
        $sTable = $this->quizQuestionTable;

        /* Indexed column (used for fast and accurate table cardinality) */
        $sIndexColumn = "$this->quizQuestionTable.id";

        /* Where (don't include 'WHERE') */
        $qWhere = "$this->quizQuestionTable.quiz_id = $quizId";
        
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
		return $this->db->get_where($this->quizQuestionTable, array("$this->quizQuestionTable.id" => $id))->row();
	}
	
	/**
	 * get quiz answer by its question id
	 *
	 * @param int $id
	 * @return details of quiz answer
	 */
	function getAnswerByQuestionId($id)
	{	
		$answerArray = array();
		$this->db->select("answer");
		$res = $this->db->get_where($this->quizAnswerTable, array("quiz_question_id" => $id))->result();
		foreach ($res as $value) {			
			$answerArray[] = $value->answer; 
		}
		return $answerArray;
	}
	
	/**
	 * add/edit the quiz question details
	 *
	 * @param int $id
	 */
	function save($id=FALSE) {
		$data = array(					  
					  'quiz_id' => $this->input->post('quiz_id'),
					  'question' => $this->input->post('question'),
					  'answer_type' => $this->input->post('answer_type'),
					  'option1' => $this->input->post('option1'),
					  'option2' => $this->input->post('option2'),
					  'option3' => $this->input->post('option3'),
					  'option4' => $this->input->post('option4'),					 
					  'created' => date('Y-m-d')
					  );
		if($id != FALSE)
        $data['modified'] = date('Y-m-d');					  
		
		if ($id == FALSE) {
			$this->db->set($data);
			if($this->db->insert($this->quizQuestionTable))
			{
				$quizQuestionId = $this->db->insert_id();
				foreach ($this->input->post('answer') as $value) {
					$answerData['quiz_question_id'] = $quizQuestionId;
					$answerData['answer'] = $value;
					$answerData['created'] = date('Y-m-d');
					$this->db->set($answerData);
					$this->db->insert($this->quizAnswerTable);					
				}				
			}	
		} else {
			$this->db->set($data);
			$this->db->where('id', $id);
			
			if($this->db->update($this->quizQuestionTable))
			{
				$this->db->delete($this->quizAnswerTable, array('quiz_question_id' => $id)); 
				foreach ($this->input->post('answer') as $value) {
					$answerData['quiz_question_id'] = $id;
					$answerData['answer'] = $value;
					$answerData['created'] = date('Y-m-d');
					$answerData['modified'] = date('Y-m-d');
					$this->db->set($answerData);
					$this->db->insert($this->quizAnswerTable);					
				}				
			}		  
		}
	}
	
	/**
	 * delete the quiz
	 *
	 * @param int $id
	 */
	function delete($id)
	{
		$this->db->delete($this->quizQuestionTable, array('id' => $id));		 
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
    
    /**
     * get magazine title
     *
     * @return magazine title
     */
    function getBookNameByQuizId($quizId) {
        $this->db->select("$this->quizTable.book_id");        
        $res = $this->db->get_where($this->quizTable,array("$this->quizTable.id"=>$quizId))->row();
        $bookId = $res->book_id;
        if($bookId == 0) {
        	return 'General Quiz';
        } else { 
        	$this->db->select("$this->bookTable.title");
        	$res = $this->db->get_where($this->bookTable,array("$this->bookTable.id"=>$bookId))->row();	
        	return $res->title;
        }	
    }
}
?>