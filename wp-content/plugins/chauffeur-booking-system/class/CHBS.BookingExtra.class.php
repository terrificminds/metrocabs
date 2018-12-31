<?php

/******************************************************************************/
/******************************************************************************/

class CHBSBookingExtra
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
        return(PLUGIN_CHBS_CONTEXT.'_booking_extra');
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
					'name'														=>	__('Booking Extras','chauffeur-booking-system'),
					'singular_name'												=>	__('Booking Extra','chauffeur-booking-system'),
					'add_new'													=>	__('Add New','chauffeur-booking-system'),
					'add_new_item'												=>	__('Add New Booking Add-on','chauffeur-booking-system'),
					'edit_item'													=>	__('Edit Booking Extra','chauffeur-booking-system'),
					'new_item'													=>	__('New Booking Extra','chauffeur-booking-system'),
					'all_items'													=>	__('Booking Extras','chauffeur-booking-system'),
					'view_item'													=>	__('View Booking Extra','chauffeur-booking-system'),
					'search_items'												=>	__('Search Booking Extras','chauffeur-booking-system'),
					'not_found'													=>	__('No Booking Extras Found','chauffeur-booking-system'),
					'not_found_in_trash'										=>	__('No Booking Extras Found in Trash','chauffeur-booking-system'), 
					'parent_item_colon'											=>	'',
					'menu_name'													=>	__('Booking Extras','chauffeur-booking-system')
				),	
				'public'														=>	false,  
				'show_ui'														=>	true, 
				'show_in_menu'													=>	'edit.php?post_type='.CHBSBooking::getCPTName(),
				'capability_type'												=>	'post',
				'menu_position'													=>	2,
				'hierarchical'													=>	false,  
				'rewrite'														=>	false,  
				'supports'														=>	array('title','page-attributes','thumbnail')  
			)
		);
        
		register_taxonomy
		(
			self::getCPTCategoryName(),
			self::getCPTName(),
			array
			(
				'label'                                                         =>	__('Booking Extra Categories','chauffeur-booking-system'),
                'hierarchical'                                                  =>  false
			)
		);
        
        add_action('save_post',array($this,'savePost'));
        add_action('add_meta_boxes_'.self::getCPTName(),array($this,'addMetaBox'));
        add_filter('postbox_classes_'.self::getCPTName().'_chbs_meta_box_booking_extra',array($this,'adminCreateMetaBoxClass'));
        
		add_filter('manage_edit-'.self::getCPTName().'_columns',array($this,'manageEditColumns')); 
		add_action('manage_'.self::getCPTName().'_posts_custom_column',array($this,'managePostsCustomColumn'));
		add_filter('manage_edit-'.self::getCPTName().'_sortable_columns',array($this,'manageEditSortableColumns'));
    }

    /**************************************************************************/
    
    function addMetaBox()
    {
        add_meta_box(PLUGIN_CHBS_CONTEXT.'_meta_box_booking_extra',__('Main','chauffeur-booking-system'),array($this,'addMetaBoxMain'),self::getCPTName(),'normal','low');		
    }
    
    /**************************************************************************/
    
    function addMetaBoxMain()
    {
        global $post;
        
		$data=array();
        
        $TaxRate=new CHBSTaxRate();
        
        $data['meta']=CHBSPostMeta::getPostMeta($post);
        
		$data['nonce']=CHBSHelper::createNonceField(PLUGIN_CHBS_CONTEXT.'_meta_box_booking_extra');
        
        $data['dictionary']['tax_rate']=$TaxRate->getDictionary();
        
		$Template=new CHBSTemplate($data,PLUGIN_CHBS_TEMPLATE_PATH.'admin/meta_box_booking_extra.php');
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
        CHBSHelper::setDefault($meta,'description','');
        
        CHBSHelper::setDefault($meta,'quantity_enable','1');
        CHBSHelper::setDefault($meta,'quantity_max','1');
        
        CHBSHelper::setDefault($meta,'price','0.00');
        
        $TaxRate=new CHBSTaxRate();
        CHBSHelper::setDefault($meta,'tax_rate_id',$TaxRate->getDefaultTaxPostId());
	}
    
    /**************************************************************************/
    
    function savePost($postId)
    {      
        if(!$_POST) return(false);
        
        if(CHBSHelper::checkSavePost($postId,PLUGIN_CHBS_CONTEXT.'_meta_box_booking_extra_noncename','savePost')===false) return(false);
        
		$meta=array();

        $TaxRate=new CHBSTaxRate();
        $Validation=new CHBSValidation();
        
		$this->setPostMetaDefault($meta);
      
        /***/
        
        $meta['description']=CHBSHelper::getPostValue('description');
        
        $meta['quantity_enable']=CHBSHelper::getPostValue('quantity_enable');
        if(!$Validation->isBool($meta['quantity_enable']))
            $meta['quantity_enable']=1;
        
        if($meta['quantity_enable']!=1) $meta['quantity_max']=1;
        else
        {
            $meta['quantity_max']=CHBSHelper::getPostValue('quantity_max');
            if(!$Validation->isNumber($meta['quantity_max'],1,9999))
                $meta['quantity_max']=1;
        }
        
        $meta['price']=CHBSHelper::getPostValue('price');
        if(!$Validation->isPrice($meta['price'],false))
           $meta['price']=0.00;  
        
        $meta['tax_rate_id']=CHBSHelper::getPostValue('tax_rate_id');
        if(!$TaxRate->isTaxRate($meta['tax_rate_id']))
            $meta['tax_rate_id']=0;
        
        /***/
		foreach($meta as $index=>$value)
			CHBSPostMeta::updatePostMeta($postId,$index,$value);
    }
    
    /**************************************************************************/
    
    function getDictionary($attr=array())
    {
		global $post;
		
		$dictionary=array();
		
		$default=array
		(
			'booking_extra_id'                                                  =>	0,
            'category_id'                                                       =>  array()
		);
		
		$attribute=shortcode_atts($default,$attr);
        
		CHBSHelper::preservePost($post,$bPost);
		
		$argument=array
		(
			'post_type'															=>	self::getCPTName(),
			'post_status'														=>	'publish',
			'posts_per_page'													=>	-1,
			'orderby'															=>	array('menu_order'=>'asc','title'=>'asc')
		);
		
		if($attribute['booking_extra_id'])
			$argument['p']=$attribute['booking_extra_id'];

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
    
    function calculatePrice($bookingExtra,$taxRate)
    {
        $Currency=new CHBSCurrency();
        
        /***/
        
        $taxRateValue=0;
        $taxRateId=$bookingExtra['meta']['tax_rate_id'];
        
        if(isset($taxRate[$taxRateId]))
            $taxRateValue=$taxRate[$taxRateId]['meta']['tax_rate_value'];
        
        /***/
        
        $currency=$Currency->getCurrency(CHBSOption::getOption('currency'));
        
        /***/
        
        if(!array_key_exists('quantity',$bookingExtra))
            $bookingExtra['quantity']=1;
        
        /***/
        
        $priceNetValue=$bookingExtra['meta']['price'];
        $priceGrossValue=CHBSPrice::calculateGross($priceNetValue,$taxRateId);
        
        $sumNetValue=$priceNetValue*$bookingExtra['quantity'];
        $sumGrossValue=$priceGrossValue*$bookingExtra['quantity'];

        $priceGrossFormat=CHBSPrice::format($priceGrossValue,CHBSOption::getOption('currency'));
        $sumGrossFormat=CHBSPrice::format($sumGrossValue,CHBSOption::getOption('currency'));
        
        $priceNetValue=number_format($priceNetValue,2,'.','');
        $priceGrossValue=number_format($priceGrossValue,2,'.','');       
        
        $priceNetValue=number_format($priceNetValue,2,'.','');
        $priceGrossValue=number_format($priceGrossValue,2,'.','');       
        
        /***/
        
        $data=array
        (
            'price'                                                             =>  array
            (
                'net'                                                           =>  array
                (
                    'value'                                                     =>  $priceNetValue,
                ),
                'gross'                                                         =>  array
                (
                    'value'                                                     =>  $priceGrossValue,
                    'format'                                                    =>  $priceGrossFormat
                )
            ),
            'sum'                                                               =>  array
            (
                'net'                                                           =>  array
                (
                    'value'                                                     =>  $sumNetValue,
                ),
                'gross'                                                         =>  array
                (
                    'value'                                                     =>  $sumGrossValue,
                    'format'                                                    =>  $sumGrossFormat
                )
            ),
            'currency'                                                          =>  $currency
        );
        
        return($data);
    }
    
    /**************************************************************************/
    
    function validate($data,$bookingExtraDictionary,$taxRateDictionary)
    {
        $bookingExtra=array();
        $bookingExtraId=preg_split('/,/',$data['booking_extra_id']);
        
        foreach($bookingExtraId as $value)
        {
            if(array_key_exists($value,$bookingExtraDictionary))
            {
                $quantity=(int)$data['booking_extra_'.$value.'_quantity'];
                
                if($bookingExtraDictionary[$value]['meta']['quantity_enable']==1) 
                {
                    if(!(($quantity>=1) && ($quantity<=$bookingExtraDictionary[$value]['meta']['quantity_max']))) 
                        $quantity=1;
                }
                else $quantity=1;
                
                $taxValue=0;
                if(isset($taxRateDictionary[$bookingExtraDictionary[$value]['meta']['tax_rate_id']]))
                    $taxValue=$taxRateDictionary[$bookingExtraDictionary[$value]['meta']['tax_rate_id']]['meta']['tax_rate_value'];
                
                array_push($bookingExtra,array
                (
                    'id'                                                        =>  $value,
                    'name'                                                      =>  $bookingExtraDictionary[$value]['post']->post_title,
                    'price'                                                     =>  $bookingExtraDictionary[$value]['meta']['price'],
                    'quantity'                                                  =>  $quantity,
                    'tax_rate_value'                                            =>  $taxValue
                ));
            }
        }
        
        return($bookingExtra);
    }
    
    /**************************************************************************/
    
    function manageEditColumns($column)
    {
        $column=array
        (
            'cb'                                                                =>  $column['cb'],
            'title'                                                             =>  __('Title','chauffeur-booking-system'),
            'price'                                                             =>  __('Price','chauffeur-booking-system'),
            'date'                                                              =>  $column['date']
        );
   
		return($column);          
    }
    
    /**************************************************************************/
    
    function managePostsCustomColumn($column)
    {
		global $post;
		
		$meta=CHBSPostMeta::getPostMeta($post);
        
		switch($column) 
		{
			case 'price':
                
                echo CHBSPrice::format($meta['price'],CHBSOption::getOption('currency'));
                
			break;
        }
    }
    
    /**************************************************************************/
    
    function manageEditSortableColumns($column)
    {
		return($column);       
    }
        
    /**************************************************************************/
}

/******************************************************************************/
/******************************************************************************/