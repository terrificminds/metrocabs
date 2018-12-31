<?php

/******************************************************************************/
/******************************************************************************/

class CHBSNexmo
{
    /**************************************************************************/
    
    function __construct()
    {
        
    }
    
    /**************************************************************************/
    
    function sendSMS($apiKey,$apiKeySecret,$senderName,$recipientPhoneNumber,$message)
    {
        $data=array();
        
        $data['api_key']=$apiKey;
        $data['api_secret']=$apiKeySecret;
        
        $data['from']=$senderName;
        $data['to']=$recipientPhoneNumber;
        
        $data['text']=$message;
        
        $url.='https://rest.nexmo.com/sms/json?'.http_build_query($data);
        
        $ch=curl_init($url);
        
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
        curl_exec($ch);
        curl_close($ch);
    }

    /**************************************************************************/
}

/******************************************************************************/
/******************************************************************************/