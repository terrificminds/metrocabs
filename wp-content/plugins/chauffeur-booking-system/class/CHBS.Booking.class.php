<?php

/******************************************************************************/
/******************************************************************************/

class CHBSBooking
{
	/**************************************************************************/
	
    function __construct()
    {
        
    }
        
    /**************************************************************************/
    
    public function init()
    {
        $this->registerCPT();
    }
    
	/**************************************************************************/

    public static function getCPTName()
    {
        return(PLUGIN_CHBS_CONTEXT.'_booking');
    }
    
    /**************************************************************************/
    
    private function registerCPT()
    {
		register_post_type
		(
            self::getCPTName(),
            array
            (
                'labels'														=>	array
                (
                    'name'														=>	__('Bookings','chauffeur-booking-system'),
                    'singular_name'												=>	__('Booking','chauffeur-booking-system'),
                    'edit_item'													=>	__('Edit Booking','chauffeur-booking-system'),
                    'all_items'													=>	__('Bookings','chauffeur-booking-system'),
                    'view_item'													=>	__('View Booking','chauffeur-booking-system'),
                    'search_items'												=>	__('Search Bookings','chauffeur-booking-system'),
                    'not_found'													=>	__('No Bookings Found','chauffeur-booking-system'),
                    'not_found_in_trash'										=>	__('No Bookings Found in Trash','chauffeur-booking-system'), 
                    'parent_item_colon'											=>	'',
                    'menu_name'													=>	__('Chauffeur Booking System','chauffeur-booking-system')
                ),	
                'public'														=>	false,  
                'menu_icon'														=>	'dashicons-calendar-alt',
                'show_ui'														=>	true,  
                'capability_type'												=>	'post',
                'capabilities'													=>	array
                (
                     'create_posts'												=>	'do_not_allow',
                ),
                'map_meta_cap'													=>	true, 
                'menu_position'													=>	100,
                'hierarchical'													=>	false,  
                'rewrite'														=>	false,  
                'supports'														=>	array('title','page-attributes')  
            )
        );
        
        add_action('save_post',array($this,'savePost'));
        
        add_action('add_meta_boxes_'.self::getCPTName(),array($this,'addMetaBox'));
        add_filter('postbox_classes_'.self::getCPTName().'_chbs_meta_box_booking_form',array($this,'adminCreateMetaBoxClass'));
        
		add_filter('manage_edit-'.self::getCPTName().'_columns',array($this,'manageEditColumns')); 
		add_action('manage_'.self::getCPTName().'_posts_custom_column',array($this,'managePostsCustomColumn'));
		add_filter('manage_edit-'.self::getCPTName().'_sortable_columns',array($this,'manageEditSortableColumns'));
        
        add_action('restrict_manage_posts',array($this,'restrictManagePosts'));
        add_filter('parse_query',array($this,'parseQuery'));
    }
    
    /**************************************************************************/
    
    function addMetaBox()
    {
        add_meta_box(PLUGIN_CHBS_CONTEXT.'_meta_box_booking_form',__('Main','chauffeur-booking-system'),array($this,'addMetaBoxMain'),self::getCPTName(),'normal','low');		
    }
    
    /**************************************************************************/
    
    function addMetaBoxMain()
    {
        global $post;
                
        $data=$this->getBooking($post->ID);
            
        $data['nonce']=CHBSHelper::createNonceField(PLUGIN_CHBS_CONTEXT.'_meta_box_booking');
        
        $data['billing']=$this->createBilling($post->ID);
        
		$Template=new CHBSTemplate($data,PLUGIN_CHBS_TEMPLATE_PATH.'admin/meta_box_booking.php');
		echo $Template->output();	        
    }
    
    /**************************************************************************/
    
    function getBookingPaymentName($meta)
    {
        if($meta['woocommerce_enable'])
            return($meta['payment_name']);
        else
        {
            $Payment=new CHBSPayment();
            return($Payment->getPaymentName($meta['payment_id']));
        }
    }
    
    /**************************************************************************/
    
    function getBooking($bookingId)
    {
        $post=get_post($bookingId);
       
		if(is_null($post)) return(false);
        
        $booking=array();
        
        $Country=new CHBSCountry();
        $WooCommerce=new CHBSWooCommerce();
        $ServiceType=new CHBSServiceType();
        $BookingForm=new CHBSBookingForm();
        $BookingStatus=new CHBSBookingStatus();
        
        $booking['post']=$post;
        $booking['meta']=CHBSPostMeta::getPostMeta($post);		
        
        $serviceType=$ServiceType->getServiceType($booking['meta']['service_type_id']);
        $booking['service_type_name']=$serviceType[0];
        
        if($booking['meta']['service_type_id']==3)
        {
            $TransferType=new CHBSTransferType();
            $transferType=$TransferType->getTransferType($booking['meta']['transfer_type_id']);
            
            $booking['transfer_type_name']=$transferType[0];
        }
    
        if($booking['meta']['client_billing_detail_enable']==1)
        {
            $country=$Country->getCountry($booking['meta']['client_billing_detail_country_code']);
            $booking['client_billing_detail_country_name']=$country[0];
        }
        
        if(!empty($booking['meta']['payment_id']))
            $booking['payment_name']=$this->getBookingPaymentName($booking['meta']);
        
        if($BookingStatus->isBookingStatus($booking['meta']['booking_status_id']))
        {
            $bookingStatus=$BookingStatus->getBookingStatus($booking['meta']['booking_status_id']);
            $booking['booking_status_name']=$bookingStatus[0];
        }
          
        /***/
        
        $booking['dictionary']['booking_status']=$BookingStatus->getBookingStatus();
        
        /***/
        
        $booking['woocommerce_payment_url']=null;
        if($booking['meta']['woocommerce_enable']==1)
        {
            $dictionary=$BookingForm->getDictionary(array('booking_form_id'=>$booking['meta']['booking_form_id']));
            if((count($dictionary)===1) && (array_key_exists($booking['meta']['booking_form_id'],$dictionary)))
            {
                if($WooCommerce->isEnable($dictionary[$booking['meta']['booking_form_id']]['meta']))
                    $booking['woocommerce_payment_url']=$WooCommerce->getPaymentURLAddress($bookingId+1);
            }
        }
        
        /***/
        
        return($booking);
    }
    
    /**************************************************************************/
    
    function adminCreateMetaBoxClass($class) 
    {
        array_push($class,'to-postbox-1');
        return($class);
    }
    
    /**************************************************************************/
    
    function sendBooking($data,$bookingForm)
    {      
        $bookingId=wp_insert_post(array
        (
            'post_type'                                                         =>  self::getCPTName(),
            'post_status'                                                       =>  'publish'
        ));
        
        if($bookingId===0) return(false);
        
        wp_update_post(array
        (
 			'ID'																=>	$bookingId,
			'post_title'														=>	$this->getBookingTitle($bookingId)           
        ));
        
        /***/
        
        $TaxRate=new CHBSTaxRate();
        $Vehicle=new CHBSVehicle();
        $WooCommerce=new CHBSWooCommerce();
        $BookingFormElement=new CHBSBookingFormElement();
        
        $taxRateDictionary=$TaxRate->getDictionary();
        
        /***/
        
        $passenger=array('enable'=>0,'adult'=>0,'children'=>0);
        
        if((CHBSBookingHelper::isPassengerEnable($bookingForm['meta'],$data['service_type_id'],'adult')) || (CHBSBookingHelper::isPassengerEnable($bookingForm['meta'],$data['service_type_id'],'children')))
        {
            $passenger['enable']=1;
            
            if(CHBSBookingHelper::isPassengerEnable($bookingForm['meta'],$data['service_type_id'],'adult'))
                $passenger['adult']=$data['passenger_adult_service_type_'.$data['service_type_id']];
            
            if(CHBSBookingHelper::isPassengerEnable($bookingForm['meta'],$data['service_type_id'],'children'))
                $passenger['children']=$data['passenger_children_service_type_'.$data['service_type_id']];            
        }
        
        CHBSPostMeta::updatePostMeta($bookingId,'passenger_enable',$passenger['enable']);
        CHBSPostMeta::updatePostMeta($bookingId,'passenger_adult_number',$passenger['adult']);
        CHBSPostMeta::updatePostMeta($bookingId,'passenger_children_number',$passenger['children']);
        
        CHBSPostMeta::updatePostMeta($bookingId,'hide_fee',$bookingForm['meta']['hide_fee']);
        CHBSPostMeta::updatePostMeta($bookingId,'price_hide',$bookingForm['meta']['price_hide']);
        
        CHBSPostMeta::updatePostMeta($bookingId,'woocommerce_enable',$WooCommerce->isEnable($bookingForm['meta']));
        
        CHBSPostMeta::updatePostMeta($bookingId,'booking_status_id',$bookingForm['meta']['booking_status_default_id']);
        
        CHBSPostMeta::updatePostMeta($bookingId,'booking_form_id',$data['booking_form_id']);
        
        CHBSPostMeta::updatePostMeta($bookingId,'currency_id',CHBSOption::getOption('currency'));
        CHBSPostMeta::updatePostMeta($bookingId,'length_unit',CHBSOption::getOption('length_unit'));
        
        CHBSPostMeta::updatePostMeta($bookingId,'service_type_id',$data['service_type_id']);
        
        CHBSPostMeta::updatePostMeta($bookingId,'pickup_time',$data['pickup_time_service_type_'.$data['service_type_id']]);
        CHBSPostMeta::updatePostMeta($bookingId,'pickup_date',$data['pickup_date_service_type_'.$data['service_type_id']]);
        
        CHBSPostMeta::updatePostMeta($bookingId,'pickup_datetime',CHBSDate::formatDateTimeToMySQL($data['pickup_date_service_type_'.$data['service_type_id']],$data['pickup_time_service_type_'.$data['service_type_id']]));

        CHBSPostMeta::updatePostMeta($bookingId,'return_time','00:00');
        CHBSPostMeta::updatePostMeta($bookingId,'return_date','00-00-0000');
        CHBSPostMeta::updatePostMeta($bookingId,'return_datetime','00-00-0000 00:00');

        if(in_array($data['service_type_id'],array(1,3)))
        {
            if($data['transfer_type_service_type_'.(int)$data['service_type_id']]==3)
            {
                CHBSPostMeta::updatePostMeta($bookingId,'return_time',$data['return_time_service_type_'.$data['service_type_id']]);
                CHBSPostMeta::updatePostMeta($bookingId,'return_date',$data['return_date_service_type_'.$data['service_type_id']]); 
                CHBSPostMeta::updatePostMeta($bookingId,'pickup_datetime',CHBSDate::formatDateTimeToMySQL($data['return_date_service_type_'.$data['service_type_id']],$data['return_time_service_type_'.$data['service_type_id']]));
            }
        }
        
        if(in_array($data['service_type_id'],array(1,3)))
        {
            CHBSPostMeta::updatePostMeta($bookingId,'extra_time_enable',$bookingForm['meta']['extra_time_enable']);
            
            if($bookingForm['meta']['extra_time_enable']==1)
                CHBSPostMeta::updatePostMeta($bookingId,'extra_time_value',$data['extra_time_service_type_'.$data['service_type_id']]*60);
            
            CHBSPostMeta::updatePostMeta($bookingId,'distance',$data['distance_map']);
            CHBSPostMeta::updatePostMeta($bookingId,'duration',$data['duration_map']);
            
            CHBSPostMeta::updatePostMeta($bookingId,'transfer_type_id',$data['transfer_type_service_type_1']);
        }
        
        if(in_array($data['service_type_id'],array(2)))
        {
            CHBSPostMeta::updatePostMeta($bookingId,'duration',$data['duration_service_type_'.$data['service_type_id']]*60);
        }
        
        if(in_array($data['service_type_id'],array(3)))
        {
            $routeDictionary=$bookingForm['dictionary']['route'][$data['route_service_type_3']];
            
            CHBSPostMeta::updatePostMeta($bookingId,'route_id',$data['route_service_type_3']);
            CHBSPostMeta::updatePostMeta($bookingId,'route_name',$routeDictionary['post']->post_title);
            
            CHBSPostMeta::updatePostMeta($bookingId,'transfer_type_id',$data['transfer_type_service_type_3']);
        }
        
        /***/
        
        $coordinate=array();
        
        if(in_array($data['service_type_id'],array(1,2)))
        {
            array_push($coordinate,json_decode($data['pickup_location_coordinate_service_type_'.$data['service_type_id']]));

            if($data['service_type_id']==1)
            {
                if(is_array($data['waypoint_location_coordinate_service_type_1']))
                {
                    foreach($data['waypoint_location_coordinate_service_type_1'] as $value)
                        array_push($coordinate,json_decode($value));
                }
            }
            
            array_push($coordinate,json_decode($data['dropoff_location_coordinate_service_type_'.$data['service_type_id']]));
        }
        else
        {
            $routeDictionary=$bookingForm['dictionary']['route'][$data['route_service_type_3']];
            $coordinate=$routeDictionary['meta']['coordinate'];
        }
        
        $coordinate=json_decode(json_encode($coordinate),true);

        CHBSPostMeta::updatePostMeta($bookingId,'coordinate',$coordinate);
        
        /***/
        
        $vehicle=$bookingForm['dictionary']['vehicle'][$data['vehicle_id']];
        
        $argument=array
        (
            'booking_form_id'                                                   =>  $data['booking_form_id'],
            'service_type_id'                                                   =>  $data['service_type_id'],
            'route_id'                                                          =>  $data['route_service_type_3'],
            'vehicle_id'                                                        =>  $data['vehicle_id'],
            'pickup_date'                                                       =>  $data['pickup_date_service_type_'.$data['service_type_id']],
            'pickup_time'                                                       =>  $data['pickup_time_service_type_'.$data['service_type_id']],
            'distance'                                                          =>  $data['distance_map'],
            'base_location_distance'                                            =>  $data['base_location_distance'],
            'base_location_distance_return'                                     =>  $data['base_location_distance_return'],
            'duration'                                                          =>  $data['duration'],
            'passenger_adult'                                                   =>  $data['passenger_adult_service_type_'.$data['service_type_id']],
            'passenger_children'                                                =>  $data['passenger_children_service_type_'.$data['service_type_id']],
            'booking_form'                                                      =>  $bookingForm
        );        
        
        $vehiclePrice=$Vehicle->calculatePrice($argument);
            
        CHBSPostMeta::updatePostMeta($bookingId,'vehicle_id',$data['vehicle_id']);
        CHBSPostMeta::updatePostMeta($bookingId,'vehicle_name',$vehicle['post']->post_title);
        
        $vehiclePriceBooking=array
        (
            'price_type'                                                        =>  $vehiclePrice['price']['base']['price_type'],
            'price_fixed_value'                                                 =>  $vehiclePrice['price']['base']['price_fixed_value'],
            'price_fixed_tax_rate_value'                                        =>  $TaxRate->getTaxRateValue($vehiclePrice['price']['base']['price_fixed_tax_rate_id'],$taxRateDictionary),
            'price_fixed_return_value'                                          =>  $vehiclePrice['price']['base']['price_fixed_return_value'],
            'price_fixed_return_tax_rate_value'                                 =>  $TaxRate->getTaxRateValue($vehiclePrice['price']['base']['price_fixed_return_tax_rate_id'],$taxRateDictionary),
            'price_initial_value'                                               =>  $vehiclePrice['price']['base']['price_initial_value'],
            'price_initial_tax_rate_value'                                      =>  $TaxRate->getTaxRateValue($vehiclePrice['price']['base']['price_initial_tax_rate_id'],$taxRateDictionary),
            'price_delivery_value'                                              =>  $vehiclePrice['price']['base']['price_delivery_value'],    
            'price_delivery_tax_rate_value'                                     =>  $TaxRate->getTaxRateValue($vehiclePrice['price']['base']['price_delivery_tax_rate_id'],$taxRateDictionary),
            'price_delivery_return_value'                                       =>  $vehiclePrice['price']['base']['price_delivery_return_value'],    
            'price_delivery_return_tax_rate_value'                              =>  $TaxRate->getTaxRateValue($vehiclePrice['price']['base']['price_delivery_return_tax_rate_id'],$taxRateDictionary),
            'price_distance_value'                                              =>  $vehiclePrice['price']['base']['price_distance_value'],
            'price_distance_tax_rate_value'                                     =>  $TaxRate->getTaxRateValue($vehiclePrice['price']['base']['price_distance_tax_rate_id'],$taxRateDictionary),     
            'price_first_4_delivery_value'                                      =>  $vehiclePrice['price']['base']['price_first_4_delivery_value'],
            'price_first_4_tax_rate_value'                                      =>  $TaxRate->getTaxRateValue($vehiclePrice['price']['base']['price_first_4_tax_rate_id'],$taxRateDictionary),
            'price_extra_km_value'                                              =>  $vehiclePrice['price']['base']['price_extra_km_value'],
            'price_extra_km_tax_rate_value'                                     =>  $TaxRate->getTaxRateValue($vehiclePrice['price']['base']['price_extra_km_tax_rate_id'],$taxRateDictionary),
            'price_distance_return_value'                                       =>  $vehiclePrice['price']['base']['price_distance_return_value'],
            'price_distance_return_tax_rate_value'                              =>  $TaxRate->getTaxRateValue($vehiclePrice['price']['base']['price_distance_return_tax_rate_id'],$taxRateDictionary),
            'price_hour_value'                                                  =>  $vehiclePrice['price']['base']['price_hour_value'],
            'price_hour_tax_rate_value'                                         =>  $TaxRate->getTaxRateValue($vehiclePrice['price']['base']['price_hour_tax_rate_id'],$taxRateDictionary),
            'price_extra_time_value'                                            =>  $vehiclePrice['price']['base']['price_extra_time_value'],
            'price_extra_time_tax_rate_value'                                   =>  $TaxRate->getTaxRateValue($vehiclePrice['price']['base']['price_extra_time_tax_rate_id'],$taxRateDictionary),
            'price_passenger_adult_value'                                       =>  $vehiclePrice['price']['base']['price_passenger_adult_value'],
            'price_passenger_adult_tax_rate_value'                              =>  $TaxRate->getTaxRateValue($vehiclePrice['price']['base']['price_passenger_adult_tax_rate_id'],$taxRateDictionary),
            'price_passenger_children_value'                                    =>  $vehiclePrice['price']['base']['price_passenger_children_value'],
            'price_passenger_children_tax_rate_value'                           =>  $TaxRate->getTaxRateValue($vehiclePrice['price']['base']['price_passenger_children_tax_rate_id'],$taxRateDictionary)
        );     
        
        
        foreach($vehiclePriceBooking as $index=>$value)
            CHBSPostMeta::updatePostMeta($bookingId,$index,$value);
        
        /***/
        
        $Coupon=new CHBSCoupon();
        $code=$Coupon->checkCode();
        
        if($code===false)
        {
            CHBSPostMeta::updatePostMeta($bookingId,'coupon_code','');
            CHBSPostMeta::updatePostMeta($bookingId,'coupon_discount_percentage',0);
        }
        else
        {
            CHBSPostMeta::updatePostMeta($bookingId,'coupon_code',$code['meta']['code']);
            CHBSPostMeta::updatePostMeta($bookingId,'coupon_discount_percentage',$code['meta']['discount_percentage']);            
        }
        
        /***/
        
        $BookingFormElement->sendBookingField($bookingId,$bookingForm['meta'],$data);
        
        /***/
        
        $BookingExtra=new CHBSBookingExtra();
        $bookingExtra=$BookingExtra->validate($data,$bookingForm['dictionary']['booking_extra'],$taxRateDictionary);
        
        CHBSPostMeta::updatePostMeta($bookingId,'booking_extra',$bookingExtra);
        
        /***/
        
        $field=array('first_name','last_name','email_address','phone_number');
        foreach($field as $value)
            CHBSPostMeta::updatePostMeta($bookingId,'client_contact_detail_'.$value,$data['client_contact_detail_'.$value]);
        
        CHBSPostMeta::updatePostMeta($bookingId,'client_billing_detail_enable',(int)$data['client_billing_detail_enable']);   
        
        if((int)$data['client_billing_detail_enable']===1)
        {
            $field=array('company_name','tax_number','street_name','street_number','city','state','postal_code','country_code');
            foreach($field as $value)
                CHBSPostMeta::updatePostMeta($bookingId,'client_billing_detail_'.$value,$data['client_billing_detail_'.$value]);            
        }
        
        /***/
        
        CHBSPostMeta::updatePostMeta($bookingId,'comment',$data['comment']);
        
        /***/
        
        CHBSPostMeta::updatePostMeta($bookingId,'payment_id',$data['payment_id']);
        CHBSPostMeta::updatePostMeta($bookingId,'payment_name',CHBSBookingHelper::getPaymentName($data['payment_id'],-1,$bookingForm['meta']));
        
        $paymentDepositEnable=CHBSBookingHelper::isPaymentDepositEnable($bookingForm['meta']);
        
        CHBSPostMeta::updatePostMeta($bookingId,'payment_deposit_enable',$paymentDepositEnable);
        CHBSPostMeta::updatePostMeta($bookingId,'payment_deposit_value',($paymentDepositEnable==1 ? $bookingForm['meta']['payment_deposit_value'] : 0));
        
        /***/
        
        CHBSPostMeta::updatePostMeta($bookingId,'base_location_distance',$data['base_location_distance']);
        CHBSPostMeta::updatePostMeta($bookingId,'base_location_return_distance',$data['base_location_return_distance']);
        
        /***/
        

        if($WooCommerce->isEnable($bookingForm['meta']))
            $WooCommerce->sendBooking($bookingId,$data);
        
        /***/
        
        $subject=sprintf(__('New booking "%s" is received','chauffeur-booking-system'),$this->getBookingTitle($bookingId));
        
        $recipient=array();
        $recipient[0]=array($data['client_contact_detail_email_address']);
        $recipient[1]=preg_split('/;/',$bookingForm['meta']['booking_new_recipient_email_address']);
        
		$this->sendEmail($bookingId,$bookingForm['meta']['booking_new_sender_email_account_id'],'booking_new_client',$recipient[0],$subject);
		$this->sendEmail($bookingId,$bookingForm['meta']['booking_new_sender_email_account_id'],'booking_new_admin',$recipient[1],$subject);
        
        if($bookingForm['meta']['nexmo_sms_enable']==1)
        {
            $Nexmo=new CHBSNexmo();
            $Nexmo->sendSMS($bookingForm['meta']['nexmo_sms_api_key'],$bookingForm['meta']['nexmo_sms_api_key_secret'],$bookingForm['meta']['nexmo_sms_sender_name'],$bookingForm['meta']['nexmo_sms_recipient_phone_number'],$bookingForm['meta']['nexmo_sms_message']);
        }
        
        if($bookingForm['meta']['twilio_sms_enable']==1)
        {
            $Nexmo=new CHBSTwilio();
            $Nexmo->sendSMS($bookingForm['meta']['twilio_sms_api_sid'],$bookingForm['meta']['twilio_sms_api_token'],$bookingForm['meta']['twilio_sms_sender_phone_number'],$bookingForm['meta']['twilio_sms_recipient_phone_number'],$bookingForm['meta']['twilio_sms_message']);
        }
        
 		if($bookingForm['meta']['telegram_enable']==1)
        {
            $Telegram=new CHBSTelegram();
            $Telegram->sendMessage($bookingForm['meta']['telegram_token'],$bookingForm['meta']['telegram_group_id'],$bookingForm['meta']['telegram_message']);
        }
        
        /***/
        
        $GoogleCalendar=new CHBSGoogleCalendar();
        $GoogleCalendar->sendBooking($bookingId);
        
        /***/
        
        return($bookingId);
    }
    
    /**************************************************************************/
    
    function getBookingTitle($bookingId)
    {
        return(sprintf(__('Booking #%s','chauffeur-booking-system'),$bookingId));
    }
    
	/**************************************************************************/
    
	function setPostMetaDefault(&$meta)
	{
        CHBSHelper::setDefault($meta,'coupon_code','');
        CHBSHelper::setDefault($meta,'coupon_discount_percentage',0);        
        
        CHBSHelper::setDefault($meta,'passenger_enable',0);
        CHBSHelper::setDefault($meta,'passenger_adult_number',0);
        CHBSHelper::setDefault($meta,'passenger_children_number',0);
        
        CHBSHelper::setDefault($meta,'hide_fee',0);
        CHBSHelper::setDefault($meta,'price_hide',0);
        
        CHBSHelper::setDefault($meta,'woocommerce_enable',0);
		CHBSHelper::setDefault($meta,'booking_status_id',1);
        CHBSHelper::setDefault($meta,'transfer_type_id',1);
        
        CHBSHelper::setDefault($meta,'base_location_distance',0);
        CHBSHelper::setDefault($meta,'base_location_return_distance',0);
        
        CHBSHelper::setDefault($meta,'price_delivery_return_value','0.00');
        CHBSHelper::setDefault($meta,'price_delivery_return_tax_rate_id',0);
	}
    
    /**************************************************************************/
    
	function savePost($postId)
	{		
        if(!$_POST) return(false);
        
        if(CHBSHelper::checkSavePost($postId,PLUGIN_CHBS_CONTEXT.'_meta_box_booking_noncename','savePost')===false) return(false);
	
		$oldMeta=CHBSPostMeta::getPostMeta($postId);
		
		$BookingStatus=new CHBSBookingStatus();
		
        if($BookingStatus->isBookingStatus(CHBSHelper::getPostValue('booking_status_id')))
           CHBSPostMeta::updatePostMeta($postId,'booking_status_id',CHBSHelper::getPostValue('booking_status_id')); 
            
        $newPost=get_post($postId);
		$newMeta=CHBSPostMeta::getPostMeta($postId);
		
		if($oldMeta['booking_status_id']!=$newMeta['booking_status_id'])
        {
            $BookingStatus=new CHBSBookingStatus();
            $bookingStatus=$BookingStatus->getBookingStatus($newMeta['booking_status_id']);
            
            $recipient=array();
            $recipient[0]=array($newMeta['client_contact_detail_email_address']);
       
            $subject=sprintf(__('Booking "%s" has changed status to "%s"','chauffeur-booking-system'),$newPost->post_title,$bookingStatus[0]);
        
            $this->sendEmail($postId,CHBSOption::getOption('sender_default_email_account_id'),'booking_change_status',$recipient[0],$subject);           
        }
	}

    /**************************************************************************/
    
    function manageEditColumns($column)
    {
        $addColumn=array
        (
            'status'                                                            =>  __('Booking status','chauffeur-booking-system'),
            'service_type'                                                      =>  __('Service type','chauffeur-booking-system'),
            'pickup_return_date'                                                =>  __('Pickup/return date','chauffeur-booking-system'),
            'client'                                                            =>  __('Client','chauffeur-booking-system'),
            'price'                                                             =>  __('Price','chauffeur-booking-system'),
            'date'                                                              =>  $column['date']
        );
   
        unset($column['date']);
        
        foreach($addColumn as $index=>$value)
            $column[$index]=$value;
        
		return($column);          
    }
    
    /**************************************************************************/
    
    /*** not using  */
    function managePostsCustomColumn($column)
    {
		global $post;
		
        $Date=new CHBSDate();
        $ServiceType=new CHBSServiceType();
		$BookingStatus=new CHBSBookingStatus();
        
		$meta=CHBSPostMeta::getPostMeta($post);
        
        $billing=$this->createBilling($post->ID);
                
        
		switch($column) 
		{
			case 'status':
				
                $bookingStatus=$BookingStatus->getBookingStatus($meta['booking_status_id']);
                echo '<div class="to-booking-status to-booking-status-'.(int)$meta['booking_status_id'].'">'.esc_html($bookingStatus[0]).'</div>';
                
			break;
        
            case 'service_type':
                
                $serviceType=$ServiceType->getServiceType($meta['service_type_id']);
                echo esc_html($serviceType[0]); 
                
            break;
        
            case 'pickup_return_date':
                
                echo esc_html($Date->formatDateToDisplay($meta['pickup_date']).' '.$Date->formatTimeToDisplay($meta['pickup_time']));

                if(in_array($meta['service_type_id'],array(1,3)))
                {
                    if(in_array($meta['transfer_type_id'],array(3)))
                    {
                        echo '<br>-<br>';
                        echo esc_html($Date->formatDateToDisplay($meta['return_date']).' '.$Date->formatTimeToDisplay($meta['return_time']));
                    }
                }
                
            break;
          
            case 'client':
                
                echo esc_html($meta['client_contact_detail_first_name'].' '.$meta['client_contact_detail_last_name']);
                
            break;
   
            case 'price':
                
                echo esc_html(CHBSPrice::format($billing['summary']['value_gross'],$meta['currency_id']));
                
            break;
        }
    }
    
    /**************************************************************************/
    
    function manageEditSortableColumns($column)
    {
		return($column);       
    }
    
    /**************************************************************************/
    
    function restrictManagePosts()
    {
 		if(!is_admin()) return;
		if(CHBSHelper::getGetValue('post_type',false)!==self::getCPTName()) return;       
        
        $html=null;
        
        /***/
        
        $BookingStatus=new CHBSBookingStatus();
        $bookingStatusDirectory=$BookingStatus->getBookingStatus();
        
        $directory=array();
        foreach($bookingStatusDirectory as $index=>$value)
            $directory[$index]=$value[0];
        
		$directory[-2]=__('New & accepted','chauffeur-booking-system');
		
		asort($directory,SORT_STRING);
        
		if(!array_key_exists('booking_status_id',$_GET))
			$_GET['booking_status_id']=-2;
        
 		foreach($directory as $index=>$value)
			$html.='<option value="'.(int)$index.'" '.(((int)CHBSHelper::getGetValue('booking_status_id',false)==$index) ?  'selected' : null).'>'.esc_html($value).'</option>';
		
		$html=
		'
			<select name="booking_status_id">
				<option value="0">'.__('All statuses','chauffeur-booking-system').'</option>
				'.$html.'
			</select>
		';
        
        /***/
        
        echo $html;
    }
    
    /**************************************************************************/
    
    function parseQuery($query)
    {
		if(!is_admin()) return;
		if(CHBSHelper::getGetValue('post_type',false)!==self::getCPTName()) return;
		if($query->query['post_type']!==self::getCPTName()) return;       
        
        /***/
        
        $metaQuery=array();
        $Validation=new CHBSValidation();
        
        /***/
        
		$bookingStatusId=CHBSHelper::getGetValue('booking_status_id',false);
		if($Validation->isEmpty($bookingStatusId)) $bookingStatusId=-2;

		if($bookingStatusId!=0)
		{
			array_push($metaQuery,array
			(
				'key'															=>	PLUGIN_CHBS_CONTEXT.'_booking_status_id',
				'value'															=>	$bookingStatusId==-2 ? array(1,2) : array($bookingStatusId),
				'compare'														=>	'IN'
			));
		}  
        
        /***/
        
		$order=CHBSHelper::getGetValue('order',false);
		$orderby=CHBSHelper::getGetValue('orderby',false);
		
		if($orderby=='title')
		{
			$query->set('orderby','title');
		}
        elseif($orderby=='date')
		{
			$query->set('orderby','date');
		}
		else
		{
			switch($orderby)
			{
				default:
                    
					$query->set('meta_key',PLUGIN_CHBS_CONTEXT.'_pickup_datetime');
					$query->set('meta_type','DATETIME');

					if($Validation->isEmpty($order)) $order='asc';
			}

			$query->set('orderby','meta_value');
		}

		$query->set('order',$order);

		if(count($metaQuery)) $query->set('meta_query',$metaQuery);
    }
    
    /**************************************************************************/
    
    function calculatePrice($data,$vehiclePrice=null,$hideFee=false)
    {       
        
        
        $Length=new CHBSLength();
        $TaxRate=new CHBSTaxRate();
        $Vehicle=new CHBSVehicle();
        $BookingExtra=new CHBSBookingExtra();
        
        $taxRateDictionary=$TaxRate->getDictionary();
        
        /***/
        
      //  $component=array('initial','delivery','delivery_return','vehicle','extra_time','booking_extra','total','pay');
        
      $component=array('initial','delivery','first_4_delivery','price_extra_km','delivery_return','vehicle','extra_time','booking_extra','total','pay');
      
        foreach($component as $value)
        {
            $price[$value]=array
            (
                'sum'                                                           =>  array
                (
                    'net'                                                       =>  array
                    (
                        'value'                                                 =>  0.00
                    ),
                    'gross'                                                     =>  array
                    (
                        'value'                                                 =>  0.00,
                        'format'                                                =>  0.00
                    )
                )                
            );
        }
        
        /***/

        if(!in_array($data['service_type_id'],$data['booking_form']['meta']['service_type_id'])) return(false);

        /***/
        
        if(array_key_exists($data['vehicle_id'],$data['booking_form']['dictionary']['vehicle']))
        { 
            $serviceTypeId=$data['service_type_id'];

            $argument=array
            (
                'booking_form_id'                                               =>  $data['booking_form_id'],
                'service_type_id'                                               =>  $data['service_type_id'],
                'route_id'                                                      =>  $data['route_service_type_'.$serviceTypeId],
                'transfer_type_id'                                              =>  $data['transfer_type_service_type_'.$serviceTypeId],
                'vehicle_id'                                                    =>  $data['vehicle_id'],
                'pickup_date'                                                   =>  $data['pickup_date_service_type_'.$serviceTypeId],
                'pickup_time'                                                   =>  $data['pickup_time_service_type_'.$serviceTypeId],
                'distance'                                                      =>  $data['distance_map'],
                'base_location_distance'                                        =>  $data['base_location_distance'],
                'base_location_distance_return'                                 =>  $data['base_location_distance_return'],
                'duration'                                                      =>  $data['duration_service_type_'.$serviceTypeId]*60,
                'passenger_adult'                                               =>  $data['passenger_adult_service_type_'.$serviceTypeId],
                'passenger_children'                                            =>  $data['passenger_children_service_type_'.$serviceTypeId],
                'booking_form'                                                  =>  $data['booking_form']
            );

            if(is_null($vehiclePrice)){
                $vehiclePrice=$Vehicle->calculatePrice($argument,false);
            }
                
            if(CHBSOption::getOption('length_unit')==2)
            {   
                $data['distance_map']=$Length->convertUnit($data['distance_map']);
                $data['base_location_distance']=$Length->convertUnit($data['base_location_distance']);
            }
            
            $price['vehicle']['sum']['gross']['value']=$vehiclePrice['price']['sum']['gross']['value'];        
            
            $price['initial']['sum']['gross']['value']=CHBSPrice::calculateGross($vehiclePrice['price']['base']['price_initial_value'],$vehiclePrice['price']['base']['price_initial_tax_rate_id']);

            if(in_array($serviceTypeId,array(1,3)))
            {
                if(in_array($serviceTypeId,$data['booking_form']['meta']['transfer_type_enable']))
                {
                    if((int)$data['transfer_type_service_type_'.$serviceTypeId]===3)
                    {
                        
                        $price['initial']['sum']['gross']['value']*=2;
                        $data['base_location_distance']*=2;
                    }
                }
            }
           
            $price['delivery']['sum']['gross']['value']=CHBSPrice::calculateGross($vehiclePrice['price']['base']['price_delivery_value']*$data['base_location_distance'],$vehiclePrice['price']['base']['price_delivery_tax_rate_id']);          
           
            $price['delivery_return']['sum']['gross']['value']=CHBSPrice::calculateGross($vehiclePrice['price']['base']['price_delivery_return_value']*$data['base_location_return_distance'],$vehiclePrice['price']['base']['price_delivery_return_tax_rate_id']);                    

            if(in_array($serviceTypeId,array(1,3)))
              
                $price['extra_time']['sum']['gross']['value']=CHBSPrice::calculateGross($vehiclePrice['price']['base']['price_extra_time_value']*$data['extra_time_service_type_'.$data['service_type_id']],$vehiclePrice['price']['base']['price_extra_time_tax_rate_id']);

                
            if((int)$vehiclePrice['price']['base']['price_type']===2)
            {
               
                $price['initial']['sum']['gross']['value']=0.00; 
                $price['delivery']['sum']['gross']['value']=0.00;
                $price['delivery_return']['sum']['gross']['value']=0.00;   
            }
            
            $price['initial']['sum']['gross']['format']=CHBSPrice::format($price['initial']['sum']['gross']['value'],CHBSOption::getOption('currency'));
            
            $price['delivery']['sum']['gross']['format']=CHBSPrice::format($price['delivery']['sum']['gross']['value'],CHBSOption::getOption('currency'));

            $price['delivery_return']['sum']['gross']['format']=CHBSPrice::format($price['delivery_return']['sum']['gross']['value'],CHBSOption::getOption('currency'));
            
            if($hideFee)
            {
                $price['vehicle']['sum']['gross']['value']+=$price['initial']['sum']['gross']['value']+$price['delivery']['sum']['gross']['value']+$price['delivery_return']['sum']['gross']['value'];
            }


            $price['vehicle']['sum']['gross']['format']=CHBSPrice::format($price['vehicle']['sum']['gross']['value'],CHBSOption::getOption('currency'));
            
            if(in_array($serviceTypeId,array(1,3)))
                $price['extra_time']['sum']['gross']['format']=CHBSPrice::format($price['extra_time']['sum']['gross']['value'],CHBSOption::getOption('currency'));
        }
        
        /***/

        $bookingExtra=$BookingExtra->validate($data,$data['booking_form']['dictionary']['booking_extra'],$taxRateDictionary);      
        foreach($bookingExtra as $value)
        {
            $price['booking_extra']['sum']['gross']['value']+=CHBSPrice::calculateGross($value['quantity']*$value['price'],0,$value['tax_rate_value']);
        }
        
        $price['booking_extra']['sum']['gross']['format']=CHBSPrice::format($price['booking_extra']['sum']['gross']['value'],CHBSOption::getOption('currency'));
        
        /***/
        
        if($hideFee)
        {
            $price['total']['sum']['gross']['value']=$price['vehicle']['sum']['gross']['value']+$price['extra_time']['sum']['gross']['value']+$price['booking_extra']['sum']['gross']['value'];
        }
        else $price['total']['sum']['gross']['value']=$price['initial']['sum']['gross']['value']+$price['delivery']['sum']['gross']['value']+$price['delivery_return']['sum']['gross']['value']+$price['vehicle']['sum']['gross']['value']+$price['extra_time']['sum']['gross']['value']+$price['booking_extra']['sum']['gross']['value'];

        /***/
        
        $price['total']['sum']['gross']['format']=CHBSPrice::format($price['total']['sum']['gross']['value'],CHBSOption::getOption('currency'));
        
        $price['pay']=$price['total'];
        
        if(CHBSBookingHelper::isPaymentDepositEnable($data['booking_form']['meta']))
        {
            $price['pay']['sum']['gross']['value']=$price['pay']['sum']['gross']['value']*($data['booking_form']['meta']['payment_deposit_value']/100);
            $price['pay']['sum']['gross']['format']=CHBSPrice::format($price['pay']['sum']['gross']['value'],CHBSOption::getOption('currency'));
        }
        
        return($price);
    }
        
    /**************************************************************************/
    
    function createResponse($response)
    {
        echo json_encode($response);
        exit;             
    }
    
    /**************************************************************************/
    
    function createBilling($bookingId)
    {
    


        $billing=array();
        
        if(($booking=$this->getBooking($bookingId))===false) return($billing);

        $Date=new CHBSDate();
        $Length=new CHBSLength();

        /***/
        
        if($booking['meta']['price_type']==2)
        {
            $booking['meta']['price_initial_value']=0.00;
            $booking['meta']['price_initial_tax_rate_value']=0;
            
            $booking['meta']['price_delivery_value']=0.00;
            $booking['meta']['price_delivery_tax_rate_value']=0;    
            
            $booking['meta']['price_delivery_return_value']=0.00;
            $booking['meta']['price_delivery_return_tax_rate_value']=0;   
        }
        
        /***/
        
        $returnFactor=1;
        if(in_array($booking['meta']['service_type_id'],array(1,3)))
        {
            if(in_array($booking['meta']['transfer_type_id'],array(2,3)))
                $returnFactor=2;
        }
        
        /***/
        
        if($booking['meta']['price_initial_value']>0)
        {
            $valueNet=$booking['meta']['price_initial_value']*$returnFactor;

            $billing['detail'][]=array
            (
                'type'                                                          =>  'initial_fee',
                'name'                                                          =>  __('Initial fee','chauffeur-booking-system'),
                'unit'                                                          =>  __('Item','chauffeur-booking-system'),  
                'quantity'                                                      =>  $returnFactor,
                'duration'                                                      =>  0,
                'distance'                                                      =>  0,
                'price_net'                                                     =>  $booking['meta']['price_initial_value'],
                'value_net'                                                     =>  $valueNet,                   
                'tax_value'                                                     =>  $booking['meta']['price_initial_tax_rate_value'],
                'value_gross'                                                   =>  CHBSPrice::calculateGross($valueNet,0,$booking['meta']['price_initial_tax_rate_value'])
            );
        }
        
        /***/
        
        if(($booking['meta']['price_delivery_value']>0) && ($booking['meta']['base_location_distance']>0))
        {

            $baseLocationDistance=$booking['meta']['base_location_distance'];

                       
            if($booking['meta']['length_unit']==2)
                $booking['meta']['base_location_distance']=$Length->convertUnit($booking['meta']['base_location_distance'],1,2);

            $baseLocationDistance*=$returnFactor;

            $valueNet=$baseLocationDistance*$booking['meta']['price_delivery_value'];

            $billing['detail'][]=array
            (
                'type'                                                          =>  'delivery_fee',
                'name'                                                          =>  __('Delivery fee','chauffeur-booking-system'),
                'unit'                                                          =>  $Length->getUnitName($booking['meta']['length_unit']),  
                'quantity'                                                      =>  $baseLocationDistance,
                'duration'                                                      =>  0,
                'distance'                                                      =>  $baseLocationDistance,
                'price_net'                                                     =>  $booking['meta']['price_delivery_value'],
                'value_net'                                                     =>  $valueNet,                   
                'tax_value'                                                     =>  $booking['meta']['price_delivery_tax_rate_value'],
                'value_gross'                                                   =>  CHBSPrice::calculateGross($valueNet,0,$booking['meta']['price_delivery_tax_rate_value'])
            );  
        }

        /***/
        
        if(($booking['meta']['price_delivery_return_value']>0) && ($booking['meta']['base_location_return_distance']>0))
        {
            $baseLocationReturnDistance=$booking['meta']['base_location_return_distance'];

            if($booking['meta']['length_unit']==2)
                $booking['meta']['base_location_return_distance']=$Length->convertUnit($booking['meta']['base_location_return_distance'],1,2);

            $baseLocationReturnDistance*=$returnFactor;

            $valueNet=$baseLocationReturnDistance*$booking['meta']['price_delivery_return_value'];

            $billing['detail'][]=array
            (
                'type'                                                          =>  'delivery_return_fee',
                'name'                                                          =>  __('Delivery fee (return)','chauffeur-booking-system'),
                'unit'                                                          =>  $Length->getUnitName($booking['meta']['length_unit']),  
                'quantity'                                                      =>  $baseLocationReturnDistance,
                'duration'                                                      =>  0,
                'distance'                                                      =>  $baseLocationReturnDistance,
                'price_net'                                                     =>  $booking['meta']['price_delivery_return_value'],
                'value_net'                                                     =>  $valueNet,                   
                'tax_value'                                                     =>  $booking['meta']['price_delivery_return_tax_rate_value'],
                'value_gross'                                                   =>  CHBSPrice::calculateGross($valueNet,0,$booking['meta']['price_delivery_return_tax_rate_value'])
            );  
        }        
        
        /***/
        
        if($booking['meta']['passenger_enable']==1)
        {
            if($booking['meta']['passenger_adult_number']>0)
            {
                $valueNet=$booking['meta']['passenger_adult_number']*$booking['meta']['price_passenger_adult_value'];
                
                $billing['detail'][]=array
                (
                    'type'                                                              =>  'chauffeur_passenger_adult',
                    'name'                                                              =>  __('Adult passengers','chauffeur-booking-system'),
                    'unit'                                                              =>  __('people','chauffeur-booking-system'),
                    'quantity'                                                          =>  $booking['meta']['passenger_adult_number'],
                    'duration'                                                          =>  0,
                    'distance'                                                          =>  0,
                    'price_net'                                                         =>  $booking['meta']['price_passenger_adult_value'],
                    'value_net'                                                         =>  $valueNet,                   
                    'tax_value'                                                         =>  $booking['meta']['price_passenger_adult_tax_rate_value'],
                    'value_gross'                                                       =>  CHBSPrice::calculateGross($valueNet,0,$booking['meta']['price_passenger_adult_tax_rate_value'])
                );         
            }
            
            if($booking['meta']['passenger_children_number']>0)
            {
                $valueNet=$booking['meta']['passenger_children_number']*$booking['meta']['price_passenger_children_value'];
                
                $billing['detail'][]=array
                (
                    'type'                                                              =>  'chauffeur_passenger_children',
                    'name'                                                              =>  __('Children passengers','chauffeur-booking-system'),
                    'unit'                                                              =>  __('people','chauffeur-booking-system'),
                    'quantity'                                                          =>  $booking['meta']['passenger_children_number'],
                    'duration'                                                          =>  0,
                    'distance'                                                          =>  0,
                    'price_net'                                                         =>  $booking['meta']['price_passenger_children_value'],
                    'value_net'                                                         =>  $valueNet,                   
                    'tax_value'                                                         =>  $booking['meta']['price_passenger_children_tax_rate_value'],
                    'value_gross'                                                       =>  CHBSPrice::calculateGross($valueNet,0,$booking['meta']['price_passenger_children_tax_rate_value'])
                );         
            }            
        }
        else
        {
            $quantity=0;
            $duration=0;
            $distance=0;

            if(in_array($booking['meta']['service_type_id'],array(1,3)))
            {
                if($booking['meta']['length_unit']==2)
                    $booking['meta']['distance']=$Length->convertUnit($booking['meta']['distance'],1,2);

                $duration=$booking['meta']['duration'];
                $distance=$booking['meta']['distance'];

                $quantity=$distance;
            }
            else 
            {
                $duration=$booking['meta']['duration'];
                $quantity=$Date->formatMinuteToTime($booking['meta']['duration']);
            }

            $unit=__('Item','chauffeur-booking-system');
            if($booking['meta']['price_type']==1)
            {
                if(in_array($booking['meta']['service_type_id'],array(1,3)))
                    $unit=$Length->getUnitName($booking['meta']['length_unit']);
                else $unit=__('Hours','chauffeur-booking-system');            
            }

            /***/

            if($booking['meta']['price_type']==1)
            {
                if(in_array($booking['meta']['service_type_id'],array(1,3)))
                {
                    
                    //Setting the custom price
                    if(isset($booking['meta']['price_first_4_delivery_value']) && ($booking['meta']['price_first_4_delivery_value'] > 0))
                    {
                        $price_for_km = 0;
                        $priceNet = 1;

                        //$taxValue=$booking['meta']['price_distance_tax_rate_value'];

                        $fixed_4_delivery = $booking['meta']['price_first_4_delivery_value'];
                        $additionalPrice  = $booking['meta']['price_extra_km_value'];         
                        $duration = ($booking['meta']['duration']/60);
                        $distance = $booking['meta']['distance'];

                        if($distance <= 40) {
                            if($fixed_4_delivery > 0) {
                                $price_for_km     = $fixed_4_delivery;                  
                            }
                        } else {
                            // Add additional price 
                            $tempDistance      = $distance - 40;                        
                            $additional        = $tempDistance * $additionalPrice;
                            $price_for_km      = $fixed_4_delivery + $additional;       
                        }

                        //If duration is greater than 4hr, then calculate the Hourly rate
                        if( $duration <= 4) {
                            $price_for_hr = $fixed_4_delivery;
                        } else {
                            $additionalHr =  $duration - 4;
                            $additionalPriceHr  = $additionalHr * $booking['meta']['price_extra_time_value']; 
                            $price_for_hr = $fixed_4_delivery + $additionalPriceHr;
                        }     
                        
                        //Compare the KM rate and the hourly rate

                        if( $price_for_km > $price_for_hr ){
                            $valueNet = $price_for_km;
                        } else {
                            $valueNet = $price_for_hr;
                        }
                        
                        
                    } else {
                        $priceNet=$booking['meta']['price_distance_value'];
                        $taxValue=$booking['meta']['price_distance_tax_rate_value'];


                        $valueNet=$priceNet*$distance; 
                    }

                   
                }
                else
                {
                    $priceNet=$booking['meta']['price_hour_value']/60;
                    $taxValue=$booking['meta']['price_hour_tax_rate_value'];

                    $valueNet=$priceNet*$duration;
                } 
            }
            else
            {
                $priceNet=$booking['meta']['price_fixed_value'];
                $taxValue=$booking['meta']['price_fixed_tax_rate_value'];

                $valueNet=$priceNet;  
            }

            if($priceNet>0)
            {
                $billing['detail'][]=array
                (
                    'type'                                                              =>  'chauffeur_service',
                    'name'                                                              =>  __('Chauffeur service','chauffeur-booking-system'),
                    'unit'                                                              =>  $unit,
                    'quantity'                                                          =>  $quantity,
                    'duration'                                                          =>  $duration,
                    'distance'                                                          =>  $distance,
                    'price_net'                                                         =>  $priceNet,
                    'value_net'                                                         =>  $valueNet,                   
                    'tax_value'                                                         =>  $taxValue,
                    'value_gross'                                                       =>  CHBSPrice::calculateGross($valueNet,0,$taxValue)
                );
            }
        }
        
        /***/
        
        if(in_array($booking['meta']['service_type_id'],array(1,3)))
        {
            if(in_array($booking['meta']['transfer_type_id'],array(2,3)))
            {
                if($booking['meta']['price_type']==1)
                {
                    $priceNet=$booking['meta']['price_distance_return_value'];
                    $taxValue=$booking['meta']['price_distance_return_tax_rate_value'];
                    
                    $valueNet=$priceNet*$distance;
                }
                else
                {
                    $priceNet=$booking['meta']['price_fixed_return_value'];
                    $taxValue=$booking['meta']['price_fixed_return_tax_rate_value'];
                    
                    $valueNet=$priceNet;   
                }
                
                if($priceNet>0)
                {
                    $billing['detail'][]=array
                    (
                        'type'                                                      =>  'chauffeur_service_return',
                        'name'                                                      =>  __('Chauffeur service (return)','chauffeur-booking-system'),
                        'unit'                                                      =>  $unit, 
                        'quantity'                                                  =>  $quantity,
                        'duration'                                                  =>  $duration,
                        'distance'                                                  =>  $distance,
                        'price_net'                                                 =>  $priceNet,
                        'value_net'                                                 =>  $valueNet,                   
                        'tax_value'                                                 =>  $taxValue,
                        'value_gross'                                               =>  CHBSPrice::calculateGross($valueNet,0,$taxValue)
                    );
                }
            }
        }
        
        /***/
        
        if(in_array($booking['meta']['service_type_id'],array(1,3)))
        {
            if($booking['meta']['extra_time_enable']==1)
            {
                if(($booking['meta']['extra_time_value']>0) && ($booking['meta']['price_extra_time_value']>0))
                {
                    $priceNet=$booking['meta']['price_extra_time_value'];
                    $valueNet=$priceNet*($booking['meta']['extra_time_value']/60);

                    $billing['detail'][]=array
                    (
                        'type'                                                  =>  'extra_time',
                        'name'                                                  =>  __('Extra time','chauffeur-booking-system'),
                        'unit'                                                  =>  __('Hours','chauffeur-booking-system'),  
                        'quantity'                                              =>  $Date->formatMinuteToTime($booking['meta']['extra_time_value']),
                        'duration'                                              =>  $booking['meta']['price_extra_time_value'],
                        'distance'                                              =>  0,
                        'price_net'                                             =>  $priceNet,
                        'value_net'                                             =>  $valueNet,                   
                        'tax_value'                                             =>  $booking['meta']['price_extra_time_tax_rate_value'],
                        'value_gross'                                           =>  CHBSPrice::calculateGross($valueNet,0,$booking['meta']['price_extra_time_tax_rate_value'])
                    );
                }
            }
        }
        
        /***/
        
        if(is_array($booking['meta']['booking_extra']))
        {
            foreach($booking['meta']['booking_extra'] as $value)
            {
                $priceNet=$value['price'];
                $valueNet=$priceNet*$value['quantity'];
                
                if($priceNet>0)
                {
                    $billing['detail'][]=array
                    (
                        'type'                                                      =>  'booking_extra',
                        'name'                                                      =>  $value['name'],
                        'unit'                                                      =>  __('Item','chauffeur-booking-system'),    
                        'quantity'                                                  =>  $value['quantity'],
                        'duration'                                                  =>  0,
                        'distance'                                                  =>  0,
                        'price_net'                                                 =>  $priceNet,
                        'value_net'                                                 =>  $valueNet,                   
                        'tax_value'                                                 =>  $value['tax_rate_value'],
                        'value_gross'                                               =>  CHBSPrice::calculateGross($valueNet,0,$value['tax_rate_value'])
                    );
                }
            }
        }
        
        /***/
        
        $billing['summary']['duration']=0;
        $billing['summary']['distance']=0;
        $billing['summary']['value_net']=0;
        $billing['summary']['value_gross']=0;
        
        foreach($billing['detail'] as $value)
        {
            $billing['summary']['duration']+=$value['duration'];
            
            if(!in_array($value['type'],array('delivery_fee')))
                $billing['summary']['distance']+=$value['distance'];
            
            $billing['summary']['value_net']+=$value['value_net'];
            $billing['summary']['value_gross']+=$value['value_gross'];
        }
        
        $billing['summary']['duration']=$Date->formatMinuteToTime($billing['summary']['duration']);
        
        foreach($billing['summary'] as $aIndex=>$aValue)
        {
            if(in_array($aIndex,array('value_net','value_gross')))
                $billing['summary'][$aIndex]=number_format(round($aValue,2),2,'.','');
        }      
        
        if(CHBSBookingHelper::isPaymentDepositEnable($booking['meta'],$bookingId)==1)
            $billing['summary']['pay']=number_format(round($billing['summary']['value_gross']*($booking['meta']['payment_deposit_value']/100),2),2,'.','');
        else $billing['summary']['pay']=$billing['summary']['value_gross'];
        
        /***/
        
        foreach($billing['detail'] as $aIndex=>$aValue)
        {
            foreach($aValue as $bIndex=>$bValue)
            {
                if(in_array($bIndex,array('price_net','value_net','tax_value','value_gross')))
                    $billing['detail'][$aIndex][$bIndex]=number_format(round($bValue,2),2,'.','');
                elseif($bIndex=='duration')
                    $billing['detail'][$aIndex][$bIndex]=$Date->formatMinuteToTime($bValue);
            }
        }

        /***/
        
        return($billing);
    }
    
    /**************************************************************************/
    
    function sendEmail($bookingId,$emailAccountId,$template,$recipient,$subject)
    {
        $Email=new CHBSEmail();
        $EmailAccount=new CHBSEmailAccount();
        
        if(($booking=$this->getBooking($bookingId))===false) return(false);
        
        if(($emailAccount=$EmailAccount->getDictionary(array('email_account_id'=>$emailAccountId)))===false) return(false);
        
        if(!isset($emailAccount[$emailAccountId])) return(false);
        
        $data=array();
        
        $emailAccount=$emailAccount[$emailAccountId];
        
        /***/
        
        global $chbs_phpmailer;
        
        $chbs_phpmailer['sender_name']=$emailAccount['meta']['sender_name'];
        $chbs_phpmailer['sender_email_address']=$emailAccount['meta']['sender_email_address'];
        
        $chbs_phpmailer['smtp_auth_enable']=$emailAccount['meta']['smtp_auth_enable'];
        $chbs_phpmailer['smtp_auth_debug_enable']=$emailAccount['meta']['smtp_auth_debug_enable'];
        
        $chbs_phpmailer['smtp_auth_username']=$emailAccount['meta']['smtp_auth_username'];
        $chbs_phpmailer['smtp_auth_password']=$emailAccount['meta']['smtp_auth_password'];
        
        $chbs_phpmailer['smtp_auth_host']=$emailAccount['meta']['smtp_auth_host'];
        $chbs_phpmailer['smtp_auth_port']=$emailAccount['meta']['smtp_auth_port'];
        
        $chbs_phpmailer['smtp_auth_secure_connection_type']=$emailAccount['meta']['smtp_auth_secure_connection_type'];
        
        /***/
        
        if(in_array($template,array('booking_new_admin','booking_new_client','booking_change_status')))
        {
            $templateFile='email_booking.php';
            
            $booking['booking_title']=$booking['post']->post_title;
            if($template==='booking_new_admin')
                $booking['booking_title']='<a href="'.get_edit_post_link($booking['post']->ID).'">'.$booking['booking_title'].'</a>';        
        }
        
        /***/
        
        $data['style']=$Email->getEmailStyle();
        
        $data['booking']=$booking;
        $data['booking']['billing']=$this->createBilling($bookingId);
        
        /***/
                
        $Template=new CHBSTemplate($data,PLUGIN_CHBS_TEMPLATE_PATH.$templateFile);
        $body=$Template->output();

        /***/
        
        $Email->send($recipient,$subject,$body);
    }
    
    /**************************************************************************/
    
    function getCouponCodeUsageCount($couponCode)
    {
        $argument=array
		(
			'post_type'															=>	self::getCPTName(),
			'post_status'														=>	'publish',
			'posts_per_page'													=>	-1,
            'meta_key'                                                          =>  PLUGIN_CHBS_CONTEXT.'_coupon_code',
            'meta_value'                                                        =>  $couponCode,
            'meta_compare'                                                      =>  '='
		);
		
        $query=new WP_Query($argument);
		if($query===false) return(false); 
        
        return($query->found_posts);
    }
    
    /**************************************************************************/
}

/******************************************************************************/
/******************************************************************************/