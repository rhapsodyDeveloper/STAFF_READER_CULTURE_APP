<?php
class Settingmodel extends CI_Model
{
	private $_settingTable = 'settings';
	function __construct()
	{
		parent::__construct();
	}
	
	public function get_all()
	{
		return $this->db->get($this->_settingTable)->result();
	}

	public function save_configs($settings){
		$success = true;	
		if(!empty($settings)){	
			foreach($settings as $key=>$value)
			{
				if(!$this->save($key,$value))
				{
					$success=false;
					break;  
				}
			}
		}else{
			$success=false;
		}
		return $success;
	}
  
        public function add()
        {
		$data = array(					  
                                'key' => $this->input->post('key'),
                                'value' => $this->input->post('value'),
                                'label' => $this->input->post('label'),
                                );
                //print_r($data);exit;
                $this->db->set($data);
		$this->db->insert($this->_settingTable);
		
        }        
	
        
	public function save($key,$value)
	{
		$config_data=array(
			'key'=>$key,
			'value'=>$value
		);
		$this->db->where('key', $key);
		return $this->db->update($this->_settingTable,$config_data); 
	}
}
?>