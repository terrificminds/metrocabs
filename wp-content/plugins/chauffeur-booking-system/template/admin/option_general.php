
        <ul class="to-form-field-list">
            <li>
                <h5><?php esc_html_e('Logo','chauffeur-booking-system'); ?></h5>
                <span class="to-legend"><?php esc_html_e('Select company logo.','chauffeur-booking-system'); ?></span>
                <div class="to-clear-fix">
                    <input type="text" name="<?php CHBSHelper::getFormName('logo'); ?>" id="<?php CHBSHelper::getFormName('logo'); ?>" class="to-float-left" value="<?php echo esc_attr($this->data['option']['logo']); ?>"/>
                    <input type="button" name="<?php CHBSHelper::getFormName('logo_browse'); ?>" id="<?php CHBSHelper::getFormName('logo_browse'); ?>" class="to-button-browse to-button" value="<?php esc_attr_e('Browse','chauffeur-booking-system'); ?>"/>
                </div>
            </li> 
            <li>
                <h5><?php esc_html_e('Google Maps API key','chauffeur-booking-system'); ?></h5>
                <span class="to-legend"><?php echo sprintf(__('You can generate your own key <a href="%s" target="_blank">here</a>.','chauffeur-booking-system'),'https://developers.google.com/maps/documentation/javascript/get-api-key'); ?></span>
                <div class="to-clear-fix">
                    <input type="text" name="<?php CHBSHelper::getFormName('google_map_api_key'); ?>" id="<?php CHBSHelper::getFormName('google_map_api_key'); ?>" value="<?php echo esc_attr($this->data['option']['google_map_api_key']); ?>"/>
                </div>
            </li>  
            <li>
                <h5><?php esc_html_e('Currency','chauffeur-booking-system'); ?></h5>
                <span class="to-legend"><?php esc_html_e('Currency.','chauffeur-booking-system'); ?></span>
                <div class="to-clear-fix">
					<select name="<?php CHBSHelper::getFormName('currency'); ?>" id="<?php CHBSHelper::getFormName('currency'); ?>">
<?php
						foreach($this->data['dictionary']['currency'] as $index=>$value)
							echo '<option value="'.esc_attr($index).'" '.(CHBSHelper::selectedIf($this->data['option']['currency'],$index,false)).'>'.esc_html($index.' ('.$value['name'].')').'</option>';
?>
					</select>
				</div>
            </li>
            <li>
                <h5><?php esc_html_e('Length unit','chauffeur-booking-system'); ?></h5>
                <span class="to-legend"><?php esc_html_e('Length unit.','chauffeur-booking-system'); ?></span>
                <div class="to-clear-fix">
					<select name="<?php CHBSHelper::getFormName('length_unit'); ?>" id="<?php CHBSHelper::getFormName('length_unit'); ?>">
<?php
						foreach($this->data['dictionary']['length_unit'] as $index=>$value)
							echo '<option value="'.esc_attr($index).'" '.(CHBSHelper::selectedIf($this->data['option']['length_unit'],$index,false)).'>'.esc_html($value[0].' ('.$value[1].')').'</option>';
?>
					</select>
				</div>
            </li>   
            <li>
                <h5><?php esc_html_e('Date format','chauffeur-booking-system'); ?></h5>
                <span class="to-legend"><?php echo sprintf(esc_html__('Select the date format to be displayed. More info you can find here %s.','chauffeur-booking-system'),'<a href="https://codex.wordpress.org/Formatting_Date_and_Time" target="_blank">Formatting Date and Time</a>'); ?></span>
                <div class="to-clear-fix">
                    <input type="text" name="<?php CHBSHelper::getFormName('date_format'); ?>" id="<?php CHBSHelper::getFormName('date_format'); ?>" value="<?php echo esc_attr($this->data['option']['date_format']); ?>"/>
                </div>
            </li>  
            <li>
                <h5><?php esc_html_e('Time format','chauffeur-booking-system'); ?></h5>
                <span class="to-legend"><?php echo sprintf(esc_html__('Select the time format to be displayed. More info you can find here %s.','chauffeur-booking-system'),'<a href="https://codex.wordpress.org/Formatting_Date_and_Time" target="_blank">Formatting Date and Time</a>'); ?></span>
                <div class="to-clear-fix">
                    <input type="text" name="<?php CHBSHelper::getFormName('time_format'); ?>" id="<?php CHBSHelper::getFormName('time_format'); ?>" value="<?php echo esc_attr($this->data['option']['time_format']); ?>"/>
                </div>
            </li>               
            <li>
                <h5><?php esc_html_e('Default sender e-mail account','chauffeur-booking-system'); ?></h5>
                <span class="to-legend">
                    <?php esc_html_e('Select default e-mail account.','chauffeur-booking-system'); ?><br/>
                    <?php esc_html_e('It will be used to sending email messages to clients about changing of booking status.','chauffeur-booking-system'); ?>
                </span>
                <div class="to-clear-fix">
					<select name="<?php CHBSHelper::getFormName('sender_default_email_account_id'); ?>" id="<?php CHBSHelper::getFormName('sender_default_email_account_id'); ?>">
<?php
						echo '<option value="-1" '.(CHBSHelper::selectedIf($this->data['option']['sender_default_email_account_id'],-1,false)).'>'.esc_html__('- Not set -','chauffeur-booking-system').'</option>';
						foreach($this->data['dictionary']['email_account'] as $index=>$value)
							echo '<option value="'.esc_attr($index).'" '.(CHBSHelper::selectedIf($this->data['option']['sender_default_email_account_id'],$index,false)).'>'.esc_html($value['post']->post_title).'</option>';
?>
					</select>
				</div>
            </li>                 
        </ul>