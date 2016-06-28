<?php
/**
 * @name: Plan Type Management
 * 
 * @desc: Plan  Type Controller
 * 
 * @author: Pratyush Dimiri
 */
class Plantype extends CI_Controller
{
	private $data = array();	
	/* Basic Form Validation will be used */
	private $rules = array(
						array(
							'field'   => 'name',
							'label'   => 'lang:Plan Name',
							'rules'   => 'trim|required',
						),
						array(
							'field'   => 'status',
							'label'   => 'lang:Status',
							'rules'   => 'trim|required',
						)
					);
					
	/**
     * Plans type constructor
     *
     */
	function __construct()
	{
		parent::__construct();
		$this->auth->restrict(3);
		$this->template->set('controller', $this);
		$this->data['module_name'] = $this->router->fetch_module();
		$this->load->model('plantypemodel');
	}
	
	/**
	 * page type list index page for plan
	 *	 
	 * @return page type listing
	 */
	function index()
	{
		$this->template->load_partial('admin/admin_master', 'plantype/index', $this->data);
	}
	
	 /**
     * Listing of plans type
     * 	 
     * @return json format listing
     */
    function get() {
        $result = $this->plantypemodel->get();
        echo $result;
        exit;
    }
	
	/**
	 * add form for plan type
	 *
	 */
	/*function create()
	{	
		$this->load->library('form_validation');
		$this->form_validation->set_rules($this->rules);
		$this->form_validation->set_error_delimiters('<li>', '</li>');
		
		if ($this->form_validation->run() == FALSE)
		{	
			$this->template->load_partial('admin/admin_master', 'plantype/form', $this->data);
		} else {
			$this->plantypemodel->save();
			$this->session->set_flashdata('info', $this->lang->line('success_insert'));
			redirect($this->config->item('base_url') . 'plantype');
		}
	}*/
	
	/**
	 * edit form for plan type
	 *
	 * @param int $id
	 */
	function edit($id)
	{
		$this->load->library('form_validation');
		$this->form_validation->set_rules($this->rules);
		$this->form_validation->set_error_delimiters('<li>', '</li>');		
		
		if (!$this->input->post('submit')) {
				$this->data['query'] = $this->plantypemodel->getById($id);
				$this->template->load_partial('admin/admin_master', 'plantype/form', $this->data);
		} else {
			// validate page and update data
			if ($this->form_validation->run() == FALSE)
			{				
				$this->template->load_partial('admin/admin_master', 'plantype/form', $this->data);
			}
			else
			{
				$this->plantypemodel->save($id);
				$this->session->set_flashdata('info', $this->lang->line('success_edit'));
				redirect($this->config->item('base_url') . 'plantype');
			}
		}			
	}
	
	/**
	 * delete plan type
	 *
	 * @param int $id
	 */
	/*function delete($id)
	{
		$this->plantypemodel->delete($id);
		$this->session->set_flashdata('info', $this->lang->line('success_delete'));
		redirect($this->config->item('base_url') . 'plantype');
	}*/	
}