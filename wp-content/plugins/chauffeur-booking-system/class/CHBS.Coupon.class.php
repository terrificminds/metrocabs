<?php

/******************************************************************************/
/******************************************************************************/

class CHBSCoupon
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
        return(PLUGIN_CHBS_CONTEXT.'_coupon');
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
					'name'														=>	__('Coupons','chauffeur-booking-system'),
					'singular_name'												=>	__('Coupons','chauffeur-booking-system'),
					'add_new'													=>	__('Add New','chauffeur-booking-system'),
					'add_new_item'												=>	__('Add New Coupon','chauffeur-booking-system'),
					'edit_item'													=>	__('Edit Coupon','chauffeur-booking-system'),
					'new_item'													=>	__('New Coupon','chauffeur-booking-system'),
					'all_items'													=>	__('Coupons','chauffeur-booking-system'),
					'view_item'													=>	__('View Coupon','chauffeur-booking-system'),
					'search_items'												=>	__('Search Coupons','chauffeur-booking-system'),
					'not_found'													=>	__('No Coupons Found','chauffeur-booking-system'),
					'not_found_in_trash'										=>	__('No Coupons in Trash','chauffeur-booking-system'), 
					'parent_item_colon'											=>	'',
					'menu_name'													=>	__('Coupons','chauffeur-booking-system')
				),	
				'public'														=>	false,  
				'show_ui'														=>	true, 
				'show_in_menu'													=>	'edit.php?post_type='.CHBSBooking::getCPTName(),
				'capability_type'												=>	'post',
				'menu_position'													=>	2,
				'hierarchical'													=>	false,  
				'rewrite'														=>	false,  
				'supports'														=>	false
			)
		);
        
        add_action('save_post',array($this,'savePost'));
        add_action('add_meta_boxes_'.self::getCPTName(),array($this,'addMetaBox'));
        add_filter('postbox_classes_'.self::getCPTName().'_chbs_meta_box_coupon',array($this,'adminCreateMetaBoxClass'));
        
		add_filter('manage_edit-'.self::getCPTName().'_columns',array($this,'manageEditColumns')); 
		add_action('manage_'.self::getCPTName().'_posts_custom_column',array($this,'managePostsCustomColumn'));
		add_filter('manage_edit-'.self::getCPTName().'_sortable_columns',array($this,'manageEditSortableColumns'));
    }

    /**************************************************************************/
    
    function addMetaBox()
    {
        add_meta_box(PLUGIN_CHBS_CONTEXT.'_meta_box_coupon',__('Main','chauffeur-booking-system'),array($this,'addMetaBoxMain'),self::getCPTName(),'normal','low');		
    }
    
    /**************************************************************************/
    
    function addMetaBoxMain()
    {
        global $post;
        
        $Booking=new CHBSBooking();
        
		$data=array();
               
        $data['meta']=CHBSPostMeta::getPostMeta($post);
        
		$data['nonce']=CHBSHelper::createNonceField(PLUGIN_CHBS_CONTEXT.'_meta_box_coupon');
        
        if(!isset($data['meta']['code']))
        {
            $code=$this->generateCode();
            
            wp_update_post(array('ID'=>$post->ID,'post_title'=>$code));
            
            CHBSPostMeta::updatePostMeta($post->ID,'code',$code);
            CHBSPostMeta::updatePostMeta($post->ID,'usage_count',0);
            
            $data['meta']=CHBSPostMeta::getPostMeta($post);
        }
        
        $data['meta']['usage_count']=$Booking->getCouponCodeUsageCount($data['meta']['code']);
                
		$Template=new CHBSTemplate($data,PLUGIN_CHBS_TEMPLATE_PATH.'admin/meta_box_coupon.php');
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
        CHBSHelper::setDefault($meta,'usage_limit','');
        
        CHBSHelper::setDefault($meta,'discount_percentage',0);
        
        CHBSHelper::setDefault($meta,'active_date_start','');
        CHBSHelper::setDefault($meta,'active_date_stop','');
	}
    
    /**************************************************************************/
    
    function savePost($postId)
    {      
        if(!$_POST) return(false);
        
        if(CHBSHelper::checkSavePost($postId,PLUGIN_CHBS_CONTEXT.'_meta_box_coupon_noncename','savePost')===false) return(false);
        
        $Date=new CHBSDate();
        $Validation=new CHBSValidation();
        
        $option=CHBSHelper::getPostOption();
        
        $key=array
        (
            'usage_limit',
            'discount_percentage',
            'active_date_start',
            'active_date_stop'
        );
        
        if(!$Validation->isNumber($option['usage_limit'],1,9999))
            $option['usage_limit']='';
         
        if(!$Validation->isNumber($option['discount_percentage'],0,99,true))
            $option['discount_percentage']=0;

        $option['active_date_start']=$Date->formatDateToStandard($option['active_date_start']);
        $option['active_date_stop']=$Date->formatDateToStandard($option['active_date_stop']);
        
        if(!$Validation->isDate($option['active_date_start']))
            $option['active_date_start']='';
        else if(!$Validation->isDate($option['active_date_stop']))
            $option['active_date_stop']='';
        else
        {
            if($Date->compareDate($option['active_date_start'],$option['active_date_stop'])==1)
            {
                $option['active_date_start']='';
                $option['active_date_stop']='';
            }
        }        
        
        foreach($key as $index)
            CHBSPostMeta::updatePostMeta($postId,$index,$option[$index]);

    }
    
    /**************************************************************************/
    
    function manageEditColumns($column)
    {
        $column=array
        (
            'cb'                                                                =>  $column['cb'],
            'title'                                                             =>  __('Code','chauffeur-booking-system'),
            'usage_limit'                                                       =>  __('Usage limit','chauffeur-booking-system'),
            'discount_percentage'                                               =>  __('Percentage discount','chauffeur-booking-system'),
            'active_date_start'                                                 =>  __('Active from','chauffeur-booking-system'),    
            'active_date_stop'                                                  =>  __('Active to','chauffeur-booking-system'),
            'date'                                                              =>  $column['date']
        );
   
		return($column);          
    }
    
    /**************************************************************************/
    
    function managePostsCustomColumn($column)
    {
		global $post;
        
        $Date=new CHBSDate();
		
		$meta=CHBSPostMeta::getPostMeta($post);
        
		switch($column) 
		{
			case 'usage_limit':
				
                echo esc_html($meta['usage_limit']);
                
			break;
        
			case 'discount_percentage':
				
                echo esc_html($meta['discount_percentage']);
                
			break;

			case 'active_date_start':

                echo $Date->formatDateToDisplay($meta['active_date_start']);
                
			break;

			case 'active_date_stop':
                
                echo $Date->formatDateToDisplay($meta['active_date_stop']);
				
			break;            
        }
    }
    
    /**************************************************************************/
    
    function manageEditSortableColumns($column)
    {
		return($column);       
    }
    
    /**************************************************************************/
    
    function create()
    {
        $option=CHBSHelper::getPostOption();

        $response=array('global'=>array('error'=>1));

        $Date=new CHBSDate();
        $Coupon=new CHBSCoupon();
        $Notice=new CHBSNotice();
        $Validation=new CHBSValidation();
        
        $invalidValue=__('This field includes invalid value.','chauffeur-booking-system');
        
        if(!$Validation->isNumber($option['coupon_generate_count'],1,999))
            $Notice->addError(CHBSHelper::getFormName('coupon_generate_count',false),$invalidValue);	        
        if(!$Validation->isNumber($option['coupon_generate_usage_limit'],1,9999,true))
            $Notice->addError(CHBSHelper::getFormName('coupon_generate_usage_limit',false),$invalidValue);	        
        
        $option['coupon_generate_active_date_start']=$Date->formatDateToStandard($option['coupon_generate_active_date_start']);
        $option['coupon_generate_active_date_stop']=$Date->formatDateToStandard($option['coupon_generate_active_date_stop']);
        
        if(!$Validation->isDate($option['coupon_generate_active_date_start'],true))
            $Notice->addError(CHBSHelper::getFormName('coupon_generate_active_date_start',false),$invalidValue);      
        else if(!$Validation->isDate($option['coupon_generate_active_date_stop'],true))
            $Notice->addError(CHBSHelper::getFormName('coupon_generate_active_date_stop',false),$invalidValue);              
        else
        {
            if($Date->compareDate($option['coupon_generate_active_date_start'],$option['coupon_generate_active_date_stop'])==1)
            {
                $Notice->addError(CHBSHelper::getFormName('coupon_generate_active_date_start',false),__('Invalid dates range.','chauffeur-booking-system'));
                $Notice->addError(CHBSHelper::getFormName('coupon_generate_active_date_stop',false),__('Invalid dates range.','chauffeur-booking-system')); 
            }            
        }
        
		if($Notice->isError())
		{
			$response['local']=$Notice->getError();
		}
		else
		{
            $Coupon->generate($option);
			$response['global']['error']=0;
		}

		$response['global']['notice']=$Notice->createHTML(PLUGIN_CHBS_TEMPLATE_PATH.'notice.php');

		echo json_encode($response);
		exit;
    }
    
    /**************************************************************************/
    
    function generate($data)
    {
        $Validation=new CHBSValidation();
        
		for($i=0;$i<$data['coupon_generate_count'];$i++)
		{
			$couponCode=$this->generateCode();
            
			$couponId=wp_insert_post
            (
                array
                (
					'comment_status'                                            =>  'closed',
					'ping_status'                                               =>	'closed',
					'post_author'                                               =>	get_current_user_id(),
					'post_title'                                                =>	$couponCode,
					'post_status'                                               =>	'publish',
					'post_type'                                                 =>	self::getCPTName()
				)
			);
            
            if($couponId>0)
            {
                $discountPercentage=$data['coupon_generate_discount_percentage'];
                
                if(!$Validation->isNumber($discountPercentage,0,99,true))
                    $discountPercentage=0;
                
                CHBSPostMeta::updatePostMeta($couponId,'code',$couponCode);
                
                CHBSPostMeta::updatePostMeta($couponId,'usage_count',0);
                CHBSPostMeta::updatePostMeta($couponId,'usage_limit',$data['coupon_generate_usage_limit']);
                
                CHBSPostMeta::updatePostMeta($couponId,'discount_percentage',$discountPercentage);
                
                CHBSPostMeta::updatePostMeta($couponId,'active_date_start',$data['coupon_generate_active_date_start']);
                CHBSPostMeta::updatePostMeta($couponId,'active_date_stop',$data['coupon_generate_active_date_stop']);
            }
		}
    }
    
    /**************************************************************************/
    
	function generateCode($length=12)
	{
		$code=null;
        
		$char='0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
		$charLength=strlen($char);
        
		for($i=0;$i<$length;$i++)
			$code.=$char[rand(0,$charLength-1)];
		return($code);
	}
    
    /**************************************************************************/
    
    function checkCode()
    {
        global $post;
        
        $Date=new CHBSDate();
        $Booking=new CHBSBooking();
        $Validation=new CHBSValidation();
        
        $data=CHBSHelper::getPostOption();
        
        if(!array_key_exists('coupon_code',$data)) return(false);
        if($Validation->isEmpty($data['coupon_code'])) return(false);
        
        /***/
        
        $argument=array
		(
			'post_type'															=>	self::getCPTName(),
			'post_status'														=>	'publish',
			'posts_per_page'													=>	-1,
            'meta_key'                                                          =>  PLUGIN_CHBS_CONTEXT.'_code',
            'meta_value'                                                        =>  isset($data['coupon_code']) ? $data['coupon_code'] : '',
            'meta_compare'                                                      =>  '='
		);
		
        $query=new WP_Query($argument);
		if($query===false) return(false);
        
        /***/
        
        if($query->found_posts!=1) return(false);
        
        $query->the_post();
        
        $meta=CHBSPostMeta::getPostMeta($post);
        
        /***/
        
        if($Validation->isNotEmpty($meta['usage_limit']))
        {    
           $count=$Booking->getCouponCodeUsageCount($data['coupon_code']);
      
           if($count===false) return(false);
           if($count>=$meta['usage_limit']) return(false);
        }
        
        /***/
        
        if($Validation->isNotEmpty($meta['active_date_start']))
        {
            if($Date->compareDate(date('Y-m-d'),$meta['active_date_start'])===2) return(false);
        }
        
        if($Validation->isNotEmpty($meta['active_date_stop']))
        {
            if($Date->compareDate($meta['active_date_stop'],date('Y-m-d'))===2) return(false);
        }     
        
        /***/

        return(array('post'=>$post,'meta'=>$meta));
    }
    
    /**************************************************************************/
    
    function calculateDiscountPercentage($discountFixed,$countDay,$countHour,$priceDay,$priceHour)
    {
        if($discountFixed==0) return(0);
        
        $sum=$countDay*$priceDay+$countHour*$priceHour;
        
        if($sum<=$discountFixed) return(0);
        
        $discountPercentage=($discountFixed/$sum)*100;

        return($discountPercentage);
    }
    
    /**************************************************************************/
}

/******************************************************************************/
/******************************************************************************/