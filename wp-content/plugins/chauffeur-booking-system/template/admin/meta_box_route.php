<?php 
        $Length=new CHBSLength();
		echo $this->data['nonce']; 
?>	
		<div class="to">
            <div class="ui-tabs">
                <ul>
                    <li><a href="#meta-box-route-1"><?php esc_html_e('Route','chauffeur-booking-system'); ?></a></li>
                    <li><a href="#meta-box-route-2"><?php esc_html_e('Prices','chauffeur-booking-system'); ?></a></li>
                </ul>
                <div id="meta-box-route-1">
                    <ul class="to-form-field-list">
                        <li>
                            <h5><?php esc_html_e('Route','chauffeur-booking-system'); ?></h5>
                            <span class="to-legend">
                                <?php esc_html_e('Enter at least start and end point.','chauffeur-booking-system'); ?>
                            </span>
                            <div>	
                                <table class="to-table" id="to-table-route">
                                    <tr>
                                        <th style="width:50%">
                                            <div>
                                                <?php esc_html_e('Point','chauffeur-booking-system'); ?>
                                                <span class="to-legend">
                                                    <?php esc_html_e('Waypoint.','chauffeur-booking-system'); ?>
                                                </span>
                                            </div>
                                        </th>
                                        <th style="width:25%">
                                            <div>
                                                <?php esc_html_e('Remove','chauffeur-booking-system'); ?>
                                                <span class="to-legend">
                                                    <?php esc_html_e('Remove a waypoint.','chauffeur-booking-system'); ?>
                                                </span>
                                            </div>
                                        </th>
                                        <th style="width:25%">
                                            <div>
                                                <?php esc_html_e('Add','chauffeur-booking-system'); ?>
                                                <span class="to-legend">
                                                    <?php esc_html_e('Add a waypoint.','chauffeur-booking-system'); ?>
                                                </span>
                                            </div>
                                        </th>
                                    </tr> 
                                    <tr class="to-hidden">
                                        <td>
                                            <div>
                                                <input type="text" name="<?php CHBSHelper::getFormName('route[]'); ?>" title="<?php esc_attr_e('Start typing to get list of available points.','chauffeur-booking-system'); ?>"/>
                                            </div>									
                                        </td>
                                        <td>
                                            <div>
                                                <a href="#" class="to-table-button-remove"><?php esc_html_e('Remove','chauffeur-booking-system'); ?></a>
                                            </div>
                                        </td>
                                        <td>
                                            <div>
                                                <?php esc_html_e('Add','chauffeur-booking-system'); ?>
                                                <a href="#" class="to-table-button-add-before"><?php esc_html_e('before','chauffeur-booking-system'); ?></a>
                                                <a href="#" class="to-table-button-add-after"><?php esc_html_e('after','chauffeur-booking-system'); ?></a>
                                                <?php esc_html_e('this point.','chauffeur-booking-system'); ?>
                                            </div>
                                        </td>
                                    </tr>
<?php
        if(count($this->data['meta']['coordinate']))
        {
            foreach($this->data['meta']['coordinate'] as $index=>$value)
            {
?>          
                                    <tr data-lng="<?php echo esc_attr($value->{'lng'}); ?>" data-lat="<?php echo esc_attr($value->{'lat'}); ?>" data-address="<?php echo esc_attr($value->{'address'}); ?>">
                                        <td>
                                            <div>
                                                <input type="text" value="<?php echo esc_attr($value->{'address'}); ?>" name="<?php CHBSHelper::getFormName('route[]'); ?>" title="<?php esc_attr_e('Start typing to get list of available points.','chauffeur-booking-system'); ?>"/>
                                            </div>									
                                        </td>
                                        <td>
                                            <div>
                                                <a href="#" class="to-table-button-remove"><?php esc_html_e('Remove','chauffeur-booking-system'); ?></a>
                                            </div>
                                        </td>
                                        <td>
                                            <div>
                                                <?php esc_html_e('Add','chauffeur-booking-system'); ?>
                                                <a href="#" class="to-table-button-add-before"><?php esc_html_e('before','chauffeur-booking-system'); ?></a>
                                                <a href="#" class="to-table-button-add-after"><?php esc_html_e('after','chauffeur-booking-system'); ?></a>
                                                <?php esc_html_e('this point.','chauffeur-booking-system'); ?>
                                            </div>
                                        </td>
                                    </tr>
<?php
            }
        }
?>
                                </table>
                            </div>
                        </li>
                    </ul>
                    <div id="to-google-map"></div>
                    <input type="hidden" name="<?php CHBSHelper::getFormName('coordinate'); ?>" id="<?php CHBSHelper::getFormName('coordinate'); ?>" value="<?php echo esc_attr(json_encode($this->data['meta']['coordinate'])); ?>"/>
                </div>
                <div id="meta-box-route-2">
<?php
        if((is_array($this->data['dictionary']['vehicle'])) && (count($this->data['dictionary']['vehicle'])))
        {
?>
                    <ul class="to-form-field-list">
                        <li>
                            <h5><?php esc_html_e('Prices','chauffeur-booking-system'); ?></h5>
                            <span class="to-legend"><?php esc_html_e('Specify prices for each vehicle separately.','chauffeur-booking-system'); ?></span>                    
                            <div>
                                <table class="to-table" id="to-table-vehicle-attribute">
                                    <tr>
                                        <th style="width:20%">
                                            <div>
                                                <?php esc_html_e('Vehicle','chauffeur-booking-system'); ?>
                                                <span class="to-legend">
                                                    <?php esc_html_e('Vehicle.','chauffeur-booking-system'); ?>
                                                </span>
                                            </div>
                                        </th>
                                        <th style="width:15%">
                                            <div>
                                                <?php esc_html_e('Price source','chauffeur-booking-system'); ?>
                                                <span class="to-legend">
                                                    <?php esc_html_e('Price source.','chauffeur-booking-system'); ?>
                                                </span>
                                            </div>
                                        </th>  
                                        <th style="width:15%">
                                            <div>
                                                <?php esc_html_e('Price type','chauffeur-booking-system'); ?>
                                                <span class="to-legend">
                                                    <?php esc_html_e('Price type.','chauffeur-booking-system'); ?>
                                                </span>
                                            </div>
                                        </th>  
                                        <th style="width:20%">
                                            <div>
                                                <?php esc_html_e('Price name','chauffeur-booking-system'); ?>
                                                <span class="to-legend">
                                                    <?php esc_html_e('Price name.','chauffeur-booking-system'); ?>
                                                </span>
                                            </div>
                                        </th>  
                                        <th style="width:15%">
                                            <div>
                                                <?php esc_html_e('Price value','chauffeur-booking-system'); ?>
                                                <span class="to-legend">
                                                    <?php esc_html_e('Price value.','chauffeur-booking-system'); ?>
                                                </span>
                                            </div>
                                        </th>                                    
                                        <th style="width:15%">
                                            <div>
                                                <?php esc_html_e('Tax rate','chauffeur-booking-system'); ?>
                                                <span class="to-legend">
                                                    <?php esc_html_e('Tax rate.','chauffeur-booking-system'); ?>
                                                </span>
                                            </div>
                                        </th>  
                                    </tr>
<?php
            foreach($this->data['dictionary']['vehicle'] as $vehicleIndex=>$vehicleValue)
            {
                $class=array(1=>array('to-price-type-1'),2=>array('to-price-type-2'));
                array_push($class[$this->data['meta']['vehicle'][$vehicleIndex]['price_type']==1 ? 2 : 1],'to-state-disabled');
?>               
                                    <tbody id="to-vehicle-<?php echo $vehicleIndex; ?>">
                                        <tr>
                                            <td rowspan="13">
                                                <div>
                                                    <div class="to-field-disabled">
                                                        <?php echo esc_html($vehicleValue['post']->post_title); ?>
                                                    </div>
                                                </div>
                                            </td> 
                                        </tr>
                                        <tr>
                                            <td rowspan="12">
                                                <div class="to-clear-fix">
                                                    <select name="<?php CHBSHelper::getFormName('vehicle['.$vehicleIndex.'][price_source]'); ?>">
<?php
                foreach($this->data['dictionary']['price_source'] as $index=>$value)
                {
                    echo '<option value="'.esc_attr($index).'" '.(CHBSHelper::selectedIf($this->data['meta']['vehicle'][$vehicleIndex]['price_source'],$index,false)).'>'.esc_html($value[0]).'</option>';
                }
?>
                                                    </select>                                       
                                                </div>
                                            </td> 
                                        </tr>
                                        <tr>
                                            <td rowspan="11">
                                                <div class="to-clear-fix">
                                                    <select name="<?php CHBSHelper::getFormName('vehicle['.$vehicleIndex.'][price_type]'); ?>">
<?php
                foreach($this->data['dictionary']['price_type'] as $index=>$value)
                {
                    echo '<option value="'.esc_attr($index).'" '.(CHBSHelper::selectedIf($this->data['meta']['vehicle'][$vehicleIndex]['price_type'],$index,false)).'>'.esc_html($value[0]).'</option>';
                }
?>
                                                    </select>                                 
                                                </div>
                                            </td> 
                                        </tr>
                                        <tr<?php echo CHBSHelper::createCSSClassAttribute($class[2]); ?>>
                                            <td>
                                                <div class="to-clear-fix">
                                                    <?php esc_html_e('Fixed','chauffeur-booking-system'); ?>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="to-clear-fix">
                                                    <input type="text" name="<?php CHBSHelper::getFormName('vehicle['.$vehicleIndex.'][price_fixed_value]'); ?>" value="<?php echo esc_attr($this->data['meta']['vehicle'][$vehicleIndex]['price_fixed_value']); ?>" title="<?php esc_attr_e('Enter price.','chauffeur-booking-system'); ?>"/>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="to-clear-fix">
                                                    <select name="<?php CHBSHelper::getFormName('vehicle['.$vehicleIndex.'][price_fixed_tax_rate_id]'); ?>">
<?php
                echo '<option value="0" '.(CHBSHelper::selectedIf($this->data['meta']['vehicle'][$vehicleIndex]['price_fixed_tax_rate_id'],0,false)).'>'.esc_html__('- Not set -','chauffeur-booking-system').'</option>';
                foreach($this->data['dictionary']['tax_rate'] as $index=>$value)
                {
                    echo '<option value="'.esc_attr($index).'" '.(CHBSHelper::selectedIf($this->data['meta']['vehicle'][$vehicleIndex]['price_fixed_tax_rate_id'],$index,false)).'>'.esc_html($value['post']->post_title).'</option>';
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
                                                    <input type="text" name="<?php CHBSHelper::getFormName('vehicle['.$vehicleIndex.'][price_fixed_return_value]'); ?>" value="<?php echo esc_attr($this->data['meta']['vehicle'][$vehicleIndex]['price_fixed_return_value']); ?>" title="<?php esc_attr_e('Enter price.','chauffeur-booking-system'); ?>"/>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="to-clear-fix">
                                                    <select name="<?php CHBSHelper::getFormName('vehicle['.$vehicleIndex.'][price_fixed_return_tax_rate_id]'); ?>">
<?php
                echo '<option value="0" '.(CHBSHelper::selectedIf($this->data['meta']['vehicle'][$vehicleIndex]['price_fixed_return_tax_rate_id'],0,false)).'>'.esc_html__('- Not set -','chauffeur-booking-system').'</option>';
                foreach($this->data['dictionary']['tax_rate'] as $index=>$value)
                {
                    echo '<option value="'.esc_attr($index).'" '.(CHBSHelper::selectedIf($this->data['meta']['vehicle'][$vehicleIndex]['price_fixed_return_tax_rate_id'],$index,false)).'>'.esc_html($value['post']->post_title).'</option>';
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
                                                    <input type="text" name="<?php CHBSHelper::getFormName('vehicle['.$vehicleIndex.'][price_initial_value]'); ?>" value="<?php echo esc_attr($this->data['meta']['vehicle'][$vehicleIndex]['price_initial_value']); ?>" title="<?php esc_attr_e('Enter price.','chauffeur-booking-system'); ?>"/>
                                                </div>
                                            </td>
                                            <td>
                                                <div>
                                                    <select name="<?php CHBSHelper::getFormName('vehicle['.$vehicleIndex.'][price_initial_tax_rate_id]'); ?>">
<?php
                echo '<option value="0" '.(CHBSHelper::selectedIf($this->data['meta']['vehicle'][$vehicleIndex]['price_initial_tax_rate_id'],0,false)).'>'.esc_html__('- Not set -','chauffeur-booking-system').'</option>';
                foreach($this->data['dictionary']['tax_rate'] as $index=>$value)
                {
                    echo '<option value="'.esc_attr($index).'" '.(CHBSHelper::selectedIf($this->data['meta']['vehicle'][$vehicleIndex]['price_initial_tax_rate_id'],$index,false)).'>'.esc_html($value['post']->post_title).'</option>';
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
                                                    <input type="text" name="<?php CHBSHelper::getFormName('vehicle['.$vehicleIndex.'][price_delivery_value]'); ?>" value="<?php echo esc_attr($this->data['meta']['vehicle'][$vehicleIndex]['price_delivery_value']); ?>" title="<?php esc_attr_e('Enter price.','chauffeur-booking-system'); ?>"/>
                                                </div>
                                            </td>
                                            <td>
                                                <div>
                                                    <select name="<?php CHBSHelper::getFormName('vehicle['.$vehicleIndex.'][price_delivery_tax_rate_id]'); ?>">
<?php
                echo '<option value="0" '.(CHBSHelper::selectedIf($this->data['meta']['vehicle'][$vehicleIndex]['price_delivery_tax_rate_id'],0,false)).'>'.esc_html__('- Not set -','chauffeur-booking-system').'</option>';
                foreach($this->data['dictionary']['tax_rate'] as $index=>$value)
                {
                    echo '<option value="'.esc_attr($index).'" '.(CHBSHelper::selectedIf($this->data['meta']['vehicle'][$vehicleIndex]['price_delivery_tax_rate_id'],$index,false)).'>'.esc_html($value['post']->post_title).'</option>';
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
                                                    <input type="text" name="<?php CHBSHelper::getFormName('vehicle['.$vehicleIndex.'][price_delivery_return_value]'); ?>" value="<?php echo esc_attr($this->data['meta']['vehicle'][$vehicleIndex]['price_delivery_return_value']); ?>" title="<?php esc_attr_e('Enter price.','chauffeur-booking-system'); ?>"/>
                                                </div>
                                            </td>
                                            <td>
                                                <div>
                                                    <select name="<?php CHBSHelper::getFormName('vehicle['.$vehicleIndex.'][price_delivery_return_tax_rate_id]'); ?>">
<?php
                echo '<option value="0" '.(CHBSHelper::selectedIf($this->data['meta']['vehicle'][$vehicleIndex]['price_delivery_return_tax_rate_id'],0,false)).'>'.esc_html__('- Not set -','chauffeur-booking-system').'</option>';
                foreach($this->data['dictionary']['tax_rate'] as $index=>$value)
                {
                    echo '<option value="'.esc_attr($index).'" '.(CHBSHelper::selectedIf($this->data['meta']['vehicle'][$vehicleIndex]['price_delivery_return_tax_rate_id'],$index,false)).'>'.esc_html($value['post']->post_title).'</option>';
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
                                                    <input type="text" name="<?php CHBSHelper::getFormName('vehicle['.$vehicleIndex.'][price_distance_value]'); ?>" value="<?php echo esc_attr($this->data['meta']['vehicle'][$vehicleIndex]['price_distance_value']); ?>" title="<?php esc_attr_e('Enter price.','chauffeur-booking-system'); ?>"/>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="to-clear-fix">
                                                    <select name="<?php CHBSHelper::getFormName('vehicle['.$vehicleIndex.'][price_distance_tax_rate_id]'); ?>">
<?php
                echo '<option value="0" '.(CHBSHelper::selectedIf($this->data['meta']['vehicle'][$vehicleIndex]['price_distance_tax_rate_id'],0,false)).'>'.esc_html__('- Not set -','chauffeur-booking-system').'</option>';
                foreach($this->data['dictionary']['tax_rate'] as $index=>$value)
                {
                    echo '<option value="'.esc_attr($index).'" '.(CHBSHelper::selectedIf($this->data['meta']['vehicle'][$vehicleIndex]['price_distance_tax_rate_id'],$index,false)).'>'.esc_html($value['post']->post_title).'</option>';
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
                                                    <?php esc_html_e(' (return)','chauffeur-booking-system'); ?>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="to-clear-fix">
                                                    <input type="text" name="<?php CHBSHelper::getFormName('vehicle['.$vehicleIndex.'][price_distance_return_value]'); ?>" value="<?php echo esc_attr($this->data['meta']['vehicle'][$vehicleIndex]['price_distance_return_value']); ?>" title="<?php esc_attr_e('Enter price.','chauffeur-booking-system'); ?>"/>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="to-clear-fix">
                                                    <select name="<?php CHBSHelper::getFormName('vehicle['.$vehicleIndex.'][price_distance_return_tax_rate_id]'); ?>">
<?php
                echo '<option value="0" '.(CHBSHelper::selectedIf($this->data['meta']['vehicle'][$vehicleIndex]['price_distance_return_tax_rate_id'],0,false)).'>'.esc_html__('- Not set -','chauffeur-booking-system').'</option>';
                foreach($this->data['dictionary']['tax_rate'] as $index=>$value)
                {
                    echo '<option value="'.esc_attr($index).'" '.(CHBSHelper::selectedIf($this->data['meta']['vehicle'][$vehicleIndex]['price_distance_return_tax_rate_id'],$index,false)).'>'.esc_html($value['post']->post_title).'</option>';
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
                                                    <input type="text" name="<?php CHBSHelper::getFormName('vehicle['.$vehicleIndex.'][price_extra_time_value]'); ?>" value="<?php echo esc_attr($this->data['meta']['vehicle'][$vehicleIndex]['price_extra_time_value']); ?>" title="<?php esc_attr_e('Enter price.','chauffeur-booking-system'); ?>"/>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="to-clear-fix">
                                                    <select name="<?php CHBSHelper::getFormName('vehicle['.$vehicleIndex.'][price_extra_time_tax_rate_id]'); ?>">
<?php
                echo '<option value="0" '.(CHBSHelper::selectedIf($this->data['meta']['vehicle'][$vehicleIndex]['price_extra_time_tax_rate_id'],0,false)).'>'.esc_html__('- Not set -','chauffeur-booking-system').'</option>';
                foreach($this->data['dictionary']['tax_rate'] as $index=>$value)
                {
                    echo '<option value="'.esc_attr($index).'" '.(CHBSHelper::selectedIf($this->data['meta']['vehicle'][$vehicleIndex]['price_extra_time_tax_rate_id'],$index,false)).'>'.esc_html($value['post']->post_title).'</option>';
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
                                                    <input type="text" name="<?php CHBSHelper::getFormName('vehicle['.$vehicleIndex.'][price_passenger_adult_value]'); ?>" value="<?php echo esc_attr($this->data['meta']['vehicle'][$vehicleIndex]['price_passenger_adult_value']); ?>" title="<?php esc_attr_e('Enter price.','chauffeur-booking-system'); ?>"/>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="to-clear-fix">
                                                    <select name="<?php CHBSHelper::getFormName('vehicle['.$vehicleIndex.'][price_passenger_adult_tax_rate_id]'); ?>">
<?php
                echo '<option value="0" '.(CHBSHelper::selectedIf($this->data['meta']['vehicle'][$vehicleIndex]['price_passenger_adult_tax_rate_id'],0,false)).'>'.esc_html__('- Not set -','chauffeur-booking-system').'</option>';
                foreach($this->data['dictionary']['tax_rate'] as $index=>$value)
                {
                    echo '<option value="'.esc_attr($index).'" '.(CHBSHelper::selectedIf($this->data['meta']['vehicle'][$vehicleIndex]['price_passenger_adult_tax_rate_id'],$index,false)).'>'.esc_html($value['post']->post_title).'</option>';
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
                                                    <input type="text" name="<?php CHBSHelper::getFormName('vehicle['.$vehicleIndex.'][price_passenger_children_value]'); ?>" value="<?php echo esc_attr($this->data['meta']['vehicle'][$vehicleIndex]['price_passenger_children_value']); ?>" title="<?php esc_attr_e('Enter price.','chauffeur-booking-system'); ?>"/>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="to-clear-fix">
                                                    <select name="<?php CHBSHelper::getFormName('vehicle['.$vehicleIndex.'][price_passenger_children_tax_rate_id]'); ?>">
<?php
                echo '<option value="0" '.(CHBSHelper::selectedIf($this->data['meta']['vehicle'][$vehicleIndex]['price_passenger_children_tax_rate_id'],0,false)).'>'.esc_html__('- Not set -','chauffeur-booking-system').'</option>';
                foreach($this->data['dictionary']['tax_rate'] as $index=>$value)
                {
                    echo '<option value="'.esc_attr($index).'" '.(CHBSHelper::selectedIf($this->data['meta']['vehicle'][$vehicleIndex]['price_passenger_children_tax_rate_id'],$index,false)).'>'.esc_html($value['post']->post_title).'</option>';
                }
?>
                                                    </select>  
                                                </div>
                                            </td>
                                        </tr>                                            
                                    </tbody>
                                    
                                    <script type="text/javascript">
                                        jQuery(document).ready(function()
                                        {	                                    
                                            toTogglePriceType('.to select[name="<?php CHBSHelper::getFormName('vehicle['.$vehicleIndex.'][price_type]'); ?>"]','#to-vehicle-<?php echo $vehicleIndex; ?>');
                                        });
                                    </script>
<?php
            }
?>
                                </table>
                            </div>
                        </li>
                    </ul>
<?php
        }
?>
                </div>
            </div>
        </div>
		<script type="text/javascript">
			jQuery(document).ready(function($)
			{
				$('.to').themeOptionElement({init:true});
                
                var panel=$().chauffeurRouteAdmin(
                {
                    message                                                     :
                    {
                        designate_route_error                                     :   '<?php esc_html_e('It is not possible to create a route between chosen points.','chauffeur-booking-system'); ?>'
                    },
                    coordinate                                                  :   <?php echo json_encode($this->data['coordinate']); ?>
                });
                
                panel.init();
                panel.create();
                panel.createRoute();
                
				$('#to-table-route').table(
                {
                    afterAddLine        :   function(line)
                    {
                        panel.createAutoComplete(line.find('input[type="text"]'));
                    },
                    afterRemoveLine     :   function()
                    {
                        panel.createRoute();
                    },
                    sortable            :
                    {
                        update          :   function()
                        {
                            panel.createRoute();
                        }
                    }
                });
            });
		</script>
