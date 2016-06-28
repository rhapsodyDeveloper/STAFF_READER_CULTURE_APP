<?php

class Subscriptionsmodel extends CI_Model {

    private $_ordersTable = 'orders';
    private $_subscriptionsTable = 'order_subscriptions';
    private $_usersTable = 'users';
    private $_booksTable = 'books';
    private $_plansTable = 'plans';
    private $_planTypesTable = 'plan_types';

    function __construct() {
        parent::__construct();
    }

    function get($params) {
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
        $aColumnsAlias = array('id', 'order_number', 'subscription_date', 'expiry_date', 'plan_title', 'content_title', 'user_email', 'payment_status');

        /* Table */
        $sTable = $this->_subscriptionsTable;

        /* Indexed column (used for fast and accurate table cardinality) */
        $sIndexColumn = $this->_subscriptionsTable . '.id';

        /* Where (don't include 'WHERE') */
        $qWhere = "$this->_usersTable.id != " . $this->session->userdata('user_id') . " and group_id != 3 ";

        /* Join */
        $sJoin = " LEFT JOIN $this->_ordersTable ON $this->_ordersTable.id = $this->_subscriptionsTable.order_id";
        $sJoin .= " LEFT JOIN $this->_usersTable ON $this->_usersTable.id = $this->_ordersTable.user_id";
        $sJoin .= " LEFT JOIN $this->_booksTable ON $this->_booksTable.id = $this->_ordersTable.book_id";
        $sJoin .= " LEFT JOIN $this->_plansTable ON $this->_plansTable.id = $this->_ordersTable.plan_id";
        /*

          /* Paging
         */
        $sLimit = "";
        if (isset($_GET['iDisplayStart']) && $_GET['iDisplayLength'] != '-1') {
            $sLimit = "LIMIT " . intval($_GET['iDisplayStart']) . ", " .
                    intval($_GET['iDisplayLength']);
        }

        /*
         * Ordering
         */
        $sOrder = "";
        if (isset($_GET['iSortCol_0'])) {
            $sOrder = "ORDER BY  ";
            for ($i = 0; $i < intval($_GET['iSortingCols']); $i++) {
                if ($_GET['bSortable_' . intval($_GET['iSortCol_' . $i])] == "true") {
                    $sOrder .= "" . $aColumns[intval($_GET['iSortCol_' . $i])] . " " .
                            ($_GET['sSortDir_' . $i] === 'asc' ? 'asc' : 'desc') . ", ";
                }
            }

            $sOrder = substr_replace($sOrder, "", -2);
            if ($sOrder == "ORDER BY") {
                $sOrder = "";
            }
        }

        /*
         * Filtering
         * NOTE this does not match the built-in DataTables filtering which does it
         * word by word on any field. It's possible to do here, but concerned about efficiency
         * on very large tables, and MySQL's regex functionality is very limited
         */
        $sWhere = "";
        if (isset($_GET['sSearch']) && $_GET['sSearch'] != "") {
            $sWhere = "WHERE (";
            for ($i = 0; $i < count($aColumns); $i++) {
                if (isset($_GET['bSearchable_' . $i]) && $_GET['bSearchable_' . $i] == "true") {
                    $sWhere .= "" . $aColumns[$i] . " LIKE '%" . mysql_real_escape_string($_GET['sSearch']) . "%' OR ";
                }
            }
            $sWhere = substr_replace($sWhere, "", -3);
            $sWhere .= ')';
        }

        /* Individual column filtering */
        for ($i = 0; $i < count($aColumns); $i++) {
            if (isset($_GET['bSearchable_' . $i]) && $_GET['bSearchable_' . $i] == "true" && $_GET['sSearch_' . $i] != '') {
                if ($sWhere == "") {
                    $sWhere = "WHERE ";
                } else {
                    $sWhere .= " AND ";
                }
                $sWhere .= "" . $aColumns[$i] . " LIKE '%" . mysql_real_escape_string($_GET['sSearch_' . $i]) . "%' ";
            }
        }
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

        if ($params['orderId'] != 'null') {
            if ($qWhere != '')
                $qWhere .= " AND ";
            $qWhere .= "$this->_subscriptionsTable.order_id = " . $params['orderId'];
        }
        if ($params['plan_type_id'] != 'null') {
            if ($qWhere != '')
                $qWhere .= " AND ";
            $qWhere .= "$this->_ordersTable.plan_id = " . $params['plan_type_id'];
        }

        /* Include query condition */
        if ($qWhere != '' && $sWhere != '')
            $sWhere = $sWhere . " AND " . $qWhere;
        elseif ($qWhere != '' && $sWhere == '')
            $sWhere = 'WHERE ' . $qWhere;

        /* Final columns with alias */
        foreach ($aColumns as $key => $value) {
            $fColumns[] = $value . ' AS ' . $aColumnsAlias[$key];
        }

        /*
         * SQL queries
         * Get data to display
         */
        $sQuery = "SELECT SQL_CALC_FOUND_ROWS " . str_replace(" , ", " ", implode(", ", $fColumns)) . "
                    FROM   $sTable
                    $sJoin
                    $sWhere
                    $sOrder
                    $sLimit";
        // echo $sQuery; exit;
        $qResult = $this->db->query($sQuery)->result_array();
        /* Data set length after filtering */
        $sQuery = "SELECT FOUND_ROWS() AS tot_rows";
        $aResultFilterTotal = $this->db->query($sQuery)->row_array();
        $iFilteredTotal = $aResultFilterTotal['tot_rows'];

        /* Total data set length */
        $sQuery = "SELECT COUNT(" . $sIndexColumn . ") AS tot FROM  $sTable";
        $aResultTotal = $this->db->query($sQuery)->row_array();
        $iTotal = $aResultTotal['tot'];

        /*
         * Output
         */

        $output = array(
            "sEcho" => intval($_GET['sEcho']),
            "iTotalRecords" => $iTotal,
            "iTotalDisplayRecords" => $iFilteredTotal,
            "aaData" => array()
        );

        foreach ($qResult as $aRow) {
            $row = array();
            for ($i = 0; $i < count($aColumnsAlias); $i++) {
                if ($aColumnsAlias[$i] == "version") {
                    /* Special output formatting for 'version' column */
                    $row[] = ($aRow[$aColumnsAlias[$i]] == "0") ? '-' : $aRow[$aColumnsAlias[$i]];
                } else if ($aColumnsAlias[$i] != ' ') {
                    /* General output */
                    $row[] = $aRow[$aColumnsAlias[$i]];
                }
            }
            $output['aaData'][] = $row;
        }

        return json_encode($output);
    }

    function get_item_by_id($id) {
        $this->db->select('order_subscriptions.id as subscription_id ,
                                   users.email,
                                   orders.order_number,
                                   plans.title as plan_title,
                                   plan_types.name as plan_type_name,
                                   books.title as content_title,
                                   order_subscriptions.subscription_date as subs_date,
                                   order_subscriptions.status as subscription_status,
                                   order_subscriptions.expiry_date,
                                 ');
        $this->db->join($this->_ordersTable, $this->_ordersTable . '.id = ' . $this->_subscriptionsTable . '.order_id', 'inner');
        $this->db->join($this->_usersTable, $this->_usersTable . '.id = ' . $this->_ordersTable . '.user_id', 'left');
        $this->db->join($this->_booksTable, $this->_booksTable . '.id = ' . $this->_ordersTable . '.book_id', 'left');
        $this->db->join($this->_plansTable, $this->_plansTable . '.id = ' . $this->_subscriptionsTable . '.plan_id', 'left');
        $this->db->join($this->_planTypesTable, $this->_planTypesTable . '.id = ' . $this->_plansTable . '.plan_type_id', 'left');

        return $this->db->get_where($this->_subscriptionsTable, array($this->_subscriptionsTable . '.id' => $id))->row();
    }

    function save() {
        //echo "<pre>"; print_r($this->input->post());exit;
        $id = $this->input->post('subscription_id');
        $data = array(
            'expiry_date' => date('Y-m-d', strtotime(str_replace('/', '-', $this->input->post('expiry_date')))),
            'status' => $this->input->post('subscription_status'),
        );
        //echo "<pre>";print_r();
        $this->db->set($data);
        $this->db->where('id', $id);
        $this->db->update($this->_subscriptionsTable);
    }

    /**
     * function:get plan_types
     * @return type plan_types
     */
    function get_plan_type() {
        $this->db->select(array('id', 'name'));
        $this->db->order_by("name", "ASC");
        return $this->db->get_where($this->_planTypesTable, array($this->_planTypesTable . '.status' => 'Active'))->result();
    }

    /**
     * function:get plans
     * @return type plans
     */
    function get_plan() {
        $this->db->select(array('id', 'title','plan_flag'));
        return $this->db->get_where($this->_plansTable, array($this->_plansTable . '.status' => 'Active'))->result();
    }

    /* End model file */
}

?>