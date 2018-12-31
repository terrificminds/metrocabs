<?php

/******************************************************************************/
/******************************************************************************/

class CHBSBookingHelper
{
	/**************************************************************************/
	
	static function getPaymentName($paymentId,$wooCommerceEnable=-1,$meta=array())
	{
        $Payment=new CHBSPayment();
        $WooCommerce=new CHBSWooCommerce();
        
        if($wooCommerceEnable===-1)
            $wooCommerceEnable=$WooCommerce->isEnable($meta);
        
        if($wooCommerceEnable)
        {
           $paymentName=$WooCommerce->getPaymentName($paymentId);
        }
        else
        {
            $paymentName=$Payment->getPaymentName($paymentId);
        }
        
        return($paymentName);
	}
        
    /**************************************************************************/
    
    static function isPayment(&$paymentId,$meta)
    {
        $Payment=new CHBSPayment();
        $WooCommerce=new CHBSWooCommerce();
        
        if((int)$meta['price_hide']===1)
        {
            $paymentId=0;
            return(true);
        }
        
        if((int)$meta['payment_mandatory_enable']===0)
        {
            if($WooCommerce->isEnable($meta))
            {
                if(empty($paymentId))
                {
                    $paymentId=0;
                    return(true);
                }
            }
            else
            {
                if($paymentId==0)
                {
                    return(true);
                }
            }
        }
        
        if($WooCommerce->isEnable($meta))
        {
            return($WooCommerce->isPayment($paymentId));
        }
        else
        {
            if(!$Payment->isPayment($paymentId)) return(false);
        }
        
        return(true);
    }
    
    /**************************************************************************/
    
    static function isPaymentDepositEnable($meta,$bookingId=-1)
    {
        if((int)$meta['price_hide']===1)
        {
            return(0);
        }
        
        if($bookingId==-1)
        {
            $WooCommerce=new CHBSWooCommerce();
            if($WooCommerce->isEnable($meta)) return(0);
        }
        
        return((int)$meta['payment_deposit_enable']);
    }

    /**************************************************************************/
    
    static function isPassengerEnable($meta,$serviceType=1,$passengerType='adult')
    {
        if($passengerType===-1)
        {
            return($meta['passenger_adult_enable_service_type_'.$serviceType] && $meta['passenger_children_enable_service_type_'.$serviceType]);
        }
        
        return($meta['passenger_'.$passengerType.'_enable_service_type_'.$serviceType]);
    }
    
    /**************************************************************************/
    
    static function getPassenegerSum($meta,$data)
    {
        $sum=0;
        
        if(CHBSBookingHelper::isPassengerEnable($meta,$data['service_type_id'],'adult'))
            $sum+=$data['passenger_adult_service_type_'.$data['service_type_id']];
            
        if(CHBSBookingHelper::isPassengerEnable($meta,$data['service_type_id'],'children'))
            $sum+=$data['passenger_children_service_type_'.$data['service_type_id']];            
        
        return($sum);
    }
    
    /**************************************************************************/
    
    static function getPassengerLabel($numberAdult,$numberChildren,$type=1)
    {
        $html=null;
        
        $Validation=new CHBSValidation();
        
        if($type===1)
        {
            if(($numberAdult>0) && ($numberChildren==0))
                $html=sprintf(__('%s passengers','chauffeur-booking-system'),$numberAdult);
            else
            {
                if($numberAdult>0)
                    $html=sprintf(__('%s adults','chauffeur-booking-system'),$numberAdult);
                
                if($numberChildren>0)
                {
                    if($Validation->isNotEmpty($html)) $html.=', ';
                    $html.=sprintf(__('%s children','chauffeur-booking-system'),$numberChildren);
                }
            }
        }
        
        return($html);
    }
    
    /**************************************************************************/
}

/******************************************************************************/
/******************************************************************************/