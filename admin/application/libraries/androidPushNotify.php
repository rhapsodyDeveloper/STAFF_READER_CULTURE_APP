<?php
//androidPushNotify.cls

//////////////////////////////////////////////////////////
 
# This Class will be use to send push notification to Android device.

//////////////////////////////////////////////////////////
class NicAndroidPushNotify {

	
	//put your code here
    // constructor
    function __construct() {

    	//define("GOOGLE_API_KEY", "AIzaSyDzmxls-YNN1pH_DXYOhWwWtpIUqTwkgPg"); // Place your Google API Key 
         
    }
 
    /**
     * Sending Push Notification
     */
    public function send_notification($registatoin_ids, $message) {
        // include config
       define("GOOGLE_API_KEY", "AIzaSyDzmxls-YNN1pH_DXYOhWwWtpIUqTwkgPg"); // Place your Google API Key 
        // Set POST variables
        $url = 'https://android.googleapis.com/gcm/send';
 
        $fields = array(
            'registration_ids' => $registatoin_ids,
            'data' => $message,
        );
 
        $headers = array(
            'Authorization: key=' . GOOGLE_API_KEY,
            'Content-Type: application/json'
        );
        // Open connection
        $ch = curl_init();
 
        // Set the url, number of POST vars, POST data
        curl_setopt($ch, CURLOPT_URL, $url);
 
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
 
        // Disabling SSL Certificate support temporarly
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
 
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
 
        // Execute post
        $result = curl_exec($ch);
        if ($result === FALSE) {
            die('Curl failed: ' . curl_error($ch));
        }
 
        // Close connection
        curl_close($ch);
        return $result;
    }
	function LatestSendAndroidNotificaiton($token,$message,$apiKey)
    {
        $url = 'https://android.googleapis.com/gcm/send';
        $api_key1         =   $apiKey;
        $device_token    =   $token;
		//echo "<br>API LATEST = ".$api_key1."<br/>";
        
        $headers        = array( 
                                    'Authorization: key=' . $api_key1,
                                    'Content-Type: application/json'
                                ); 

         
        $data = array(
            'registration_ids' => array($device_token),
            'data'  =>   array('message'=>$message,'key'=>'1')
        );


        $ch = curl_init();      
        
        curl_setopt($ch, CURLOPT_URL, $url);
        
        if ($headers){
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        }
        
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));       
    
        $response = curl_exec($ch);

        preg_match("/(error=)([\\w|-]+)/", $response, $matches);        
       /* echo '<pre>';
        print_r($response);
		print_r($matches);
		echo'</pre>';*/
		if($matches){
        if (!$matches[2]) { 
            return false;
        }
		}
        curl_close($ch);
        
        return true;
    }

}
