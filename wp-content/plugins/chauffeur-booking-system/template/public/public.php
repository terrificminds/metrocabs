<?php    
        global $post;
        
        $Validation=new CHBSValidation();
        
        $class=array('chbs-main','chbs-booking-form-id-'.$this->data['booking_form_post_id'],'chbs-clear-fix','chbs-hidden');

        $widgetServiceTypeId=1;
?>
        <div<?php echo CHBSHelper::createCSSClassAttribute($class); ?> id="<?php echo esc_attr($this->data['booking_form_html_id']); ?>">

            <form name="chbs-form">
<?php
        if($this->data['widget_mode']!=1)
        {
?>
                <div class="chbs-main-navigation-default chbs-clear-fix" data-step-count="<?php echo count($this->data['step']['dictionary']); ?>">
                    <ul class="chbs-list-reset">
<?php
            foreach($this->data['step']['dictionary'] as $index=>$value)
            {
                $class=array();
                if($index==1) array_push($class,'chbs-state-selected');
?>           
                        <li data-step="<?php echo esc_attr($index); ?>"<?php echo CHBSHelper::createCSSClassAttribute($class); ?> >
                            <div></div>
                            <a href="#">
                                <span>
                                    <span><?php echo esc_html($value['navigation']['number']); ?></span>
                                    <span class="chbs-meta-icon-tick"></span>
                                </span>
                                <span><?php echo esc_html($value['navigation']['label']); ?></span>
                            </a>
                        </li>       
<?php          
            }
?>
                    </ul>
                </div>
                
                <div class="chbs-main-navigation-responsive chbs-box-shadow chbs-clear-fix">
                    <div class="chbs-form-field">
                        <select name="<?php CHBSHelper::getFormName('navigation_responsive'); ?>" data-value="1">
<?php
            foreach($this->data['step']['dictionary'] as $index=>$value)
            {
?>            
                            <option value="<?php echo esc_attr($index); ?>">
                                <?php echo esc_html($value['navigation']['number'].'. '.$value['navigation']['label']); ?>
                            </option>       
<?php          
            }          
?>                
                        </select>
                    </div>
                </div>
<?php
        }
?>
                <div class="chbs-main-content chbs-clear-fix">
<?php
        $step=$this->data['widget_mode']==1 ? 1 : 5;

        for($i=1;$i<=$step;$i++)
        {
?> 
                    <div class="chbs-main-content-step-<?php echo $i; ?>">
<?php
            $Template=new CHBSTemplate($this->data,PLUGIN_CHBS_TEMPLATE_PATH.'public/public-step-'.$i.'.php');
            echo $Template->output();
?>
                    </div>
<?php
        }
?>
                </div>
<?php
        if($this->data['widget_mode']!=1)
        {
?>
                <input type="hidden" name="action" data-value=""/>
                
                <input type="hidden" name="<?php CHBSHelper::getFormName('step') ?>" data-value="1"/>
                <input type="hidden" name="<?php CHBSHelper::getFormName('step_request') ?>" data-value="1"/>
                
                <input type="hidden" name="<?php CHBSHelper::getFormName('payment_id') ?>" data-value="0"/>
                <input type="hidden" name="<?php CHBSHelper::getFormName('vehicle_id') ?>" data-value="<?php echo (int)$this->data['meta']['vehicle_id_default']; ?>"/>
                
                <input type="hidden" name="<?php CHBSHelper::getFormName('booking_extra_id') ?>" data-value="0"/>
                
                <input type="hidden" name="<?php CHBSHelper::getFormName('distance_map') ?>" data-value="0"/>
                <input type="hidden" name="<?php CHBSHelper::getFormName('duration_map') ?>" data-value="0"/>

                <input type="hidden" name="<?php CHBSHelper::getFormName('distance_sum') ?>" data-value="0"/>
                <input type="hidden" name="<?php CHBSHelper::getFormName('duration_sum') ?>" data-value="0"/>
                
                <input type="hidden" name="<?php CHBSHelper::getFormName('base_location_distance') ?>" data-value="0"/>
                <input type="hidden" name="<?php CHBSHelper::getFormName('base_location_duration') ?>" data-value="0"/>
                
                <input type="hidden" name="<?php CHBSHelper::getFormName('base_location_return_distance') ?>" data-value="0"/>
                <input type="hidden" name="<?php CHBSHelper::getFormName('base_location_return_duration') ?>" data-value="0"/>
<?php
        }
?>
                <input type="hidden" name="<?php CHBSHelper::getFormName('booking_form_id') ?>" data-value="<?php echo esc_attr($this->data['booking_form_post_id']); ?>"/>

                <input type="hidden" name="<?php CHBSHelper::getFormName('service_type_id') ?>" data-value="<?php echo esc_attr((int)CHBSRequestData::get('service_type_id')==0 ? $this->data['meta']['service_type_id_default'] : (int)CHBSRequestData::get('service_type_id')); ?>"/>

                <input type="hidden" name="<?php CHBSHelper::getFormName('post_id') ?>" data-value="<?php echo esc_attr($post->ID); ?>"/>
                
            </form>
<?php
        if($this->data['widget_mode']!=1)
        {
            if(isset($this->data['dictionary']['payment']))
            {
                if(array_key_exists(3,$this->data['dictionary']['payment']))
                {
                    $PaymentPaypal=new CHBSPaymentPaypal();
                    echo $PaymentPaypal->createPaymentForm($post->ID,$this->data['meta']['payment_paypal_email_address'],$this->data['meta']['payment_paypal_sandbox_mode_enable']);
                }
            }
        }
?>
            <div id="chbs-preloader"></div>
            <div id="chbs-preloader-start"></div>
            
        </div>

        <script type="text/javascript">
            jQuery(document).ready(function($)
            {
                var bookingForm=$('#<?php echo esc_attr($this->data['booking_form_html_id']); ?>').chauffeurBookingForm(
                {
                    ajax_url                                                    :   '<?php echo $this->data['ajax_url']; ?>',
                    length_unit                                                 :   <?php echo CHBSOption::getOption('length_unit'); ?>,
                    time_format                                                 :   '<?php echo CHBSOption::getOption('time_format'); ?>',
                    date_format                                                 :   '<?php echo CHBSOption::getOption('date_format'); ?>',
                    date_format_js                                              :   '<?php echo CHBSJQueryUIDatePicker::convertDateFormat(CHBSOption::getOption('date_format')); ?>',
                    message                                                     :
                    {
                        designate_route_error                                   :   '<?php esc_html_e('It is not possible to create a route between chosen points.','chauffeur-booking-system'); ?>'
                    },
                    text                                                        :
                    {
                        unit_length_short                                       :   '<?php esc_html_e('km','chauffeur-booking-system')  ?>',
                        unit_time_hour_short                                    :   '<?php esc_html_e('h','chauffeur-booking-system')  ?>',
                        unit_time_minute_short                                  :   '<?php esc_html_e('h','chauffeur-booking-system')  ?>',
                    },
                    date_exclude                                                :   <?php echo json_encode($this->data['meta']['date_exclude']); ?>,
                    business_hour                                               :   <?php echo json_encode($this->data['meta']['business_hour']); ?>,
                    booking_period_from                                         :   <?php echo (($Validation->isEmpty($this->data['meta']['booking_period_from']) || $this->data['meta']['booking_period_type']!=1)  ? 'null' : (int)$this->data['meta']['booking_period_from']) ?>,
                    booking_period_to                                           :   <?php echo (($Validation->isEmpty($this->data['meta']['booking_period_to']) || $this->data['meta']['booking_period_to']!=1) ? 'null' : (int)$this->data['meta']['booking_period_to']) ?>,
                    timepicker_step                                             :   <?php echo $this->data['meta']['timepicker_step']; ?>,
                    summary_sidebar_sticky_enable                               :   <?php echo $this->data['meta']['summary_sidebar_sticky_enable']; ?>,
                    waypoint_country_available                                  :   <?php echo json_encode($this->data['meta']['waypoint_country_available']); ?>,
                    waypoint_pickup_area_available                              :   <?php echo ($Validation->isEmpty($this->data['meta']['waypoint_pickup_area_available']) ? "''" : $this->data['meta']['waypoint_pickup_area_available']); ?>,
                    waypoint_dropoff_area_available                             :   <?php echo ($Validation->isEmpty($this->data['meta']['waypoint_dropoff_area_available']) ? "''" : $this->data['meta']['waypoint_dropoff_area_available']); ?>,
                    gooogle_map_option                                          :
                    {
                        route_avoid                                             :   <?php echo json_encode($this->data['meta']['google_map_route_avoid']); ?>,
                        draggable                                               :
                        {
                            enable                                              :   <?php echo (int)$this->data['meta']['google_map_draggable_enable']; ?>
                        },
                        traffic_layer                                           :
                        {
                            enable                                              :   <?php echo (int)$this->data['meta']['google_map_traffic_layer_enable']; ?>
                        },
                        scrollwheel                                             :
                        {
                            enable                                              :   <?php echo (int)$this->data['meta']['google_map_scrollwheel_enable']; ?>
                        },
                        map_control                                             :
                        {
                            enable                                              :   <?php echo (int)$this->data['meta']['google_map_map_type_control_enable']; ?>,
                            id                                                  :   '<?php echo $this->data['meta']['google_map_map_type_control_id']; ?>',
                            style                                               :   '<?php echo $this->data['meta']['google_map_map_type_control_style']; ?>',
                            position                                            :   '<?php echo $this->data['meta']['google_map_map_type_control_position']; ?>'
                        },
                        zoom_control                                            :
                        {
                            enable                                              :   <?php echo (int)$this->data['meta']['google_map_zoom_control_enable']; ?>,
                            style                                               :   '<?php echo $this->data['meta']['google_map_zoom_control_style']; ?>',
                            position                                            :   '<?php echo $this->data['meta']['google_map_zoom_control_position']; ?>',
                            level                                               :   <?php echo (int)$this->data['meta']['google_map_zoom_control_level']; ?>                            
                        },
                        default_location                                        :
                        {
                            type                                                :   <?php echo (int)$this->data['meta']['google_map_default_location_type']; ?>,
                            coordinate                                          :
                            {
                                lat                                             :   '<?php echo $this->data['meta']['google_map_default_location_fixed_coordinate_lat']; ?>',
                                lng                                             :   '<?php echo $this->data['meta']['google_map_default_location_fixed_coordinate_lng']; ?>'
                            }
                        },
                    },
                    base_location                                               :
                    {
                        coordinate                                              :
                        {
                            lat                                                 :   '<?php echo $this->data['meta']['base_location_coordinate_lat']; ?>',
                            lng                                                 :   '<?php echo $this->data['meta']['base_location_coordinate_lng']; ?>'
                        }
                    },
                    fixed_location                                              :
                    {
                        1                                                       :
                        {
                            pickup                                              :
                            {
                                name                                            :   '<?php echo $this->data['meta']['location_fixed_pickup_service_type_1']; ?>',
                                lat                                             :   '<?php echo $this->data['meta']['location_fixed_pickup_service_type_1_coordinate_lat']; ?>',
                                lng                                             :   '<?php echo $this->data['meta']['location_fixed_pickup_service_type_1_coordinate_lng']; ?>'
                            },
                            dropoff                                             :
                            {
                                name                                            :   '<?php echo $this->data['meta']['location_fixed_dropoff_service_type_1']; ?>',
                                lat                                             :   '<?php echo $this->data['meta']['location_fixed_dropoff_service_type_1_coordinate_lat']; ?>',
                                lng                                             :   '<?php echo $this->data['meta']['location_fixed_dropoff_service_type_1_coordinate_lng']; ?>'
                            }
                        },
                        2                                                       :
                        {
                            pickup                                              :
                            {
                                name                                            :   '<?php echo $this->data['meta']['location_fixed_pickup_service_type_2']; ?>',
                                lat                                             :   '<?php echo $this->data['meta']['location_fixed_pickup_service_type_2_coordinate_lat']; ?>',
                                lng                                             :   '<?php echo $this->data['meta']['location_fixed_pickup_service_type_2_coordinate_lng']; ?>'
                            },
                            dropoff                                             :
                            {
                                name                                            :   '<?php echo $this->data['meta']['location_fixed_dropoff_service_type_2']; ?>',
                                lat                                             :   '<?php echo $this->data['meta']['location_fixed_dropoff_service_type_2_coordinate_lat']; ?>',
                                lng                                             :   '<?php echo $this->data['meta']['location_fixed_dropoff_service_type_2_coordinate_lng']; ?>'
                            }
                        }
                    },
                    widget                                                      :
                    {
                        mode                                                    :   <?php echo (int)$this->data['widget_mode']; ?>,
                        booking_form_url                                        :   '<?php echo $this->data['widget_booking_form_url']; ?>'
                    },
                    rtl_mode                                                    :   <?php echo (int)is_rtl(); ?> ,
                    scroll_to_booking_extra_after_select_vehicle_enable         :   <?php echo (int)$this->data['meta']['scroll_to_booking_extra_after_select_vehicle_enable']; ?>,
                    current_date                                                :   '<?php echo date('d-m-Y'); ?>',
                    current_time                                                :   '<?php echo date('H:i'); ?>'
               });
               bookingForm.setup();
            });
        </script>
            