<?php

/******************************************************************************/
/******************************************************************************/

class CHBSPriceRule
{
	/**************************************************************************/
	
    function __construct()
    {
        $this->priceType=array
        (
            1                                                                   =>  array(__('Variable pricing','chauffeur-booking-system')),
            2                                                                   =>  array(__('Fixed pricing','chauffeur-booking-system'))
        );
    }
    
    /**************************************************************************/
    
    function getPriceType()
    {
        return($this->priceType);
    }
    
    /**************************************************************************/
    
    function isPriceType($priceType)
    {
        return(array_key_exists($priceType,$this->priceType));
    }
    
    /**************************************************************************/
    
    public function init()
    {
        $this->registerCPT();
    }
    
	/**************************************************************************/

    public static function getCPTName()
    {
        return(PLUGIN_CHBS_CONTEXT.'_price_rule');
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
					'name'														=>	__('Pricing Rules','chauffeur-booking-system'),
					'singular_name'												=>	__('Pricing Rule','chauffeur-booking-system'),
					'add_new'													=>	__('Add New','chauffeur-booking-system'),
					'add_new_item'												=>	__('Add New Pricing Rule','chauffeur-booking-system'),
					'edit_item'													=>	__('Edit Pricing Rule','chauffeur-booking-system'),
					'new_item'													=>	__('New Pricing Rule','chauffeur-booking-system'),
					'all_items'													=>	__('Pricing Rules','chauffeur-booking-system'),
					'view_item'													=>	__('View Pricing Rule','chauffeur-booking-system'),
					'search_items'												=>	__('Search Pricing Rules','chauffeur-booking-system'),
					'not_found'													=>	__('No Pricing Rules Found','chauffeur-booking-system'),
					'not_found_in_trash'										=>	__('No Pricing Rules in Trash','chauffeur-booking-system'), 
					'parent_item_colon'											=>	'',
					'menu_name'													=>	__('Pricing Rules','chauffeur-booking-system')
				),	
				'public'														=>	false,  
				'show_ui'														=>	true, 
				'show_in_menu'													=>	'edit.php?post_type='.CHBSBooking::getCPTName(),
				'capability_type'												=>	'post',
				'menu_position'													=>	2,
				'hierarchical'													=>	false,  
				'rewrite'														=>	false,  
				'supports'														=>	array('title')  
			)
		);
        
        add_action('save_post',array($this,'savePost'));
        add_action('add_meta_boxes_'.self::getCPTName(),array($this,'addMetaBox'));
        add_filter('postbox_classes_'.self::getCPTName().'_chbs_meta_box_price_rule',array($this,'adminCreateMetaBoxClass'));
        
		add_filter('manage_edit-'.self::getCPTName().'_columns',array($this,'manageEditColumns')); 
		add_action('manage_'.self::getCPTName().'_posts_custom_column',array($this,'managePostsCustomColumn'));
		add_filter('manage_edit-'.self::getCPTName().'_sortable_columns',array($this,'manageEditSortableColumns'));
    }

    /**************************************************************************/
    
    function addMetaBox()
    {
        add_meta_box(PLUGIN_CHBS_CONTEXT.'_meta_box_price_rule',__('Main','chauffeur-booking-system'),array($this,'addMetaBoxMain'),self::getCPTName(),'normal','low');		
    }
    
    /**************************************************************************/
    
    function addMetaBoxMain()
    {
        global $post;
        
		$data=array();
        
        $Route=new CHBSRoute();
        $Vehicle=new CHBSVehicle();
        $TaxRate=new CHBSTaxRate();
        $PriceType=new CHBSPriceType();
        $ServiceType=new CHBSServiceType();
        $BookingForm=new CHBSBookingForm();
        
        $data['meta']=CHBSPostMeta::getPostMeta($post);
        
		$data['nonce']=CHBSHelper::createNonceField(PLUGIN_CHBS_CONTEXT.'_meta_box_price_rule');

        $data['dictionary']['route']=$Route->getDictionary();
        $data['dictionary']['vehicle']=$Vehicle->getDictionary();
        $data['dictionary']['tax_rate']=$TaxRate->getDictionary();
        $data['dictionary']['price_type']=$PriceType->getPriceType();
        $data['dictionary']['booking_form']=$BookingForm->getDictionary();
        $data['dictionary']['service_type']=$ServiceType->getServiceType();
        
		$Template=new CHBSTemplate($data,PLUGIN_CHBS_TEMPLATE_PATH.'admin/meta_box_price_rule.php');
		echo $Template->output();	        
    }
    
     /**************************************************************************/
    
    function adminCreateMetaBoxClass($class) 
    {
        array_push($class,'to-postbox-1');
        return($class);
    }

	/**************************************************************************/
	
	function setPostMetaDefault(&$meta)
	{
        $TaxRate=new CHBSTaxRate();
        
        CHBSHelper::setDefault($meta,'booking_form_id',array(-1));
        CHBSHelper::setDefault($meta,'service_type_id',array(-1));
        
        CHBSHelper::setDefault($meta,'route_id',array(-1));
        CHBSHelper::setDefault($meta,'vehicle_id',array(-1));
        
        CHBSHelper::setDefault($meta,'pickup_day_number',array(-1));
              
        CHBSHelper::setDefault($meta,'price_type',1);
        
        CHBSHelper::setDefault($meta,'price_fixed_value','0.00');
        CHBSHelper::setDefault($meta,'price_fixed_tax_rate_id',$TaxRate->getDefaultTaxPostId());

        CHBSHelper::setDefault($meta,'price_fixed_return_value','0.00');
        CHBSHelper::setDefault($meta,'price_fixed_return_tax_rate_id',$TaxRate->getDefaultTaxPostId());
        
        CHBSHelper::setDefault($meta,'price_initial_value','0.00');
        CHBSHelper::setDefault($meta,'price_initial_tax_rate_id',$TaxRate->getDefaultTaxPostId());

        CHBSHelper::setDefault($meta,'price_delivery_value','0.00');
        CHBSHelper::setDefault($meta,'price_delivery_tax_rate_id',$TaxRate->getDefaultTaxPostId());

        CHBSHelper::setDefault($meta,'price_delivery_return_value','0.00');
        CHBSHelper::setDefault($meta,'price_delivery_return_tax_rate_id',$TaxRate->getDefaultTaxPostId());
        
        CHBSHelper::setDefault($meta,'price_distance_value','0.00');
        CHBSHelper::setDefault($meta,'price_distance_tax_rate_id',$TaxRate->getDefaultTaxPostId());

        CHBSHelper::setDefault($meta,'price_distance_return_value','0.00');
        CHBSHelper::setDefault($meta,'price_distance_return_tax_rate_id',$TaxRate->getDefaultTaxPostId());

        CHBSHelper::setDefault($meta,'price_hour_value','0.00');
        CHBSHelper::setDefault($meta,'price_hour_tax_rate_id',$TaxRate->getDefaultTaxPostId());

        CHBSHelper::setDefault($meta,'price_extra_time_value','0.00');
        CHBSHelper::setDefault($meta,'price_extra_time_tax_rate_id',$TaxRate->getDefaultTaxPostId());  
        
        CHBSHelper::setDefault($meta,'price_passenger_adult_value','0.00');
        CHBSHelper::setDefault($meta,'price_passenger_adult_tax_rate_id',$TaxRate->getDefaultTaxPostId()); 
 
        CHBSHelper::setDefault($meta,'price_passenger_children_value','0.00');
        CHBSHelper::setDefault($meta,'price_passenger_children_tax_rate_id',$TaxRate->getDefaultTaxPostId()); 
	}
    
    /**************************************************************************/
    
    function savePost($postId)
    {      
        if(!$_POST) return(false);
        
        if(CHBSHelper::checkSavePost($postId,PLUGIN_CHBS_CONTEXT.'_meta_box_price_rule_noncename','savePost')===false) return(false);
        
        $Date=new CHBSDate();
        $Route=new CHBSRoute();
        $Vehicle=new CHBSVehicle();
        $TaxRate=new CHBSTaxRate();
        $ServiceType=new CHBSServiceType();
        $BookingForm=new CHBSBookingForm();
        
        $Validation=new CHBSValidation();
        
        $option=CHBSHelper::getPostOption();
        
        /***/
        
        $dictionary=array
        (
            'booking_form_id'                                                   =>  array
            (
                'dictionary'                                                    =>  $BookingForm->getDictionary()
            ),
            'service_type_id'                                                   =>  array
            (
                'dictionary'                                                    =>  $ServiceType->getServiceType()
            ),            
            'route_id'                                                          =>  array
            (
                'dictionary'                                                    =>  $Route->getDictionary()
            ),            
            'vehicle_id'                                                        =>  array
            (
                'dictionary'                                                    =>  $Vehicle->getDictionary()
            ),
            'pickup_day_number'                                                 =>  array
            (
                'dictionary'                                                    =>  array(1,2,3,4,5,6,7)
            ),
        );
        
        foreach($dictionary as $dIndex=>$dValue)
        {
            $option[$dIndex]=(array)CHBSHelper::getPostValue($dIndex);
            if(in_array(-1,$option[$dIndex]))
            {
                $option[$dIndex]=array(-1);
            }
            else
            {
                foreach($option[$dIndex] as $oIndex=>$oValue)
                {
                    if(!isset($dValue['dictionary']))
                        unset($option[$dIndex][$oIndex]);                
                }
            }             
        }
        
        /***/
        
        $date=array();
       
        foreach($option['pickup_date']['start'] as $index=>$value)
        {
            $d=array($value,$option['pickup_date']['stop'][$index]);
            
            $d[0]=$Date->formatDateToStandard($d[0]);
            $d[1]=$Date->formatDateToStandard($d[1]);
            
            if(!$Validation->isDate($d[0])) continue;
            if(!$Validation->isDate($d[1])) continue;
            
            if($Date->compareDate($d[0],$d[1])==1) continue;
            
            array_push($date,array('start'=>$d[0],'stop'=>$d[1]));
        }

        $option['pickup_date']=$date;

        /***/
        
        $time=array();
       
        foreach($option['pickup_time']['start'] as $index=>$value)
        {
            $t=array($value,$option['pickup_time']['stop'][$index]);
            
            $t[0]=$Date->formatTimeToStandard($t[0]);
            $t[1]=$Date->formatTimeToStandard($t[1]);
            
            if(!$Validation->isTime($t[0])) continue;
            if(!$Validation->isTime($t[1])) continue;
            
            if($Date->compareTime($t[0],$t[1])!=2) continue;
            
            array_push($time,array('start'=>$t[0],'stop'=>$t[1]));
        }
        
        $option['pickup_time']=$time;
        
        /***/
        
        $distance=array();
       
        foreach($option['distance']['start'] as $index=>$value)
        {
            $d=array($value,$option['distance']['stop'][$index]);
            
            if(!$Validation->isFloat($d[0],0,999999999.99)) continue;
            if(!$Validation->isFloat($d[1],0,999999999.99)) continue;
  
            if($d[0]>$d[1]) continue;
            
            if(CHBSOption::getOption('length_unit')==2)
            {
                $Length=new CHBSLength();
                
                $d[0]=$Length->convertUnit($d[0],2,1);
                $d[1]=$Length->convertUnit($d[1],2,1);
            }

            array_push($distance,array('start'=>$d[0],'stop'=>$d[1]));
        }
        
        $option['distance']=$distance;
        
        /***/
        
        $passenger=array();
       
        foreach($option['passenger']['start'] as $index=>$value)
        {
            $d=array($value,$option['passenger']['stop'][$index]);
            
            if(!$Validation->isNumber($d[0],1,99)) continue;
            if(!$Validation->isNumber($d[1],1,99)) continue;
  
            if($d[0]>$d[1]) continue;

            array_push($passenger,array('start'=>$d[0],'stop'=>$d[1]));
        }        
        
        $option['passenger']=$passenger;
       
        /***/
        
        $duration=array();
       
        foreach($option['duration']['start'] as $index=>$value)
        {
            $d=array($value,$option['duration']['stop'][$index]);
            
            if(!$Validation->isNumber($d[0],0,9999)) continue;
            if(!$Validation->isNumber($d[1],0,9999)) continue;
  
            if($d[0]>$d[1]) continue;

            array_push($duration,array('start'=>$d[0],'stop'=>$d[1]));
        }        
        
        $option['duration']=$duration;        
        
        /***/
        
        if(!$this->isPriceType($option['price_type']))
            $option['price_type']=1;

        if(!$Validation->isPrice($option['price_fixed_value'],false))
           $option['price_fixed_value']=0.00;
        if(!$TaxRate->isTaxRate($option['price_fixed_tax_rate_id']))
            $option['price_fixed_tax_rate_id']=0;

        if(!$Validation->isPrice($option['price_fixed_return_value'],false))
           $option['price_fixed_return_value']=0.00;
        if(!$TaxRate->isTaxRate($option['price_fixed_return_tax_rate_id']))
            $option['price_fixed_return_tax_rate_id']=0;
        
        if(!$Validation->isPrice($option['price_initial_value'],false))
           $option['price_initial_value']=0.00;
        if(!$TaxRate->isTaxRate($option['price_initial_tax_rate_id']))
            $option['price_initial_tax_rate_id']=0;
        
        if(!$Validation->isPrice($option['price_delivery_value'],false))
           $option['price_delivery_value']=0.00;
        if(!$TaxRate->isTaxRate($option['price_delivery_tax_rate_id']))
            $option['price_delivery_tax_rate_id']=0;        

        if(!$Validation->isPrice($option['price_delivery_return_value'],false))
           $option['price_delivery_return_value']=0.00;
        if(!$TaxRate->isTaxRate($option['price_delivery_return_tax_rate_id']))
            $option['price_delivery_return_tax_rate_id']=0;    
        
        if(!$Validation->isPrice($option['price_distance_value'],false))
           $option['price_distance_value']=0.00;
        if(!$TaxRate->isTaxRate($option['price_distance_tax_rate_id']))
            $option['price_distance_tax_rate_id']=0;

        if(!$Validation->isPrice($option['price_distance_return_value'],false))
           $option['price_distance_return_value']=0.00;
        if(!$TaxRate->isTaxRate($option['price_distance_return_tax_rate_id']))
            $option['price_distance_return_tax_rate_id']=0;

        if(!$Validation->isPrice($option['price_hour_value'],false))
           $option['price_hour_value']=0.00;
        if(!$TaxRate->isTaxRate($option['price_hour_tax_rate_id']))
            $option['price_hour_tax_rate_id']=0;

        if(!$Validation->isPrice($option['price_extra_time_value'],false))
           $option['price_extra_time_value']=0.00;
        if(!$TaxRate->isTaxRate($option['price_extra_time_tax_rate_id']))
            $option['price_extra_time_tax_rate_id']=0;        
        
        if(!$Validation->isPrice($option['price_passenger_adult_value'],false))
           $option['price_passenger_adult_value']=0.00;
        if(!$TaxRate->isTaxRate($option['price_passenger_adult_tax_rate_id']))
            $option['price_passenger_adult_tax_rate_id']=0;
        
        if(!$Validation->isPrice($option['price_passenger_children_value'],false))
           $option['price_passenger_children_value']=0.00;
        if(!$TaxRate->isTaxRate($option['price_passenger_children_tax_rate_id']))
            $option['price_passenger_children_tax_rate_id']=0;        
        
        /***/
        
        $key=array
        (
            'booking_form_id',
            'service_type_id',
            'route_id',
            'vehicle_id',
            'pickup_day_number',
            'pickup_date',
            'pickup_time',
            'distance',
            'passenger',
            'duration',
            'price_type',
            'price_fixed_value',
            'price_fixed_tax_rate_id',
            'price_fixed_return_value',
            'price_fixed_return_tax_rate_id',
            'price_initial_value',
            'price_initial_tax_rate_id',
            'price_delivery_value',
            'price_delivery_tax_rate_id',
            'price_delivery_return_value',
            'price_delivery_return_tax_rate_id',
            'price_distance_value',
            'price_distance_tax_rate_id',
            'price_distance_return_value',
            'price_distance_return_tax_rate_id',
            'price_hour_value',
            'price_hour_tax_rate_id',
            'price_extra_time_value',
            'price_extra_time_tax_rate_id',
            'price_passenger_adult_value',
            'price_passenger_adult_tax_rate_id',
            'price_passenger_children_value',
            'price_passenger_children_tax_rate_id'
        );
        
        foreach($key as $value)
            CHBSPostMeta::updatePostMeta($postId,$value,$option[$value]);
    }
    
    /**************************************************************************/

    function manageEditColumns($column)
    {
        $column=array
        (
            'cb'                                                                =>  $column['cb'],
            'title'                                                             =>  $column['title'],
            'rule_booking_form'                                                 =>  __('Booking forms','chauffeur-booking-system'),
            'rule_service_type'                                                 =>  __('Service types','chauffeur-booking-system'),
            'rule_route'                                                        =>  __('Routes','chauffeur-booking-system'),
            'rule_vehicle'                                                      =>  __('Vehicles','chauffeur-booking-system'),
            'rule_day_number'                                                   =>  __('Day numbers','chauffeur-booking-system'),
            'rule_date'                                                         =>  __('Dates','chauffeur-booking-system'),
            'rule_hour'                                                         =>  __('Hours','chauffeur-booking-system'),
            'rule_distance'                                                     =>  __('Distance','chauffeur-booking-system'),
            'rule_passenger'                                                    =>  __('Passengers','chauffeur-booking-system'),
            'rule_duration'                                                     =>  __('Duration','chauffeur-booking-system'),
            'rule_price'                                                        =>  __('Price','chauffeur-booking-system')
        );
   
		return($column);          
    }
    
    /**************************************************************************/
    
    function getPricingRuleAdminListDictionary()
    {
        $dictionary=array();
    
        $Date=new CHBSDate();
        $Route=new CHBSRoute();
        $Vehicle=new CHBSVehicle();
        $ServiceType=new CHBSServiceType();
        $BookingForm=new CHBSBookingForm();
        
        $dictionary['route']=$Route->getDictionary();
        $dictionary['vehicle']=$Vehicle->getDictionary();
        $dictionary['booking_form']=$BookingForm->getDictionary();
        
        $dictionary['service_type']=$ServiceType->getServiceType();

        $dictionary['day']=$Date->day;
        
        return($dictionary);
    }
    
    /**************************************************************************/
    
    function displayPricingRuleAdminListValue($data,$dictionary,$link=false,$sort=false)
    {
        $html=null;
        
        $dataSort=array();

        foreach($data as $value)
        {
            if(!array_key_exists($value,$dictionary)) continue;

            if(array_key_exists('post',$dictionary[$value]))
                $label=$dictionary[$value]['post']->post_title;
            else $label=$dictionary[$value][0];            

            $dataSort[$value]=$label;
        }

        if($sort) asort($dataSort);

        $data=$dataSort;
        
        foreach($data as $index=>$value)
        {
            $label=$value;
            
            if($link) $label='<a href="'.get_edit_post_link($index).'">'.$value.'</a>';
            $html.='<div>'.$label.'</div>';
        }
        
        return($html);
    }
    
    /**************************************************************************/
    
    function managePostsCustomColumn($column)
    {
		global $post;
        
        $Date=new CHBSDate();
        $Length=new CHBSLength();
        $PriceType=new CHBSPriceType();
        
        $meta=CHBSPostMeta::getPostMeta($post);
        
        $dictionary=CHBSGlobalData::setGlobalData('pricing_rule_admin_list_dictionary',array($this,'getPricingRuleAdminListDictionary'));
        
		switch($column) 
		{
			case 'rule_booking_form':
                
                if(in_array(-1,$meta['booking_form_id'])) esc_html_e('All booking forms','chauffeur-booking-system');
                else echo $this->displayPricingRuleAdminListValue($meta['booking_form_id'],$dictionary['booking_form'],true,true);
				
			break;
        
			case 'rule_service_type':
                
                if(in_array(-1,$meta['service_type_id'])) esc_html_e('All service types','chauffeur-booking-system');
                else echo $this->displayPricingRuleAdminListValue($meta['service_type_id'],$dictionary['service_type'],false,true);
				
			break;
       
			case 'rule_route':
				
                if(in_array(-1,$meta['route_id'])) esc_html_e('All routes','chauffeur-booking-system');
                else echo $this->displayPricingRuleAdminListValue($meta['route_id'],$dictionary['route'],true,true);
                
			break;
        
			case 'rule_vehicle':
                
                if(in_array(-1,$meta['vehicle_id'])) esc_html_e('All vehicles','chauffeur-booking-system');
                else echo $this->displayPricingRuleAdminListValue($meta['vehicle_id'],$dictionary['vehicle'],true,true);
				
			break;
        
			case 'rule_day_number':
				
                if(in_array(-1,$meta['pickup_day_number'])) esc_html_e('All days','chauffeur-booking-system');
                else echo $this->displayPricingRuleAdminListValue($meta['pickup_day_number'],$dictionary['day'],false,false);
                
			break;
       
			case 'rule_date':
                
                if((isset($meta['pickup_date'])) && (count($meta['pickup_date'])))
                {
                    foreach($meta['pickup_date'] as $value)
                        echo '<div>'.$Date->formatDateToDisplay($value['start']).' - <br>'.$Date->formatDateToDisplay($value['stop']).'</div>';                    
                }
                else esc_html_e('All ranges','chauffeur-booking-system');
				
			break;
        
			case 'rule_hour':
                
                if((isset($meta['pickup_time'])) && (count($meta['pickup_time'])))
                {
                    foreach($meta['pickup_time'] as $value)
                        echo '<div>'.$Date->formatTimeToDisplay($value['start']).' - '.$Date->formatTimeToDisplay($value['stop']).'</div>';                    
                }
                else esc_html_e('All ranges','chauffeur-booking-system');
                
			break;
        
			case 'rule_distance':
                
                if((isset($meta['distance'])) && (count($meta['distance'])))
                {
                    foreach($meta['distance'] as $value)
                    {
                        if(CHBSOption::getOption('length_unit')==2)
                        {
                            $value['start']=round($Length->convertUnit($value['start'],1,2),1);
                            $value['stop']=round($Length->convertUnit($value['stop'],1,2),1); 
                        }
                        
                        echo '<div>'.$value['start'].' - '.$value['stop'].' '.$Length->getUnitShortName(CHBSOption::getOption('length')).'</div>';
                    }
                }                
                else esc_html_e('All ranges','chauffeur-booking-system');
                
			break;
            
			case 'rule_passenger':
                
                if((isset($meta['passenger'])) && (count($meta['passenger'])))
                {
                    foreach($meta['passenger'] as $value)
                        echo '<div>'.$value['start'].' - '.$value['stop'].'</div>';
                }                
                else esc_html_e('All ranges','chauffeur-booking-system');
                
			break;
            
			case 'rule_duration':
                
                if((isset($meta['duration'])) && (count($meta['duration'])))
                {
                    foreach($meta['duration'] as $value)
                        echo '<div>'.$value['start'].' - '.$value['stop'].'</div>';
                }                
                else esc_html_e('All ranges','chauffeur-booking-system');
                
			break;
        
			case 'rule_price':
                
                echo sprintf(__('Price type: <b>%s</b>','chauffeur-booking-system'),$PriceType->getPriceTypeName($meta['price_type']));
                echo '<br/>';
                
                if((int)$meta['price_type']===2)
                {
                    echo sprintf(__('Fixed price: <b>%s</b>','chauffeur-booking-system'),CHBSPrice::format($meta['price_fixed_value'],CHBSOption::getOption('currency')));
                    echo '<br/>';
                    echo sprintf(__('Fixed price (return): <b>%s</b>','chauffeur-booking-system'),CHBSPrice::format($meta['price_fixed_return_value'],CHBSOption::getOption('currency')));
                    echo '<br/>';
                }
                
                if((int)$meta['price_type']===1)
                {
                    echo sprintf(__('Initial fee: <b>%s</b>','chauffeur-booking-system'),CHBSPrice::format($meta['price_initial_value'],CHBSOption::getOption('currency')));
                    echo '<br/>';
                    echo sprintf(__('Delivery fee: <b>%s</b>','chauffeur-booking-system'),CHBSPrice::format($meta['price_delivery_value'],CHBSOption::getOption('currency')));
                    echo '<br/>';                
                    echo sprintf(__('Delivery (return) fee: <b>%s</b>','chauffeur-booking-system'),CHBSPrice::format($meta['price_delivery_return_value'],CHBSOption::getOption('currency')));
                    echo '<br/>';   

                    if(CHBSOption::getOption('length_unit')==2)
                    {
                        echo sprintf(__('Price per mile: <b>%s</b>','chauffeur-booking-system'),CHBSPrice::format($meta['price_distance_value'],CHBSOption::getOption('currency')));
                        echo '<br/>';
                        echo sprintf(__('Price per mile (return): <b>%s</b>','chauffeur-booking-system'),CHBSPrice::format($meta['price_distance_value'],CHBSOption::getOption('currency')));
                        echo '<br/>';
                    }
                    else 
                    {
                        echo sprintf(__('Price per kilometer: <b>%s</b>','chauffeur-booking-system'),CHBSPrice::format($meta['price_distance_return_value'],CHBSOption::getOption('currency')));
                        echo '<br/>';
                        echo sprintf(__('Price per kilometer (return): <b>%s</b>','chauffeur-booking-system'),CHBSPrice::format($meta['price_distance_return_value'],CHBSOption::getOption('currency')));
                        echo '<br/>';
                    }

                    echo sprintf(__('Price per hour: <b>%s</b>','chauffeur-booking-system'),CHBSPrice::format($meta['price_hour_value'],CHBSOption::getOption('currency')));
                    echo '<br/>';
                }

                echo sprintf(__('Price per extra time: <b>%s</b>','chauffeur-booking-system'),CHBSPrice::format($meta['price_extra_time_value'],CHBSOption::getOption('currency')));
                
                if((int)$meta['price_type']===1)
                {
                    echo '<br/>';
                    echo sprintf(__('Price per adult passenger: <b>%s</b>','chauffeur-booking-system'),CHBSPrice::format($meta['price_passenger_adult_value'],CHBSOption::getOption('currency')));
                    echo '<br/>';
                    echo sprintf(__('Price per child passenger: <b>%s</b>','chauffeur-booking-system'),CHBSPrice::format($meta['price_passenger_children_value'],CHBSOption::getOption('currency')));
                }
                
			break;
        }
    }
    
    /**************************************************************************/
    
    function manageEditSortableColumns($column)
    {
		return($column);       
    }
    
    /**************************************************************************/
    
    function getPriceFromRule($bookingData,$rule)
    {
        if($rule===false) return(false);
        
        $Date=new CHBSDate();
        
        foreach($rule as $ruleData)
        {
            if(!in_array(-1,$ruleData['meta']['booking_form_id']))
            {
                if(!in_array($bookingData['booking_form_id'],$ruleData['meta']['booking_form_id'])) continue;
            }
            if(!in_array(-1,$ruleData['meta']['service_type_id']))
            {
                if(!in_array($bookingData['service_type_id'],$ruleData['meta']['service_type_id'])) continue;
            }  
            
            if($bookingData['service_type_id']==3)
            {
                if(!in_array(-1,$ruleData['meta']['route_id']))
                {
                    if(!in_array($bookingData['route_id'],$ruleData['meta']['route_id'])) continue;
                }
            }
         
            if(!in_array(-1,$ruleData['meta']['vehicle_id']))
            {
                if(!in_array($bookingData['vehicle_id'],$ruleData['meta']['vehicle_id'])) continue;
            } 
            
            if(!in_array(-1,$ruleData['meta']['pickup_day_number']))
            {
                $date=$Date->formatDateToStandard($bookingData['pickup_date']);
                
                if(!in_array(date('N',CHBSDate::strtotime($date)),$ruleData['meta']['pickup_day_number'])) continue;
            }
            
            if(is_array($ruleData['meta']['pickup_date']))
            {
                $match=!count($ruleData['meta']['pickup_date']);
                
                foreach($ruleData['meta']['pickup_date'] as $value)
                {
                    $date=$Date->formatDateToStandard($bookingData['pickup_date']);
                    
                    if($Date->dateInRange($date,$value['start'],$value['stop']))
                    {
                        $match=true;
                        break;
                    }
                }
                
                if(!$match) continue;
            }
            
            if(is_array($ruleData['meta']['pickup_time']))
            {
                $match=!count($ruleData['meta']['pickup_time']);
                
                foreach($ruleData['meta']['pickup_time'] as $value)
                {
                    $time=$Date->formatTimeToStandard($bookingData['pickup_time']);
                    
                    if($Date->timeInRange($time,$value['start'],$value['stop']))
                    {
                        $match=true;
                        break;
                    }
                }
                
                if(!$match) continue;
            }
            
            if(is_array($ruleData['meta']['distance']))
            {
                $match=!count($ruleData['meta']['distance']);
                
                foreach($ruleData['meta']['distance'] as $value)
                {
                    if(($value['start']<=$bookingData['distance']) && ($bookingData['distance']<=$value['stop']))
                    {
                        $match=true;
                        break;                        
                    }
                }
                
                if(!$match) continue;
            }     
            
            if(is_array($ruleData['meta']['passenger']))
            {
                $match=!count($ruleData['meta']['passenger']);
                foreach($ruleData['meta']['passenger'] as $value)
                {
                    if(($value['start']<=$bookingData['passenger_sum']) && ($bookingData['passenger_sum']<=$value['stop']))
                    {
                        $match=true;
                        break;                        
                    }
                }
                
                if(!$match) continue;
            }          
            
            if($bookingData['service_type_id']==2)
            {
                if(is_array($ruleData['meta']['duration']))
                {
                    $bookingData['duration']/=60;
                    $match=!count($ruleData['meta']['duration']);
                    
                    foreach($ruleData['meta']['duration'] as $value)
                    {
                        if(($value['start']<=$bookingData['duration']) && ($bookingData['duration']<=$value['stop']))
                        {
                            $match=true;
                            break;                        
                        }
                    }

                    if(!$match) continue;
                }                     
            }
            
            return(array
            (
                'price_type'                                                    =>  $ruleData['meta']['price_type'],
                'price_fixed_value'                                             =>  $ruleData['meta']['price_fixed_value'],
                'price_fixed_tax_rate_id'                                       =>  $ruleData['meta']['price_fixed_tax_rate_id'],
                'price_fixed_return_value'                                      =>  $ruleData['meta']['price_fixed_return_value'],
                'price_fixed_return_tax_rate_id'                                =>  $ruleData['meta']['price_fixed_return_tax_rate_id'],
                'price_initial_value'                                           =>  $ruleData['meta']['price_initial_value'],
                'price_initial_tax_rate_id'                                     =>  $ruleData['meta']['price_initial_tax_rate_id'],
                'price_delivery_value'                                          =>  $ruleData['meta']['price_delivery_value'],    
                'price_delivery_tax_rate_id'                                    =>  $ruleData['meta']['price_delivery_tax_rate_id'],
                'price_delivery_return_value'                                   =>  $ruleData['meta']['price_delivery_return_value'],    
                'price_delivery_return_tax_rate_id'                             =>  $ruleData['meta']['price_delivery_return_tax_rate_id'],
                'price_distance_value'                                          =>  $ruleData['meta']['price_distance_value'],
                'price_distance_tax_rate_id'                                    =>  $ruleData['meta']['price_distance_tax_rate_id'],
                'price_distance_return_value'                                   =>  $ruleData['meta']['price_distance_return_value'],
                'price_distance_return_tax_rate_id'                             =>  $ruleData['meta']['price_distance_return_tax_rate_id'],
                'price_hour_value'                                              =>  $ruleData['meta']['price_hour_value'],
                'price_hour_tax_rate_id'                                        =>  $ruleData['meta']['price_hour_tax_rate_id'],
                'price_extra_time_value'                                        =>  $ruleData['meta']['price_extra_time_value'],
                'price_extra_time_tax_rate_id'                                  =>  $ruleData['meta']['price_extra_time_tax_rate_id'],
                'price_passenger_adult_value'                                   =>  $ruleData['meta']['price_passenger_adult_value'],
                'price_passenger_adult_tax_rate_id'                             =>  $ruleData['meta']['price_passenger_adult_tax_rate_id'],
                'price_passenger_children_value'                                =>  $ruleData['meta']['price_passenger_children_value'],
                'price_passenger_children_tax_rate_id'                          =>  $ruleData['meta']['price_passenger_children_tax_rate_id']
            ));
        }
        
        return(false);
    }
    
    /**************************************************************************/
    
    function getDictionary($attr=array())
    {
		global $post;
		
		$dictionary=array();
		
		$default=array
		(
			'price_rule_id'                                                     =>	0
		);
		
		$attribute=shortcode_atts($default,$attr);
		CHBSHelper::preservePost($post,$bPost);
		
		$argument=array
		(
			'post_type'															=>	self::getCPTName(),
			'post_status'														=>	'publish',
			'posts_per_page'													=>	-1,
			'orderby'															=>	array('menu_order'=>'desc')
		);
		
		if($attribute['price_rule_id'])
			$argument['p']=$attribute['price_rule_id'];
               
		$query=new WP_Query($argument);
		if($query===false) return($dictionary);
        
		while($query->have_posts())
		{
			$query->the_post();
			$dictionary[$post->ID]['post']=$post;
			$dictionary[$post->ID]['meta']=CHBSPostMeta::getPostMeta($post);
		}
		
		CHBSHelper::preservePost($post,$bPost,0);	
		
		return($dictionary);        
    }
    
    /**************************************************************************/
}

/******************************************************************************/
/******************************************************************************/