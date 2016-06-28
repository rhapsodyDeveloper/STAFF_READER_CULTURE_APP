<?php

class Preload {
	function checkModule()
	{
		$CI =& get_instance();
		$module_name = $CI->router->fetch_module();
		$list_of_modules = $CI->config->item('modules_list');
		$CI->load->language($CI->config->item('admin_language'), $CI->config->item('admin_language'));		

		if($module_name && isset($list_of_modules) && !in_array($module_name, $list_of_modules)) show_error("Please declare you module name in config/modules.php file.");
	}

	function loadSettings()
	{
		$CI =& get_instance();
		foreach($CI->Commonmodel->get('settings') as $site_config)
		{
			$CI->config->set_item($site_config->key,$site_config->value);
		}
	}

	function loadLanguages()
	{
		$CI =& get_instance();
		foreach($CI->Commonmodel->get('languages') as $each)
		{
			$lang_array[$each->id] = $each->name;
		}
		ksort($lang_array);
		
		$CI->config->set_item('language_array',$lang_array);
		
		

	}


	
}