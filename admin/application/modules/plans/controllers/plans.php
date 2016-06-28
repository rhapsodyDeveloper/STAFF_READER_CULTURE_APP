<?php
/**
 * @name: Plan Management
 * 
 * @desc: Plan Controller
 * 
 * @author: Pratyush Dimiri
 */
class Plans extends CI_Controller
{
	private $data = array();
	/* Basic Form Validation will be used */	
	private $rules = array(
						array(
							'field'   => 'plan_type_id',
							'label'   => 'lang:Plan Type',
							'rules'   => 'trim|required',
						),
						array(
							'field'   => 'title',
							'label'   => 'lang:Title',
							'rules'   => 'trim|required',
						),
						array(
							'field'   => 'price',
							'label'   => 'lang:Price',
							'rules'   => 'trim|required|greater_than[-1]',
						),
						array(
							'field'   => 'currency',
							'label'   => 'lang:Currency',
							'rules'   => 'trim|required', 
						),
						array(
							'field'   => 'plan_type_id',
							'label'   => 'lang:Plan Name',
							'rules'   => 'trim|required',
						),
						array(
							'field'   => 'duration_number',
							'label'   => 'lang:Plan Period Months',
							'rules'   => 'trim|required', //is_natural_no_zero'
						),
						array(
							'field'   => 'apple_product_id',
							'label'   => 'lang:Apple Product Code',
							'rules'   => 'trim|required',
						),
						array(
							'field'   => 'google_product_id',
							'label'   => 'lang:Google Product Code',
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
		$this->load->model('planmodel');
	}
	
	/**
	 * page list index page for plan
	 *	 
	 * @return page listing
	 */
	function index()
	{
		$this->template->load_partial('admin/admin_master', 'plans/index', $this->data);
	}
	
	/**
     * Listing of plan
     * 	 
     * @return json format listing
     */
    function get() {
        $result = $this->planmodel->get();
        echo $result;
        exit;
    }
	
    /**
        * add form for plan
        *
        */
    /*function create()
    {	
            $this->load->library('form_validation');
            $this->form_validation->set_rules($this->rules);
            $this->form_validation->set_error_delimiters('<li>', '</li>');
            $this->form_validation->set_message('greater_than', 'The Price field is not valid value.');

            $this->data['planType'] = $this->planmodel->getPlanTypes();

            if ($this->form_validation->run() == FALSE)
            {	
                    $this->template->load_partial('admin/admin_master', 'plans/form', $this->data);
            } else {
                    $this->planmodel->save();
                    $this->session->set_flashdata('info', $this->lang->line('success_insert'));
                    redirect($this->config->item('base_url') . 'plans');
            }
    }*/

	/**
	 * edit form for plan
	 *
	 * @param int $id
	 */
	function edit($id)
	{
		$this->load->library('form_validation');
		$this->form_validation->set_rules($this->rules);
		$this->form_validation->set_error_delimiters('<li>', '</li>');	
		$this->form_validation->set_message('greater_than', 'The Price field is not valid value.');	
		
		$this->data['planType'] = $this->planmodel->getPlanTypes();
		
		if (!$this->input->post('submit')) {
				$this->data['query'] = $this->planmodel->getById($id);
				$this->template->load_partial('admin/admin_master', 'plans/form', $this->data);
		} else {
			// validate page and update data
			if ($this->form_validation->run() == FALSE)
			{
				$this->template->load_partial('admin/admin_master', 'plans/form', $this->data);
			}
			else
			{
				$this->load->model('planmodel');
				$this->planmodel->save($id);
				$this->session->set_flashdata('info', $this->lang->line('success_edit'));
				redirect($this->config->item('base_url') . 'plans');
			}
		}			
	}
	
	/**
	 * delete plan
	 *
	 * @param int $id
	 */
	/*function delete($id)
	{
		$this->planmodel->delete($id);
		$this->session->set_flashdata('info', $this->lang->line('success_delete'));
		redirect($this->config->item('base_url') . 'plans');
	}*/
}