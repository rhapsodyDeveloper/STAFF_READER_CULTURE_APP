<?php
/**
 * @name: Slider Management
 * 
 * @desc: Slider Controller
 * 
 * @author: Pratyush Dimri
 */
class Slider extends CI_Controller
{
	private $data = array();	
	/* Basic Form Validation will be used */
	private $rules = array(
						array(
							'field'   => 'name',
							'label'   => 'lang:Name',
							'rules'   => 'trim|required',
						),
						array(
							'field'   => 'status',
							'label'   => 'lang:Status',
							'rules'   => 'trim|required',
						)
					);
	public $uploadImage;					
					
	/**
     * Slider constructor
     *
     */
	function __construct()
	{
		parent::__construct();
		$this->auth->restrict(3);
		$this->template->set('controller', $this);
		$this->data['module_name'] = $this->router->fetch_module();
		$this->load->model('slidermodel');
	}
	
	/**
	 * slider list index page
	 *	 
	 * @return slider listing
	 */
	function index()
	{
		$this->template->load_partial('admin/admin_master', 'slider/index', $this->data);
	}
	
	 /**
     * Listing of slider
     * 	 
     * @return json format listing
     */
    function get() {
        $result = $this->slidermodel->get();
        echo $result;
        exit;
    }
	
	/**
	 * add form for slider
	 *
	 */
	function create()
	{	
		$this->load->library('form_validation');
		$this->form_validation->set_rules($this->rules);
		$this->form_validation->set_error_delimiters('<li>', '</li>');
		
		if (empty($_FILES['image']['name']))
		{
    		$this->form_validation->set_rules('image', 'Image', 'required');
		}
		
		if ($this->form_validation->run() == FALSE)
		{	
			$this->template->load_partial('admin/admin_master', 'slider/form', $this->data);
		} else 
		{	
			if(isset($_FILES['image']['name']) && $_FILES['image']['name'] != '')
			{
				$this->load->library('upload_img',$_FILES['image']);
									
				$this->upload_img->file_name_body_pre = time();
				$this->upload_img->image_resize = true;
				$this->upload_img->file_overwrite = true;
				$this->upload_img->image_ratio = true;
				$this->upload_img->allowed	= array('image/jpeg','image/jpg','image/png');
				$this->upload_img->image_y = 400;
				$this->upload_img->image_x = 550;
				$this->upload_img->Process(SLIDER_IMG_PATH);
				
				if (!$this->upload_img->processed)
				{	
					$this->session->set_flashdata('error', $this->upload_img->error);	
					redirect($this->config->item('base_url') . 'slider/create/');			
				}
				else {
					$this->uploadImage = $this->upload_img->file_dst_name;
				}
			}
						
			$this->slidermodel->save();
			$this->session->set_flashdata('info', $this->lang->line('success_insert'));
			redirect($this->config->item('base_url') . 'slider');
		}
	}
	
	/**
	 * edit form for slider
	 *
	 * @param int $id
	 */
	function edit($id)
	{
		$this->load->library('form_validation');
		$this->form_validation->set_rules($this->rules);
		$this->form_validation->set_error_delimiters('<li>', '</li>');		
                
		if (!$this->input->post('submit')) {
		    $this->data['query'] = $this->slidermodel->getById($id);
                    $this->template->load_partial('admin/admin_master', 'slider/form', $this->data);
		} else {
			// validate page and update data
			if ($this->form_validation->run() == FALSE)
			{
				$this->template->load_partial('admin/admin_master', 'slider/form', $this->data);
			}
			else
			{
				if(isset($_FILES['image']['name']) && $_FILES['image']['name'] != '')
				{
					$this->load->library('upload_img',$_FILES['image']);

					$this->upload_img->file_name_body_pre = time();				
					$this->upload_img->image_resize = true;
					$this->upload_img->image_ratio = true;
					$this->upload_img->file_overwrite = true;
					$this->upload_img->allowed	= array('image/jpeg','image/jpg','image/png');
					$this->upload_img->image_y = 400;
					$this->upload_img->image_x = 550;
					$this->upload_img->Process(SLIDER_IMG_PATH);
					
					if (!$this->upload_img->processed)
					{		
						$this->session->set_flashdata('error', $this->upload_img->error);	
						redirect($this->config->item('base_url') . 'slider/edit/');			
					}
					else {
						$this->uploadImage = $this->upload_img->file_dst_name;
					}
				}
				else {
					$this->uploadImage = $this->input->post('current_image');
				}
			
				$this->slidermodel->save($id);
				$this->session->set_flashdata('info', $this->lang->line('success_edit'));
				redirect($this->config->item('base_url') . 'slider');
			}
		}			
	}
	
	/**
	 * delete slider
	 *
	 * @param int $id
	 */
	function delete($id)
	{	
		$img = $this->slidermodel->getImageById($id);
		if($this->slidermodel->delete($id))
		{			
			unlink(SLIDER_IMG_PATH.$img);
		}

		$this->session->set_flashdata('info', $this->lang->line('success_delete'));
		redirect($this->config->item('base_url') . 'slider');
	}	
}