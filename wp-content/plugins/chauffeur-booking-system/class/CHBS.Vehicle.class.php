<?php

/******************************************************************************/
/******************************************************************************/

class CHBSVehicle
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
        return(PLUGIN_CHBS_CONTEXT.'_vehicle');
    }
    
    /**************************************************************************/
    
    public static function getCPTCategoryName()
    {
        return(self::getCPTName().'_c');
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
					'name'														=>	__('Vehicles','chauffeur-booking-system'),
					'singular_name'												=>	__('Vehicle','chauffeur-booking-system'),
					'add_new'													=>	__('Add New','chauffeur-booking-system'),
					'add_new_item'												=>	__('Add New Vehicle','chauffeur-booking-system'),
					'edit_item'													=>	__('Edit Vehicle','chauffeur-booking-system'),
					'new_item'													=>	__('New Vehicle','chauffeur-booking-system'),
					'all_items'													=>	__('Vehicles','chauffeur-booking-system'),
					'view_item'													=>	__('View Vehicle','chauffeur-booking-system'),
					'search_items'												=>	__('Search Vehicles','chauffeur-booking-system'),
					'not_found'													=>	__('No Vehicles Found','chauffeur-booking-system'),
					'not_found_in_trash'										=>	__('No Vehicles Found in Trash','chauffeur-booking-system'), 
					'parent_item_colon'											=>	'',
					'menu_name'													=>	__('Vehicles','chauffeur-booking-system')
				),	
				'public'														=>	false,  
				'show_ui'														=>	true, 
				'show_in_menu'													=>	'edit.php?post_type='.CHBSBooking::getCPTName(),
				'capability_type'												=>	'post',
				'menu_position'													=>	2,
				'hierarchical'													=>	false,  
				'rewrite'														=>	false,  
				'supports'														=>	array('title','editor','page-attributes','thumbnail')
			)
		);
        
		register_taxonomy
		(
			self::getCPTCategoryName(),
			self::getCPTName(),
			array
			(
				'label'                                                         =>	__('Vehicle Types','chauffeur-booking-system'),
                'hierarchical'                                                  =>  false
			)
		);
        
        add_action('save_post',array($this,'savePost'));
        add_action('add_meta_boxes_'.self::getCPTName(),array($this,'addMetaBox'));
        add_filter('postbox_classes_'.self::getCPTName().'_chbs_meta_box_vehicle',array($this,'adminCreateMetaBoxClass'));
        
		add_filter('manage_edit-'.self::getCPTName().'_columns',array($this,'manageEditColumns')); 
		add_action('manage_'.self::getCPTName().'_posts_custom_column',array($this,'managePostsCustomColumn'));
		add_filter('manage_edit-'.self::getCPTName().'_sortable_columns',array($this,'manageEditSortableColumns'));
    }

    /**************************************************************************/
    
    function addMetaBox()
    {
        add_meta_box(PLUGIN_CHBS_CONTEXT.'_meta_box_vehicle',__('Main','chauffeur-booking-system'),array($this,'addMetaBoxMain'),self::getCPTName(),'normal','low');		
    }
    
    /**************************************************************************/
    
    function addMetaBoxMain()
    {
        global $post;
        
		$data=array();
        
        $TaxRate=new CHBSTaxRate();
        $PriceType=new CHBSPriceType();
        $VehicleAttribute=new CHBSVehicleAttribute();
        
        $data['meta']=CHBSPostMeta::getPostMeta($post);
        
		$data['nonce']=CHBSHelper::createNonceField(PLUGIN_CHBS_CONTEXT.'_meta_box_vehicle');
       
        $data['dictionary']['tax_rate']=$TaxRate->getDictionary();
        $data['dictionary']['price_type']=$PriceType->getPriceType();
        $data['dictionary']['vehicleAttribute']=$VehicleAttribute->getDictionary();

		$Template=new CHBSTemplate($data,PLUGIN_CHBS_TEMPLATE_PATH.'admin/meta_box_vehicle.php');
		echo $Template->output();	        
    }
    
    /**************************************************************************/
    
    function adminCreateMetaBoxClass($class) 
    {
        array_push($class,'to-postbox-1');
        return($class);
    }
    
    /**************************************************************************/
    
    function savePost($postId)
    {      
        if(!$_POST) return(false);
        
        if(CHBSHelper::checkSavePost($postId,PLUGIN_CHBS_CONTEXT.'_meta_box_vehicle_noncename','savePost')===false) return(false);
        
        $Date=new CHBSDate();
        $TaxRate=new CHBSTaxRate();
        $PriceType=new CHBSPriceType();
        $Validation=new CHBSValidation();
        
        $option=CHBSHelper::getPostOption();
        
        if(!$Validation->isNumber($option['passenger_count'],1,99)) 
            $option['passenger_count']=4;
        if(!$Validation->isNumber($option['bag_count'],1,99)) 
            $option['bag_count']=4;      
        
        /***/
        
        if(!$PriceType->isPriceType($option['price_type']))
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
        
        $attribute=array();
        
        $attributePost=$option['attribute'];
        
        $VehicleAttribute=new CHBSVehicleAttribute();
        $attributeDictionary=$VehicleAttribute->getDictionary();

        foreach($attributeDictionary as $attributeDictionaryIndex=>$attributeDictionaryValue)
        {
            if(!isset($attributePost[$attributeDictionaryIndex])) continue;
            
            switch($attributeDictionaryValue['meta']['attribute_type'])
            {
                case 1:
                    
                    $attribute[$attributeDictionaryIndex]=$attributePost[$attributeDictionaryIndex];
                    
                break;
                
                case 2:
                case 3:
                    
                    if(!is_array($attributePost[$attributeDictionaryIndex])) break;
                    
                    foreach($attributeDictionaryValue['meta']['attribute_value'] as $value)
                    {
                        if(in_array($value['id'],$attributePost[$attributeDictionaryIndex]))
                        {
                            if($attributeDictionaryValue['meta']['attribute_type']===2)
                            {
                                $attribute[$attributeDictionaryIndex]=(int)$value['id'];
                                break;
                            }
                            else $attribute[$attributeDictionaryIndex][]=(int)$value['id'];
                        }
                    }
    
                break;
            }
        }
        
        /***/
        
		$dateExclude=array();
        $dateExcludePost=CHBSHelper::getPostValue('date_exclude');
        
        $count=count($dateExcludePost);
        
        for($i=0;$i<$count;$i+=4)
		{
            $dateExcludePost[$i]=$Date->formatDateToStandard($dateExcludePost[$i]);
            $dateExcludePost[$i+1]=$Date->formatTimeToStandard($dateExcludePost[$i+1]);
            $dateExcludePost[$i+2]=$Date->formatDateToStandard($dateExcludePost[$i+2]);
            $dateExcludePost[$i+3]=$Date->formatTimeToStandard($dateExcludePost[$i+3]);
            
            if($Validation->isEmpty($dateExcludePost[$i+1])) $dateExcludePost[$i+1]='00:00';
            if($Validation->isEmpty($dateExcludePost[$i+3])) $dateExcludePost[$i+3]='00:00';
            
			if(!$Validation->isDate($dateExcludePost[$i],true)) continue;
			if(!$Validation->isDate($dateExcludePost[$i+2],true)) continue;

			if(!$Validation->isTime($dateExcludePost[$i+1],true)) continue;
			if(!$Validation->isTime($dateExcludePost[$i+3],true)) continue;
            
			if($Date->compareDate($dateExcludePost[$i],$dateExcludePost[$i+2])==1) continue;
            if($Date->compareDate(date_i18n('d-m-Y'),$dateExcludePost[$i])==1) continue;
            
            if($Date->compareDate($dateExcludePost[$i],$dateExcludePost[$i+2])===0)
            {
                if($Date->compareTime($dateExcludePost[$i+1],$dateExcludePost[$i+3])===1) continue;
            }
            
			$dateExclude[]=array('startDate'=>$dateExcludePost[$i],'startTime'=>$dateExcludePost[$i+1],'stopDate'=>$dateExcludePost[$i+2],'stopTime'=>$dateExcludePost[$i+3]);
		}
        
        /***/
        
        $key=array
        (
            'vehicle_make',
            'vehicle_model',
            'passenger_count',
            'bag_count',
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
        
        CHBSPostMeta::updatePostMeta($postId,'attribute',$attribute);
        CHBSPostMeta::updatePostMeta($postId,'date_exclude',$dateExclude);
    }
    
	/**************************************************************************/
	
	function setPostMetaDefault(&$meta)
	{
        $TaxRate=new CHBSTaxRate();
        $VehicleAttribute=new CHBSVehicleAttribute();
        
		CHBSHelper::setDefault($meta,'vehicle_make','');
        CHBSHelper::setDefault($meta,'vehicle_model','');
        
        CHBSHelper::setDefault($meta,'passenger_count','4');
        CHBSHelper::setDefault($meta,'bag_count','4');
        
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
        
        $attribute=$VehicleAttribute->getDictionary();
        foreach($attribute as $attributeIndex=>$attributeData)
        {
            if(isset($meta['attribute'][$attributeIndex])) continue;
            
            if($attributeData['meta']['attribute_type']==1)
                $meta['attribute'][$attributeIndex]='';
            else $meta['attribute'][$attributeIndex]=array(-1);
        }
        
		if(!array_key_exists('date_exclude',$meta))
			$meta['date_exclude']=array();
	}
    
    /**************************************************************************/
    
    function getDictionary($attr=array())
    {
		global $post;
		
		$dictionary=array();
		
		$default=array
		(
			'vehicle_id'           												=>	0,
            'category_id'                                                       =>  0
		);
		
		$attribute=shortcode_atts($default,$attr);
        
        $Validation=new CHBSValidation();
		
		CHBSHelper::preservePost($post,$bPost);
		
		$argument=array
		(
			'post_type'															=>	self::getCPTName(),
			'post_status'														=>	'publish',
			'posts_per_page'													=>	-1,
			'orderby'															=>	array('menu_order'=>'menu','title'=>'asc')
		);
		
		if($attribute['vehicle_id'])
			$argument['p']=$attribute['vehicle_id'];
 
        if(!is_array($attribute['category_id']))
            $attribute['category_id']=array($attribute['category_id']);

        if(array_sum($attribute['category_id']))
        {
            $argument['tax_query']=array
            (
                array
                (
                    'taxonomy'                                                  =>  self::getCPTCategoryName(),
                    'field'                                                     =>  'term_id',
                    'terms'                                                     =>  $attribute['category_id'],
                    'operator'                                                  =>  'IN'
                )
            );
        }
         
        $query=new WP_Query($argument);
		if($query===false) return($dictionary);
 
		while($query->have_posts())
		{
			$query->the_post();
			$dictionary[$post->ID]['post']=$post;
			$dictionary[$post->ID]['meta']=CHBSPostMeta::getPostMeta($post);
            
            if($Validation->isEmpty($post->post_title))
                $post->post_title=trim($dictionary[$post->ID]['meta']['vehicle_make'].' '.$dictionary[$post->ID]['meta']['vehicle_model']);
		}
        
		CHBSHelper::preservePost($post,$bPost,0);	
        
		return($dictionary);        
    }
    
    /**************************************************************************/
    
    function getCategory()
    {
        $category=array();
        
        $result=get_terms(self::getCPTCategoryName());
        if(is_wp_error($result)) return($category);
        
        foreach($result as $value)
            $category[$value->{'term_id'}]=array('name'=>$value->{'name'});
        
        return($category);
    }
    
    /**************************************************************************/
    
    function calculatePrice($data,$calculateHiddenFee=true)
    {
        $Length=new CHBSLength();
        $TaxRate=new CHBSTaxRate();
        $Currency=new CHBSCurrency();
        $PriceRule=new CHBSPriceRule();
        
        $taxRate=$TaxRate->getDictionary();
        
        /***/
        
        $passengerSum=0;
        if(CHBSBookingHelper::isPassengerEnable($data['booking_form']['meta'],$data['service_type_id'],'adult'))
            $passengerSum+=$data['passenger_adult'];
        if(CHBSBookingHelper::isPassengerEnable($data['booking_form']['meta'],$data['service_type_id'],'children'))
            $passengerSum+=$data['passenger_children'];           
        
        /***/
                
        $argument=array
        (
            'booking_form_id'                                                   =>  (int)$data['booking_form_id'],
            'service_type_id'                                                   =>  (int)$data['service_type_id'],
            'route_id'                                                          =>  (int)$data['route_id'],
            'vehicle_id'                                                        =>  (int)$data['vehicle_id'],
            'pickup_date'                                                       =>  $data['pickup_date'],
            'pickup_time'                                                       =>  $data['pickup_time'],
            'distance'                                                          =>  $data['distance'],
            'duration'                                                          =>  $data['duration'],
            'passenger_sum'                                                     =>  $passengerSum
        );
    
        $rule=$PriceRule->getPriceFromRule($argument,$data['booking_form']['dictionary']['price_rule']);
        
        /***/
        
        $priceBase=array
        (
            'price_type'                                                        =>  1,
            'price_fixed_value'                                                 =>  0.00,
            'price_fixed_tax_rate_id'                                           =>  0,
            'price_fixed_return_value'                                          =>  0.00,
            'price_fixed_return_tax_rate_id'                                    =>  0,
            'price_initial_value'                                               =>  0.00,
            'price_initial_tax_rate_id'                                         =>  0,
            'price_delivery_value'                                              =>  0.00,
            'price_delivery_tax_rate_id'                                        =>  0,
            'price_delivery_return_value'                                       =>  0.00,
            'price_delivery_return_tax_rate_id'                                 =>  0,
            'price_distance_value'                                              =>  0.00,
            'price_distance_tax_rate_id'                                        =>  0,
            'price_distance_return_value'                                       =>  0.00,
            'price_distance_return_tax_rate_id'                                 =>  0,
            'price_hour_value'                                                  =>  0.00,
            'price_hour_tax_rate_id'                                            =>  0,
            'price_extra_time_value'                                            =>  0.00,
            'price_extra_time_tax_rate_id'                                      =>  0,
            'price_passenger_adult_value'                                       =>  0.00,
            'price_passenger_adult_tax_rate_id'                                 =>  0,
            'price_passenger_children_value'                                    =>  0.00,
            'price_passenger_children_tax_rate_id'                              =>  0            
        );

        if($rule===false) 
        {
            $priceSet=false;
            
            if((int)$data['service_type_id']==3)
            {
                $dictionary=$data['booking_form']['dictionary']['route'];
        
                if($dictionary!==false)
                {
                    if((array_key_exists($data['route_id'],$dictionary)) && (array_key_exists($data['vehicle_id'],$dictionary[$data['route_id']]['meta']['vehicle'])))
                    {
                        $routeVehicle=$dictionary[$data['route_id']]['meta']['vehicle'][$data['vehicle_id']];

                        if((int)$routeVehicle['price_source']===2)
                        {
                            $priceSet=true;
                            
                            foreach($priceBase as $index=>$value)
                            {
                                if(isset($routeVehicle[$index]))
                                    $priceBase[$index]=$routeVehicle[$index];
                            }
                        }
                    }
                }
            }
            
            if(!$priceSet)
            {
                $dictionary=$data['booking_form']['dictionary']['vehicle'];
                
                foreach($priceBase as $index=>$value)
                {
                    if(isset($dictionary[$data['vehicle_id']]['meta'][$index]))
                        $priceBase[$index]=$dictionary[$data['vehicle_id']]['meta'][$index];
                }                
            }
        }
        else
        {
            foreach($priceBase as $index=>$value)
            {
                if(isset($rule[$index]))
                    $priceBase[$index]=$rule[$index];
            } 
        }

        /***/
        
        $currency=$Currency->getCurrency(CHBSOption::getOption('currency'));
        
        /***/
  
        $distance=$data['distance'];
        if(CHBSOption::getOption('length_unit')==2)
            $distance=$Length->convertUnit($distance);
        
        $duration=$data['duration']/60;

        /***/
        
        $Coupon=new CHBSCoupon();
        $coupon=$Coupon->checkCode();
        
        if($coupon!==false)
        {
            $discountPercentage=$coupon['meta']['discount_percentage'];
            foreach($priceBase as $index=>$value)
            {
                if(preg_match('/\_value$/',$index))
                {
                    $priceBase[$index]=round($priceBase[$index]*(1-$discountPercentage/100),2);
                }
            }
        }
        
        /***/
        
        if((int)$priceBase['price_type']===2)
        {
            $priceSumNetValue=$priceBase['price_fixed_value'];
            $priceSumGrossValue=number_format($priceSumNetValue*(1+$TaxRate->getTaxRateValue($priceBase['price_fixed_tax_rate_id'],$taxRate)/100),2,'.',''); 
            
            if(in_array($data['service_type_id'],array(1,3)))
            {
                if(in_array((int)$data['transfer_type_id'],array(2,3)))
                {
                    $priceSumNetValue+=$priceBase['price_fixed_return_value'];
                    $priceSumGrossValue+=number_format($priceBase['price_fixed_return_value']*(1+$TaxRate->getTaxRateValue($priceBase['price_fixed_return_tax_rate_id'],$taxRate)/100),2,'.','');                     
                }
            }
        }
        else
        {
            if((CHBSBookingHelper::isPassengerEnable($data['booking_form']['meta'],$data['service_type_id'],'adult')) || (CHBSBookingHelper::isPassengerEnable($data['booking_form']['meta'],$data['service_type_id'],'children'))) 
            {
                if(CHBSBookingHelper::isPassengerEnable($data['booking_form']['meta'],$data['service_type_id'],'adult'))
                {
                    $priceSumNetValue=$priceBase['price_passenger_adult_value']*$data['passenger_adult'];
                    $priceSumGrossValue=number_format($priceBase['price_passenger_adult_value']*$data['passenger_adult']*(1+$TaxRate->getTaxRateValue($priceBase['price_passenger_adult_tax_rate_id'],$taxRate)/100),2,'.','');                     
                }
                
                if(CHBSBookingHelper::isPassengerEnable($data['booking_form']['meta'],$data['service_type_id'],'children'))
                {
                    $priceSumNetValue+=$priceBase['price_passenger_children_value']*$data['passenger_children']; 
                    $priceSumGrossValue+=number_format($priceBase['price_passenger_children_value']*$data['passenger_children']*(1+$TaxRate->getTaxRateValue($priceBase['price_passenger_children_tax_rate_id'],$taxRate)/100),2,'.','');                     
                }
            }
            else
            {
                if(in_array($data['service_type_id'],array(1,3)))
                {
                    $priceSumNetValue=$priceBase['price_distance_value']*$distance;
                    $priceSumGrossValue=number_format($priceSumNetValue*(1+$TaxRate->getTaxRateValue($priceBase['price_distance_tax_rate_id'],$taxRate)/100),2,'.','');                   
                }
                elseif((int)$data['service_type_id']===2)
                {
                    $priceSumNetValue=$priceBase['price_hour_value']*$duration;
                    $priceSumGrossValue=number_format($priceSumNetValue*(1+$TaxRate->getTaxRateValue($priceBase['price_hour_tax_rate_id'],$taxRate)/100),2,'.','');                  
                }
            }
            
            if(in_array((int)$data['service_type_id'],array(1,3)))
            {
                if(in_array((int)$data['transfer_type_id'],array(2,3)))
                {
                    $priceSumNetValue+=$priceBase['price_distance_return_value']*$distance;
                    $priceSumGrossValue=number_format($priceSumNetValue*(1+$TaxRate->getTaxRateValue($priceBase['price_distance_return_tax_rate_id'],$taxRate)/100),2,'.','');                      
                }
            }
        }
        
        /***/
        
        if(in_array((int)$data['service_type_id'],array(1,3)))
        {
            if(in_array((int)$data['transfer_type_id'],array(2,3)))
            {
                $duration*=2;
                $distance*=2;
            }
        }
        
        /***/
 
        $price=array
        (
            'price'                                                             =>  array
            (
                'base'                                                          =>  $priceBase,
                'sum'                                                           =>  array
                (
                    'net'                                                       =>  array
                    (
                        'value'                                                 =>  $priceSumNetValue,
                    ),
                    'gross'                                                     =>  array
                    (
                        'value'                                                 =>  $priceSumGrossValue,
                        'format'                                                =>  CHBSPrice::format($priceSumGrossValue,CHBSOption::getOption('currency'))
                    )            
                )
            ),
            'currency'                                                          =>  $currency
        );
        
        if(((int)$data['booking_form']['meta']['booking_summary_hide_fee']===1) && ($calculateHiddenFee))
        {
            $Booking=new CHBSBooking();
            $priceBooking=$Booking->calculatePrice($data,$price,true);
            
            $price['price']['sum']['gross']['value']=number_format($priceBooking['vehicle']['sum']['gross']['value'],2,'.','');
            $price['price']['sum']['gross']['format']=CHBSPrice::format($price['price']['sum']['gross']['value'],CHBSOption::getOption('currency'));
        }
        
        /***/
        
        $t=preg_split('/\\'.$currency['separator'].'/',$price['price']['sum']['gross']['value']);
        $priceSumGrossFormatHtml=$t[0].'<sup>.'.(empty($t[1]) ? '00' : $t[1]).'</sup>';
        
        if(isset($currency['position']) && $currency['position']==='right')
        {
            $priceSumGrossFormatHtml.=$currency['symbol'];
        }
        else 
        {
            $priceSumGrossFormatHtml='<sup>'.$currency['symbol'].'</sup>'.$priceSumGrossFormatHtml;
        }  
        
        $price['price']['sum']['gross']['formatHtml']=$priceSumGrossFormatHtml;
        
        return($price);
    }
    
    /**************************************************************************/
    
    function getPrice($name,$vehicle,$serviceTypeId,$routeId)
    {
        $vehicleId=$vehicle['post']->ID;
        
        if(in_array($serviceTypeId,array(1,2)))
            return($vehicle['meta'][$name]);
        
        $Route=new CHBSRoute();
        $dictionary=$Route->getDictionary(array('route_id'=>$routeId));
        
        if(!array_key_exists($routeId,$dictionary))
            return($vehicle['meta'][$name]);
        
        $routeVehicle=$dictionary[$routeId]['meta']['vehicle'];

        if(isset($routeVehicle[$vehicleId][$name]))
        {
            if($name=='tax_rate_id')
            {
                if((int)$routeVehicle[$vehicleId][$name]!=-1)
                    return($routeVehicle[$vehicleId][$name]);
            }
            else
            {
                if($routeVehicle[$vehicleId][$name]>0)
                    return($routeVehicle[$vehicleId][$name]);
            }
        }
        
        return($vehicle['meta'][$name]);
    }
    
    /**************************************************************************/
    
    function getVehicleAttribute(&$vehicle)
    {
        $Validation=new CHBSValidation();
        $VehicleAttribute=new CHBSVehicleAttribute();
        
        $dictionary=$VehicleAttribute->getDictionary();

        foreach($vehicle as $vehicleIndex=>$vehicleValue)
        {
            foreach($vehicleValue['meta']['attribute'] as $vehicleAttributeIndex=>$vehicleAttributeValue)
            {
                if(!isset($dictionary[$vehicleAttributeIndex])) continue;
                
                switch($dictionary[$vehicleAttributeIndex]['meta']['attribute_type'])
                {
                    case 1:
                        
                        if($Validation->isNotEmpty($vehicleAttributeValue))
                            $vehicle[$vehicleIndex]['attribute'][$vehicleAttributeIndex]=array('name'=>get_the_title($vehicleAttributeIndex),'value'=>$vehicleAttributeValue);
                        
                    break;
                
                    case 2:
                    case 3:
                        
                        $value=null;
                        
                        foreach($vehicleAttributeValue as $vehicleAttributeValueValue)
                        {
                            foreach($dictionary[$vehicleAttributeIndex]['meta']['attribute_value'] as $dictionaryAttributeValue)
                            {
                                if($dictionaryAttributeValue['id']===$vehicleAttributeValueValue)
                                {
                                    if(!$Validation->isEmpty($value)) $value.=', ';
                                    $value.=$dictionaryAttributeValue['value'];
                                }
                                
                            }
                        }
                        
                        if($Validation->isNotEmpty($value))
                            $vehicle[$vehicleIndex]['attribute'][$vehicleAttributeIndex]=array('name'=>get_the_title($vehicleAttributeIndex),'value'=>$value);
          
                    break;
                }
            }
        }
    }

    /**************************************************************************/
    
    function manageEditColumns($column)
    {
        $column=array
        (
            'cb'                                                                =>  $column['cb'],
            'thumbnail'                                                         =>  __('Thumbnail','chauffeur-booking-system'),
            'title'                                                             =>  __('Title','chauffeur-booking-system'),
            'vehicle_make_model'                                                =>  __('Vehicle make and model','chauffeur-booking-system'),
            'passenger_bag_count'                                               =>  __('Number of passengers and suitcases','chauffeur-booking-system'),
            'price'                                                             =>  __('Net prices','chauffeur-booking-system'),
            'date'                                                              =>  $column['date']
        );
   
		return($column);          
    }
    
    /**************************************************************************/
    
    function managePostsCustomColumn($column)
    {
		global $post;
		
        $PriceType=new CHBSPriceType();
        
		$meta=CHBSPostMeta::getPostMeta($post);
        
		switch($column) 
		{
			case 'thumbnail':
				
                echo get_the_post_thumbnail($post,PLUGIN_CHBS_CONTEXT.'_vehicle');
                
			break;
        
			case 'vehicle_make_model':
				
                echo esc_html(trim($meta['vehicle_make'].' '.$meta['vehicle_model']));
                
			break;
        
			case 'passenger_bag_count':
				
                echo sprintf(__('Passsengers: <b>%s</b>','chauffeur-booking-system'),$meta['passenger_count']);
                echo '<br/>';
                echo sprintf(__('Suitcases: <b>%s</b>','chauffeur-booking-system'),$meta['bag_count']);
                
			break;
        
			case 'price':
				
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
    
    function checkAvailability($dictionary,$pickupDate,$pickupTime,$returnDate,$returnTime,$duration,$preventDoubleVehicleBooking,$bookingVehicleInterval)
    {
        /***/
        
        $Validation=new CHBSValidation();
        
        /***/
        
        $dateSet=array();

        if(($Validation->isDate($returnDate)) && ($Validation->isTime($returnTime)))
        {
            $duration/=2;
            $dateSet[0][1][0]=CHBSDate::strtotime($returnDate.' '.$returnTime);
            $dateSet[0][1][1]=CHBSDate::strtotime($returnDate.' '.$returnTime.' + '.$duration.' minute');
        }

        if(($Validation->isDate($pickupDate)) && ($Validation->isTime($pickupTime)))
        {
            $dateSet[0][0][0]=CHBSDate::strtotime($pickupDate.' '.$pickupTime);
            $dateSet[0][0][1]=CHBSDate::strtotime($pickupDate.' '.$pickupTime.' + '.$duration.' minute');
        }

        /***/
        
        foreach($dictionary as $dictionaryIndex=>$dictionaryValue)
        {
            $meta=$dictionaryValue['meta'];
            if(!array_key_exists('date_exclude',$meta)) continue;
           
            foreach($meta['date_exclude'] as $dateExcludeValue)
            {
                $dateStart=CHBSDate::strtotime($dateExcludeValue['startDate'].' '.$dateExcludeValue['startTime']);
                $dateStop=CHBSDate::strtotime($dateExcludeValue['stopDate'].' '.$dateExcludeValue['stopTime']);
  
                foreach($dateSet[0] as $date)
                {
                    $b=array_fill(0,4,false);

                    $b[0]=CHBSHelper::valueInRange($date[0],$dateStart,$dateStop);
                    $b[1]=CHBSHelper::valueInRange($date[1],$dateStart,$dateStop);
                    $b[2]=CHBSHelper::valueInRange($dateStart,$date[0],$date[1]);
                    $b[3]=CHBSHelper::valueInRange($dateStop,$date[0],$date[1]);

                    if(in_array(true,$b,true))
                    {
                        unset($dictionary[$dictionaryIndex]);
                        break;                    
                    }
                }
            }
        }

        /***/
        
        if(($preventDoubleVehicleBooking==1) && (count($dictionary)))
        {
            $Booking=new CHBSBooking();
            
            $argument=array
            (
                'post_type'														=>	$Booking::getCPTName(),
                'post_status'													=>	'publish',
                'posts_per_page'                                                =>	-1,
                'meta_query'                                                    =>  array
                (
                    array
                    (
                        'key'                                                   =>  PLUGIN_CHBS_CONTEXT.'_vehicle_id',
                        'value'                                                 =>  array_keys($dictionary),
                        'compare'                                               =>  'IN'
                    ),
                    array
                    (
                        'key'                                                   =>  PLUGIN_CHBS_CONTEXT.'_booking_status_id',
                        'value'                                                 =>  array(1,2),
                        'compare'                                               =>  'IN'
                    )
                )
            );
            
            global $post;
            
            CHBSHelper::preservePost($post,$bPost);
        
            $query=new WP_Query($argument);
            if($query===false) 
            {
                CHBSHelper::preservePost($post,$bPost,0);
                return($dictionary); 
            }
            
            while($query->have_posts())
            {
                $query->the_post();
                
                $meta=CHBSPostMeta::getPostMeta($post);
 
                /***/
                
                $bookingReturn=false;
                $bookingDuration=(int)$meta['duration'];
                $bookingExtraTime=(int)$meta['extra_time_value'];
                
                if(in_array($meta['service_type_id'],array(1,3)))
                {
                    if(in_array($meta['transfer_type_id'],array(3)))
                    {
                        $bookingReturn=true;
                        $bookingExtraTime=ceil($bookingExtraTime/2);
                    }
                }       
                
                $dateSet[1][0][0]=CHBSDate::strtotime($meta['pickup_date'].' '.$meta['pickup_time']);
                $dateSet[1][0][1]=CHBSDate::strtotime($meta['pickup_date'].' '.$meta['pickup_time'].' + '.($bookingDuration+$bookingExtraTime+(int)$bookingVehicleInterval).' minute');
                
                if($bookingReturn)
                {
                    $dateSet[1][1][0]=CHBSDate::strtotime($meta['return_date'].' '.$meta['return_time']);
                    $dateSet[1][1][1]=CHBSDate::strtotime($meta['return_date'].' '.$meta['return_time'].' + '.($bookingDuration+$bookingExtraTime+(int)$bookingVehicleInterval).' minute');                 
                }
                
                /***/
                
                foreach($dateSet[0] as $dateCurrent)
                {
                    foreach($dateSet[1] as $dateBooking)
                    {
                        $b=array_fill(0,4,false);

                        $b[0]=CHBSHelper::valueInRange($dateCurrent[0],$dateBooking[0],$dateBooking[1]);
                        $b[1]=CHBSHelper::valueInRange($dateCurrent[1],$dateBooking[0],$dateBooking[1]);
                        $b[2]=CHBSHelper::valueInRange($dateBooking[0],$dateCurrent[0],$dateCurrent[1]);
                        $b[3]=CHBSHelper::valueInRange($dateBooking[1],$dateCurrent[0],$dateCurrent[1]);

                        if(in_array(true,$b,true))
                        {
                            unset($dictionary[$meta['vehicle_id']]);
                            continue;                    
                        }                        
                    }
                }
            }            
            
            CHBSHelper::preservePost($post,$bPost,0);
        }
        
        /***/
        
        return($dictionary);
    }
    
    /**************************************************************************/
}

/******************************************************************************/
/******************************************************************************/