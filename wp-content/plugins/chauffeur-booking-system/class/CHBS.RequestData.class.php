<?php

/******************************************************************************/
/******************************************************************************/

class CHBSRequestData
{
    /**************************************************************************/
    
    static function getFromWidget($serviceTypeId,$name)
    {
        if((int)self::get('service_type_id')!==(int)$serviceTypeId) return;
        
        return(self::get($name));
    }
    
    /**************************************************************************/

    static function getCoordinateFromWidget($serviceTypeId,$name)
    {
        if((int)self::get('service_type_id')!==(int)$serviceTypeId) return;
        
        return(json_encode(array('lat'=>self::get($name.'_lat'),'lng'=>self::get($name.'_lng'),'address'=>self::get($name.'_address'),'formatted_address'=>self::get($name.'_formatted_address'))));
    }
    
    /**************************************************************************/
    
    static function get($name)
    {
        if(array_key_exists($name,$_GET))
            return($_GET[$name]);
    
        return;
    }

    /**************************************************************************/
}

/******************************************************************************/
/******************************************************************************/