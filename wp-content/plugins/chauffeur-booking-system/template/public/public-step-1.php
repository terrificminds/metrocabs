<?php
        if($this->data['widget_mode']!=1)
        {
?>
        <div class="chbs-notice chbs-hidden"></div>
<?php
        }
        
        $class=array('chbs-layout-50x50');
        
        if($this->data['widget_mode']==1) $class=array('chbs-layout-100');
        
        array_push($class,'chbs-clear-fix');
?>
        <div<?php echo CHBSHelper::createCSSClassAttribute($class); ?>>

            <div class="chbs-layout-column-left">

                <div class="chbs-tab chbs-box-shadow">

                    <ul>
<?php
        if(in_array(1,$this->data['meta']['service_type_id']))
        {
?>
                        <li data-id="1"><a href="#panel-1"><?php esc_html_e('Distance','chauffeur-booking-system'); ?></a></li>
<?php
        }
        if(in_array(2,$this->data['meta']['service_type_id']))
        {        
?>
                        <li data-id="2"><a href="#panel-2"><?php esc_html_e('Hourly','chauffeur-booking-system'); ?></a></li>
<?php
        }
        if(in_array(3,$this->data['meta']['service_type_id']))
        {
?>
                        <li data-id="3"><a href="#panel-3"><?php esc_html_e('Flat rate','chauffeur-booking-system'); ?></a></li>
<?php
        }
?>
                    </ul>
<?php

        if(in_array(1,$this->data['meta']['service_type_id']))
        {            
?>
                    <div id="panel-1">
<?php
            if($this->data['widget_mode']!=1)
            {
?>
                        <label class="chbs-form-label-group"><?php esc_html_e('Ride Details','chauffeur-booking-system'); ?></label>
<?php
            }
?>
                        <div class="chbs-clear-fix">

                            <div class="chbs-form-field chbs-form-field-width-50">
                                <label class="chbs-form-field-label"><?php esc_html_e('Pickup date','chauffeur-booking-system'); ?></label>
                                <input type="text" autocomplete="off" name="<?php CHBSHelper::getFormName('pickup_date_service_type_1'); ?>" class="chbs-datepicker" value="<?php echo esc_attr(CHBSRequestData::getFromWidget(1,'pickup_date')); ?>"/>
                            </div>

                            <div class="chbs-form-field chbs-form-field-width-50">
                                <label><?php esc_html_e('Pickup time','chauffeur-booking-system'); ?></label>
                                <input type="text" autocomplete="off" name="<?php CHBSHelper::getFormName('pickup_time_service_type_1'); ?>" class="chbs-timepicker" value="<?php echo esc_attr(CHBSRequestData::getFromWidget(1,'pickup_time')); ?>"/>
                            </div>

                        </div>
<?php
            if($this->data['widget_mode']!=1)
            {
?>
                        <div class="chbs-form-field chbs-form-field-location-autocomplete chbs-form-field-location-switch chbs-hidden">
                            <label><?php esc_html_e('Waypoint','chauffeur-booking-system'); ?></label>
                            <input type="text" autocomplete="off" name="<?php CHBSHelper::getFormName('waypoint_location_service_type_1[]'); ?>"/>
                            <input type="hidden" name="<?php CHBSHelper::getFormName('waypoint_location_coordinate_service_type_1[]'); ?>"/>
                            <!-- <span class="chbs-location-add chbs-meta-icon-plus"></span>
                            <span class="chbs-location-remove chbs-meta-icon-minus"></span> -->
                        </div>  
<?php
            }
?>
                        <div class="chbs-form-field chbs-form-field-location-autocomplete chbs-form-field-location-switch" data-label-waypoint="<?php esc_attr_e('Waypoint'); ?>">
                            <label><?php esc_html_e('Pickup location','chauffeur-booking-system'); ?></label>
                            <input type="text" autocomplete="off" name="<?php CHBSHelper::getFormName('pickup_location_service_type_1'); ?>" value="<?php echo esc_attr(CHBSRequestData::getFromWidget(1,'pickup_location_text')); ?>"/>
                            <input type="hidden" name="<?php CHBSHelper::getFormName('pickup_location_coordinate_service_type_1'); ?>" value="<?php echo esc_attr(CHBSRequestData::getCoordinateFromWidget(1,'pickup_location')); ?>"/>
<?php
            if($this->data['widget_mode']!=1)
            {
?>
                            <!-- <span class="chbs-location-add chbs-meta-icon-plus"></span> -->
<?php
            }
?>
                        </div> 

                        <div class="chbs-form-field chbs-form-field-location-autocomplete">
                            <label><?php esc_html_e('Drop-off location','chauffeur-booking-system'); ?></label>
                            <input type="text" autocomplete="off" name="<?php CHBSHelper::getFormName('dropoff_location_service_type_1'); ?>" value="<?php echo esc_attr(CHBSRequestData::getFromWidget(1,'dropoff_location_text')); ?>"/>
                            <input type="hidden" name="<?php CHBSHelper::getFormName('dropoff_location_coordinate_service_type_1'); ?>" value="<?php echo esc_attr(CHBSRequestData::getCoordinateFromWidget(1,'dropoff_location')); ?>"/>
                        </div>
<?php
            if(in_array(1,$this->data['meta']['transfer_type_enable']))
            {
?>
                        <div class="chbs-form-field" style="display:none;">
                            <label><?php esc_html_e('Transfer type','chauffeur-booking-system'); ?></label>
                            <select name="<?php CHBSHelper::getFormName('transfer_type_service_type_1'); ?>">
<?php
                foreach($this->data['dictionary']['transfer_type'] as $index=>$value)
                {
?>
                                <option value="<?php echo esc_attr($index); ?>" <?php CHBSHelper::selectedIf(CHBSRequestData::getFromWidget(1,'transfer_type'),$index); ?>><?php echo esc_html($value[0]); ?></option>
<?php              
                }
?>                            
                            </select>
                        </div>
<?php
                $class=array('chbs-clear-fix');
                if(CHBSRequestData::getFromWidget(1,'transfer_type')!=3)
                    array_push($class,'chbs-hidden');
?>
                        <div<?php echo CHBSHelper::createCSSClassAttribute($class); ?>>

                            <div class="chbs-form-field chbs-form-field-width-50">
                                <label class="chbs-form-field-label"><?php esc_html_e('Return date','chauffeur-booking-system'); ?></label>
                                <input type="text" autocomplete="off" name="<?php CHBSHelper::getFormName('return_date_service_type_1'); ?>" class="chbs-datepicker" value="<?php echo esc_attr(CHBSRequestData::getFromWidget(1,'return_date')); ?>"/>
                            </div>

                            <div class="chbs-form-field chbs-form-field-width-50">
                                <label><?php esc_html_e('Return time','chauffeur-booking-system'); ?></label>
                                <input type="text" autocomplete="off" name="<?php CHBSHelper::getFormName('return_time_service_type_1'); ?>" class="chbs-timepicker" value="<?php echo esc_attr(CHBSRequestData::getFromWidget(1,'return_time')); ?>"/>
                            </div>

                        </div>                                  
<?php
            }
            
            if((CHBSBookingHelper::isPassengerEnable($this->data['meta'],1,'adult')) || (CHBSBookingHelper::isPassengerEnable($this->data['meta'],1,'children')))
            {
                $class=array(array('chbs-clear-fix'),array('chbs-form-field'));
                
                if(CHBSBookingHelper::isPassengerEnable($this->data['meta'],1))
                     array_push($class[1],'chbs-form-field-width-50');
                
                if($this->data['widget_mode']!=1)
                {
?>
                        <label class="chbs-form-label-group"><?php esc_html_e('Number of passengers','chauffeur-booking-system'); ?></label>
<?php
                }
?>
                        <div<?php echo CHBSHelper::createCSSClassAttribute($class[0]); ?>>
<?php
                if(CHBSBookingHelper::isPassengerEnable($this->data['meta'],1,'adult'))
                {
?>
                            <div<?php echo CHBSHelper::createCSSClassAttribute($class[1]); ?>>
                                <label class="chbs-form-field-label"><?php esc_html_e('Adults','chauffeur-booking-system'); ?></label>
                                <input type="text" maxlength="2" name="<?php CHBSHelper::getFormName('passenger_adult_service_type_1'); ?>" value="<?php echo esc_attr(CHBSRequestData::getFromWidget(1,'passenger_adult')); ?>"/>
                            </div>                       
<?php
                }
                if(CHBSBookingHelper::isPassengerEnable($this->data['meta'],1,'children'))
                {
?>
                            <div<?php echo CHBSHelper::createCSSClassAttribute($class[1]); ?>>
                                <label class="chbs-form-field-label"><?php esc_html_e('Children','chauffeur-booking-system'); ?></label>
                                <input type="text" maxlength="2" name="<?php CHBSHelper::getFormName('passenger_children_service_type_1'); ?>" value="<?php echo esc_attr(CHBSRequestData::getFromWidget(1,'passenger_children')); ?>"/>
                            </div>             
<?php                  
                }                
?>     
                        </div>
<?php    
            }

            if($this->data['meta']['extra_time_enable']==1)
            {
                if($this->data['widget_mode']!=1)
                {
?>
                        <label class="chbs-form-label-group"><?php esc_html_e('Extra options','chauffeur-booking-system'); ?></label>
<?php
                }
?>
                        <div class="chbs-form-field">
                            <label><?php esc_html_e('Extra time','chauffeur-booking-system'); ?></label>
                            <select name="<?php CHBSHelper::getFormName('extra_time_service_type_1'); ?>">
<?php
                for($i=$this->data['meta']['extra_time_range_min'];$i<=$this->data['meta']['extra_time_range_max'];$i+=$this->data['meta']['extra_time_step'])
                {    
?>
                                <option value="<?php echo esc_attr($i); ?>" <?php CHBSHelper::selectedIf(CHBSRequestData::getFromWidget(1,'extra_time'),$i); ?>><?php echo sprintf(esc_html__('%d hour(s)','chauffeur-booking-system'),$i); ?></option>
<?php              
                }
?>
                            </select>
                        </div>    
<?php
            }
?>
                    </div>
<?php
        }
        
        if(in_array(2,$this->data['meta']['service_type_id']))
        {
?>
                    <div id="panel-2">
<?php
            if($this->data['widget_mode']!=1)
            {
?>                        
                        <label class="chbs-form-label-group"><?php esc_html_e('Ride details','chauffeur-booking-system'); ?></label>
<?php
            }
?>
                        <div class="chbs-clear-fix">

                            <div class="chbs-form-field chbs-form-field-width-50">
                                <label class="chbs-form-field-label"><?php esc_html_e('Pickup date','chauffeur-booking-system'); ?></label>
                                <input type="text" autocomplete="off" name="<?php CHBSHelper::getFormName('pickup_date_service_type_2'); ?>" class="chbs-datepicker" value="<?php echo esc_attr(CHBSRequestData::getFromWidget(2,'pickup_date')); ?>"/>
                            </div>

                            <div class="chbs-form-field chbs-form-field-width-50">
                                <label><?php esc_html_e('Pickup time','chauffeur-booking-system'); ?></label>
                                <input type="text" autocomplete="off" name="<?php CHBSHelper::getFormName('pickup_time_service_type_2'); ?>" class="chbs-timepicker" value="<?php echo esc_attr(CHBSRequestData::getFromWidget(2,'pickup_time')); ?>"/>
                            </div>

                        </div>

                        <div class="chbs-form-field chbs-form-field-location-autocomplete">
                            <label><?php esc_html_e('Pickup location','chauffeur-booking-system'); ?></label>
                            <input type="text" autocomplete="off" name="<?php CHBSHelper::getFormName('pickup_location_service_type_2'); ?>" value="<?php echo esc_attr(CHBSRequestData::getFromWidget(2,'pickup_location_text')); ?>"/>
                            <input type="hidden" name="<?php CHBSHelper::getFormName('pickup_location_coordinate_service_type_2'); ?>" value="<?php echo esc_attr(CHBSRequestData::getCoordinateFromWidget(2,'pickup_location')); ?>"/>
                        </div>                                

                        <div class="chbs-form-field">
                            <label><?php esc_html_e('Duration (in hours)','chauffeur-booking-system'); ?></label>
                            <select name="<?php CHBSHelper::getFormName('duration_service_type_2'); ?>">
<?php
            for($i=$this->data['meta']['duration_min'];$i<=$this->data['meta']['duration_max'];$i+=$this->data['meta']['duration_step'])
            {
?>
                                <option value="<?php echo esc_attr($i); ?>" <?php CHBSHelper::selectedIf(CHBSRequestData::getFromWidget(2,'duration'),$i); ?>><?php echo sprintf(esc_html__('%d hour(s)','chauffeur-booking-system'),$i); ?></option>
<?php              
            }
?>
                            </select>
                        </div> 
<?php
            if((CHBSBookingHelper::isPassengerEnable($this->data['meta'],2,'adult')) || (CHBSBookingHelper::isPassengerEnable($this->data['meta'],2,'children')))
            {
                $class=array(array('chbs-clear-fix'),array('chbs-form-field'));
                
                if(CHBSBookingHelper::isPassengerEnable($this->data['meta'],1))
                     array_push($class[1],'chbs-form-field-width-50');
                
                if($this->data['widget_mode']!=1)
                {
?>
                        <label class="chbs-form-label-group"><?php esc_html_e('Number of passengers','chauffeur-booking-system'); ?></label>
<?php
                }
?>
                        <div<?php echo CHBSHelper::createCSSClassAttribute($class[0]); ?>>
<?php
                if(CHBSBookingHelper::isPassengerEnable($this->data['meta'],2,'adult'))
                {
?>
                            <div<?php echo CHBSHelper::createCSSClassAttribute($class[1]); ?>>
                                <label class="chbs-form-field-label"><?php esc_html_e('Adults','chauffeur-booking-system'); ?></label>
                                <input type="text" maxlength="2" name="<?php CHBSHelper::getFormName('passenger_adult_service_type_2'); ?>" value="<?php echo esc_attr(CHBSRequestData::getFromWidget(2,'passenger_adult')); ?>"/>
                            </div>                       
<?php
                }
                if(CHBSBookingHelper::isPassengerEnable($this->data['meta'],2,'children'))
                {
?>
                            <div<?php echo CHBSHelper::createCSSClassAttribute($class[1]); ?>>
                                <label class="chbs-form-field-label"><?php esc_html_e('Children','chauffeur-booking-system'); ?></label>
                                <input type="text" maxlength="2" name="<?php CHBSHelper::getFormName('passenger_children_service_type_2'); ?>" value="<?php echo esc_attr(CHBSRequestData::getFromWidget(2,'passenger_children')); ?>"/>
                            </div>             
<?php                  
                }                
?>     
                        </div>
<?php    
            }

            if($this->data['widget_mode']!=1)
            {
?>
                        <label class="chbs-form-label-group"><?php esc_html_e('Extra options','chauffeur-booking-system'); ?></label>
<?php
            }
?>
                        <div class="chbs-form-field chbs-form-field-location-autocomplete">
                            <label><?php esc_html_e('Drop-off location','chauffeur-booking-system'); ?></label>
                            <input type="text" autocomplete="off" name="<?php CHBSHelper::getFormName('dropoff_location_service_type_2'); ?>" value="<?php echo esc_attr(CHBSRequestData::getFromWidget(2,'dropoff_location_text')); ?>"/>
                            <input type="hidden" name="<?php CHBSHelper::getFormName('dropoff_location_coordinate_service_type_2'); ?>" value="<?php echo esc_attr(CHBSRequestData::getCoordinateFromWidget(2,'dropoff_location')); ?>"/>
                        </div>
                        
                    </div>
<?php
        }
        
        if(in_array(3,$this->data['meta']['service_type_id']))
        {
?>
                    <div id="panel-3">
<?php
            if($this->data['widget_mode']!=1)
            {
?>                        
                        <label class="chbs-form-label-group"><?php esc_html_e('Ride details','chauffeur-booking-system'); ?></label>
<?php
            }
?>
                        <div class="chbs-clear-fix">

                            <div class="chbs-form-field chbs-form-field-width-50">
                                <label class="chbs-form-field-label"><?php esc_html_e('Pickup date','chauffeur-booking-system'); ?></label>
                                <input type="text" name="<?php CHBSHelper::getFormName('pickup_date_service_type_3'); ?>" class="chbs-datepicker" value="<?php echo esc_attr(CHBSRequestData::getFromWidget(3,'pickup_date')); ?>"/>
                            </div>

                            <div class="chbs-form-field chbs-form-field-width-50">
                                <label><?php esc_html_e('Pickup time','chauffeur-booking-system'); ?></label>
                                <input type="text" name="<?php CHBSHelper::getFormName('pickup_time_service_type_3'); ?>"  class="chbs-timepicker" value="<?php echo esc_attr(CHBSRequestData::getFromWidget(3,'pickup_time')); ?>"/>
                            </div>

                        </div>

                        <div class="chbs-form-field">
                            <label><?php esc_html_e('Route','chauffeur-booking-system'); ?></label>
                            <select name="<?php CHBSHelper::getFormName('route_service_type_3'); ?>">
<?php
            foreach($this->data['dictionary']['route'] as $index=>$value)
            {
?>
                                <option value="<?php echo esc_attr($index); ?>" data-coordinate="<?php echo esc_attr(json_encode($value['meta']['coordinate'])); ?>" <?php CHBSHelper::selectedIf(CHBSRequestData::getFromWidget(3,'route_id'),$index); ?>><?php echo esc_html(get_the_title($index)); ?></option>
<?php
            }
?>
                            </select>
                            <input type="hidden" name="<?php CHBSHelper::getFormName('route_coordinate_service_type_3'); ?>"/>
                        </div>                                
<?php
            if(in_array(3,$this->data['meta']['transfer_type_enable']))
            {
?>
      
                        <div class="chbs-form-field" style="display:none;">
                            <label><?php esc_html_e('Transfer type','chauffeur-booking-system'); ?></label>
                            <select name="<?php CHBSHelper::getFormName('transfer_type_service_type_3'); ?>">
<?php
                foreach($this->data['dictionary']['transfer_type'] as $index=>$value)
                {
?>
                                <option value="<?php echo esc_attr($index); ?>" <?php CHBSHelper::selectedIf(CHBSRequestData::getFromWidget(3,'transfer_type'),$index); ?>><?php echo esc_html($value[0]); ?></option>
<?php              
                }
?>                            
                            </select>
                        </div>
<?php
                $class=array('chbs-clear-fix');
                if(CHBSRequestData::getFromWidget(3,'transfer_type')!=3)
                    array_push($class,'chbs-hidden');
?>
                        <div<?php echo CHBSHelper::createCSSClassAttribute($class); ?>>

                            <div class="chbs-form-field chbs-form-field-width-50">
                                <label class="chbs-form-field-label"><?php esc_html_e('Return date','chauffeur-booking-system'); ?></label>
                                <input type="text" name="<?php CHBSHelper::getFormName('return_date_service_type_3'); ?>" class="chbs-datepicker" value="<?php echo esc_attr(CHBSRequestData::getFromWidget(3,'return_date')); ?>"/>
                            </div>

                            <div class="chbs-form-field chbs-form-field-width-50">
                                <label><?php esc_html_e('Return time','chauffeur-booking-system'); ?></label>
                                <input type="text" name="<?php CHBSHelper::getFormName('return_time_service_type_3'); ?>" class="chbs-timepicker" value="<?php echo esc_attr(CHBSRequestData::getFromWidget(3,'return_time')); ?>"/>
                            </div>

                        </div>                                  
<?php
            }

            if((CHBSBookingHelper::isPassengerEnable($this->data['meta'],3,'adult')) || (CHBSBookingHelper::isPassengerEnable($this->data['meta'],3,'children')))
            {
                $class=array(array('chbs-clear-fix'),array('chbs-form-field'));
                
                if(CHBSBookingHelper::isPassengerEnable($this->data['meta'],1))
                     array_push($class[1],'chbs-form-field-width-50');
                
                if($this->data['widget_mode']!=1)
                {
?>
                        <label class="chbs-form-label-group"><?php esc_html_e('Number of passengers','chauffeur-booking-system'); ?></label>
<?php
                }
?>
                        <div<?php echo CHBSHelper::createCSSClassAttribute($class[0]); ?>>
<?php
                if(CHBSBookingHelper::isPassengerEnable($this->data['meta'],3,'adult'))
                {
?>
                            <div<?php echo CHBSHelper::createCSSClassAttribute($class[1]); ?>>
                                <label class="chbs-form-field-label"><?php esc_html_e('Adults','chauffeur-booking-system'); ?></label>
                                <input type="text" maxlength="2" name="<?php CHBSHelper::getFormName('passenger_adult_service_type_3'); ?>" value="<?php echo esc_attr(CHBSRequestData::getFromWidget(3,'passenger_adult')); ?>"/>
                            </div>                       
<?php
                }
                if(CHBSBookingHelper::isPassengerEnable($this->data['meta'],3,'children'))
                {
?>
                            <div<?php echo CHBSHelper::createCSSClassAttribute($class[1]); ?>>
                                <label class="chbs-form-field-label"><?php esc_html_e('Children','chauffeur-booking-system'); ?></label>
                                <input type="text" maxlength="2" name="<?php CHBSHelper::getFormName('passenger_children_service_type_3'); ?>" value="<?php echo esc_attr(CHBSRequestData::getFromWidget(3,'passenger_children')); ?>"/>
                            </div>             
<?php                  
                }                
?>     
                        </div>
<?php    
            }
            
            if($this->data['meta']['extra_time_enable']==1)
            {
                if($this->data['widget_mode']!=1)
                {
?>
                        <label class="chbs-form-label-group"><?php esc_html_e('Extra options','chauffeur-booking-system'); ?></label>
<?php
                }
?>
                        <div class="chbs-form-field">
                            <label><?php esc_html_e('Extra time','chauffeur-booking-system'); ?></label>
                            <select name="<?php CHBSHelper::getFormName('extra_time_service_type_3'); ?>">
<?php
                for($i=$this->data['meta']['extra_time_range_min'];$i<=$this->data['meta']['extra_time_range_max'];$i+=$this->data['meta']['extra_time_step'])
                {
?>
                                <option value="<?php echo esc_attr($i); ?>" <?php CHBSHelper::selectedIf(CHBSRequestData::getFromWidget(3,'extra_time'),$i); ?>><?php echo sprintf(esc_html__('%d hour(s)','chauffeur-booking-system'),$i); ?></option>
<?php              
                }
?>
                            </select>
                        </div>                        
<?php
            }
?>      
                    </div>
<?php
        }
?>          
                </div>    

            </div>
<?php
        if($this->data['widget_mode']!=1)
        {
?>
            <div class="chbs-layout-column-right">
                <div class="chbs-google-map">
                    <div id="chbs_google_map"></div>
                </div>
                <div class="chbs-ride-info chbs-box-shadow">
                    <div>
                        <span class="chbs-meta-icon-route"></span>
                        <span><?php esc_html_e('Total distance','chauffeur-booking-system'); ?></span>
                        <span>
                            <span>0</span>
                            <span><?php echo esc_html($this->data['length_unit'][1]); ?></span>
                        </span>
                    </div>
                    <div>
                        <span class="chbs-meta-icon-clock"></span>
                        <span><?php esc_html_e('Total time','chauffeur-booking-system'); ?></span>
                        <span>
                            <span>0</span>
                            <span><?php esc_html_e('h','chauffeur-booking-system'); ?></span>
                            <span>0</span>
                            <span><?php esc_html_e('m','chauffeur-booking-system'); ?></span>
                        </span>
                    </div>                    
                </div>
            </div>
<?php
        }
?>
        </div>
<?php
        if($this->data['widget_mode']==1)
        {
?>
        <div class="chbs-clear-fix">
            <a href="#" class="chbs-button chbs-button-style-1 chbs-button-widget-submit">
                <?php esc_html_e('Book now','chauffeur-booking-system'); ?>
            </a>
        </div>
<?php
        }
        else
        {
?>
        <div class="chbs-clear-fix chbs-main-content-navigation-button">
            <a href="#" class="chbs-button chbs-button-style-1 chbs-button-step-next">
                <?php echo esc_html($this->data['step']['dictionary'][1]['button']['next']); ?>
                <span class="chbs-meta-icon-arrow-horizontal-large"></span>
            </a> 
        </div>
<?php
        }