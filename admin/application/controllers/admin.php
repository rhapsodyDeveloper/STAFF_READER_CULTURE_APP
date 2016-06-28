<?php

class Admin extends CI_Controller
{
	function __construct()
	{
		parent::__construct();
		$this->lang->load($this->config->item('admin_language'), $this->config->item('admin_language'));
	}
	
	private $rules = array(
                                    array(
                                            'field'   => 'email',
                                            'label'   => 'lang:email',
                                            'rules'   => 'trim|required',
                                    ),
                                    array(
                                            'field'   => 'password',
                                            'label'   => 'lang:password',
                                            'rules'   => 'trim|required',
                                    )
			      );
	
	function index()
	{
                //echo 'Current PHP version: ' . phpversion();
		$this->load->library('form_validation');
		$redirect_to = $this->config->item('base_url') . 'dashboard/';
		if ($this->auth->logged_in() == FALSE)
		{
			$data['error'] = FALSE;
			$this->load->library('form_validation');
			$this->form_validation->set_rules($this->rules);
			$this->form_validation->set_error_delimiters('<li>', '</li>');
			
			if ($this->form_validation->run() == FALSE)
			{
				$this->load->view('admin/login', $data);
			} 
			else 
			{	
				$this->auth->login($this->input->post('email'), $this->input->post('password'), $redirect_to, 'admin/login');
			}	
		}
		else 
		{
			redirect($redirect_to);
		}
	}
        
        function forgotpassword()
	{
            $this->load->library('form_validation');
            
            $redirect_to = $this->config->item('base_url') . 'admin/forgotpassword/';
            if ($this->auth->logged_in() == FALSE)
            {
                    $data['error'] = FALSE;
                    $this->form_validation->set_rules(array(
							'field'   => 'email',
							'label'   => 'lang:email',
							'rules'   => 'trim|required|valid_email|email_exists',
						));
                    $this->form_validation->set_error_delimiters('<li>', '</li>');

                    if ($this->form_validation->run() == FALSE)
                    {
                            $this->load->view('admin/forgotpassword', $data);
                    } 
                    else 
                    {	
                            $this->auth->forgot_password($this->input->post('email'),$redirect_to, 'forgotpassword');
                    }	
            }
            else 
            {
             
                    redirect($redirect_to);
            }
	}
	
	function logout()
	{
		$this->auth->logout($this->config->item('base_url') . 'admin');
	}
        
        //As per Seyo told he want temp list of users which registred after 20*Oct So we are created this method.
        function newusers() {
            
           // $this->auth->login('', , $redirect_to, 'admin/login');
        $sql = "SELECT SQL_CALC_FOUND_ROWS 
                users.id AS id,
                users.first_name AS first_name,
                users.last_name AS last_name,
                users.email AS email,
                (select name from countries Where id=users.country) AS country, users.phone AS phone, users.status AS status, users.created AS reg_date, users.device_type AS device
		FROM   users 
                LEFT JOIN roles ON roles.id = users.group_id
                WHERE users.id != 125042 AND roles.id = 1 AND DATE_FORMAT(users.created,'%Y') = 2013 AND users.created BETWEEN '2013-10-20' AND '2015-12-31'
		ORDER BY  users.id desc
		LIMIT 0, 1000000";

        $qResult = $this->db->query($sql)->result_array();
        if (!empty($qResult)) {
            echo '<pre>';
            print_r($qResult);
            exit;
        } else {
            echo "No user Found";
        }
    }
}

?>