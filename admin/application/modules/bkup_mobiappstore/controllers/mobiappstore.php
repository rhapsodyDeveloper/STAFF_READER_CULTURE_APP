<?php
/**
 * @name: mobiappstore.php
 * 
 * @desc: Content/Books  Controller
 * 
 * @author: Pratyush Dimiri
 */
class Mobiappstore extends CI_Controller {

    private $data = array();
    /* Basic Form Validation will be used */
    private $rules = array(
        array(
            'field' => 'title',
            'label' => 'lang:Title',
            'rules' => 'trim|required',
        ),
        array(
            'field' => 'description',
            'label' => 'lang:Description',
            'rules' => 'trim',
        ),        
        array(
            'field' => 'apple_product_id',
            'label' => 'lang:Apple Product Code',
            'rules' => 'trim',
        ),
        array(
            'field' => 'google_product_id',
            'label' => 'lang:Google Product Code',
            'rules' => 'trim',
        ),
        array(
            'field' => 'content_type',
            'label' => 'lang:Book Type',
            'rules' => 'trim|required',
        ),        
        array(
            'field' => 'plan_type_id',
            'label' => 'lang:Category',
            'rules' => 'trim|required',
        ),
        array(
            'field' => 'price',
            'label' => 'lang:Ebook Price',
            'rules' => 'trim'
        ),        
        array(
            'field' => 'publish_date',
            'label' => 'lang:Publish Date',
            'rules' => 'trim|required',
        ),               
        array(
            'field' => 'current_ebook_name',
            'rules' => 'trim'
        ),        
        array(
            'field' => 'current_thumb_image',
            'rules' => 'trim'
        ),
        array(
            'field' => 'status',
            'label' => 'lang:Available',
            'rules' => 'trim|required',
        )
    );    
    
    /**
     * Mob app store constructor
     *
     */
    function __construct() {
        parent::__construct();
        $this->auth->restrict(3);
        $this->template->set('controller', $this);
        $this->data['module_name'] = $this->router->fetch_module();
        $this->load->model('bookmodel');       
    }

    /**
     * Mob app store list index page for mob app store
     * 	 
     * @return Mob app store listing
     */
    function index() {
        $this->template->load_partial('admin/admin_master', 'mobiappstore/index', $this->data);
    }

    /**
     * Listing of mob app store
     * 	 
     * @return json format listing
     */
    function get() {
        $result = $this->bookmodel->get();
        echo $result;
        exit;
    }

    /**
     * add form for Mob app store
     *
     */
    function create() {    
        $this->load->library('form_validation');
        $this->form_validation->set_rules($this->rules);
        $this->form_validation->set_error_delimiters('<li>', '</li>');
        //$this->form_validation->set_message('greater_than', 'The Price field is not valid value');

        $this->data['author']		= $this->bookmodel->getAuthor();
        $this->data['planType']		= $this->bookmodel->getPlanType();
               
        if($this->session->userdata('BOOK_POST_DATA')) {
        $this->data['query'] 	= $this->session->userdata('BOOK_POST_DATA');
        $this->session->unset_userdata('BOOK_POST_DATA');
        }
        
        $this->form_validation->set_rules('title', 'Title', 'trim|required|is_unique[books.title]');
        
        if($this->input->post('plan_type_id') != '1')
		$this->form_validation->set_rules('author_id', 'Author', 'trim|required');
        
        if (empty($_FILES['ebook_name']['name']))		
   		$this->form_validation->set_rules('ebook_name', 'Ebook', 'required');		       
		
		if (empty($_FILES['thumb_image']['name']))		
    	$this->form_validation->set_rules('thumb_image', 'Thumb Image', 'required');		
		
        if ($this->form_validation->run() == FALSE) {        	
            $this->template->load_partial('admin/admin_master', 'mobiappstore/form', $this->data);
        } else {    									
			$size = $_FILES['ebook_name']['size']; 									
			$bookId = $this->bookmodel->save('',$size);
			if(!$this->updateFile($bookId,'add')) {
				$this->session->set_userdata('BOOK_POST_DATA',$this->input->post());
				redirect($this->config->item('base_url') . 'mobiappstore/create');
			}
			$this->session->set_flashdata('info', $this->lang->line('success_insert'));
			redirect($this->config->item('base_url') . 'mobiappstore');
			}
    }       

    /**
     * edit form for for Mob app store
     *
     * @param int $id
     */
    function edit($id) {
        $this->load->library('form_validation');
        $this->form_validation->set_rules($this->rules);
        $this->form_validation->set_error_delimiters('<li>', '</li>');
        $this->form_validation->set_message('greater_than', 'The Price field is not valid value');

        $this->data['author']		= $this->bookmodel->getAuthor();
        $this->data['planType']		= $this->bookmodel->getPlanType();            
                
		if($this->input->post('plan_type_id') != '1')
		$this->form_validation->set_rules('author_id', 'Author', 'trim|required');
		
		if (empty($_FILES['ebook_name']['name']) && $this->input->post('current_ebook_name') == '')		
   		$this->form_validation->set_rules('ebook_name', 'Ebook', 'required');
   		
		if (empty($_FILES['thumb_image']['name']) && $this->input->post('current_thumb_image') == '')		
    	$this->form_validation->set_rules('thumb_image', 'Thumb Image', 'required');    
		
		if (!$this->input->post('submit')) {
				if($this->session->userdata('BOOK_POST_DATA')) {
					$bookData = $this->session->userdata('BOOK_POST_DATA');
					$bookData['thumb_image'] = $bookData['current_thumb_image'];
					$bookData['ebook_name'] = $bookData['current_ebook_name'];					
					$this->data['query'] 	= $bookData;
					$this->session->unset_userdata('BOOK_POST_DATA');
				} else {
					$bookData = $this->bookmodel->getById($id);					
					$bookData['publish_date'] = DATE(DATE_FORMAT,strtotime($bookData['publish_date']));
					$this->data['query'] = $bookData; 
				}
				$this->template->load_partial('admin/admin_master', 'mobiappstore/form', $this->data);
		} else {
	        // validate page and update data
	        if ($this->form_validation->run() == FALSE) {
	            $this->template->load_partial('admin/admin_master', 'mobiappstore/form', $this->data);
	        }else {
	            $size = $_FILES['ebook_name']['size'];
	            $this->bookmodel->save($id,$size);
	            if(!$this->updateFile($id,'edit')){ 
	            	$this->session->set_userdata('BOOK_POST_DATA',$this->input->post());
					redirect($this->config->item('base_url') . 'mobiappstore/edit/'.$id);	
	            }
	            $this->session->set_flashdata('info', $this->lang->line('success_edit'));
				redirect($this->config->item('base_url') . 'mobiappstore');
	        } 
		}
    }
    
    /**
     * uploaf file(epub and img)
     *
     * @param int $id
     * @param string $mode
     */
    function updateFile($id,$mode)
    {
    	$this->load->library('awslib');
    	$s3 = $this->awslib->get_s3();
    	$uploadData = array();
    	$validateError = array();
    	
		if(isset($_FILES['ebook_name']['name']) && $_FILES['ebook_name']['name'] != '')
		{	
		//echo "Content Type == ".$this->input->post('content_type')."<br/>";
		
			if($this->input->post('content_type') == 'ebook' && !in_array($_FILES['ebook_name']['type'],array('application/epub+zip','application/octet-stream')))
			{
				if($mode == 'add')
				$this->bookmodel->delete($id);
				
				$validateError[] = $this->lang->line('mobi_app_store_proper_format_ebook');
				$this->session->set_flashdata('error', $validateError);
				return false;					
			}
			elseif($this->input->post('content_type') == 'combo' && !in_array($_FILES['ebook_name']['type'],array('application/epub+zip')))//!in_array($_FILES['ebook_name']['type'],array('application/epub+zip','application/octet-stream'))
			{ //echo 'in combo ';die;
				/*AWS S3 BUCKET UPLOAD*/						
				$ext = end(explode('.',$_FILES['ebook_name']['name']));
				$keyname = str_replace(array(" ","'","."),'',$this->input->post('title')).'.'.$ext;
				$filepath = $_FILES['ebook_name']['tmp_name'];				
				$response = $s3->create_object(BUCKET_NAME,'books/'.$id.'/'.$keyname,array('fileUpload' => $filepath,'acl' => AmazonS3::ACL_PUBLIC));								
				/*AWS S3 BUCKET UPLOAD*/

				if(!$response->isOK()){ 										
					if($mode == 'add')
					$this->bookmodel->delete($id);
					
					$validateError[] = $this->lang->line('mobi_app_store_upload_error_ebook');
					$this->session->set_flashdata('error', $validateError);
					return false;				
				} else { 				
					$uploadData['combo_name'] = $keyname;
					$uploadData['ebook_name'] = $keyname;
					$uploadData['combo_price'] = $this->input->post('price');
					if($mode == 'edit')
					$uploadData['modified'] = date('Y-m-d H:i:s');
					$this->bookmodel->updateFile($id,$uploadData);					
				}	
			}
			elseif ($this->input->post('content_type') == 'audio' && !in_array($_FILES['ebook_name']['type'],array('application/zip')))
			{
				//echo "In Audio <br/>";die;
				/*AWS S3 BUCKET UPLOAD*/						
				$ext = end(explode('.',$_FILES['ebook_name']['name']));
				$keyname = str_replace(array(" ","'","."),'',$this->input->post('title')).'.'.$ext;
				$filepath = $_FILES['ebook_name']['tmp_name'];				
				$response = $s3->create_object(BUCKET_NAME,'books/'.$id.'/'.$keyname,array('fileUpload' => $filepath,'acl' => AmazonS3::ACL_PUBLIC));								
				/*AWS S3 BUCKET UPLOAD*/

				if(!$response->isOK()){ 										
					if($mode == 'add')
					$this->bookmodel->delete($id);
					
					$validateError[] = $this->lang->line('mobi_app_store_upload_error_ebook');
					$this->session->set_flashdata('error', $validateError);
					return false;				
				} else { 				
					$uploadData['audio_name'] = $keyname;
					$uploadData['ebook_name'] = $keyname;
					$uploadData['audio_price'] = $this->input->post('price');
					if($mode == 'edit')
					$uploadData['modified'] = date('Y-m-d H:i:s');
					$this->bookmodel->updateFile($id,$uploadData);					
				}	
			}
			else
			{  //echo 'in elese ';die;
				/*AWS S3 BUCKET UPLOAD*/						
				$ext = end(explode('.',$_FILES['ebook_name']['name']));
				$keyname = str_replace(array(" ","'","."),'',$this->input->post('title')).'.'.$ext;
				$filepath = $_FILES['ebook_name']['tmp_name'];				
				$response = $s3->create_object(BUCKET_NAME,'books/'.$id.'/'.$keyname,array('fileUpload' => $filepath,'acl' => AmazonS3::ACL_PUBLIC));								
				/*AWS S3 BUCKET UPLOAD*/

				if(!$response->isOK()){ 										
					if($mode == 'add')
					$this->bookmodel->delete($id);
					
					$validateError[] = $this->lang->line('mobi_app_store_upload_error_ebook');
					$this->session->set_flashdata('error', $validateError);
					return false;				
				} else { 				
					$uploadData['ebook_name'] = $keyname;
					$uploadData['audio_name'] = $keyname;
					
					if($mode == 'edit')
					$uploadData['modified'] = date('Y-m-d H:i:s');
					$this->bookmodel->updateFile($id,$uploadData);					
				}	
			}
		}	
						
		if(isset($_FILES['thumb_image']['name']) && $_FILES['thumb_image']['name'] != '')
		{	
			$this->load->library('upload_img',$_FILES['thumb_image']);
			
			$this->upload_img->file_new_name_body = str_replace(array(" ","'","."),'',$this->input->post('title'));
			$this->upload_img->image_resize = true;
			$this->upload_img->file_overwrite = true;
			$this->upload_img->allowed	= array('image/jpeg','image/jpg','image/png');
			$this->upload_img->image_y = 180;
			$this->upload_img->image_x = 120;
			$this->upload_img->Process(IMAGE_PATH);
			
			if (!$this->upload_img->processed)
			{																			
				if($mode == 'add')
				$this->bookmodel->delete($id); 
				
				$validateError[] = $this->lang->line('mobi_app_store_upload_error_image');
				$this->session->set_flashdata('error', $validateError);
				return false;	
			}
			else {
				/*AWS S3 BUCKET UPLOAD*/								
				$keyname = $this->upload_img->file_dst_name;
				$filepath = IMAGE_PATH.$this->upload_img->file_dst_name;
				$response = $s3->create_object(BUCKET_NAME,'books/'.$id.'/'.$keyname,array('fileUpload' => $filepath, 'acl' => AmazonS3::ACL_PUBLIC));								
				/*AWS S3 BUCKET UPLOAD*/
				
				if(!$response->isOK()){ 															
					if($mode == 'add')
					$this->bookmodel->delete($id); 	
					
					$validateError[] = $this->lang->line('mobi_app_store_upload_error_image');
					$this->session->set_flashdata('error', $validateError);
					return false;				
				} else { 				
					$uploadData['thumb_image'] = $keyname;
					$this->bookmodel->updateFile($id,$uploadData);
					unlink(IMAGE_PATH.$keyname);					
				}
			}
		}

		return true;	
    }

    /**
     * delete Mob app store
     *
     * @param int $id
     */
    function delete($id) {
    	$this->load->library('awslib');
    	$s3 = $this->awslib->get_s3();    	
        
        if($this->bookmodel->delete($id))
        {
        	/*AWS S3 BUCKET UPLOAD*/											
			$response = $s3->get_object_list(BUCKET_NAME, array(
           		'prefix' => 'books/'.$id.'/'
        	));
        	foreach ($response as $v) {            
            	$s3->delete_object(BUCKET_NAME, $v);
        	}
			/*AWS S3 BUCKET UPLOAD*/
        }   
        
        $this->session->set_flashdata('info', $this->lang->line('success_delete')); 
        redirect($this->config->item('base_url') . 'mobiappstore');
    }
}