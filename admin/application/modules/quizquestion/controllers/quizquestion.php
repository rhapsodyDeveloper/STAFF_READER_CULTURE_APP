<?php
/**
 * @name: quiz question Management
 * 
 * @desc: quiz question Controller
 * 
 * @author: Pratyush Dimri
 */
class Quizquestion extends CI_Controller
{
	private $data = array();	
	/* Basic Form Validation will be used */
	private $rules = array(					
						array(
							'field'   => 'question',
							'label'   => 'lang:Question',
							'rules'   => 'trim|required',
						),
						array(
							'field'   => 'answer_type',
							'label'   => 'lang:Answer Type',
							'rules'   => 'trim|required',
						),
						array(
							'field'   => 'option1',
							'label'   => 'lang:Option 1',
							'rules'   => 'trim|required',
						),
						array(
							'field'   => 'option2',
							'label'   => 'lang:Option 2',
							'rules'   => 'trim|required',
						),
						array(
							'field'   => 'option3',
							'label'   => 'lang:Option 3',
							'rules'   => 'trim',
						),
						array(
							'field'   => 'option4',
							'label'   => 'lang:Option 4',
							'rules'   => 'trim',
						),
						array(
							'field'   => 'answer[]',
							'label'   => 'lang:Correct Answer',
							'rules'   => 'trim|required',
						)
					);
					
	/**
     * Quiz Question constructor
     *
     */
	function __construct()
	{
		parent::__construct();
		$this->auth->restrict(3);
		$this->template->set('controller', $this);
		//$this->config->config['modules_list'][] = 'quizquestion';
		$this->data['module_name'] = $this->router->fetch_module();
		$this->load->model('quizquestionmodel');
	}
	
	/**
	 * quiz question list index page
	 *	 
	 * @return quiz listing
	 */
	function index($id)
	{
		$this->data['bookName'] = $this->quizquestionmodel->getBookNameByQuizId($id);
		$this->data['quizId'] = $id;
		$this->template->load_partial('admin/admin_master', 'quizquestion/index', $this->data);
	}
	
	 /**
     * Listing of quiz question
     * 	 
     * @return json format listing
     */
    function get($quizId) {
        $result = $this->quizquestionmodel->get($quizId);
        echo $result;
        exit;
    }
	
	/**
	 * add form for quiz question
	 *
	 */
	function create($quizId)
	{	
		$this->load->library('form_validation');
		$this->form_validation->set_rules($this->rules);
		$this->form_validation->set_error_delimiters('<li>', '</li>');
		$this->data['quizId'] = $quizId;
		
		if ($this->form_validation->run() == FALSE)
		{	
			$this->template->load_partial('admin/admin_master', 'quizquestion/form', $this->data);
		} else {		
			$this->quizquestionmodel->save();
			$this->session->set_flashdata('info', $this->lang->line('success_insert'));
			redirect($this->config->item('base_url') . 'quizquestion/'.$quizId);
		}
	}
	
	/**
	 * edit form for quiz question
	 *
	 * @param int $id
	 */
	function edit($quizId,$id)
	{
		$this->load->library('form_validation');
		$this->form_validation->set_rules($this->rules);
		$this->form_validation->set_error_delimiters('<li>', '</li>');
		$this->data['quizId'] = $quizId;		
		$this->data['answers'] = $this->quizquestionmodel->getAnswerByQuestionId($id);		
		
		if (!$this->input->post('submit')) {
				$this->data['query'] = $this->quizquestionmodel->getById($id);										
				$this->template->load_partial('admin/admin_master', 'quizquestion/form', $this->data);
		} else {
			if ($this->form_validation->run() == FALSE)
			{				
				$this->template->load_partial('admin/admin_master', 'quizquestion/form', $this->data);
			}
			else
			{
				$this->quizquestionmodel->save($id);
				$this->session->set_flashdata('info', $this->lang->line('success_edit'));
				redirect($this->config->item('base_url') . 'quizquestion/'.$quizId);
			}
		}			
	}
	
	/**
	 * delete quiz
	 *
	 * @param int $id
	 */
	function delete($quizId,$id)
	{
		$this->quizquestionmodel->delete($id);
		$this->session->set_flashdata('info', $this->lang->line('success_delete'));
		redirect($this->config->item('base_url') . 'quizquestion/'.$quizId);
	}
}