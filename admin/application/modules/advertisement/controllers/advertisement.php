<?php
/**
 * @name: advertisement.php
 * 
 * @desc: advertisement Controller
 * 
 * @author: Pratyush Dimri
 */
class Advertisement extends CI_Controller {

    private $data = array();
    private $advertisementTable = 'advertisements';  
    private $countrytable='countries';
    private $plantable='plans';
    
    /* Basic Form Validation will be used */
    private $rules = array(
        array(
            'field' => 'title',
            'label' => 'lang:Title',
            'rules' => 'trim|required',
        ),
        array(
            'field' => 'plan_id',
            'label' => 'lang:Plan',
            'rules' => 'trim|required',
        ),        
        array(
            'field' => 'country',
            'label' => 'lang:country',
            'rules' => 'trim|required',
        ),
        array(
            'field' => 'device_type',
            'label' => 'lang:Device Type',
            'rules' => 'trim|required',
        ),
        array(
            'field' => 'duration',
            'label' => 'lang:Duration',
            'rules' => 'trim|required|is_natural_no_zero',
        ),
        array(
            'field' => 'link',
            'label' => 'lang:Link',
            'rules' => 'trim|required|valid_url',
        ),
        array(
            'field' => 'current_image_path',
            'label' => 'lang:Image Path',
            'rules' => 'trim'
        ),
        array(
            'field' => 'status',
            'label' => 'lang:Status',
            'rules' => 'trim|required'
        )
    );
    
    /**
     * advertisement store constructor
     *
     */
    function __construct() {
        parent::__construct();
        $this->auth->restrict(3);
        $this->template->set('controller', $this);
        $this->data['module_name'] = $this->router->fetch_module();
        $this->load->model('commonmodel');       
        $this->load->model('advertisementmodel');       
    }

    /**
     * advertisement store list index page for advertisement
     * 	 
     * @return advertisement listing
     */
    function index() {
        $this->template->load_partial('admin/admin_master', 'advertisement/index', $this->data);
    }

    /**
     * Listing of advertisement
     * 	 
     * @return json format listing
     */
    function get() {
        $result = $this->advertisementmodel->get();
        echo $result;
        exit;
    }

    /**
     * add form for advertisement
     *
     */
    function create() {
        $this->load->library('form_validation');
        $this->form_validation->set_rules($this->rules);
        $this->form_validation->set_error_delimiters('<li>', '</li>');
        
        $this->data['status']=  $this->config->item('user_status');
        $this->data['country'] = $this->commonmodel->get($this->countrytable);
        $this->data['plan'] = $this->commonmodel->get($this->plantable);

		if (empty($_FILES['image_path']['name']))
		{
    		$this->form_validation->set_rules('image_path', 'Image', 'required');
		}
		
        if ($this->form_validation->run() == FALSE) {
            $this->template->load_partial('admin/admin_master', 'advertisement/form', $this->data);
        } else {
        	
			$config['upload_path'] = ADVERTISEMENT_PATH;
			$config['allowed_types'] = 'gif|jpg|png|jpeg';
			$config['encrypt_name'] = true;
			
			$this->load->library('upload', $config);
			
			if (!$this->upload->do_upload('image_path'))
			{								
				$this->data['fileError'] = $this->upload->display_errors();
				$this->template->load_partial('admin/admin_master', 'advertisement/form', $this->data);			
			}
			else
			{
				$uploadData = $this->upload->data();											
				$this->fileName = $uploadData['file_name'];
				
				$bookId = $this->advertisementmodel->save();	
				$this->session->set_flashdata('info', $this->lang->line('success_insert'));
				redirect($this->config->item('base_url') . 'advertisement');
			}          
        }
    }       

    /**
     * edit form for for advertisement
     *
     * @param int $id
     */
    function edit($id) {
        $this->load->library('form_validation');
        $this->form_validation->set_rules($this->rules);
        $this->form_validation->set_error_delimiters('<li>', '</li>'); 
        $uploadFlag = true;
        
        if(empty($_FILES['image_path']['name']) && $this->input->post('current_image_path') == '')
        $this->form_validation->set_rules('image_path', 'Image', 'required');   
                 
        $this->data['status']=  $this->config->item('user_status');
        $this->data['country'] = $this->commonmodel->get($this->countrytable);
        $this->data['plan'] = $this->commonmodel->get($this->plantable);                
		
		if (!$this->input->post('submit')) {
				$this->data['query'] = $this->advertisementmodel->getById($id);
				$this->template->load_partial('admin/admin_master', 'advertisement/form', $this->data);
		} else {
	        // validate page and update data
	        if ($this->form_validation->run() == FALSE) {
	            $this->template->load_partial('admin/admin_master', 'advertisement/form', $this->data);
	        }else {	        	
	        	if($_FILES['image_path']['name'] == '')
	        	{	
	        		$this->fileName = $this->input->post('current_image_path');	
	        	}
	        	else {			
					$config['upload_path'] = ADVERTISEMENT_PATH;
					$config['allowed_types'] = 'gif|jpg|png|jpeg';
					$config['encrypt_name'] = true;
				
					$this->load->library('upload', $config);
				
					if (!$this->upload->do_upload('image_path'))
					{								
						$this->data['fileError'] = $this->upload->display_errors();
						$this->template->load_partial('admin/admin_master', 'advertisement/form', $this->data);
						$uploadFlag = false;			
					}
					else
					{	
						$uploadData = $this->upload->data();											
						$this->fileName = $uploadData['file_name'];										
					}  
	        	}
	        	
	        	if ($uploadFlag == true) {
	        		$this->advertisementmodel->save($id);
					$this->session->set_flashdata('info', $this->lang->line('success_edit'));
					redirect($this->config->item('base_url') . 'advertisement');	
	        	}
	        	
	            
	        } 
		}
    }

    /**
     * delete advertisement
     *
     * @param int $id
     */
    function delete($id) {
    	$filePath = ADVERTISEMENT_PATH.$this->getAdvFileName($id);
        if($this->advertisementmodel->delete($id))
        unlink($filePath);
        $this->session->set_flashdata('info', $this->lang->line('success_delete'));
        redirect($this->config->item('base_url') . 'advertisement');
    }
    
    /**
     * get advertisement file name
     *
     * @param int $id
     */
    function getAdvFileName($id) {
        $this->db->select("image_path");
    	$res = $this->db->get_where($this->advertisementTable, array('id' => $id))->first_row();
    	return $res->image_path;
    }
}