<?php

/******************************************************************************/
/******************************************************************************/

class CHBSWooCommerce
{
	/**************************************************************************/
	
    function __construct()
    {
        
    }
    
    /**************************************************************************/
    
    function isEnable($meta)
    {
        return((class_exists('WooCommerce')) && ($meta['woocommerce_enable']) && (!$meta['price_hide']));
    }
    
    /**************************************************************************/
    
    function isPayment($paymentId,$dictionary=null)
    {
        if(is_null($dictionary)) $dictionary=$this->getPaymentDictionary();

        foreach($dictionary as $value)
        {
            if($value->{'id'}==$paymentId) return(true);
        }
        
        return(false);
    }
    
    /**************************************************************************/
    
    function getPaymentDictionary()
    {
        $dictionary=WC()->payment_gateways->payment_gateways();
    
        foreach($dictionary as $index=>$value)
        {
            if(!(isset($value->enabled) && ($value->enabled==='yes')))
            {
                unset($dictionary[$index]);
            }
        }
        
        return($dictionary);
    }
    
    /**************************************************************************/
    
    function getPaymentName($paymentId,$dictionary=null)
    {
        if(is_null($dictionary)) $dictionary=$this->getPaymentDictionary();
        
        foreach($dictionary as $value)
        {
            if($value->{'id'}==$paymentId) return($value->{'method_title'});
        }        
        
        return(null);
    }
    
    /**************************************************************************/
    
    function sendBooking($bookingId,$data)
    {
        global $wpdb;
        
        $User=new CHBSUser();
        $Booking=new CHBSBooking();
        
        if(($booking=$Booking->getBooking($bookingId))===false) return(false);        
        
        $userId=0;
        
        if(!$User->isSignIn())
        {
            if((int)$data['client_sign_up_enable']===1)
                $userId=$User->createUser($data['client_contact_detail_email_address'],$data['client_sign_up_login'],$data['client_sign_up_password']);
        }
        else
        {
            $userData=$User->getCurrentUserData();
            $userId=$userData->data->ID;
        }
        
		$address=array
        (
            'first_name'                                                        =>  $booking['meta']['client_contact_detail_first_name'],
            'last_name'                                                         =>  $booking['meta']['client_contact_detail_last_name'],
			'company'                                                           =>  $booking['meta']['client_billing_detail_company_name'],
			'address_1'                                                         =>  trim($booking['meta']['client_billing_detail_street_name'].' '.$booking['meta']['client_billing_detail_street_number']),
			'address_2'                                                         =>  '',
            'city'                                                              =>  $booking['meta']['client_billing_detail_city'],
            'postcode'                                                          =>  $booking['meta']['client_billing_detail_postal_code'],
            'country'                                                           =>  $booking['meta']['client_billing_detail_country_code'],
            'state'                                                             =>  $booking['meta']['client_billing_detail_state'],
			'phone'                                                             =>  $booking['meta']['client_contact_detail_phone_number'],
            'email'                                                             =>  $booking['meta']['client_contact_detail_email_address']			
		);
				
		$order=wc_create_order();
		$order->set_address($address,'billing');
		$order->set_address($address,'shipping');
        $order->set_payment_method($booking['meta']['payment_id']);
        
        update_post_meta($order->get_id(),PLUGIN_CHBS_CONTEXT.'_booking_id',$bookingId);
               
        if($userId>0) 
        {
            update_post_meta($order->get_id(),'_customer_user',$userId);
        
            foreach($address as $index=>$value) 
            {
                update_user_meta($userId,'billing_'.$index,$value);
                update_user_meta($userId,'shipping_'.$index,$value);
            }          
        }
            
        /***/

        $billing=$Booking->createBilling($bookingId);
				
        if(isset($billing['detail']))
		{
            $productId=array();
            
            /***/
            
            foreach($billing['detail'] as $detail)
			{
				$product=$this->prepareProduct
                (
                    array
                    (
                        'post'                                                  =>  array
                        (
                            'post_title'                                        =>  $detail['name']
                        ),
                        'meta'                                                  =>  array
                        (
                            'chbs_price_gross'                                  =>  $detail['value_gross'],
                            'chbs_tax_value'                                    =>  $detail['tax_value'],
                            '_regular_price'                                    =>  $detail['value_net'],
                            '_sale_price'                                       =>  $detail['value_net'],
                            '_price'                                            =>  $detail['value_net']
                        )
                    )
                );
                
                $productId[]=$this->createProduct($product);
				$order->add_product(wc_get_product(end($productId)));
            }
            
            $order->calculate_totals();
				
            /***/
		
            $query=$wpdb->prepare('delete '.$wpdb->prefix.'woocommerce_order_items where order_id=%d and order_item_type=%s',$order->get_id(),'tax');
			$wpdb->query($query);
            
            /***/
            
            $taxRateId=1;
            $orderItem=$order->get_items();
				
            /***/
            
            $taxArray=array();
            foreach($orderItem as $item)
			{
                $priceNet=get_post_meta($item->get_product_id(),'_price',true);
				$priceGross=get_post_meta($item->get_product_id(),'chbs_price_gross',true);
				$taxValue=get_post_meta($item->get_product_id(),'chbs_tax_value',true);
				$taxAmount=round($priceGross-$priceNet,2);
				$taxLabel=sprintf(__('Tax %.2f','chauffeur-booking-system'),$taxValue);
                
                if(!isset($taxArray[$taxValue]))
                {
                    $query=$wpdb->prepare('insert into '.$wpdb->prefix.'woocommerce_order_items(order_item_name,order_item_type,order_id) VALUES (%s,%s,%d)',array('TAX-1','tax',$order->get_id()));
                    $wpdb->query($query);

                    $taxItemId=$wpdb->insert_id;

                    $taxArray[$taxValue]=array
                    (
                        'taxItemId'                                             =>  $taxItemId,
                        'rate_id'                                               =>  $taxRateId,
                        'label'                                                 =>  $taxLabel,
                        'compound'                                              =>  '',
                        'tax_amount'                                            =>  $taxAmount,
                        'shipping_tax_amount'                                   =>  0,
					);
                    
                    wc_add_order_item_meta($taxArray[$taxValue]['taxItemId'],'rate_id',$taxArray[$taxValue]['rate_id']);
                    wc_add_order_item_meta($taxArray[$taxValue]['taxItemId'],'label',$taxArray[$taxValue]['label']);
                    wc_add_order_item_meta($taxArray[$taxValue]['taxItemId'],'compound',$taxArray[$taxValue]['compound']);
                    wc_add_order_item_meta($taxArray[$taxValue]['taxItemId'],'tax_amount',$taxArray[$taxValue]['tax_amount']);
                    wc_add_order_item_meta($taxArray[$taxValue]['taxItemId'],'shipping_tax_amount',$taxArray[$taxValue]['shipping_tax_amount']);
                }
                else
                {
                    $taxArray[$taxValue]['tax_amount']+=$taxAmount;
					wc_update_order_item_meta($taxArray[$taxValue]['taxItemId'],'tax_amount',$taxArray[$taxValue]['tax_amount']);        
                }
                
				$taxData=array
                (
                    'total'                                                     =>  array
                    (
                        $taxArray[$taxValue]['rate_id']                         =>  (string)$taxAmount,
                    ),
                    'subtotal'                                                  =>  array
                    (
                        $taxArray[$taxValue]['rate_id']                         =>  (string)$taxAmount,
                    )
                );
                
				wc_update_order_item_meta($item->get_id(),'_line_subtotal_tax',$taxAmount);
				wc_update_order_item_meta($item->get_id(),'_line_tax',$taxAmount);
				wc_update_order_item_meta($item->get_id(),'_line_tax_data',$taxData);
						
				$taxRateId++;
			}
					
            update_post_meta($order->get_id(),'_order_tax',$billing['summary']['value_gross']-$billing['summary']['value_net']);
			update_post_meta($order->get_id(),'_order_total',$billing['summary']['value_gross']);
					
			foreach($productId as $value) wp_delete_post($value);
            
            return($order->get_checkout_payment_url());
        }
        
        return(null);
    }
    
    /**************************************************************************/
    
    function prepareProduct($data)
    {
 		$argument=array
        (
			'post'=>array
            (
				'post_title'													=>  '',
				'post_content'													=>  '',
				'post_status'													=>  'publish',
				'post_type'														=>  'product',
			),
			'meta'=>array
            (
				'chbs_price_gross'												=>  0,
				'chbs_tax_value'												=>  0,
				'_visibility'													=>  'visible',
				'_stock_status'													=>  'instock',
				'_downloadable'													=>  'no',
				'_virtual'														=>  'yes',
				'_regular_price'												=>  0,
				'_sale_price'													=>  0,
				'_purchase_note'												=>  '',
				'_featured'														=>  'no',
				'_weight'														=>  '',
				'_length'														=>  '',
				'_width'														=>  '',
				'_height'														=>  '',
				'_sku'															=>  '',
				'_product_attributes'											=>  array(),
				'_sale_price_dates_from'										=>  '',
				'_sale_price_dates_to'											=>  '',
				'_price'														=>  0,
				'_sold_individually'											=>  '',
				'_manage_stock'													=>  'no',
				'_backorders'													=>  'no',
				'_stock'														=>  '',
                'total_sales'													=>  '0',
			),
		);
        
        if(isset($data['post']))
        {
            foreach($data['post'] as $index=>$value)
                $argument['post'][$index]=$value;
        }
        
        if(isset($data['meta']))
        {
            foreach($data['meta'] as $index=>$value)
                $argument['meta'][$index]=$value;
        }        
        
		return($argument);       
    }
    
    /**************************************************************************/
    
	function createProduct($data)
	{
		$productId=wp_insert_post($data['post']);
		wp_set_object_terms($productId,'simple','product_type');
		foreach($data['meta'] as $key=>$value)
			update_post_meta($productId,$key,$value);
		return($productId);
	}
    
    /**************************************************************************/
    
    function locateTemplate($template,$templateName,$templatePath) 
    {
        global $woocommerce;
       
        $templateTemp=$template;
        if(!$templatePath) $templatePath=$woocommerce->template_url;
 
        $pluginPath=PLUGIN_CHBS_PATH.'woocommerce/';
     
        $template=locate_template(array($templatePath.$templateName,$templateName));
 
        if((!$template) && (file_exists($pluginPath.$templateName)))
            $template=$pluginPath.$templateName;
 
        if(!$template) $template=$templateTemp;
   
        return ($template);
    }
    
    /**************************************************************************/
    
    function getUserData()
    {
        $userData=array();
        $userCurrent=wp_get_current_user();
        
        $Country=new WC_Countries();
        $Customer=new WC_Customer($userCurrent->ID);
        
        $billingAddress=$Customer->get_billing();
        
        $userData['client_contact_detail_first_name']=$userCurrent->user_firstname;
        $userData['client_contact_detail_last_name']=$userCurrent->user_lastname;
        $userData['client_contact_detail_email_address']=$userCurrent->user_email;
        $userData['client_contact_detail_phone_number']=null;
        
        $userData['client_billing_detail_enable']=1;
        $userData['client_billing_detail_company_name']=$billingAddress['company'];
        $userData['client_billing_detail_tax_number']=null;
        $userData['client_billing_detail_street_name']=$billingAddress['address_1'];
        $userData['client_billing_detail_street_number']=$billingAddress['address_2'];
        $userData['client_billing_detail_city']=$billingAddress['city'];
        $userData['client_billing_detail_state']=null;
        $userData['client_billing_detail_postal_code']=$billingAddress['postcode'];
        $userData['client_billing_detail_country_code']=$billingAddress['country'];
        
        $state=$billingAddress['state'];
        $country=$billingAddress['country'];
        
        $countryState=$Country->get_states();
        
        if((isset($countryState[$country])) && (isset($countryState[$country][$state])))
            $userData['client_billing_detail_state']=$countryState[$country][$state];
        
        return($userData);
    }
    
    /**************************************************************************/
    
    function getPaymentURLAddress($bookingId)
    {
        $order=wc_get_order($bookingId);
        
        if($order!==false)
            return($order->get_checkout_payment_url());
        
        return(null);
    }
    
    /**************************************************************************/
}

/******************************************************************************/
/******************************************************************************/