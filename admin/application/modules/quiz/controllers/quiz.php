<?php
/**
 * @name: quiz Management
 * 
 * @desc: quiz Controller
 * 
 * @author: Pratyush Dimri
 */
class Quiz extends CI_Controller
{
	private $data = array();	
	/* Basic Form Validation will be used */
	private $rules = array(
						array(
							'field'   => 'book_id',
							'label'   => 'lang:Book',
							'rules'   => 'trim|required',
						),					
						array(
							'field'   => 'status',
							'label'   => 'lang:Status',
							'rules'   => 'trim|required',
						)
					);
					
	/**
     * Quiz constructor
     *
     */
	function __construct()
	{
		parent::__construct();
		$this->auth->restrict(3);
		$this->template->set('controller', $this);
		$this->data['module_name'] = $this->router->fetch_module();
		$this->load->model('quizmodel');
	}
	
	/**
	 * quiz list index page
	 *	 
	 * @return quiz listing
	 */
	function index()
	{
		$this->template->load_partial('admin/admin_master', 'quiz/index', $this->data);
	}
	
	 /**
     * Listing of quiz
     * 	 
     * @return json format listing
     */
    function get() {
        $result = $this->quizmodel->get();
        echo $result;
        exit;
    }
	
	/**
	 * add form for quiz
	 *
	 */
	function create()
	{	
		$this->load->library('form_validation');
		$this->form_validation->set_rules($this->rules);
		$this->form_validation->set_error_delimiters('<li>', '</li>');
		$this->data['magazines']	= $this->quizmodel->getMagazine();
		
		if ($this->form_validation->run() == FALSE)
		{	
			$this->template->load_partial('admin/admin_master', 'quiz/form', $this->data);
		} else {		
			$this->quizmodel->save();
			$this->session->set_flashdata('info', $this->lang->line('success_insert'));
			redirect($this->config->item('base_url') . 'quiz');
		}
	}
	
	/**
	 * edit form for quiz
	 *
	 * @param int $id
	 */
	function edit($id)
	{
		$this->load->library('form_validation');
		$this->form_validation->set_rules($this->rules);
		$this->form_validation->set_error_delimiters('<li>', '</li>');
		$this->data['magazines']	= $this->quizmodel->getMagazine();		
		
		if (!$this->input->post('submit')) {
				$this->data['query'] = $this->quizmodel->getById($id);										
				$this->template->load_partial('admin/admin_master', 'quiz/form', $this->data);
		} else {
			if ($this->form_validation->run() == FALSE)
			{				
				$this->template->load_partial('admin/admin_master', 'quiz/form', $this->data);
			}
			else
			{
				$this->quizmodel->save($id);
				$this->session->set_flashdata('info', $this->lang->line('success_edit'));
				redirect($this->config->item('base_url') . 'quiz');
			}
		}			
	}
	
	/**
	 * delete quiz
	 *
	 * @param int $id
	 */
	function delete($id)
	{
		$this->quizmodel->delete($id);
		$this->session->set_flashdata('info', $this->lang->line('success_delete'));
		redirect($this->config->item('base_url') . 'quiz');
	}
}