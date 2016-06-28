<?php

/**
 * Push Notification Model
 *
 */
class Pushmodel extends CI_Model {

    private $user_deviceTable = 'devices';

    function __construct() {
        parent::__construct();
    }

    /**
     * Send Notification to Users
     *
     */
    function send() {
        
        $sendANDROID_flag = FALSE;
        $sendIOS_flag = FALSE;

        $sendto = $this->input->post('send_to');
        $push_message = $this->input->post('message');
        $total_option_selected = count($sendto);
        
        if ($total_option_selected > 0) {
            if ($total_option_selected == 1 && $sendto[0] == "android") {
                $sendANDROID = $sendto[0];
                $sendIOS = '';
                $sendANDROID_flag = TRUE;
            } else if ($total_option_selected == 1 && $sendto[0] == "ios") {
                $sendIOS = $sendto[0];
                $sendANDROID = '';
                $sendIOS_flag = TRUE;
            } else if ($total_option_selected == 2) {
                $sendIOS = $sendto[0];
                $sendANDROID = $sendto[1];
                $sendANDROID_flag = TRUE;
                $sendIOS_flag = TRUE;
            }
        }
        
        if ($sendANDROID_flag == TRUE && $sendIOS_flag == TRUE) {
            $where = " where $this->user_deviceTable.device_code != 'NoToken' AND $this->user_deviceTable.device_code !='' ";
            $sQuery = "select $this->user_deviceTable.device_type, $this->user_deviceTable.device_code from $this->user_deviceTable" . $where . " GROUP BY device_code ORDER BY modified DESC";
        } elseif ($sendANDROID_flag == TRUE) {
            $where = " where $this->user_deviceTable.device_type = 'Android' AND $this->user_deviceTable.device_code!=''";
            $sQuery = "select $this->user_deviceTable.device_type, $this->user_deviceTable.device_code from $this->user_deviceTable " . $where . " GROUP BY device_code ORDER BY modified DESC";
        } else {
            $where = " where $this->user_deviceTable.device_type != 'Android' AND ($this->user_deviceTable.device_code != 'NoToken' AND $this->user_deviceTable.device_code !='')";
            $sQuery = "select $this->user_deviceTable.device_type, $this->user_deviceTable.device_code from $this->user_deviceTable" . $where . " GROUP BY device_code ORDER BY modified DESC";
        }

        $row_devices = $qResult = $this->db->query($sQuery)->result_array();
        $total_row = count($row_devices);
        
        
        /* Notifications For Google devices */
        require_once(APPPATH . "libraries/androidPushNotify.php");
        $pushAndroObj = new NicAndroidPushNotify();
        $google_api_key = "AIzaSyBoEYvJBhiYmGBupOg4TO1HPTYAMMK8brc";
       // $deviceToken = 'APA91bFNlaChGRAZyuEmXuakfEty5Bol3_f2hgm0-GQtbkt4dxq8t25_1oITXDOOlP0eopWjMuHHfVKtsiZmWkF0xBxBzF3Dyto9Xa4OQR-UgwrySTxez9MWL-_tybBb1pGTaA-B4W14AqfOvKPheM7EjWZY-8Xm0w';

        /* Notifications For IOS devices */
        $deviceToken = 'a719e0a92fc61dff2f7d7a79cb1646a8a9d5dd47';
        require_once(APPPATH . "libraries/iphonepushnotify.php");
        $apnPath = FCPATH . 'application/libraries/pushcert_07.pem';
        //$apnPath = FCPATH . 'application/libraries/pushcert_3.pem';
        $pushObj = new IphonePushNotify($apnPath);           
        
        if ($total_row > 0) {
            foreach ($row_devices as $devices) {
                $deviceToken = $devices['device_code'];
                if ($devices['device_type'] == 'Android') {
                    
                   // $deviceToken = 'APA91bFNlaChGRAZyuEmXuakfEty5Bol3_f2hgm0-GQtbkt4dxq8t25_1oITXDOOlP0eopWjMuHHfVKtsiZmWkF0xBxBzF3Dyto9Xa4OQR-UgwrySTxez9MWL-_tybBb1pGTaA-B4W14AqfOvKPheM7EjWZY-8Xm0w';
                    $REsult = $pushAndroObj->LatestSendAndroidNotificaiton($deviceToken, $push_message, $google_api_key);
                    //break;
                } else {
                   //$deviceToken = 'ba92ef94cfd9bea0cd4af5d87d781b98aacccf8a778c7d01d56729b7bc6a7dd0';
                    //$deviceToken = 'e9073101271dab348d6f7444112249637383e8fe';
                    $res = $pushObj->send_direct($deviceToken, $push_message);
                    //break;
                }
            }/* End for each */
            return true;
        }/* End of condition */
        return false;
    }

}

?>