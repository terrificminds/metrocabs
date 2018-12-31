<?php

/******************************************************************************/
/******************************************************************************/

class CHBSGeoLocation
{
    /**************************************************************************/

    function __construct()
    {
        
    }
    
    /**************************************************************************/
    
    function getIPAddress()
    {
        $address=null;
        
        $data=array('HTTP_CLIENT_IP','HTTP_X_FORWARDED_FOR','REMOTE_ADDR');
        
        foreach($data as $value)
        {
            if(array_key_exists($value,$_SERVER))
            {
                $address=$_SERVER[$value];
                break;
            }     
        }

        return($address);
    }
    
    /**************************************************************************/
    
    function getCountryCode()
    {
        $document=$this->getDocument();
        if($document===false) return(null);
        return(strval($document->geoplugin_countryCode));
    }
    
    /**************************************************************************/
    
    function getCoordinate()
    {
        $Validation=new CHBSValidation();
        
        if(($document=$this->getDocument())===false)
            return(array('lat'=>0,'lng'=>0));
        
        $coordinate=array
        (
            'lat'                                                               =>  strval($document->geoplugin_latitude),
            'lng'                                                               =>  strval($document->geoplugin_longitude)
        );
        
        foreach($coordinate as $index=>$value)
        {
            if($Validation->isEmpty($value))
                $coordinate[$index]=0;
        }
        
        return($coordinate);
    }
    
    /**************************************************************************/
    
    function getDocument()
    {
        if(($document=simplexml_load_file('http://www.geoplugin.net/xml.gp?ip='.$this->getIPAddress()))===false) return(false);
        return($document);
    }
    
    /**************************************************************************/
}

/******************************************************************************/
/******************************************************************************/