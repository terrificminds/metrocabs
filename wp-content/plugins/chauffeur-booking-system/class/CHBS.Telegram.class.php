<?php

/******************************************************************************/
/******************************************************************************/

class CHBSTelegram
{
    /**************************************************************************/
    
    function __construct()
    {
        
    }
    
    /**************************************************************************/
    
    function sendMessage($token,$groupId,$message)
    {
		$url=add_query_arg(array('chat_id'=>'-'.$groupId,'text'=>urlencode($message)),'https://api.telegram.org/bot'.$token.'/sendMessage');

        $ch=curl_init($url);
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
		curl_setopt($ch,CURLOPT_SSL_VERIFYHOST,0);
		curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,0);
        curl_exec($ch);
        curl_close($ch);
    }

    /**************************************************************************/
}

/******************************************************************************/
/******************************************************************************/