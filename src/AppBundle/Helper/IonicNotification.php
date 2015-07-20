<?php
/**
 * Created by PhpStorm.
 * User: steven
 * Date: 12/07/15
 * Time: 00:11
 */

namespace AppBundle\Helper;


class IonicNotification {
    protected $appId;
    protected $appKey;

    public function __construct($appId, $appKey)
    {
        $this->appId = $appId;
        $this->appKey = $appKey;
    }

    public function push($text, $deviceId)
    {
        // Get cURL resource
        $curl = curl_init();


        $data_string = '{"tokens":["'.$deviceId.'"],"notification":{"alert":"'.addslashes($text).'"}}';


        curl_setopt($curl, CURLOPT_HTTPHEADER,
            [
                'Content-Type: application/json' ,
                'Content-Length: ' . strlen($data_string)  ,
                'X-Ionic-Application-Id: '.$this->appId ,
                'Authorization: Basic '.base64_encode($this->appKey),
            ]
        );

        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data_string);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_URL, 'https://push.ionic.io/api/v1/push');

// Send the request & save response to $resp
        $resp = curl_exec($curl);
// Close request to clear up some resources
        curl_close($curl);
        return $resp;
    }
}