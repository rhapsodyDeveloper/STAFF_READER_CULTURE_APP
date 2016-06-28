<?php
/**
 * @name: Plan Management
 * 
 * @desc: Plan Controller
 * 
 * @author: Pratyush Dimiri
 */
class Pushnotification extends CI_Controller
{
	private $data = array();
	/* Basic Form Validation will be used */	
	private $rules = array(
						array(
							'field'   => 'send_to[]',
							'label'   => 'lang:Device Type',
							'rules'   => 'required',
						),
						array(
							'field'   => 'message',
							'label'   => 'lang:Message',
							'rules'   => 'trim|required',
						)
					);
	
	/**
	 * plans constructor
	 *
	 */
	function __construct()
	{
		parent::__construct();
		$this->auth->restrict(3);
		$this->template->set('controller', $this);
		$this->data['module_name'] = $this->router->fetch_module();
		$this->load->model('pushmodel');
		$this->load->helper('form');
	}
	
	/**
	 * page list index page for plan
	 *	 
	 * @return page listing
	 */
	function index()
	{
		$this->load->library('form_validation');
            $this->form_validation->set_rules($this->rules);
            $this->form_validation->set_error_delimiters('<li>', '</li>');

           

            if ($this->form_validation->run() == FALSE)
            {	
                    $this->template->load_partial('admin/admin_master', 'pushnotification/form', $this->data);
            } else {
                    $this->pushmodel->send();
                    $this->session->set_flashdata('info', $this->lang->line('notification_successfully_sent'));
                    redirect($this->config->item('base_url') . 'pushnotification');
            }
	}
	
}