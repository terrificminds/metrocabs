<?php

/******************************************************************************/
/******************************************************************************/

class CHBSPlugin
{
    /**************************************************************************/
    
    private $optionDefault;
    private $libraryDefault;

    /**************************************************************************/	
	
	function __construct()
	{
        /***/
        
		$this->libraryDefault=array
		(
			'script'															=>	array
			(
				'use'															=>	1,
				'inc'															=>	true,
				'path'															=>	PLUGIN_CHBS_SCRIPT_URL,
				'file'															=>	'',
				'in_footer'														=>	true,
				'dependencies'													=>	array('jquery'),
			),
			'style'																=>	array
			(
				'use'															=>	1,
				'inc'															=>	true,
				'path'															=>	PLUGIN_CHBS_STYLE_URL,
				'file'															=>	'',
				'dependencies'													=>	array()
			)
		);
        
        /***/
        
        $this->optionDefault=array
        (
            'logo'                                                              =>  '',
            'google_map_api_key'                                                =>  '',
            'currency'                                                          =>  'USD',
            'length_unit'                                                       =>  '1',
            'date_format'                                                       =>  'd-m-Y',
            'time_format'                                                       =>  'G:i',
            'sender_default_email_account_id'                                   =>  '-1',
            'coupon_generate_count'                                             =>  '1',
            'coupon_generate_usage_limit'                                       =>  '1',
            'coupon_generate_discount_percentage'                               =>  '0',
            'coupon_generate_active_date_start'                                 =>  '',
            'coupon_generate_active_date_stop'                                  =>  ''
        );
        
        /***/
	}
	
	/**************************************************************************/
	
	private function prepareLibrary()
	{
		$this->library=array
		(
			'script'															=>	array
			(
				'jquery-ui-core'												=>	array
				(
					'path'														=>	''
				),
				'jquery-ui-tabs'												=>	array
				(
                    'use'                                                       =>  3,
					'path'														=>	''
				),
				'jquery-ui-button'												=>	array
				(
					'path'														=>	''
				),
 				'jquery-ui-slider'  											=>	array
				(
					'path'														=>	''
				),    
				'jquery-ui-selectmenu'											=>	array
				(
                    'use'                                                       =>  2,
					'path'														=>	''
				), 
				'jquery-ui-sortable'                                            =>	array
				(
					'path'														=>	''
				),
				'jquery-ui-datepicker'                                          =>	array
				(
                    'use'                                                       =>  3,
					'path'														=>	''
				),
				'jquery-colorpicker'											=>	array
				(
					'file'														=>	'jquery.colorpicker.js'
				),
				'jquery-actual'                                                 =>	array
				(
                    'use'                                                       =>  2,
					'file'														=>	'jquery.actual.min.js'
				),
				'jquery-timepicker'                                             =>	array
				(
                    'use'                                                       =>  3,
					'file'														=>	'jquery.timepicker.min.js'
				),
				'jquery-dropkick'												=>	array
				(
					'file'														=>	'jquery.dropkick.min.js'
				),
				'jquery-qtip'													=>	array
				(
                    'use'                                                       =>  3,
					'file'														=>	'jquery.qtip.min.js'
				),
				'jquery-blockUI'												=>	array
				(
					'file'														=>	'jquery.blockUI.js'
				),
				'jquery-sticky-kit'                                             =>	array
				(
                    'use'                                                       =>  3,
					'file'														=>	'jquery.sticky-kit.min.js'
				),
				'jquery-table'                                                  =>	array
				(
					'file'														=>	'jquery.table.js'
				),	
				'jquery-infieldlabel'											=>	array
				(
					'file'														=>	'jquery.infieldlabel.min.js'
				),
 				'jquery-scrollTo'                                               =>	array
				(
                    'use'                                                       =>  3,
					'file'														=>	'jquery.scrollTo.min.js'
				),  
 				'clipboard'                                                     =>	array
				(
					'file'														=>	'clipboard.min.js'
				),       
				'jquery-themeOption'											=>	array
				(
					'file'														=>	'jquery.themeOption.js'
				),
				'jquery-themeOptionElement'										=>	array
				(
					'file'														=>	'jquery.themeOptionElement.js'
				),
				'chbs-helper'                                                    =>	array
				(
                    'use'                                                       =>  3,
					'file'														=>	'helper.js'
				),
				'chbs-admin'                                                     =>	array
				(
					'file'														=>	'admin.js'
				),
				'chbs-chauffeur-route-admin'                                    =>	array
				(
					'file'														=>	'jquery.chauffeurRouteAdmin.js'
				),
				'chbs-booking-form-admin'                                       =>	array
				(
					'file'														=>	'jquery.chauffeurBookingFormAdmin.js'
				),	    
				'chbs-booking-form'                                              =>	array
				(
                    'use'                                                       =>  2,
					'file'														=>	'jquery.chauffeurBookingForm.js'
				),	                
				'google-map'        											=>	array
				(
					'use'														=>	3,
					'path'														=>	'',
					'file'														=>	add_query_arg(array('key'=>urlencode(CHBSOption::getOption('google_map_api_key')),'libraries'=>'places,drawing','language'=>(defined('ICL_LANGUAGE_CODE') ? ICL_LANGUAGE_CODE : '')),'//maps.google.com/maps/api/js'),
				),	
			),
			'style'																=>	array
			(
				'google-font-open-sans'											=>	array
				(
					'path'														=>	'', 
					'file'														=>	add_query_arg(array('family'=>urlencode('Open Sans:300,300i,400,400i,600,600i,700,700i,800,800i'),'subset'=>'cyrillic,cyrillic-ext,greek,greek-ext,latin-ext,vietnamese'),'//fonts.googleapis.com/css')
				),
				'google-font-lato'                                              =>	array
				(
                    'use'                                                       =>  2,
					'path'														=>	'', 
					'file'														=>	add_query_arg(array('family'=>urlencode('Lato:300,400,700'),'subset'=>'latin-ext'),'//fonts.googleapis.com/css')
				),
				'jquery-ui'														=>	array
				(
                    'use'                                                       =>  3,
					'file'														=>	'jquery.ui.min.css'
				),
				'jquery-qtip'   												=>	array
				(
                    'use'                                                       =>  3,
					'file'														=>	'jquery.qtip.min.css'
				),
				'jquery-dropkick'   											=>	array
				(
					'file'														=>	'jquery.dropkick.css'
				),
				'jquery-dropkick-rtl'											=>	array
				(				
					'inc'														=>	false,
					'file'														=>	'jquery.dropkick.rtl.css'
				),
				'jquery-colorpicker'   											=>	array
				(
					'file'														=>	'jquery.colorpicker.css'
				),
				'jquery-timepicker'   											=>	array
				(
                    'use'                                                       =>  3,
					'file'														=>	'jquery.timepicker.min.css'
				),
				'jquery-themeOption'											=>	array
				(
					'file'														=>	'jquery.themeOption.css'
				),
				'jquery-themeOption-rtl'										=>	array
				(
					'inc'														=>	false,
					'file'														=>	'jquery.themeOption.rtl.css'
				),
				'jquery-themeOption-overwrite'                                  =>	array
				(
					'file'														=>	'jquery.themeOption.overwrite.css'
				),
				'chbs-public'        											=>	array
				(
                    'use'                                                       =>  2,
					'file'														=>	'public.css'
				),
				'chbs-public-rtl'   											=>	array
				(
                    'use'                                                       =>  2,
					'inc'														=>	false,
					'file'														=>	'public.rtl.css'
				)
			)
		);		
	}	
	
	/**************************************************************************/
	
	private function addLibrary($type,$use)
	{
		if(CHBSFile::fileExist(CHBSFile::getMultisiteBlogCSS()))
		{
            $this->library['style']['chbs-public-booking-form-']=array
			(
                'use'                                                           =>	2,
				'path'                                                          =>	'',
				'file'                                                          =>	CHBSFile::getMultisiteBlogCSS('url')
			);
		}
        
		foreach($this->library[$type] as $index=>$value)
			$this->library[$type][$index]=array_merge($this->libraryDefault[$type],$value);
        
		foreach($this->library[$type] as $index=>$data)
		{
			if(!$data['inc']) continue;
			
			if($data['use']!=3)
			{
				if($data['use']!=$use) continue;
			}			
			
			if($type=='script')
			{
				wp_enqueue_script($index,$data['path'].$data['file'],$data['dependencies'],false,$data['in_footer']);
			}
			else 
			{
				wp_enqueue_style($index,$data['path'].$data['file'],$data['dependencies'],false);
			}
		}
	}
	
	/**************************************************************************/
	
	public function pluginActivation()
	{    
        CHBSOption::createOption();
        
        $optionSave=array();
        $optionCurrent=CHBSOption::getOptionObject();
             
		foreach($this->optionDefault as $index=>$value)
		{
			if(!array_key_exists($index,$optionCurrent))
				$optionSave[$index]=$value;
		}
		
		$optionSave=array_merge((array)$optionSave,$optionCurrent);
		foreach($optionSave as $index=>$value)
		{
			if(!array_key_exists($index,$this->optionDefault))
				unset($optionSave[$index]);
		}
        
        CHBSOption::resetOption();
        CHBSOption::updateOption($optionSave);
        
        $BookingFormStyle=new CHBSBookingFormStyle();
        $BookingFormStyle->createCSSFile();
                   
        /***/
        
		$argument=array
		(
			'post_type'															=>	CHBSVehicle::getCPTName(),
			'post_status'														=>	'any',
			'posts_per_page'													=>	-1
		);
        
        $query=new WP_Query($argument);
		if($query!==false)
        {
            while($query->have_posts())
            {
                $query->the_post();

                $type=(int)get_post_meta(get_the_ID(),PLUGIN_CHBS_CONTEXT.'_price_type');
                
                if(in_array($type,array(1,2))) continue;
                
                $meta=CHBSPostMeta::getPostMeta(get_the_ID());
                
                $data=array
                (
                    'price_type'                                                =>  1,
                    'price_fixed_value'                                         =>  0.00,
                    'price_fixed_tax_rate_id'                                   =>  0,
                    'price_fixed_return_value'                                  =>  0.00,
                    'price_fixed_return_tax_rate_id'                            =>  0,
                    'price_initial_value'                                       =>  0.00,
                    'price_initial_tax_rate_id'                                 =>  0,
                    'price_delivery_value'                                      =>  $meta['price_distance'],
                    'price_delivery_tax_rate_id'                                =>  $meta['tax_rate_id'],
                    'price_distance_value'                                      =>  $meta['price_distance'],
                    'price_distance_tax_rate_id'                                =>  $meta['tax_rate_id'],
                    'price_distance_return_value'                               =>  $meta['price_distance'],
                    'price_distance_return_tax_rate_id'                         =>  $meta['tax_rate_id'],
                    'price_hour_value'                                          =>  $meta['price_hour'],
                    'price_hour_tax_rate_id'                                    =>  $meta['tax_rate_id'],
                    'price_extra_time_value'                                    =>  $meta['price_hour'],
                    'price_extra_time_tax_rate_id'                              =>  $meta['tax_rate_id'],
                    'price_passenger_adult_value'                               =>  0.00,
                    'price_passenger_adult_tax_rate_id'                         =>  0,                    
                    'price_passenger_children_value'                            =>  0.00,
                    'price_passenger_children_tax_rate_id'                      =>  0 
                );
                
                foreach($data as $index=>$value)
                    CHBSPostMeta::updatePostMeta(get_the_ID(),$index,$value);
                
                CHBSPostMeta::removePostMeta(get_the_ID(),'price_hour');
                CHBSPostMeta::removePostMeta(get_the_ID(),'price_distance');
                CHBSPostMeta::removePostMeta(get_the_ID(),'tax_rate_id');
            }
        } 
        
        /***/
        
		$argument=array
		(
			'post_type'															=>	CHBSBooking::getCPTName(),
			'post_status'														=>	'any',
			'posts_per_page'													=>	-1
		);
        
        $query=new WP_Query($argument);
		if($query!==false)
        {
            while($query->have_posts())
            {
                $query->the_post();

                $type=(int)get_post_meta(get_the_ID(),PLUGIN_CHBS_CONTEXT.'_price_type');
                
                $meta=CHBSPostMeta::getPostMeta(get_the_ID());
                
                if(!array_key_exists('return_date',$meta)) $meta['return_date']='00-00-0000';
                if(!array_key_exists('return_time',$meta)) $meta['return_time']='00:00';
                
                CHBSPostMeta::updatePostMeta(get_the_ID(),'pickup_datetime',CHBSDate::formatDateTimeToMySQL($meta['pickup_date'],$meta['pickup_time']));
                CHBSPostMeta::updatePostMeta(get_the_ID(),'return_datetime',CHBSDate::formatDateTimeToMySQL($meta['return_date'],$meta['return_time']));

                if(in_array($type,array(1,2))) continue;
                
                $data=array
                (
                    'price_type'                                                =>  1,
                    'price_fixed_value'                                         =>  0.00,
                    'price_fixed_tax_rate_value'                                =>  0,
                    'price_fixed_return_value'                                  =>  0.00,
                    'price_fixed_return_tax_rate_value'                         =>  0,
                    'price_initial_value'                                       =>  0.00,
                    'price_initial_tax_rate_value'                              =>  0,
                    'price_delivery_value'                                      =>  $meta['vehicle_price_distance'],
                    'price_delivery_tax_rate_value'                             =>  $meta['vehicle_tax_rate_value'],
                    'price_distance_value'                                      =>  $meta['vehicle_price_distance'],
                    'price_distance_tax_rate_value'                             =>  $meta['vehicle_tax_rate_value'],
                    'price_distance_return_value'                               =>  $meta['vehicle_price_distance'],
                    'price_distance_return_tax_rate_value'                      =>  $meta['vehicle_tax_rate_value'],
                    'price_hour_value'                                          =>  $meta['vehicle_price_hour'],
                    'price_hour_tax_rate_value'                                 =>  $meta['vehicle_tax_rate_value'],
                    'price_extra_time_value'                                    =>  $meta['vehicle_price_hour'],
                    'price_extra_time_tax_rate_value'                           =>  $meta['vehicle_tax_rate_value'],
                    'price_passenger_adult_value'                               =>  0.00,
                    'price_passenger_adult_tax_rate_value'                      =>  0,                    
                    'price_passenger_children_value'                            =>  0.00,
                    'price_passenger_children_tax_rate_value'                   =>  0 
                );
  
                foreach($data as $index=>$value)
                    CHBSPostMeta::updatePostMeta(get_the_ID(),$index,$value);
                
                CHBSPostMeta::removePostMeta(get_the_ID(),'vehicle_price_hour');
                CHBSPostMeta::removePostMeta(get_the_ID(),'vehicle_price_distance');
                CHBSPostMeta::removePostMeta(get_the_ID(),'vehicle_tax_rate_value');
            }
        }
        
        /***/
    
		$argument=array
		(
			'post_type'															=>	CHBSRoute::getCPTName(),
			'post_status'														=>	'any',
			'posts_per_page'													=>	-1
		);
        
        $query=new WP_Query($argument);
		if($query!==false)
        {
            while($query->have_posts())
            {
                $query->the_post();
			
                $meta=CHBSPostMeta::getPostMeta(get_the_ID());
                
                $data=array();
                $vehicle=$meta['vehicle'];
                
                foreach($vehicle as $vehicleIndex=>$vehicleValue)
                {
                    if(array_key_exists('price_type',$vehicleValue)) continue;
     
                    if(!array_key_exists('price_hour',$vehicleValue))
                        $vehicleValue['price_hour']=0.00;
                    if(!array_key_exists('price_distance',$vehicleValue))
                        $vehicleValue['price_distance']=0.00;                    
                    if(!array_key_exists('tax_rate_id',$vehicleValue))
                        $vehicleValue['tax_rate_id']=0;                      
                    
                    if(($vehicleValue['price_distance']==0.00) && ($vehicleValue['price_hour']==0.00))
                    {
                        $data[$vehicleIndex]=array
                        (
                            'price_type'                                        =>  1,
                            'price_source'                                      =>  1,
                            'price_fixed_value'                                 =>  0.00,
                            'price_fixed_tax_rate_id'                           =>  0,
                            'price_fixed_return_value'                          =>  0.00,
                            'price_fixed_return_tax_rate_id'                    =>  0,
                            'price_initial_value'                               =>  0.00,
                            'price_initial_tax_rate_id'                         =>  0,
                            'price_delivery_value'                              =>  0.00,
                            'price_delivery_tax_rate_id'                        =>  0,
                            'price_distance_value'                              =>  0.00,
                            'price_distance_tax_rate_id'                        =>  0,
                            'price_distance_return_value'                       =>  0.00,
                            'price_distance_return_tax_rate_id'                 =>  0,
                            'price_hour_value'                                  =>  0.00,
                            'price_hour_tax_rate_id'                            =>  0,
                            'price_extra_time_value'                            =>  0.00,
                            'price_extra_time_tax_rate_id'                      =>  0,
                            'price_passenger_adult_value'                       =>  0.00,
                            'price_passenger_adult_tax_rate_id'                 =>  0,                    
                            'price_passenger_children_value'                    =>  0.00,
                            'price_passenger_children_tax_rate_vid'             =>  0                             
                        );
                    }
                    else
                    {
                        $data[$vehicleIndex]=array
                        (
                            'price_type'                                        =>  1,
                            'price_source'                                      =>  2,
                            'price_fixed_value'                                 =>  0.00,
                            'price_fixed_tax_rate_id'                           =>  0,
                            'price_fixed_return_value'                          =>  0.00,
                            'price_fixed_return_tax_rate_id'                    =>  0,
                            'price_initial_value'                               =>  0.00,
                            'price_initial_tax_rate_id'                         =>  0,
                            'price_delivery_value'                              =>  $vehicleValue['price_distance'],
                            'price_delivery_tax_rate_id'                        =>  $vehicleValue['tax_rate_id'],
                            'price_distance_value'                              =>  $vehicleValue['price_distance'],
                            'price_distance_tax_rate_id'                        =>  $vehicleValue['tax_rate_id'],
                            'price_distance_return_value'                       =>  $vehicleValue['price_distance'],
                            'price_distance_return_tax_rate_id'                 =>  $vehicleValue['tax_rate_id'],
                            'price_hour_value'                                  =>  $vehicleValue['price_hour'],
                            'price_hour_tax_rate_id'                            =>  $vehicleValue['tax_rate_id'],
                            'price_extra_time_value'                            =>  $vehicleValue['price_hour'],
                            'price_extra_time_tax_rate_id'                      =>  $vehicleValue['tax_rate_id'],
                            'price_passenger_adult_value'                       =>  0.00,
                            'price_passenger_adult_tax_rate_id'                 =>  0,                    
                            'price_passenger_children_value'                    =>  0.00,
                            'price_passenger_children_tax_rate_vid'             =>  0
                        );                        
                    }
                }
                
                if(count($data))
                    CHBSPostMeta::updatePostMeta(get_the_ID(),'vehicle',$data);
            }
        }   
	}
	
	/**************************************************************************/
	
	public function pluginDeactivation()
	{

	}
    
	/**************************************************************************/
	
	public function init()
	{
        $Booking=new CHBSBooking();
        $BookingForm=new CHBSBookingForm();
        $BookingExtra=new CHBSBookingExtra();

        $Route=new CHBSRoute();
        $Vehicle=new CHBSVehicle();
        $VehicleAttribute=new CHBSVehicleAttribute();
        
        $PriceRule=new CHBSPriceRule();
        
        $Coupon=new CHBSCoupon();
        
        $TaxRate=new CHBSTaxRate();
        $EmailAccount=new CHBSEmailAccount();
        
        $Booking->init();
        $BookingForm->init();
        $BookingExtra->init();
        
        $Route->init();
        $Vehicle->init();
        $VehicleAttribute->init();
        
        $PriceRule->init();
        
        $Coupon->init();
        
        $TaxRate->init();
        $EmailAccount->init();
        
        add_filter('custom_menu_order',array($this,'adminCustomMenuOrder'));
        
        add_action('admin_init',array($this,'adminInit'));
        add_action('admin_menu',array($this,'adminMenu'));
        
        add_action('wp_ajax_'.PLUGIN_CHBS_CONTEXT.'_option_page_save',array($this,'adminOptionPanelSave'));
        
		add_action('wp_ajax_'.PLUGIN_CHBS_CONTEXT.'_go_to_step',array($BookingForm,'goToStep'));
		add_action('wp_ajax_nopriv_'.PLUGIN_CHBS_CONTEXT.'_go_to_step',array($BookingForm,'goToStep'));
        
		add_action('wp_ajax_'.PLUGIN_CHBS_CONTEXT.'_vehicle_filter',array($BookingForm,'vehicleFilter'));
		add_action('wp_ajax_nopriv_'.PLUGIN_CHBS_CONTEXT.'_vehicle_filter',array($BookingForm,'vehicleFilter'));        
        
        add_action('wp_ajax_'.PLUGIN_CHBS_CONTEXT.'_option_page_import_demo',array($this,'importDemo'));
		add_action('wp_ajax_nopriv_'.PLUGIN_CHBS_CONTEXT.'_option_page_import_demo',array($this,'importDemo'));
        
        add_action('wp_ajax_'.PLUGIN_CHBS_CONTEXT.'_create_summary_price_element',array($BookingForm,'createSummaryPriceElementAjax'));
		add_action('wp_ajax_nopriv_'.PLUGIN_CHBS_CONTEXT.'_create_summary_price_element',array($BookingForm,'createSummaryPriceElementAjax'));
        
        add_action('wp_ajax_'.PLUGIN_CHBS_CONTEXT.'_user_sign_in',array($BookingForm,'userSignIn'));
		add_action('wp_ajax_nopriv_'.PLUGIN_CHBS_CONTEXT.'_user_sign_in',array($BookingForm,'userSignIn'));        
        
        add_action('wp_ajax_'.PLUGIN_CHBS_CONTEXT.'_option_page_create_coupon_code',array($Coupon,'create'));
		add_action('wp_ajax_nopriv_'.PLUGIN_CHBS_CONTEXT.'_option_page_create_coupon_code',array($Coupon,'create'));
        
        add_action('wp_ajax_'.PLUGIN_CHBS_CONTEXT.'_coupon_code_check',array($BookingForm,'checkCouponCode'));
		add_action('wp_ajax_nopriv_'.PLUGIN_CHBS_CONTEXT.'_coupon_code_check',array($BookingForm,'checkCouponCode'));  
        
        add_action('admin_notices',array($this,'adminNotice'));
        
        add_theme_support('post-thumbnails');
        
        add_image_size(PLUGIN_CHBS_CONTEXT.'_vehicle',460,306); 
        
		if(!is_admin())
        {
            $PaymentStripe=new CHBSPaymentStripe();
            
			add_action('wp_enqueue_scripts',array($this,'publicInit'));
         
            add_action('wp_loaded',array($PaymentStripe,'redirect'));
        }
	}
    
	/**************************************************************************/
	
	public function publicInit()
	{
		$this->prepareLibrary();
		
		if(is_rtl())
		{
            $this->library['style']['chbs-public-rtl']['inc']=true;
		}
        
		$this->addLibrary('style',2);
		$this->addLibrary('script',2);	
	}
	
	/**************************************************************************/
	
	public function adminInit()
	{
		$this->prepareLibrary();
        
		if(is_rtl())
		{
			$this->library['style']['jquery-themeOption-rtl']['inc']=true;
			$this->library['style']['jquery-dropkick-rtl']['inc']=true;
		}
		
		$this->addLibrary('style',1);
		$this->addLibrary('script',1);
	}
    
    /**************************************************************************/
    
    public function adminMenu()
    {
        global $submenu;

        add_options_page(__('Chauffeur Booking System','chauffeur-booking-system'),__('Chauffeur<br/>Booking System','chauffeur-booking-system'),'edit_theme_options',PLUGIN_CHBS_CONTEXT,array($this,'adminCreateOptionPage'));
        add_submenu_page('edit.php?post_type=chbs_booking',__('Vehicle Types','chauffeur-booking-system'),__('Vehicle Types','chauffeur-booking-system'),'edit_themes', 'edit-tags.php?taxonomy='.CHBSVehicle::getCPTCategoryName());
    }
    
    /**************************************************************************/
    
    public function adminCreateOptionPage()
    {
		$data=array();
        
        $Length=new CHBSLength();
        $Currency=new CHBSCurrency();
        $EmailAccount=new CHBSEmailAccount();
        
        $data['option']=CHBSOption::getOptionObject();
        
        $data['dictionary']['currency']=$Currency->getCurrency();
        $data['dictionary']['length_unit']=$Length->getUnit();
        
        $data['dictionary']['email_account']=$EmailAccount->getDictionary();
     
        wp_enqueue_media();
        
		$Template=new CHBSTemplate($data,PLUGIN_CHBS_TEMPLATE_PATH.'admin/option.php');
		echo $Template->output();	
    }
    
    /**************************************************************************/
    
    public function adminOptionPanelSave()
    {        
        $option=CHBSHelper::getPostOption();

        $response=array('global'=>array('error'=>1));

        $Notice=new CHBSNotice();
        $Length=new CHBSLength();
        $Currency=new CHBSCurrency();
        $Validation=new CHBSValidation();
        
        $invalidValue=__('This field includes invalid value.','chauffeur-booking-system');
        
        /* General */
        if(!$Currency->isCurrency($option['currency']))
            $Notice->addError(CHBSHelper::getFormName('currency',false),$invalidValue);	
        if(!$Length->isUnit($option['length_unit']))
            $Notice->addError(CHBSHelper::getFormName('length_unit',false),$invalidValue);	
        if($Validation->isEmpty($option['date_format']))
            $Notice->addError(CHBSHelper::getFormName('date_format',false),$invalidValue);
        if($Validation->isEmpty($option['time_format']))
            $Notice->addError(CHBSHelper::getFormName('time_format',false),$invalidValue);        
        
		if($Notice->isError())
		{
			$response['local']=$Notice->getError();
		}
		else
		{
			$response['global']['error']=0;
			CHBSOption::updateOption($option);
		}

		$response['global']['notice']=$Notice->createHTML(PLUGIN_CHBS_TEMPLATE_PATH.'notice.php');

		echo json_encode($response);
		exit;
    }
    
    /**************************************************************************/
    
    function importDemo()
    {
		$Demo=new CHBSDemo();
		$Notice=new CHBSNotice();
		$Validation=new CHBSValidation();
		
		$response=array('global'=>array('error'=>1));
		
		$buffer=$Demo->import();
		
		if($buffer!==false)
		{
			$response['global']['error']=0;
			$subtitle=__('Seems, that demo data has been imported. To make sure if this process has been sucessfully completed,please check below content of buffer returned by external applications.','chauffeur-booking-system');
		}
		else
		{
			$response['global']['error']=1;
			$subtitle=__('Dummy data cannot be imported.','chauffeur-booking-system');
		}
			
		$response['global']['notice']=$Notice->createHTML(PLUGIN_CHBS_TEMPLATE_PATH.'admin/notice.php',true,$response['global']['error'],$subtitle);
		
		if($Validation->isNotEmpty($buffer))
		{
			$response['global']['notice'].=
			'
				<div class="to-buffer-output">
					'.$buffer.'
				</div>
			';
		}
		
		echo json_encode($response);
		exit;			        
    }
    
    /**************************************************************************/
    
    function adminCustomMenuOrder()
    {
        global $submenu;

        $key='edit.php?post_type=chbs_booking';
        
        if(array_key_exists($key,$submenu))
        {
            $menu=array();
            
            $menu[5]=$submenu[$key][5];
            $menu[11]=$submenu[$key][11];
            $menu[12]=$submenu[$key][12];
            $menu[13]=$submenu[$key][13];
            $menu[14]=$submenu[$key][14];
            $menu[15]=$submenu[$key][20];
            $menu[16]=$submenu[$key][15];
            
            $menu[17]=$submenu[$key][16];
            $menu[18]=$submenu[$key][17];
            $menu[19]=$submenu[$key][18];
            $menu[20]=$submenu[$key][19];
            
            $menu[15][2].='&post_type=chbs_booking';
            
            $submenu[$key]=$menu;
        }
    }
    
    /**************************************************************************/
    
    function afterSetupTheme()
    {
        $VisualComposer=new CHBSVisualComposer();
        $VisualComposer->init();
    }
    
    /**************************************************************************/
    
    function adminNotice()
    {
        $Validation=new CHBSValidation();
        
        if($Validation->isEmpty(CHBSOption::getOption('google_map_api_key')))
        {
            echo 
            '
                <div class="notice notice-error">
                    <p>
                        <b>'.esc_html('Chauffeur Booking System','chauffeur-booking-system').'</b> '.sprintf(__('Please enter your Google Maps API key in <a href="%s">Plugin Options</a>.','chauffeur-booking-system'),admin_url('options-general.php?page=chbs',false)).'
                    </p>
                </div>
            ';
        }
    }
    
    /**************************************************************************/
}

/******************************************************************************/
/******************************************************************************/