<?php 
		echo $this->data['nonce']; 
        $Date=new CHBSDate();
?>	
		<div class="to">
            <div class="ui-tabs">
                <ul>
                    <li><a href="#meta-box-coupon-1"><?php esc_html_e('General','chauffeur-booking-system'); ?></a></li>
                </ul>
                <div id="meta-box-coupon-1">
                    <ul class="to-form-field-list">
                        <li>
                            <h5><?php esc_html_e('Coupon code','chauffeur-booking-system'); ?></h5>
                            <span class="to-legend"><?php esc_html_e('Unique, 12-characters coupon code.','chauffeur-booking-system'); ?></span>
                            <div class="to-field-disabled">
                                <?php echo esc_html($this->data['meta']['code']); ?>
                            </div>
                        </li>  
                        <li>
                            <h5><?php esc_html_e('Usage count','chauffeur-booking-system'); ?></h5>
                            <span class="to-legend"><?php esc_html_e('Current usage count of the code.','chauffeur-booking-system'); ?></span>
                            <div class="to-field-disabled">
                                <?php echo esc_html($this->data['meta']['usage_count']); ?>
                            </div>
                        </li>  
                        <li>
                            <h5><?php esc_html_e('Usage limit','chauffeur-booking-system'); ?></h5>
                            <span class="to-legend"><?php esc_html_e('Usage limit of the code. Allowed are integer values from range 1-9999. Leave blank for unlimited.','chauffeur-booking-system'); ?></span>
                            <div>
                                <input type="text" maxlength="4" name="<?php CHBSHelper::getFormName('usage_limit'); ?>" id="<?php CHBSHelper::getFormName('usage_limit'); ?>" value="<?php echo esc_attr($this->data['meta']['usage_limit']); ?>"/>
                            </div>
                        </li>                             
                        <li>
                            <h5><?php esc_html_e('Percentage discount','chauffeur-booking-system'); ?></h5>
                            <span class="to-legend"><?php esc_html_e('Perecentage discount. Allowed are integer numbers from 0 to 99.','chauffeur-booking-system'); ?></span>
                            <div>
                                <input type="text" maxlength="2" name="<?php CHBSHelper::getFormName('discount_percentage'); ?>" id="<?php CHBSHelper::getFormName('discount_percentage'); ?>" value="<?php echo esc_attr($this->data['meta']['discount_percentage']); ?>"/>
                            </div>
                        </li>     
                        <li>
                            <h5><?php esc_html_e('Active from','chauffeur-booking-system'); ?></h5>
                            <span class="to-legend"><?php esc_html_e('Start date. Leave blank for no start date.','chauffeur-booking-system'); ?></span>
                            <div>
                                <input type="text" class="to-datepicker-custom" name="<?php CHBSHelper::getFormName('active_date_start'); ?>" id="<?php CHBSHelper::getFormName('active_date_start'); ?>" value="<?php echo $Date->formatDateToDisplay($this->data['meta']['active_date_start']); ?>"/>
                            </div>
                        </li>  
                        <li>
                            <h5><?php esc_html_e('Active to','chauffeur-booking-system'); ?></h5>
                            <span class="to-legend"><?php esc_html_e('Stop date. Leave blank for no stop date.','chauffeur-booking-system'); ?></span>
                            <div>
                                <input type="text" class="to-datepicker-custom" name="<?php CHBSHelper::getFormName('active_date_stop'); ?>" id="<?php CHBSHelper::getFormName('active_date_stop'); ?>" value="<?php echo $Date->formatDateToDisplay($this->data['meta']['active_date_stop']); ?>"/>
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
                
                var timeFormat='<?php echo CHBSOption::getOption('time_format'); ?>';
                var dateFormat='<?php echo CHBSJQueryUIDatePicker::convertDateFormat(CHBSOption::getOption('date_format')); ?>';
                
                toCreateCustomDateTimePicker(dateFormat,timeFormat);
            });
		</script>