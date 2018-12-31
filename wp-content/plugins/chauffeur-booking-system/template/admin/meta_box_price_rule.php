<?php 
		echo $this->data['nonce'];
        
        $Date=new CHBSDate();
        $Length=new CHBSLength();
?>	
		<div class="to">
            <div class="ui-tabs">
                <ul>
                    <li><a href="#meta-box-price-rule-1"><?php esc_html_e('Rules','chauffeur-booking-system'); ?></a></li>
                    <li><a href="#meta-box-price-rule-2"><?php esc_html_e('Prices','chauffeur-booking-system'); ?></a></li>
                </ul>
                <div id="meta-box-price-rule-1">
                    <ul class="to-form-field-list">
                        <li>
                            <h5><?php esc_html_e('Booking forms','chauffeur-booking-system'); ?></h5>
                            <span class="to-legend"><?php esc_html_e('Select forms.','chauffeur-booking-system'); ?></span>
                            <div class="to-checkbox-button">
                                <input type="checkbox" value="-1" id="<?php CHBSHelper::getFormName('booking_form_id_0'); ?>" name="<?php CHBSHelper::getFormName('booking_form_id[]'); ?>" <?php CHBSHelper::checkedIf($this->data['meta']['booking_form_id'],-1); ?>/>
                                <label for="<?php CHBSHelper::getFormName('booking_form_id_0'); ?>"><?php esc_html_e('- All forms -','chauffeur-booking-system') ?></label>
<?php
		foreach($this->data['dictionary']['booking_form'] as $index=>$value)
		{
?>
                                <input type="checkbox" value="<?php echo esc_attr($index); ?>" id="<?php CHBSHelper::getFormName('booking_form_id_'.$index); ?>" name="<?php CHBSHelper::getFormName('booking_form_id[]'); ?>" <?php CHBSHelper::checkedIf($this->data['meta']['booking_form_id'],$index); ?>/>
                                <label for="<?php CHBSHelper::getFormName('booking_form_id_'.$index); ?>"><?php echo esc_html($value['post']->post_title); ?></label>
<?php		
		}
?>
                            </div>
                        </li>
                        <li>
                            <h5><?php esc_html_e('Service type','chauffeur-booking-system'); ?></h5>
                            <span class="to-legend"><?php esc_html_e('Select service type.','chauffeur-booking-system'); ?></span>
                            <div class="to-checkbox-button">
                                <input type="checkbox" value="-1" id="<?php CHBSHelper::getFormName('service_type_id_0'); ?>" name="<?php CHBSHelper::getFormName('service_type_id[]'); ?>" <?php CHBSHelper::checkedIf($this->data['meta']['service_type_id'],-1); ?>/>
                                <label for="<?php CHBSHelper::getFormName('service_type_id_0'); ?>"><?php esc_html_e('- All service types -','chauffeur-booking-system') ?></label>
<?php
		foreach($this->data['dictionary']['service_type'] as $index=>$value)
		{
?>
                                <input type="checkbox" value="<?php echo esc_attr($index); ?>" id="<?php CHBSHelper::getFormName('service_type_id_'.$index); ?>" name="<?php CHBSHelper::getFormName('service_type_id[]'); ?>" <?php CHBSHelper::checkedIf($this->data['meta']['service_type_id'],$index); ?>/>
                                <label for="<?php CHBSHelper::getFormName('service_type_id_'.$index); ?>"><?php echo esc_html($value[0]); ?></label>
<?php		
		}
?>
                            </div>
                        </li>                         
                        <li>
                            <h5><?php esc_html_e('Routes','chauffeur-booking-system'); ?></h5>
                            <span class="to-legend"><?php esc_html_e('Select routes.','chauffeur-booking-system'); ?></span>
                            <div class="to-checkbox-button">
                                <input type="checkbox" value="-1" id="<?php CHBSHelper::getFormName('route_id_0'); ?>" name="<?php CHBSHelper::getFormName('route_id[]'); ?>" <?php CHBSHelper::checkedIf($this->data['meta']['route_id'],-1); ?>/>
                                <label for="<?php CHBSHelper::getFormName('route_id_0'); ?>"><?php esc_html_e('- All routes -','chauffeur-booking-system') ?></label>
<?php
		foreach($this->data['dictionary']['route'] as $index=>$value)
		{
?>
                                <input type="checkbox" value="<?php echo esc_attr($index); ?>" id="<?php CHBSHelper::getFormName('route_id_'.$index); ?>" name="<?php CHBSHelper::getFormName('route_id[]'); ?>" <?php CHBSHelper::checkedIf($this->data['meta']['route_id'],$index); ?>/>
                                <label for="<?php CHBSHelper::getFormName('route_id_'.$index); ?>"><?php echo esc_html($value['post']->post_title); ?></label>
<?php		
		}
?>
                            </div>
                        </li>  
                        <li>
                            <h5><?php esc_html_e('Vehicles','chauffeur-booking-system'); ?></h5>
                            <span class="to-legend"><?php esc_html_e('Select vehicles.','chauffeur-booking-system'); ?></span>
                            <div class="to-checkbox-button">
                                <input type="checkbox" value="-1" id="<?php CHBSHelper::getFormName('vehicle_id_0'); ?>" name="<?php CHBSHelper::getFormName('vehicle_id[]'); ?>" <?php CHBSHelper::checkedIf($this->data['meta']['vehicle_id'],-1); ?>/>
                                <label for="<?php CHBSHelper::getFormName('vehicle_id_0'); ?>"><?php esc_html_e('- All vehicles -','chauffeur-booking-system') ?></label>
<?php
		foreach($this->data['dictionary']['vehicle'] as $index=>$value)
		{
?>
                                <input type="checkbox" value="<?php echo esc_attr($index); ?>" id="<?php CHBSHelper::getFormName('vehicle_id_'.$index); ?>" name="<?php CHBSHelper::getFormName('vehicle_id[]'); ?>" <?php CHBSHelper::checkedIf($this->data['meta']['vehicle_id'],$index); ?>/>
                                <label for="<?php CHBSHelper::getFormName('vehicle_id_'.$index); ?>"><?php echo esc_html($value['post']->post_title); ?></label>
<?php		
		}
?>
                            </div>
                        </li>
                        <li>
                            <h5><?php esc_html_e('Day number','chauffeur-booking-system'); ?></h5>
                            <span class="to-legend"><?php esc_html_e('Select pickup day of the week.','chauffeur-booking-system'); ?></span>
                            <div class="to-checkbox-button">
                                <input type="checkbox" value="-1" id="<?php CHBSHelper::getFormName('pickup_day_number_0'); ?>" name="<?php CHBSHelper::getFormName('pickup_day_number[]'); ?>" <?php CHBSHelper::checkedIf($this->data['meta']['pickup_day_number'],-1); ?>/>
                                <label for="<?php CHBSHelper::getFormName('pickup_day_number_0'); ?>"><?php esc_html_e('- All days -','chauffeur-booking-system') ?></label>
<?php
        for($i=1;$i<=7;$i++)
        {
?>
                                <input type="checkbox" value="<?php echo esc_attr($i); ?>" id="<?php CHBSHelper::getFormName('pickup_day_number_'.$i); ?>" name="<?php CHBSHelper::getFormName('pickup_day_number[]'); ?>" <?php CHBSHelper::checkedIf($this->data['meta']['pickup_day_number'],$i); ?>/>
                                <label for="<?php CHBSHelper::getFormName('pickup_day_number_'.$i); ?>"><?php echo esc_html(date_i18n('l',strtotime('Sunday +'.$i.' days'))); ?></label>
<?php
        }
?>                                
                            </div>                        
                        </li>
                        <li>
                            <h5><?php esc_html_e('Dates','chauffeur-booking-system'); ?></h5>
                            <span class="to-legend"><?php esc_html_e('Enter pickup dates.','chauffeur-booking-system'); ?></span>
                            <div>
                                <table class="to-table" id="to-table-date">
                                    <tr>
                                        <th style="width:40%">
                                            <div>
                                                <?php esc_html_e('From','chauffeur-booking-system'); ?>
                                                <span class="to-legend">
                                                    <?php esc_html_e('From.','chauffeur-booking-system'); ?>
                                                </span>
                                            </div>
                                        </th>
                                        <th style="width:40%">
                                            <div>
                                                <?php esc_html_e('To','chauffeur-booking-system'); ?>
                                                <span class="to-legend">
                                                    <?php esc_html_e('To.','chauffeur-booking-system'); ?>
                                                </span>
                                            </div>
                                        </th>
                                        <th style="width:20%">
                                            <div>
                                                <?php esc_html_e('Remove','chauffeur-booking-system'); ?>
                                                <span class="to-legend">
                                                    <?php esc_html_e('Remove this entry.','chauffeur-booking-system'); ?>
                                                </span>
                                            </div>
                                        </th>                                            
                                    </tr>
                                    <tr class="to-hidden">
                                        <td>
                                            <div>
                                                <input type="text" class="to-datepicker-custom" name="<?php CHBSHelper::getFormName('pickup_date[start][]'); ?>"/>
                                            </div>									
                                        </td>
                                        <td>
                                            <div>
                                                <input type="text" class="to-datepicker-custom" name="<?php CHBSHelper::getFormName('pickup_date[stop][]'); ?>"/>
                                            </div>									
                                        </td>
                                        <td>
                                            <div>
                                                <a href="#" class="to-table-button-remove"><?php esc_html_e('Remove','chauffeur-booking-system'); ?></a>
                                            </div>
                                        </td>
                                    </tr>                         
<?php
        if(isset($this->data['meta']['pickup_date']))
        {
            if(is_array($this->data['meta']['pickup_date']))
            {
                foreach($this->data['meta']['pickup_date'] as $index=>$value)
                {
?>
                                    <tr>
                                        <td>
                                            <div>
                                                <input type="text" class="to-datepicker-custom" name="<?php CHBSHelper::getFormName('pickup_date[start][]'); ?>" value="<?php echo esc_attr($Date->formatDateToDisplay($value['start'])); ?>"/>
                                            </div>									
                                        </td>
                                        <td>
                                            <div>
                                                <input type="text" class="to-datepicker-custom" name="<?php CHBSHelper::getFormName('pickup_date[stop][]'); ?>" value="<?php echo esc_attr($Date->formatDateToDisplay($value['stop'])); ?>"/>
                                            </div>									
                                        </td>
                                        <td>
                                            <div>
                                                <a href="#" class="to-table-button-remove"><?php esc_html_e('Remove','chauffeur-booking-system'); ?></a>
                                            </div>
                                        </td>
                                    </tr>     
<?php                  
                }
            }
        }
?>
                                </table>
                                <div> 
                                    <a href="#" class="to-table-button-add"><?php esc_html_e('Add','chauffeur-booking-system'); ?></a>
                                </div>
                            </div>
                        </li>                        
                        <li>
                            <h5><?php esc_html_e('Hours','chauffeur-booking-system'); ?></h5>
                            <span class="to-legend"><?php esc_html_e('Enter pickup hours.','chauffeur-booking-system'); ?></span>
                            <div>
                                <table class="to-table" id="to-table-hour">
                                    <tr>
                                        <th style="width:40%">
                                            <div>
                                                <?php esc_html_e('From','chauffeur-booking-system'); ?>
                                                <span class="to-legend">
                                                    <?php esc_html_e('From.','chauffeur-booking-system'); ?>
                                                </span>
                                            </div>
                                        </th>
                                        <th style="width:40%">
                                            <div>
                                                <?php esc_html_e('To','chauffeur-booking-system'); ?>
                                                <span class="to-legend">
                                                    <?php esc_html_e('To.','chauffeur-booking-system'); ?>
                                                </span>
                                            </div>
                                        </th>
                                        <th style="width:20%">
                                            <div>
                                                <?php esc_html_e('Remove','chauffeur-booking-system'); ?>
                                                <span class="to-legend">
                                                    <?php esc_html_e('Remove this entry.','chauffeur-booking-system'); ?>
                                                </span>
                                            </div>
                                        </th>                                            
                                    </tr>
                                    <tr class="to-hidden">
                                        <td>
                                            <div>
                                                <input type="text" class="to-timepicker-custom" name="<?php CHBSHelper::getFormName('pickup_time[start][]'); ?>"/>
                                            </div>									
                                        </td>
                                        <td>
                                            <div>
                                                <input type="text" class="to-timepicker-custom" name="<?php CHBSHelper::getFormName('pickup_time[stop][]'); ?>"/>
                                            </div>									
                                        </td>
                                        <td>
                                            <div>
                                                <a href="#" class="to-table-button-remove"><?php esc_html_e('Remove','chauffeur-booking-system'); ?></a>
                                            </div>
                                        </td>
                                    </tr>  
<?php
        if(isset($this->data['meta']['pickup_time']))
        {
            if(is_array($this->data['meta']['pickup_time']))
            {
                foreach($this->data['meta']['pickup_time'] as $index=>$value)
                {
?>
                                    <tr>
                                        <td>
                                            <div>
                                                <input type="text" class="to-timepicker-custom" name="<?php CHBSHelper::getFormName('pickup_time[start][]'); ?>" value="<?php echo esc_attr($Date->formatTimeToDisplay($value['start'])); ?>"/>
                                            </div>									
                                        </td>
                                        <td>
                                            <div>
                                                <input type="text" class="to-timepicker-custom" name="<?php CHBSHelper::getFormName('pickup_time[stop][]'); ?>" value="<?php echo esc_attr($Date->formatTimeToDisplay($value['stop'])); ?>"/>
                                            </div>									
                                        </td>
                                        <td>
                                            <div>
                                                <a href="#" class="to-table-button-remove"><?php esc_html_e('Remove','chauffeur-booking-system'); ?></a>
                                            </div>
                                        </td>
                                    </tr>     
<?php                  
                }
            }
        }
?>
                                </table>
                                <div> 
                                    <a href="#" class="to-table-button-add"><?php esc_html_e('Add','chauffeur-booking-system'); ?></a>
                                </div>
                            </div>
                        </li>
                        <li>
                            <h5><?php esc_html_e('Distance','chauffeur-booking-system'); ?></h5>
                            <span class="to-legend"><?php esc_html_e('Enter ride distance.','chauffeur-booking-system'); ?></span>
                            <div>
                                <table class="to-table" id="to-table-distance">
                                    <tr>
                                        <th style="width:40%">
                                            <div>
                                                <?php esc_html_e('From','chauffeur-booking-system'); ?>
                                                <span class="to-legend">
                                                    <?php esc_html_e('From.','chauffeur-booking-system'); ?>
                                                </span>
                                            </div>
                                        </th>
                                        <th style="width:40%">
                                            <div>
                                                <?php esc_html_e('To','chauffeur-booking-system'); ?>
                                                <span class="to-legend">
                                                    <?php esc_html_e('To.','chauffeur-booking-system'); ?>
                                                </span>
                                            </div>
                                        </th>
                                        <th style="width:20%">
                                            <div>
                                                <?php esc_html_e('Remove','chauffeur-booking-system'); ?>
                                                <span class="to-legend">
                                                    <?php esc_html_e('Remove this entry.','chauffeur-booking-system'); ?>
                                                </span>
                                            </div>
                                        </th>                                            
                                    </tr>
                                    <tr class="to-hidden">
                                        <td>
                                            <div>
                                                <input type="text" maxlength="12" name="<?php CHBSHelper::getFormName('distance[start][]'); ?>"/>
                                            </div>									
                                        </td>
                                        <td>
                                            <div>
                                                <input type="text" maxlength="12" name="<?php CHBSHelper::getFormName('distance[stop][]'); ?>"/>
                                            </div>									
                                        </td>
                                        <td>
                                            <div>
                                                <a href="#" class="to-table-button-remove"><?php esc_html_e('Remove','chauffeur-booking-system'); ?></a>
                                            </div>
                                        </td>
                                    </tr>   
<?php
        if(isset($this->data['meta']['distance']))
        {
            if(is_array($this->data['meta']['distance']))
            {
                foreach($this->data['meta']['distance'] as $index=>$value)
                {
                    if(CHBSOption::getOption('length_unit')==2)
                    {
                        $value['start']=round($Length->convertUnit($value['start'],1,2),1);
                        $value['stop']=round($Length->convertUnit($value['stop'],1,2),1); 
                    }
?>
                                    <tr>
                                        <td>
                                            <div>
                                                <input type="text" maxlength="12" name="<?php CHBSHelper::getFormName('distance[start][]'); ?>" value="<?php echo esc_attr($value['start']); ?>"/>
                                            </div>									
                                        </td>
                                        <td>
                                            <div>
                                                <input type="text" maxlength="12" name="<?php CHBSHelper::getFormName('distance[stop][]'); ?>" value="<?php echo esc_attr($value['stop']); ?>"/>
                                            </div>									
                                        </td>
                                        <td>
                                            <div>
                                                <a href="#" class="to-table-button-remove"><?php esc_html_e('Remove','chauffeur-booking-system'); ?></a>
                                            </div>
                                        </td>                                        
                                    </tr>     
<?php                  
                }
            }
        }
?>
                                </table>
                                <div> 
                                    <a href="#" class="to-table-button-add"><?php esc_html_e('Add','chauffeur-booking-system'); ?></a>
                                </div>
                            </div>
                        </li>
                        <li>
                            <h5><?php esc_html_e('Passengers number','chauffeur-booking-system'); ?></h5>
                            <span class="to-legend"><?php esc_html_e('Enter passengers number.','chauffeur-booking-system'); ?></span>
                            <div>
                                <table class="to-table" id="to-table-passenger">
                                    <tr>
                                        <th style="width:40%">
                                            <div>
                                                <?php esc_html_e('From','chauffeur-booking-system'); ?>
                                                <span class="to-legend">
                                                    <?php esc_html_e('From.','chauffeur-booking-system'); ?>
                                                </span>
                                            </div>
                                        </th>
                                        <th style="width:40%">
                                            <div>
                                                <?php esc_html_e('To','chauffeur-booking-system'); ?>
                                                <span class="to-legend">
                                                    <?php esc_html_e('To.','chauffeur-booking-system'); ?>
                                                </span>
                                            </div>
                                        </th>
                                        <th style="width:20%">
                                            <div>
                                                <?php esc_html_e('Remove','chauffeur-booking-system'); ?>
                                                <span class="to-legend">
                                                    <?php esc_html_e('Remove this entry.','chauffeur-booking-system'); ?>
                                                </span>
                                            </div>
                                        </th>                                            
                                    </tr>
                                    <tr class="to-hidden">
                                        <td>
                                            <div>
                                                <input type="text" maxlength="12" name="<?php CHBSHelper::getFormName('passenger[start][]'); ?>"/>
                                            </div>									
                                        </td>
                                        <td>
                                            <div>
                                                <input type="text" maxlength="12" name="<?php CHBSHelper::getFormName('passenger[stop][]'); ?>"/>
                                            </div>									
                                        </td>
                                        <td>
                                            <div>
                                                <a href="#" class="to-table-button-remove"><?php esc_html_e('Remove','chauffeur-booking-system'); ?></a>
                                            </div>
                                        </td>
                                    </tr>   
<?php
        if(isset($this->data['meta']['passenger']))
        {
            if(is_array($this->data['meta']['passenger']))
            {
                foreach($this->data['meta']['passenger'] as $index=>$value)
                {
?>
                                    <tr>
                                        <td>
                                            <div>
                                                <input type="text" maxlength="12" name="<?php CHBSHelper::getFormName('passenger[start][]'); ?>" value="<?php echo esc_attr($value['start']); ?>"/>
                                            </div>									
                                        </td>
                                        <td>
                                            <div>
                                                <input type="text" maxlength="12" name="<?php CHBSHelper::getFormName('passenger[stop][]'); ?>" value="<?php echo esc_attr($value['stop']); ?>"/>
                                            </div>									
                                        </td>
                                        <td>
                                            <div>
                                                <a href="#" class="to-table-button-remove"><?php esc_html_e('Remove','chauffeur-booking-system'); ?></a>
                                            </div>
                                        </td>                                        
                                    </tr>     
<?php                  
                }
            }
        }
?>
                                </table>
                                <div> 
                                    <a href="#" class="to-table-button-add"><?php esc_html_e('Add','chauffeur-booking-system'); ?></a>
                                </div>
                            </div>
                        </li>
                        <li>
                            <h5><?php esc_html_e('Ride duration','chauffeur-booking-system'); ?></h5>
                            <span class="to-legend"><?php echo __('Enter range of ride duration (in hours). This rule applies for <b>Hourly</b> service type only.','chauffeur-booking-system'); ?></span>
                            <div>
                                <table class="to-table" id="to-table-duration">
                                    <tr>
                                        <th style="width:40%">
                                            <div>
                                                <?php esc_html_e('From','chauffeur-booking-system'); ?>
                                                <span class="to-legend">
                                                    <?php esc_html_e('From.','chauffeur-booking-system'); ?>
                                                </span>
                                            </div>
                                        </th>
                                        <th style="width:40%">
                                            <div>
                                                <?php esc_html_e('To','chauffeur-booking-system'); ?>
                                                <span class="to-legend">
                                                    <?php esc_html_e('To.','chauffeur-booking-system'); ?>
                                                </span>
                                            </div>
                                        </th>
                                        <th style="width:20%">
                                            <div>
                                                <?php esc_html_e('Remove','chauffeur-booking-system'); ?>
                                                <span class="to-legend">
                                                    <?php esc_html_e('Remove this entry.','chauffeur-booking-system'); ?>
                                                </span>
                                            </div>
                                        </th>                                            
                                    </tr>
                                    <tr class="to-hidden">
                                        <td>
                                            <div>
                                                <input type="text" maxlength="4" name="<?php CHBSHelper::getFormName('duration[start][]'); ?>"/>
                                            </div>									
                                        </td>
                                        <td>
                                            <div>
                                                <input type="text" maxlength="4" name="<?php CHBSHelper::getFormName('duration[stop][]'); ?>"/>
                                            </div>									
                                        </td>
                                        <td>
                                            <div>
                                                <a href="#" class="to-table-button-remove"><?php esc_html_e('Remove','chauffeur-booking-system'); ?></a>
                                            </div>
                                        </td>
                                    </tr>   
<?php
        if(isset($this->data['meta']['duration']))
        {
            if(is_array($this->data['meta']['duration']))
            {
                foreach($this->data['meta']['duration'] as $index=>$value)
                {
?>
                                    <tr>
                                        <td>
                                            <div>
                                                <input type="text" maxlength="5" name="<?php CHBSHelper::getFormName('duration[start][]'); ?>" value="<?php echo esc_attr($value['start']); ?>"/>
                                            </div>									
                                        </td>
                                        <td>
                                            <div>
                                                <input type="text" maxlength="5" name="<?php CHBSHelper::getFormName('duration[stop][]'); ?>" value="<?php echo esc_attr($value['stop']); ?>"/>
                                            </div>									
                                        </td>
                                        <td>
                                            <div>
                                                <a href="#" class="to-table-button-remove"><?php esc_html_e('Remove','chauffeur-booking-system'); ?></a>
                                            </div>
                                        </td>                                        
                                    </tr>     
<?php                  
                }
            }
        }
?>
                                </table>
                                <div> 
                                    <a href="#" class="to-table-button-add"><?php esc_html_e('Add','chauffeur-booking-system'); ?></a>
                                </div>
                            </div>
                        </li>
                        
                        
                    </ul>
                </div>
                <div id="meta-box-price-rule-2">
                    <ul class="to-form-field-list">
                        <li>
                            <h5><?php esc_html_e('Price type','chauffeur-booking-system'); ?></h5>
                            <span class="to-legend">
                                <?php esc_html_e('Select price type.','chauffeur-booking-system'); ?><br/>
                                <?php echo __('For a <b>Variable pricing</b> final price of the ride depends on distance or time.','chauffeur-booking-system'); ?><br/>
                                <?php echo __('For a <b>Fixed pricing</b> final price of the ride is independent on distance or time.','chauffeur-booking-system'); ?>
                            </span>
                            <div class="to-radio-button">
<?php
		foreach($this->data['dictionary']['price_type'] as $index=>$value)
		{
?>
                                <input type="radio" value="<?php echo esc_attr($index); ?>" id="<?php CHBSHelper::getFormName('price_type_'.$index); ?>" name="<?php CHBSHelper::getFormName('price_type'); ?>" <?php CHBSHelper::checkedIf($this->data['meta']['price_type'],$index); ?>/>
                                <label for="<?php CHBSHelper::getFormName('price_type_'.$index); ?>"><?php echo esc_html($value[0]); ?></label>
<?php		
		}
?>
                            </div>
                        </li>  
<?php
        $class=array(1=>array('to-price-type-1'),2=>array('to-price-type-2'));
        array_push($class[$this->data['meta']['price_type']==1 ? 2 : 1],'to-state-disabled');
?>
                        <li>
                            <h5><?php esc_html_e('Prices','chauffeur-booking-system'); ?></h5>
                            <span class="to-legend"><?php esc_html_e('Prices.','chauffeur-booking-system'); ?></span>
                            <div>
                                <table class="to-table to-table-price">
                                    <tr>
                                        <th style="width:15%">
                                            <div>
                                                <?php esc_html_e('Name','chauffeur-booking-system'); ?>
                                                <span class="to-legend">
                                                    <?php esc_html_e('Name.','chauffeur-booking-system'); ?>
                                                </span>
                                            </div>
                                        </th>
                                        <th style="width:10%">
                                            <div>
                                                <?php esc_html_e('Type','chauffeur-booking-system'); ?>
                                                <span class="to-legend">
                                                    <?php esc_html_e('Type.','chauffeur-booking-system'); ?>
                                                </span>
                                            </div>
                                        </th>                                        
                                        <th style="width:35%">
                                            <div>
                                                <?php esc_html_e('Description','chauffeur-booking-system'); ?>
                                                <span class="to-legend">
                                                    <?php esc_html_e('Description.','chauffeur-booking-system'); ?>
                                                </span>
                                            </div>
                                        </th>
                                        <th style="width:20%">
                                            <div>
                                                <?php esc_html_e('Value','chauffeur-booking-system'); ?>
                                                <span class="to-legend">
                                                    <?php esc_html_e('Value.','chauffeur-booking-system'); ?>
                                                </span>
                                            </div>
                                        </th>                                        
                                        <th style="width:20%">
                                            <div>
                                                <?php esc_html_e('Tax','chauffeur-booking-system'); ?>
                                                <span class="to-legend">
                                                    <?php esc_html_e('Tax.','chauffeur-booking-system'); ?>
                                                </span>
                                            </div>
                                        </th>                                          
                                    </tr> 
                                    <tr<?php echo CHBSHelper::createCSSClassAttribute($class[2]); ?>>
                                        <td>
                                            <div class="to-clear-fix">
                                                <?php esc_html_e('Fixed','chauffeur-booking-system'); ?>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="to-clear-fix">
                                                <?php esc_html_e('Fixed','chauffeur-booking-system'); ?>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="to-clear-fix">
                                                <?php _e('Fixed price for a ride.','chauffeur-booking-system'); ?>
                                            </div>
                                        </td>                                            
                                        <td>
                                            <div class="to-clear-fix">
                                                <input maxlength="12" type="text" name="<?php CHBSHelper::getFormName('price_fixed_value'); ?>" id="<?php CHBSHelper::getFormName('price_fixed_value'); ?>" value="<?php echo esc_attr($this->data['meta']['price_fixed_value']); ?>"/>
                                            </div>
                                        </td>                                        
                                        <td>
                                            <div class="to-clear-fix">
                                                <select name="<?php CHBSHelper::getFormName('price_fixed_tax_rate_id'); ?>">
<?php
                echo '<option value="0" '.(CHBSHelper::selectedIf($this->data['meta']['price_fixed_tax_rate_id'],0,false)).'>'.esc_html__('- Not set -','chauffeur-booking-system').'</option>';
                foreach($this->data['dictionary']['tax_rate'] as $index=>$value)
                {
                    echo '<option value="'.esc_attr($index).'" '.(CHBSHelper::selectedIf($this->data['meta']['price_fixed_tax_rate_id'],$index,false)).'>'.esc_html($value['post']->post_title).'</option>';
                }
?>
                                                </select>                                                  
                                            </div>
                                        </td>                                        
                                    </tr>
                                    <tr<?php echo CHBSHelper::createCSSClassAttribute($class[2]); ?>>
                                        <td>
                                            <div class="to-clear-fix">
                                                <?php esc_html_e('Fixed (return)','chauffeur-booking-system'); ?>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="to-clear-fix">
                                                <?php esc_html_e('Fixed','chauffeur-booking-system'); ?>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="to-clear-fix">
                                                <?php _e('Fixed price for a return ride.','chauffeur-booking-system'); ?>
                                            </div>
                                        </td>                                     
                                        <td>
                                            <div class="to-clear-fix">
                                                <input maxlength="12" type="text" name="<?php CHBSHelper::getFormName('price_fixed_return_value'); ?>" id="<?php CHBSHelper::getFormName('price_fixed_return_value'); ?>" value="<?php echo esc_attr($this->data['meta']['price_fixed_return_value']); ?>"/>
                                            </div>
                                        </td>                                        
                                        <td>
                                            <div class="to-clear-fix">
                                                <select name="<?php CHBSHelper::getFormName('price_fixed_return_tax_rate_id'); ?>">
<?php
                echo '<option value="0" '.(CHBSHelper::selectedIf($this->data['meta']['price_fixed_return_tax_rate_id'],0,false)).'>'.esc_html__('- Not set -','chauffeur-booking-system').'</option>';
                foreach($this->data['dictionary']['tax_rate'] as $index=>$value)
                {
                    echo '<option value="'.esc_attr($index).'" '.(CHBSHelper::selectedIf($this->data['meta']['price_fixed_return_tax_rate_id'],$index,false)).'>'.esc_html($value['post']->post_title).'</option>';
                }
?>
                                                </select>                                                  
                                            </div>
                                        </td>                                        
                                    </tr> 
                                    <tr<?php echo CHBSHelper::createCSSClassAttribute($class[1]); ?>>
                                        <td>
                                            <div class="to-clear-fix">
                                                <?php esc_html_e('Initial','chauffeur-booking-system'); ?>
                                            </div>
                                        </td>
                                         <td>
                                            <div class="to-clear-fix">
                                                <?php esc_html_e('Variable','chauffeur-booking-system'); ?>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="to-clear-fix">
                                                <?php _e('Fixed value which is added to the order sum.','chauffeur-booking-system'); ?>
                                            </div>
                                        </td>                                          
                                        <td>
                                            <div class="to-clear-fix">
                                                <input maxlength="12" type="text" name="<?php CHBSHelper::getFormName('price_initial_value'); ?>" id="<?php CHBSHelper::getFormName('price_initial_value'); ?>" value="<?php echo esc_attr($this->data['meta']['price_initial_value']); ?>"/>
                                            </div>
                                        </td>                                        
                                        <td>
                                            <div class="to-clear-fix">
                                                <select name="<?php CHBSHelper::getFormName('price_initial_tax_rate_id'); ?>">
<?php
                echo '<option value="0" '.(CHBSHelper::selectedIf($this->data['meta']['price_initial_tax_rate_id'],0,false)).'>'.esc_html__('- Not set -','chauffeur-booking-system').'</option>';
                foreach($this->data['dictionary']['tax_rate'] as $index=>$value)
                {
                    echo '<option value="'.esc_attr($index).'" '.(CHBSHelper::selectedIf($this->data['meta']['price_initial_tax_rate_id'],$index,false)).'>'.esc_html($value['post']->post_title).'</option>';
                }
?>
                                                </select>                                                  
                                            </div>
                                        </td>                                        
                                    </tr>                                     
                                    <tr<?php echo CHBSHelper::createCSSClassAttribute($class[1]); ?>>
                                        <td>
                                            <div class="to-clear-fix">
                                                <?php esc_html_e('Delivery','chauffeur-booking-system'); ?>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="to-clear-fix">
                                                <?php esc_html_e('Variable','chauffeur-booking-system'); ?>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="to-clear-fix">
                                                <?php _e('Price per kilometer of ride from base to customer pickup location.','chauffeur-booking-system'); ?>
                                            </div>
                                        </td>                                         
                                        <td>
                                            <div class="to-clear-fix">
                                                <input maxlength="12" type="text" name="<?php CHBSHelper::getFormName('price_delivery_value'); ?>" id="<?php CHBSHelper::getFormName('price_delivery_value'); ?>" value="<?php echo esc_attr($this->data['meta']['price_delivery_value']); ?>"/>
                                            </div>
                                        </td>                                        
                                        <td>
                                            <div class="to-clear-fix">
                                                <select name="<?php CHBSHelper::getFormName('price_delivery_tax_rate_id'); ?>">
<?php
                echo '<option value="0" '.(CHBSHelper::selectedIf($this->data['meta']['price_delivery_tax_rate_id'],0,false)).'>'.esc_html__('- Not set -','chauffeur-booking-system').'</option>';
                foreach($this->data['dictionary']['tax_rate'] as $index=>$value)
                {
                    echo '<option value="'.esc_attr($index).'" '.(CHBSHelper::selectedIf($this->data['meta']['price_delivery_tax_rate_id'],$index,false)).'>'.esc_html($value['post']->post_title).'</option>';
                }
?>
                                                </select>                                                  
                                            </div>
                                        </td>                                        
                                    </tr>  
                                    <tr<?php echo CHBSHelper::createCSSClassAttribute($class[1]); ?>>
                                        <td>
                                            <div class="to-clear-fix">
                                                <?php esc_html_e('Delivery (return)','chauffeur-booking-system'); ?>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="to-clear-fix">
                                                <?php esc_html_e('Variable','chauffeur-booking-system'); ?>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="to-clear-fix">
                                                <?php _e('Price per kilometer of ride from customer drop off location to base.','chauffeur-booking-system'); ?>
                                            </div>
                                        </td>                                         
                                        <td>
                                            <div class="to-clear-fix">
                                                <input maxlength="12" type="text" name="<?php CHBSHelper::getFormName('price_delivery_return_value'); ?>" id="<?php CHBSHelper::getFormName('price_delivery_return_value'); ?>" value="<?php echo esc_attr($this->data['meta']['price_delivery_return_value']); ?>"/>
                                            </div>
                                        </td>                                        
                                        <td>
                                            <div class="to-clear-fix">
                                                <select name="<?php CHBSHelper::getFormName('price_delivery_return_tax_rate_id'); ?>">
<?php
                echo '<option value="0" '.(CHBSHelper::selectedIf($this->data['meta']['price_delivery_return_tax_rate_id'],0,false)).'>'.esc_html__('- Not set -','chauffeur-booking-system').'</option>';
                foreach($this->data['dictionary']['tax_rate'] as $index=>$value)
                {
                    echo '<option value="'.esc_attr($index).'" '.(CHBSHelper::selectedIf($this->data['meta']['price_delivery_return_tax_rate_id'],$index,false)).'>'.esc_html($value['post']->post_title).'</option>';
                }
?>
                                                </select>                                                  
                                            </div>
                                        </td>                                        
                                    </tr>                                             
                                    <tr<?php echo CHBSHelper::createCSSClassAttribute($class[1]); ?>>
                                        <td>
                                            <div class="to-clear-fix">
                                                <?php echo $Length->label(-1,3); ?>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="to-clear-fix">
                                                <?php esc_html_e('Variable','chauffeur-booking-system'); ?>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="to-clear-fix">
                                                <?php _e('Price per distance.','chauffeur-booking-system'); ?>
                                            </div>
                                        </td>                                        
                                        <td>
                                            <div class="to-clear-fix">
                                                <input maxlength="12" type="text" name="<?php CHBSHelper::getFormName('price_distance_value'); ?>" id="<?php CHBSHelper::getFormName('price_distance_value'); ?>" value="<?php echo esc_attr($this->data['meta']['price_distance_value']); ?>"/>
                                            </div>
                                        </td>                                        
                                        <td>
                                            <div class="to-clear-fix">
                                                <select name="<?php CHBSHelper::getFormName('price_distance_tax_rate_id'); ?>">
<?php
                echo '<option value="0" '.(CHBSHelper::selectedIf($this->data['meta']['price_distance_tax_rate_id'],0,false)).'>'.esc_html__('- Not set -','chauffeur-booking-system').'</option>';
                foreach($this->data['dictionary']['tax_rate'] as $index=>$value)
                {
                    echo '<option value="'.esc_attr($index).'" '.(CHBSHelper::selectedIf($this->data['meta']['price_distance_tax_rate_id'],$index,false)).'>'.esc_html($value['post']->post_title).'</option>';
                }
?>
                                                </select>                                                  
                                            </div>
                                        </td>                                        
                                    </tr> 
                                    <tr<?php echo CHBSHelper::createCSSClassAttribute($class[1]); ?>>
                                        <td>
                                            <div class="to-clear-fix">
                                                <?php echo $Length->label(-1,3); ?><?php esc_html_e(' (return)'); ?>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="to-clear-fix">
                                                <?php esc_html_e('Variable','chauffeur-booking-system'); ?>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="to-clear-fix">
                                                <?php _e('Price per distance for return ride.','chauffeur-booking-system'); ?>
                                            </div>
                                        </td>                                        
                                        <td>
                                            <div class="to-clear-fix">
                                                <input maxlength="12" type="text" name="<?php CHBSHelper::getFormName('price_distance_return_value'); ?>" id="<?php CHBSHelper::getFormName('price_distance_return_value'); ?>" value="<?php echo esc_attr($this->data['meta']['price_distance_return_value']); ?>"/>
                                            </div>
                                        </td>                                        
                                        <td>
                                            <div class="to-clear-fix">
                                                <select name="<?php CHBSHelper::getFormName('price_distance_return_tax_rate_id'); ?>">
<?php
                echo '<option value="0" '.(CHBSHelper::selectedIf($this->data['meta']['price_distance_return_tax_rate_id'],0,false)).'>'.esc_html__('- Not set -','chauffeur-booking-system').'</option>';
                foreach($this->data['dictionary']['tax_rate'] as $index=>$value)
                {
                    echo '<option value="'.esc_attr($index).'" '.(CHBSHelper::selectedIf($this->data['meta']['price_distance_return_tax_rate_id'],$index,false)).'>'.esc_html($value['post']->post_title).'</option>';
                }
?>
                                                </select>                                                  
                                            </div>
                                        </td>                                        
                                    </tr>                                     
                                    <tr<?php echo CHBSHelper::createCSSClassAttribute($class[1]); ?>>
                                        <td>
                                            <div class="to-clear-fix">
                                                <?php esc_html_e('Per hour','chauffeur-booking-system'); ?>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="to-clear-fix">
                                                <?php esc_html_e('Variable','chauffeur-booking-system'); ?>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="to-clear-fix">
                                                <?php _e('Price per hour.','chauffeur-booking-system'); ?>
                                            </div>
                                        </td>                                          
                                        <td>
                                            <div class="to-clear-fix">
                                                <input maxlength="12" type="text" name="<?php CHBSHelper::getFormName('price_hour_value'); ?>" id="<?php CHBSHelper::getFormName('price_hour_value'); ?>" value="<?php echo esc_attr($this->data['meta']['price_hour_value']); ?>"/>
                                            </div>
                                        </td>                                        
                                        <td>
                                            <div class="to-clear-fix">
                                                <select name="<?php CHBSHelper::getFormName('price_hour_tax_rate_id'); ?>">
<?php
                echo '<option value="0" '.(CHBSHelper::selectedIf($this->data['meta']['price_hour_tax_rate_id'],0,false)).'>'.esc_html__('- Not set -','chauffeur-booking-system').'</option>';
                foreach($this->data['dictionary']['tax_rate'] as $index=>$value)
                {
                    echo '<option value="'.esc_attr($index).'" '.(CHBSHelper::selectedIf($this->data['meta']['price_hour_tax_rate_id'],$index,false)).'>'.esc_html($value['post']->post_title).'</option>';
                }
?>
                                                </select>                                                  
                                            </div>
                                        </td>                                        
                                    </tr>
                                    <tr>
                                        <td>
                                            <div class="to-clear-fix">
                                                <?php esc_html_e('Per extra time (hour)','chauffeur-booking-system'); ?>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="to-clear-fix">
                                                <?php esc_html_e('Fixed','chauffeur-booking-system'); ?><br/>
                                                <?php esc_html_e('Variable','chauffeur-booking-system'); ?>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="to-clear-fix">
                                                <?php _e('Price per hour for extra time.','chauffeur-booking-system'); ?>
                                            </div>
                                        </td>                                         
                                        <td>
                                            <div class="to-clear-fix">
                                                <input maxlength="12" type="text" name="<?php CHBSHelper::getFormName('price_extra_time_value'); ?>" id="<?php CHBSHelper::getFormName('price_extra_time_value'); ?>" value="<?php echo esc_attr($this->data['meta']['price_extra_time_value']); ?>"/>
                                            </div>
                                        </td>                                        
                                        <td>
                                            <div class="to-clear-fix">
                                                <select name="<?php CHBSHelper::getFormName('price_extra_time_tax_rate_id'); ?>">
<?php
                echo '<option value="0" '.(CHBSHelper::selectedIf($this->data['meta']['price_extra_time_tax_rate_id'],0,false)).'>'.esc_html__('- Not set -','chauffeur-booking-system').'</option>';
                foreach($this->data['dictionary']['tax_rate'] as $index=>$value)
                {
                    echo '<option value="'.esc_attr($index).'" '.(CHBSHelper::selectedIf($this->data['meta']['price_extra_time_tax_rate_id'],$index,false)).'>'.esc_html($value['post']->post_title).'</option>';
                }
?>
                                                </select>                                                  
                                            </div>
                                        </td>                                        
                                    </tr>
                                    <tr<?php echo CHBSHelper::createCSSClassAttribute($class[1]); ?>>
                                        <td>
                                            <div class="to-clear-fix">
                                                <?php esc_html_e('Per adult passenger','chauffeur-booking-system'); ?>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="to-clear-fix">
                                                <?php esc_html_e('Variable','chauffeur-booking-system'); ?>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="to-clear-fix">
                                                <?php _e('Price per adult passenger.','chauffeur-booking-system'); ?>
                                            </div>
                                        </td>                                          
                                        <td>
                                            <div class="to-clear-fix">
                                                <input maxlength="12" type="text" name="<?php CHBSHelper::getFormName('price_passenger_adult_value'); ?>" id="<?php CHBSHelper::getFormName('price_passenger_adult_value'); ?>" value="<?php echo esc_attr($this->data['meta']['price_passenger_adult_value']); ?>"/>
                                            </div>
                                        </td>                                        
                                        <td>
                                            <div class="to-clear-fix">
                                                <select name="<?php CHBSHelper::getFormName('price_passenger_adult_tax_rate_id'); ?>">
<?php
                echo '<option value="0" '.(CHBSHelper::selectedIf($this->data['meta']['price_passenger_adult_tax_rate_id'],0,false)).'>'.esc_html__('- Not set -','chauffeur-booking-system').'</option>';
                foreach($this->data['dictionary']['tax_rate'] as $index=>$value)
                {
                    echo '<option value="'.esc_attr($index).'" '.(CHBSHelper::selectedIf($this->data['meta']['price_passenger_adult_tax_rate_id'],$index,false)).'>'.esc_html($value['post']->post_title).'</option>';
                }
?>
                                                </select>                                                  
                                            </div>
                                        </td>                                        
                                    </tr>
                                    <tr<?php echo CHBSHelper::createCSSClassAttribute($class[1]); ?>>
                                        <td>
                                            <div class="to-clear-fix">
                                                <?php esc_html_e('Per children passenger','chauffeur-booking-system'); ?>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="to-clear-fix">
                                                <?php esc_html_e('Variable','chauffeur-booking-system'); ?>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="to-clear-fix">
                                                <?php _e('Price per children passenger.','chauffeur-booking-system'); ?>
                                            </div>
                                        </td>                                          
                                        <td>
                                            <div class="to-clear-fix">
                                                <input maxlength="12" type="text" name="<?php CHBSHelper::getFormName('price_passenger_children_value'); ?>" id="<?php CHBSHelper::getFormName('price_passenger_children_value'); ?>" value="<?php echo esc_attr($this->data['meta']['price_passenger_children_value']); ?>"/>
                                            </div>
                                        </td>                                        
                                        <td>
                                            <div class="to-clear-fix">
                                                <select name="<?php CHBSHelper::getFormName('price_passenger_children_tax_rate_id'); ?>">
<?php
                echo '<option value="0" '.(CHBSHelper::selectedIf($this->data['meta']['price_passenger_children_tax_rate_id'],0,false)).'>'.esc_html__('- Not set -','chauffeur-booking-system').'</option>';
                foreach($this->data['dictionary']['tax_rate'] as $index=>$value)
                {
                    echo '<option value="'.esc_attr($index).'" '.(CHBSHelper::selectedIf($this->data['meta']['price_passenger_children_tax_rate_id'],$index,false)).'>'.esc_html($value['post']->post_title).'</option>';
                }
?>
                                                </select>                                                  
                                            </div>
                                        </td>                                        
                                    </tr>                                    
                                </table>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
		<script type="text/javascript">
			jQuery(document).ready(function($)
			{	
                /***/
                
				$('.to').themeOptionElement({init:true});
                
                /***/
                
                $('input[name="<?php CHBSHelper::getFormName('booking_form_id'); ?>[]"],input[name="<?php CHBSHelper::getFormName('service_type_id'); ?>[]"],input[name="<?php CHBSHelper::getFormName('route_id'); ?>[]"],input[name="<?php CHBSHelper::getFormName('vehicle_id'); ?>[]"],input[name="<?php CHBSHelper::getFormName('pickup_day_number'); ?>[]"]').on('change',function()
                {
                    var checkbox=$(this).parents('li:first').find('input');
                    
                    var value=parseInt($(this).val());
                    if(value===-1)
                    {
                        checkbox.removeAttr('checked');
                        checkbox.first().attr('checked','checked');
                    }
                    else checkbox.first().removeAttr('checked');
                    
                    var checked=[];
                    checkbox.each(function()
                    {
                        if($(this).is(':checked'))
                            checked.push(parseInt($(this).val(),10));
                    });
                    
                    if(checked.length===0)
                    {
                        checkbox.removeAttr('checked');
                        checkbox.first().attr('checked','checked');
                    }
                    
                    checkbox.button('refresh');
                });
                
                /***/
                
                $('#to-table-date').table();
                $('#to-table-hour').table();
                $('#to-table-distance').table();
                $('#to-table-passenger').table();
                $('#to-table-duration').table();
                
                /***/
                
                var timeFormat='<?php echo CHBSOption::getOption('time_format'); ?>';
                var dateFormat='<?php echo CHBSJQueryUIDatePicker::convertDateFormat(CHBSOption::getOption('date_format')); ?>';
                
                toCreateCustomDateTimePicker(dateFormat,timeFormat);
                
                /***/
                
                toTogglePriceType('.to input[name="<?php CHBSHelper::getFormName('price_type'); ?>"]','.to .to-table-price');
                
                /***/
            });
		</script>