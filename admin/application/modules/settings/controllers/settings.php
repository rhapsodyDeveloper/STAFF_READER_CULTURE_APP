<?php

class Settings extends CI_Controller
{
	private $data = array();
        /* Basic Form Validation will be used */	
	private $rules = array(
                                array(
                                        'field'   => 'key',
                                        'label'   => 'lang:key',
                                        'rules'   => 'trim|required',
                                ),
                                array(
                                        'field'   => 'value',
                                        'label'   => 'lang:value',
                                        'rules'   => 'trim|required',
                                ),
                                array(
                                        'field'   => 'label',
                                        'label'   => 'lang:label',
                                        'rules'   => 'trim|required',
                                )
                        );
	
	function __construct()
	{
		parent::__construct();
		$this->template->set('controller', $this);
		$this->data['module_name'] = $this->router->fetch_module();
		$this->load->model('commonmodel');
		$this->load->model('settingmodel');
		//$this->output->enable_profiler(TRUE);
	}
	
	private $table = 'settings';	
	
	function index($num = 0)
	{
		$this->auth->restrict(2);
		$this->load->library('form_validation');
		$this->data['query'] = $this->settingmodel->get_all();
		if (!$this->input->post('submit')) {				
			$this->template->load_partial('admin/admin_master', 'form', $this->data);
		} else {
			$settings = $this->input->post('settings');
			$this->settingmodel->save_configs($settings);
			$this->session->set_flashdata('info', $this->lang->line('success_edit'));
			redirect(base_url() . 'settings');
		}
		
	}
        
         /**
	 * add form for settings
	 *
	 */
	function add()
	{	
		$this->load->library('form_validation');
		$this->form_validation->set_rules($this->rules);
		$this->form_validation->set_error_delimiters('<li>', '</li>');
		
		if ($this->form_validation->run() == FALSE)
		{	
			$this->template->load_partial('admin/admin_master', 'settings/add', $this->data);
		} else {
			$this->settingmodel->add();
			$this->session->set_flashdata('info', $this->lang->line('success_insert'));
			redirect($this->config->item('base_url') . 'settings/index');
		}
	}
        
        function delete($id)
	{
		$this->auth->restrict(2);
		$this->commonmodel->delete($this->table, $id);
		$this->session->set_flashdata('info', $this->lang->line('success_delete'));
		redirect(base_url() . 'settings');	
	}	
}