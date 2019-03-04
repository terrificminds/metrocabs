<?php

/******************************************************************************/
/******************************************************************************/

class CHBSPostMeta
{
	/**************************************************************************/
	
	static function getPostMeta($post)
	{
		$data=array();
		
		$prefix=PLUGIN_CHBS_CONTEXT.'_';
		
		if(!is_object($post)) $post=get_post($post);
		
		$meta=get_post_meta($post->ID);
		
		if(!is_array($meta)) $meta=array();
		
		foreach($meta as $metaIndex=>$metaData)
		{
			if(preg_match('/^'.$prefix.'/',$metaIndex))
				$data[preg_replace('/'.$prefix.'/',null,$metaIndex)]=$metaData[0];
		}
        
		switch($post->post_type)
		{
			case PLUGIN_CHBS_CONTEXT.'_route':
                
                self::unserialize($data,array('coordinate','vehicle'));
                
				$Route=new CHBSRoute();
				$Route->setPostMetaDefault($data);
				
			break;
            
			case PLUGIN_CHBS_CONTEXT.'_vehicle':
                
                self::unserialize($data,array('attribute','date_exclude'));
                
				$Vehicle=new CHBSVehicle();
				$Vehicle->setPostMetaDefault($data);
				
			break;
            
			case PLUGIN_CHBS_CONTEXT.'_vehicle_attr':
                
                self::unserialize($data,array('attribute_value'));

				$VehicleAttribute=new CHBSVehicleAttribute();
				$VehicleAttribute->setPostMetaDefault($data);
				
			break;
            
			case PLUGIN_CHBS_CONTEXT.'_booking_extra':
                
				$BookingExtra=new CHBSBookingExtra();
				$BookingExtra->setPostMetaDefault($data);
				
			break;
        
			case PLUGIN_CHBS_CONTEXT.'_booking_form':
                
                self::unserialize($data,array('service_type_id','transfer_type_enable','vehicle_category_id','booking_extra_category_id','route_id','business_hour','break_hour','waypoint_country_available','date_exclude','payment_id','google_map_route_avoid','style_color','form_element_panel','form_element_field','form_element_agreement'));
  
				$BookingForm=new CHBSBookingForm();
				$BookingForm->setPostMetaDefault($data);
				
			break;
        
			case PLUGIN_CHBS_CONTEXT.'_booking':
                
                self::unserialize($data,array('booking_extra','coordinate','payment_data','form_element_panel','form_element_field'));
  
				$Booking=new CHBSBooking();
				$Booking->setPostMetaDefault($data);
				
			break;
        
			case PLUGIN_CHBS_CONTEXT.'_price_rule':
                
                self::unserialize($data,array('booking_form_id','service_type_id','route_id','vehicle_id','pickup_day_number','pickup_date','pickup_time','distance','passenger','duration'));
                
				$PriceRule=new CHBSPriceRule();
				$PriceRule->setPostMetaDefault($data);
                
			break;
               
			case PLUGIN_CHBS_CONTEXT.'_tax_rate':
                
				$TaxRate=new CHBSTaxRate();
				$TaxRate->setPostMetaDefault($data);
				
			break;
        
			case PLUGIN_CHBS_CONTEXT.'_email_account':
                
				$EmailAccount=new CHBSEmailAccount();
				$EmailAccount->setPostMetaDefault($data);
				
			break;
		}
		
		return($data);
	}
    
    /**************************************************************************/
    
    static function unserialize(&$data,$unserializeIndex)
    {
        foreach($unserializeIndex as $index)
        {
            if(isset($data[$index]))
                $data[$index]=maybe_unserialize($data[$index]);
        }
    }
	
	/**************************************************************************/
	
	static function updatePostMeta($post,$name,$value)
	{
		$name=PLUGIN_CHBS_CONTEXT.'_'.$name;
		$postId=(int)(is_object($post) ? $post->ID : $post);
		
		update_post_meta($postId,$name,$value);
		update_post_meta($postId,'remarks',$post['remarksgit ']);
	}
    
	/**************************************************************************/
	
	static function removePostMeta($post,$name)
	{
		$name=PLUGIN_CHBS_CONTEXT.'_'.$name;
		$postId=(int)(is_object($post) ? $post->ID : $post);
		
		delete_post_meta($postId,$name);
	}
    	
	/**************************************************************************/
	
	static function createArray(&$array,$index)
	{
		$array=array($index=>array());
		return($array);
	}

	/**************************************************************************/
}

/******************************************************************************/
/******************************************************************************/