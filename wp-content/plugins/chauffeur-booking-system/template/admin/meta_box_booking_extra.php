<?php 
		echo $this->data['nonce']; 
?>	
		<div class="to">
            <div class="ui-tabs">
                <ul>
                    <li><a href="#meta-box-vehicle-1"><?php esc_html_e('General','chauffeur-booking-system'); ?></a></li>
                </ul>
                <div id="meta-box-vehicle-1">
                    <ul class="to-form-field-list">
                        <li>
                            <h5><?php esc_html_e('Description','chauffeur-booking-system'); ?></h5>
                            <span class="to-legend"><?php esc_html_e('Description of the additive.','chauffeur-booking-system'); ?></span>
                            <div>
                                <textarea rows="1" cols="1" name="<?php CHBSHelper::getFormName('description'); ?>" id="<?php CHBSHelper::getFormName('description'); ?>"><?php echo esc_html($this->data['meta']['description']); ?></textarea>
                            </div>
                        </li>
                        <li>
                            <h5><?php esc_html_e('Quantity','chauffeur-booking-system'); ?></h5>
                            <span class="to-legend"><?php esc_html_e('Define whether an add-on can be ordered more then once.','chauffeur-booking-system'); ?></span>                        
                            <div class="to-radio-button">
                                <input type="radio" value="1" id="<?php CHBSHelper::getFormName('quantity_enable_1'); ?>" name="<?php CHBSHelper::getFormName('quantity_enable'); ?>" <?php CHBSHelper::checkedIf($this->data['meta']['quantity_enable'],1); ?>/>
                                <label for="<?php CHBSHelper::getFormName('quantity_enable_1'); ?>"><?php esc_html_e('Enable','chauffeur-booking-system'); ?></label>
                                <input type="radio" value="0" id="<?php CHBSHelper::getFormName('quantity_enable_0'); ?>" name="<?php CHBSHelper::getFormName('quantity_enable'); ?>" <?php CHBSHelper::checkedIf($this->data['meta']['quantity_enable'],0); ?>/>
                                <label for="<?php CHBSHelper::getFormName('quantity_enable_0'); ?>"><?php esc_html_e('Disable','chauffeur-booking-system'); ?></label>
                            </div>
                        </li>
<?php
        $class=array();
        
        if($this->data['meta']['quantity_enable']!=1)
            array_push($class,'to-hidden');
?>
                        <li<?php echo CHBSHelper::createCSSClassAttribute($class); ?>>
                            <h5><?php esc_html_e('Maximum number','chauffeur-booking-system'); ?></h5>
                            <span class="to-legend"><?php esc_html_e('A maximum number possible to order. Integer value from 1 to 9999.','chauffeur-booking-system'); ?></span>                        
                            <div>
                                <input type="text" name="<?php CHBSHelper::getFormName('quantity_max'); ?>" id="<?php CHBSHelper::getFormName('quantity_max'); ?>" value="<?php echo esc_attr($this->data['meta']['quantity_max']); ?>" maxlength="4"/>
                            </div>
                        </li>                        
                        <li>
                            <h5><?php esc_html_e('Price','chauffeur-booking-system'); ?></h5>
                            <span class="to-legend"><?php esc_html_e('Price per single addition.','chauffeur-booking-system'); ?></span>
                            <div>
                                <input maxlength="12" type="text" name="<?php CHBSHelper::getFormName('price'); ?>" id="<?php CHBSHelper::getFormName('price'); ?>" value="<?php echo esc_attr($this->data['meta']['price']); ?>"/>
                            </div>
                        </li>
                        <li>
                            <h5><?php esc_html_e('Tax rate','chauffeur-booking-system'); ?></h5>
                            <span class="to-legend"><?php esc_html_e('Select tax rate for the price.','chauffeur-booking-system'); ?></span>
                            <div class="to-radio-button">
                                <input type="radio" value="0" id="<?php CHBSHelper::getFormName('tax_rate_id_0'); ?>" name="<?php CHBSHelper::getFormName('tax_rate_id'); ?>" <?php CHBSHelper::checkedIf($this->data['meta']['tax_rate_id'],0); ?>/>
                                <label for="<?php CHBSHelper::getFormName('tax_rate_id_0'); ?>"><?php esc_html_e('- Not set -','chauffeur-booking-system'); ?></label>
<?php
		foreach($this->data['dictionary']['tax_rate'] as $index=>$value)
		{
?>
                                <input type="radio" value="<?php echo esc_attr($index); ?>" id="<?php CHBSHelper::getFormName('tax_rate_id_'.$index); ?>" name="<?php CHBSHelper::getFormName('tax_rate_id'); ?>" <?php CHBSHelper::checkedIf($this->data['meta']['tax_rate_id'],$index); ?>/>
                                <label for="<?php CHBSHelper::getFormName('tax_rate_id_'.$index); ?>"><?php echo esc_html($value['post']->post_title); ?></label>
<?php		
		}
?>
                            </div>
                        </li>                  
                    </ul>
                </div>
            </div>
        </div>
		<script type="text/javascript">
			jQuery(document).ready(function($)
			{	
				$('.to').themeOptionElement({init:true});
                
                $('input[name="<?php echo CHBSHelper::getFormName('quantity_enable') ?>"]').on('change',function()
                {
                    var value=parseInt($(this).val());
                    
                    var line=$('#<?php echo CHBSHelper::getFormName('quantity_max') ?>').parents('li');
                    
                    if(value===1) line.removeClass('to-hidden');
                    else line.addClass('to-hidden');
                });
            });
		</script>