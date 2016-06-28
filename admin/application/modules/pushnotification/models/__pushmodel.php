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

        $sendto = $this->input->post('send_to');
        $push_message = $this->input->post('message');
        $total_option_selected = count($sendto);
        if ($total_option_selected > 0) {
            if ($total_option_selected == 1 && $sendto[0] == "android") {
                $sendANDROID = $sendto[0];
                $sendIOS = '';
            } else if ($total_option_selected == 1 && $sendto[0] == "ios") {
                $sendIOS = $sendto[0];
                $sendANDROID = '';
            } else if ($total_option_selected == 2) {
                $sendIOS = $sendto[0];
                $sendANDROID = $sendto[1];
            }
        }

        if ($sendANDROID <> "" && $sendANDROID == "android" && $sendIOS == "") {
            $where = " where $this->user_deviceTable.device_type = 'Android' AND $this->user_deviceTable.device_code!=''";
            $sQuery = "select $this->user_deviceTable.device_type, $this->user_deviceTable.device_code from $this->user_deviceTable " . $where . " ORDER BY modified DESC LIMIT 1";
        }/* This condition is for ANDROID ONLY */ else if ($sendIOS <> "" && $sendANDROID == "") {
            $where = " where $this->user_deviceTable.device_type != 'Android' AND ($this->user_deviceTable.device_code != 'NoToken' AND $this->user_deviceTable.device_code !='')";
            $sQuery = "select $this->user_deviceTable.device_type, $this->user_deviceTable.device_code from $this->user_deviceTable" . $where . " ORDER BY modified DESC LIMIT 1";
        }/* This condition is for IOS ONLY */ else if ($sendIOS <> "" && $sendANDROID <> "") {
            $where = " where $this->user_deviceTable.device_code != 'NoToken' AND $this->user_deviceTable.device_code !='' ";
            $sQuery = "select $this->user_deviceTable.device_type, $this->user_deviceTable.device_code from $this->user_deviceTable" . $where . " ORDER BY modified DESC LIMIT 1";
        }/* This condition is for BOTH IOS and ANDROID ONLY */
        //echo $sQuery;
        $row_devices = $qResult = $this->db->query($sQuery)->result_array();
        $total_row = count($row_devices);

        if ($total_row > 0) {
            foreach ($row_devices as $devices) {
                $deviceToken = $devices['device_code'];
                if ($devices['device_type'] == 'Android') {
                    //echo 'in android';
                    require_once(APPPATH . "libraries/androidPushNotify.php");
                    $pushAndroObj = new NicAndroidPushNotify();
                    //$google_api_key = "AIzaSyDDyjVXe9YVoC4pTNHWkESv-5AebupuVoc";
                    $google_api_key = "AIzaSyBoEYvJBhiYmGBupOg4TO1HPTYAMMK8brc";
                    $pushAndroObj->LatestSendAndroidNotificaiton($deviceToken, $push_message, $google_api_key);
                } else {/* Notifications For IOS devices */
                    //echo 'in ios';						
                    require_once(APPPATH . "libraries/iphonepushnotify.php");
                    $apnPath = FCPATH . 'application/libraries/rhapsodyOfRealities.pem';
                    $pushObj = new IphonePushNotify($apnPath);
                    $pushObj->send_direct($deviceToken, $push_message);
                }
            }/* End for each */
            return true;
        }/* End of condition */
        return false;
    }

}

?>