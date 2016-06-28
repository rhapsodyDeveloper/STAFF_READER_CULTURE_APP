<?php

/**
 * @name: subscription.php
 * 
 * @desc: Order Subscriptions  Controller
 * 
 * @author: Ravindra Shekhawat
 */
class Subscriptions extends CI_Controller {

    private $data = array();
    private $_ordersTable = 'orders';
    private $_subscriptionsTable = 'order_subscriptions';
    private $_usersTable = 'users';
    private $_booksTable = 'books';
    private $_plansTable = 'plans';
    private $_planTypesTable = 'plan_types';

    function __construct() {
        parent::__construct();
        $this->template->set('controller', $this);
        $this->data['module_name'] = $this->router->fetch_module();
        $this->load->model('commonmodel');
        $this->load->model('subscriptionsmodel');
        //$this->output->enable_profiler(TRUE);
    }

    private $table = 'order_subscriptions';
    private $rules = array(
        array(
            'field' => 'order_number',
            'label' => 'lang:order_number',
            'rules' => 'trim|required',
        ),
    );

    /**
     * @desc:  Order Subscriptions listing
     * @return Order Subscriptions listing with limits
     */
    function index($num = 0) {
        $this->auth->restrict(2);
         $this->data['plan_title'] = $this->subscriptionsmodel->get_plan();
        $this->template->load_partial('admin/admin_master', 'subscriptions/index', $this->data);
    }
    
    /**
    * @desc:  Get order listing
    * @return Get order listing with limits
    */
    function get() {
         $params = $this->uri->uri_to_assoc(3);
        $result = $this->subscriptionsmodel->get($params);
        echo $result;
        exit;
    }
	

     /**
     * @desc:  Edit Orders Subscriptions
     * @return Save results with success/error message
     */
    function edit($id) {
        $this->auth->restrict(3);
        $this->load->library('form_validation');
        $this->form_validation->set_rules($this->rules);
        $this->form_validation->set_error_delimiters('<li>', '</li>');
        $this->data['status'] = $this->config->item('status');
        $this->data['plan_title'] = $this->subscriptionsmodel->get_plan();
        
        $this->data['query'] = $this->subscriptionsmodel->get_item_by_id($id);
        if (!$this->input->post('submit')) {
            $this->template->load_partial('admin/admin_master', 'subscriptions/form', $this->data);
        } else {
            if ($this->form_validation->run() == FALSE) {
                $this->template->load_partial('admin/admin_master', 'subscriptions/form', $this->data);
            } else {
                $this->subscriptionsmodel->save();
                $this->session->set_flashdata('info', $this->lang->line('success_edit'));
                redirect(base_url() . 'subscriptions');
            }
        }
    }

     /**
     * @desc:  Delete Order
     * @return Delete results with success/error message
     */
    function delete($id) {
        $this->auth->restrict(2);
        $this->commonmodel->delete($this->table, $id);
        $this->session->set_flashdata('info', $this->lang->line('success_delete'));
        redirect(base_url() . 'subscriptions');
    }

/**
     * export csv
     *
     * @return csv
     */
    function exportCsv() 
    {       
	  $this->load->dbutil();	
          //print_r($_REQUEST);
          //exit;
	  $params = $this->uri->uri_to_assoc(3);
          $params['year'] = $_REQUEST['year'];
          $params['from'] = $_REQUEST['from'];
          $params['to'] = $_REQUEST['to'];
          $params['plan_type_id'] = $_REQUEST['plan_type_id'];
          $aColumns = array(  
                            "$this->_subscriptionsTable.id",
                            "$this->_ordersTable.order_number",
                            "DATE_FORMAT($this->_subscriptionsTable.subscription_date,'" . DATE_FORMAT_SQL . "')",
                            "DATE_FORMAT($this->_subscriptionsTable.expiry_date,'" . DATE_FORMAT_SQL . "')",
                            "$this->_usersTable.email",
                            "$this->_plansTable.title",
                            "$this->_booksTable.title",    
                            "$this->_ordersTable.payment_status",    
                        );
        $aColumnsAlias = array('id', 'order_number', 'subscription_date', 'expiry_date', 'Email', 'plan', 'book','payment_status');
		
        /* Table*/
        $sTable = $this->_subscriptionsTable;
        
        /* Indexed column (used for fast and accurate table cardinality) */
        $sIndexColumn = $this->_subscriptionsTable . '.id';

        /* Where (don't include 'WHERE') */
        $qWhere = "$this->_usersTable.id != ".$this->session->userdata('user_id')." and group_id != 3 ";
        
        /* Join */        
        $sJoin = " LEFT JOIN $this->_ordersTable ON $this->_ordersTable.id = $this->_subscriptionsTable.order_id";
        $sJoin .= " LEFT JOIN $this->_usersTable ON $this->_usersTable.id = $this->_ordersTable.user_id";
        $sJoin .= " LEFT JOIN $this->_booksTable ON $this->_booksTable.id = $this->_ordersTable.book_id";
        $sJoin .= " LEFT JOIN $this->_plansTable ON $this->_plansTable.id = $this->_ordersTable.plan_id";
        $sOrder = "ORDER BY $this->_subscriptionsTable.id desc";

        /*
         * Filtering
         * NOTE this does not match the built-in DataTables filtering which does it
         * word by word on any field. It's possible to do here, but concerned about efficiency
         * on very large tables, and MySQL's regex functionality is very limited
         */
        
        if ($params['year'] != 'null') {
            if ($qWhere != '')
                $qWhere .= " AND ";
            $qWhere .= "DATE_FORMAT($this->_subscriptionsTable.subscription_date,'%Y') = " . $params['year'];
        }

        if ($params['from'] != 'null' && $params['to'] != 'null') {
            if ($qWhere != '')
                $qWhere .= " AND ";
            $qWhere .= $this->_subscriptionsTable . '.subscription_date BETWEEN "' . $params['from'] . '" AND "' . $params['to'] . '"';
        }
        
        if ($params['plan_type_id'] != 'null') {
            if ($qWhere != '')
                $qWhere .= " AND ";
            $qWhere .= "$this->_ordersTable.plan_id = " . $params['plan_type_id'];
        }
        
        if($qWhere != '')
        $sWhere = " WHERE ".$qWhere;
        else
        $sWhere = ''; 
        
        /* Final columns with alias */
        foreach ($aColumns as $key => $value) {
            $fColumns[] = $value . ' AS ' . $aColumnsAlias[$key];
        }

        /*
         * SQL queries
         * Get data to display
         */
        $sQuery = "
		SELECT SQL_CALC_FOUND_ROWS " . str_replace(" , ", " ", implode(", ", $fColumns)) . "
		FROM   $sTable
		$sJoin
		$sWhere        
		$sOrder		
		";
        $query = $this->db->query($sQuery);
        header("Content-type: application/ms-excel");
      	header("Content-disposition: csv" . date("d-m-Y") . ".csv");
      	header("Content-disposition: filename=download-subscription-".date("d-m-Y").".csv");	
        print $this->dbutil->csv_from_result($query);
        exit;
    }

}