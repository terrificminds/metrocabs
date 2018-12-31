/******************************************************************************/
/******************************************************************************/

;(function($,doc,win) 
{
	"use strict";
	
	var ChauffeurBookingForm=function(object,option)
	{
		/**********************************************************************/

        var $this=$(object);
		
		var $optionDefault;
		var $option=$.extend($optionDefault,option);
        
        var $marker=[];
        
        var $googleMap;
        
        var $directionsRenderer;
        
        var $directionsService;
        var $directionsServiceResponse;
        
        var $startLocation;
        
        var $googleMapHeightInterval;

        var $self=this;

        /**********************************************************************/
        
        this.setup=function()
        {
            $self.e('select,input[type="hidden"]').each(function()
            {
                if($(this)[0].hasAttribute('data-value'))
                    $(this).val($(this).attr('data-value'));
            });
            
            $self.init();
        };
            
        /**********************************************************************/
        
        this.init=function()
        {
            var helper=new Helper();
            
            if(helper.isMobile())
            {
                $self.e('input[name="chbs_pickup_date_service_type_1"]').attr('readonly','readonly');
                $self.e('input[name="chbs_pickup_date_service_type_2"]').attr('readonly','readonly');
                $self.e('input[name="chbs_pickup_date_service_type_3"]').attr('readonly','readonly');
                $self.e('input[name="chbs_return_date_service_type_1"]').attr('readonly','readonly');
                $self.e('input[name="chbs_return_date_service_type_3"]').attr('readonly','readonly');
            }
            
            /***/
            
            var fixedLocation={1:['pickup','dropoff'],2:['pickup','dropoff']};
            
            for(var i in fixedLocation)
            {
                for(var j in fixedLocation[i])
                {
                    var k=fixedLocation[i][j];
                    
                    var field=$self.e('input[name="chbs_'+k+'_location_coordinate_service_type_'+i+'"]');
                    
                    if(field.length!==1) continue;
                    if((helper.isEmpty($option.fixed_location[i][k].name)) || (helper.isEmpty($option.fixed_location[i][k].lat)) || (helper.isEmpty($option.fixed_location[i][k].lng))) continue;
                    
                    var text=field.prev('input[type="text"]');
                    text.val($option.fixed_location[i][k].name).attr('readonly','readonly');
                    
                    var placeData=
                    {
                        lat                                                     :   $option.fixed_location[i][k].lat,
                        lng                                                     :   $option.fixed_location[i][k].lng,
                        formatted_address                                       :   $option.fixed_location[i][k].name
                    };

                    field.val(JSON.stringify(placeData));

                    $self.googleMapSetAddress(field,function()
                    {
                        $self.googleMapCreate();
                        $self.googleMapCreateRoute();                    
                    });
                }
            }
           
            /***/
            
            $(window).resize(function() 
			{
                try
                {
                    $self.e('select').selectmenu('close');
                }
                catch(e) {}
                
                try
                {
                    $self.e('.chbs-datepicker').datepicker('hide');
                }
                catch(e) {}
                
                try
                {
                    $self.e('.chbs-timepicker').timepicker('hide');
                }
                catch(e) {}
                
                try
                {
                    $self.e('.ui-timepicker-wrapper').css({opacity:0});
                }
                catch(e) {}
                
                try
                {
                    var currCenter=$googleMap.getCenter();
                    google.maps.event.trigger($googleMap,'resize');
                    $googleMap.setCenter(currCenter);
                }
                catch(e) {}
			});
            
            $self.setWidthClass();
            
            /***/
            
            var active=1;
            var panel=$self.e('.chbs-tab>ul').children('li[data-id="'+parseInt($self.e('[name="chbs_service_type_id"]').val())+'"]');
            
            if(panel.length===1) active=panel.index();
            
            $self.e('.chbs-tab').tabs(
            {
                activate                                                        :   function(event,ui)
                {
                    $self.googleMapReInit();
                    
                    var serviceTypeId=$self.getServiceTypeId();
                    $self.setServiceTypeId(serviceTypeId);
                    
                    $self.googleMapCreate();
                    $self.googleMapCreateRoute();
                },
                active                                                          :   active
            });
                          
            /***/
            
            $self.e('.chbs-main-navigation-default a').on('click',function(e)
            {
                e.preventDefault();
                
                var navigation=parseInt($(this).parent('li').data('step'),10);
                var step=parseInt($self.e('input[name="chbs_step"]').val(),10);
                
                if(navigation-step===0) return;
                
                $self.goToStep(navigation-step);
            });
            
            $self.e('.chbs-button-step-next').on('click',function(e)
            {
                e.preventDefault();
                $self.goToStep(1);
            });
            
            $self.e('.chbs-button-step-prev').on('click',function(e)
            {
                e.preventDefault();
                $self.goToStep(-1);
            });
            
             /***/
            
            $self.e('.chbs-form-field').on('click',function(e)
            {
                e.preventDefault();
                if(($(e.target).hasClass('chbs-location-add')) || ($(e.target).hasClass('chbs-location-remove'))) return;
                $(this).find(':input').focus(); 
                
                var select=$(this).find('select');
                
                if(select.length)
                    select.selectmenu('open');
            });
            
             /***/
            
            $self.e('.chbs-location-add').on('click',function(e)
            {
                e.preventDefault();

                var field=$(this).parent('.chbs-form-field:first');
                var newField=$self.e('.chbs-form-field-location-autocomplete.chbs-hidden').clone(true,true);

                newField.insertAfter(field);
                newField.removeClass('chbs-hidden');
                
                newField.find(':input').focus();
                
                $self.googleMapAutocompleteCreate(newField.find('input[type="text"]'));
            });

            $self.e('.chbs-location-remove').on('click',function(e)
            {
                e.preventDefault();
                $(this).parent('.chbs-form-field:first').remove();

                $self.googleMapCreate();
                $self.googleMapCreateRoute();
            });       

            $self.e('.chbs-form-field-location-autocomplete input[type="text"]').each(function()
            {
                $self.googleMapAutocompleteCreate($(this));
            });
                       
            /***/
            
            $self.e('.chbs-payment>li>a').on('click',function(e)
            {
                e.preventDefault();
                
                $(this).parents('.chbs-payment').find('li>a').removeClass('chbs-state-selected');
                $(this).addClass('chbs-state-selected');
                
                $self.getGlobalNotice().addClass('chbs-hidden');
                
                $self.e('input[name="chbs_payment_id"]').val($(this).attr('data-payment-id'));
            });
            
            $self.e('>*').on('click','.chbs-form-checkbox',function(e)
            {
                var text=$(this).next('input[type="hidden"]');
                var value=parseInt(text.val())===1 ? 0 : 1;
                
                if(value===1) $(this).addClass('chbs-state-selected');
                else $(this).removeClass('chbs-state-selected');
                
                $(this).next('input[type="hidden"]').on('change',function(e)
                { 
                    var value=parseInt($(this).val())===1 ? 1 : 0;
                    var section=$(this).parents('.chbs-clear-fix:first').nextAll('div:first');

                    if(value===0) section.addClass('chbs-hidden');
                    else section.removeClass('chbs-hidden');

                    $(window).scroll();
                });
                
                text.val(value).trigger('change');
            });
            
            /***/
            
            $self.e('.chbs-booking-extra').on('click','.chbs-booking-extra-list .chbs-button.chbs-button-style-2',function(e)
            {
                e.preventDefault();
                $(this).toggleClass('chbs-state-selected'); 
                
                var data=[];
                $self.e('.chbs-booking-extra-list .chbs-button.chbs-button-style-2').each(function()
                {
                    if($(this).hasClass('chbs-state-selected'))
                        data.push($(this).attr('data-id'));
                });
                
                $self.e('input[name="chbs_booking_extra_id"]').val(data.join(','));
                
                $self.createSummaryPriceElement();
            });
            
            /***/
            
            $self.e('.chbs-booking-extra').on('blur','.chbs-booking-extra-list input[type="text"]',function(e)
            {
               if(isNaN($(this).val())) $(this).val(1);
               $self.createSummaryPriceElement();
            });
            
            $self.e('.chbs-booking-extra').on('click','.chbs-booking-extra-list .chbs-column-2',function()
            {
                $(this).find('input[type="text"]').select();
            });
            
            /***/
            
            $self.e('.chbs-main-content-step-2').on('click','.chbs-vehicle-list .chbs-button.chbs-button-style-2',function(e)
            {
                e.preventDefault();
                
                if($(this).hasClass('chbs-state-selected')) return;
                
                $self.e('.chbs-vehicle-list .chbs-button.chbs-button-style-2').removeClass('chbs-state-selected');
                
                $(this).addClass('chbs-state-selected');
                
                $self.e('input[name="chbs_vehicle_id"]').val(parseInt($(this).parents('.chbs-vehicle').attr('data-id'),10));
                
                $self.getGlobalNotice().addClass('chbs-hidden');
                
                $self.createSummaryPriceElement();
                
                if(parseInt($option.scroll_to_booking_extra_after_select_vehicle_enable)===1)
                {
                    var header=$self.e('.chbs-booking-extra-header');
                    if(header.length===1) $.scrollTo(header,200,{offset:-50});
                }
            });
            
            /***/
            
            $self.e('.chbs-main-content-step-2').on('click','.chbs-vehicle .chbs-vehicle-content>.chbs-vehicle-content-meta a',function(e)
            {
                e.preventDefault();
                
                $(this).toggleClass('chbs-state-selected');
                
                var section=$(this).parents('.chbs-vehicle:first').find('.chbs-vehicle-content-description');
                
                var height=parseInt(section.children('div').actual('outerHeight',{includeMargin:true}),10);
                
                if(section.hasClass('chbs-state-open'))
                {
                    section.animate({height:0},150,function()
                    {
                        section.removeClass('chbs-state-open');
                        $(window).scroll();
                    });                      
                }
                else
                {
                    section.animate({height:height},150,function()
                    {
                        section.addClass('chbs-state-open');
                        $(window).scroll();
                    });                        
                }
            });
            
            /***/
            
            $self.e('.chbs-main-content-step-4').on('click','.chbs-summary .chbs-summary-header a',function(e)
            {
                e.preventDefault();
                $self.goToStep(parseInt($(this).attr('data-step'),10)-4);
            });
            
            /***/
            
            $self.e('.chbs-main-content-step-4').on('click','.chbs-coupon-code-section a',function(e)
            {
                e.preventDefault();
                
                $self.setAction('coupon_code_check');
       
                $self.post($self.e('form[name="chbs-form"]').serialize(),function(response)
                {
                    $self.e('.chbs-summary-price-element').replaceWith(response.html);
                    
                    var object=$self.e('.chbs-coupon-code-section');
                    
                    object.qtip(
                    {
                        show            :	
                        { 
                            target      :	$(this) 
                        },
                        style           :	
                        { 
                            classes     :	(response.error===1 ? 'chbs-qtip chbs-qtip-error' : 'chbs-qtip chbs-qtip-success')
                        },
                        content         : 	
                        { 
                            text        :	response.message 
                        },
                        position        : 	
                        { 
                            my          :	($option.is_rtl ? 'bottom right' : 'bottom left'),
                            at          :	($option.is_rtl ? 'top right' : 'top left'),
                            container   :   object.parent()
                        }
                    }).qtip('show');	
                });
            });
            
            /***/
            
            var minDate=$option.booking_period_from;
            var maxDate=$option.booking_period_to;
            
            if(minDate===null) minDate=0;
            if(maxDate!==null) maxDate+=minDate;
            
            $self.e('.chbs-datepicker').datepicker(
            {
                autoSize                                                        :   true,
                minDate                                                         :   minDate,
                maxDate                                                         :   maxDate,
                dateFormat                                                      :   $option.date_format_js,
                beforeShowDay                                                   :   function(date)
                {
                    var helper=new Helper();
                    
                    date=$.datepicker.formatDate('dd-mm-yy',date);
                    
                    for(var i in $option.date_exclude)
                    {
                        var r=helper.compareDate([date,$option.date_exclude[i].start,$option.date_exclude[i].stop]);
                        if(r) return([false,'','']);
                    }
                    
                    /***/
                    
                    var sDate=date.split('-');
                    var date=new Date(sDate[2],sDate[1]-1,sDate[0]);
                    
                    var dayWeek=parseInt(date.getUTCDay(),10)+1;
               
                    if((!$option.business_hour[dayWeek].start) || (!$option.business_hour[dayWeek].stop)) 
                        return([false,'','']);
                    
                    /***/
                    
                    return([true,'','']);
                },
                onSelect                                                        :   function(date)
                {
                    var dateField=$(this);
                                
                    var prefix=dateField.attr('name').indexOf('pickup')>-1 ? 'pickup' : 'return';
                    
                    var timeField=$self.e('input[name="chbs_'+prefix+'_time_service_type_'+$self.getServiceTypeId()+'"]');
                    
                    timeField.timepicker(
                    { 
                        showOn                                                  :   [],
                        showOnFocus                                             :   false,
                        timeFormat                                              :   $option.time_format,
                        step                                                    :   $option.timepicker_step,
                        disableTouchKeyboard                                    :   true
                    });

                    var dayWeek=parseInt(dateField.datepicker('getDate').getUTCDay(),10)+1;

                    if(new String(typeof($option.business_hour[dayWeek]))!=='undefined')
                    {
                        timeField.timepicker('option','minTime',$option.business_hour[dayWeek].start);
                        timeField.timepicker('option','maxTime',$option.business_hour[dayWeek].stop);                        
                    }
                    else
                    {
                        timeField.timepicker('option','minTime','');
                        timeField.timepicker('option','maxTime','');                            
                    }
                    
                    timeField.val('').timepicker('show');
                    timeField.blur();

                    $self.setTimepicker(timeField);
                }
            });
            
            $this.on('focusin','.chbs-timepicker',function()
			{
                var helper=new Helper();
                
                var prefix=$(this).attr('name').indexOf('pickup')>-1 ? 'pickup' : 'return';
                    
                var field=$self.e('input[name="chbs_'+prefix+'_date_service_type_'+$self.getServiceTypeId()+'"]');
                
                if(helper.isEmpty(field.val()))
                {
                    $(this).timepicker('remove');
                    field.click();
                    return;
                }
                else
                {
                    if(helper.isEmpty($(this).val()))
                        $(this).timepicker('show');
                }
            });
            
            /***/
            
            $self.createSelectMenu();
                 
            /***/
            
            $self.e('.chbs-booking-extra').on('blur','.chbs-booking-extra-list input[type="text"]',function()
            {
                if(!$(this)[0].hasAttribute('data-quantity-max')) return;
                
                var value=$(this).val();
                
                if(isNaN(value)) value=1;
                
                value=parseInt(value,10);
                
                if(value>parseInt($(this).attr('data-quantity-max'),10))
                    $(this).val($(this).attr('data-quantity-max'));
                
                $self.createSummaryPriceElement();
            });
            
            $self.e('.chbs-form-field').has('select').css({cursor:'pointer'});
            
            /***/
            
            $self.e('.chbs-main-content-step-3').on('click','.chbs-button-sign-up',function(e)
            {
                e.preventDefault();
                
                $self.e('.chbs-client-form-sign-up').removeClass('chbs-hidden');
                $self.e('input[name="chbs_client_account"]').val(1);
            });
            
            /***/
            
            $self.e('.chbs-button-widget-submit').on('click',function(e)
            {
                e.preventDefault();
               
                var helper=new Helper();
                
                var data={};
                
                data.service_type_id=$self.getServiceTypeId();
                
                data.pickup_date=$self.e('[name="chbs_pickup_date_service_type_'+data.service_type_id+'"]').val();
                data.pickup_time=$self.e('[name="chbs_pickup_time_service_type_'+data.service_type_id+'"]').val();
                
                if($.inArray($self.getServiceTypeId(),[1,2])>-1)
                {
                    var coordinate=$self.e('[name="chbs_pickup_location_coordinate_service_type_'+data.service_type_id+'"]').val();
                    if(!helper.isEmpty(coordinate))
                    {
                        var json=JSON.parse(coordinate);
                        data.pickup_location_lat=json.lat;
                        data.pickup_location_lng=json.lng;
                        data.pickup_location_address=json.address;
                        data.pickup_location_formatted_address=json.formatted_address;
                        data.pickup_location_text=$self.e('[name="chbs_pickup_location_service_type_'+data.service_type_id+'"]').val();  
                    }
                    
                    var coordinate=$self.e('[name="chbs_dropoff_location_coordinate_service_type_'+data.service_type_id+'"]').val();
                    if(!helper.isEmpty(coordinate))
                    {
                        var json=JSON.parse(coordinate);
                        data.dropoff_location_lat=json.lat;
                        data.dropoff_location_lng=json.lng;
                        data.dropoff_location_address=json.address;
                        data.dropoff_location_formatted_address=json.formatted_address;
                        data.dropoff_location_text=$self.e('[name="chbs_dropoff_location_service_type_'+data.service_type_id+'"]').val();
                    }                    
                }
                else
                {
                    data.route_id=$self.e('[name="chbs_route_service_type_'+data.service_type_id+'"]').val();    
                }
                
                if($.inArray($self.getServiceTypeId(),[1,3])>-1)
                {
                    data.extra_time=$self.e('[name="chbs_extra_time_service_type_'+data.service_type_id+'"]').val();
                    data.transfer_type=$self.e('[name="chbs_transfer_type_service_type_'+data.service_type_id+'"]').val(); 
              
                    if($.inArray(data.transfer_type,[3]))
                    {
                        data.duration=$self.e('[name="chbs_duration_service_type_'+data.service_type_id+'"]').val();  
                        
                        data.return_date=$self.e('[name="chbs_return_date_service_type_'+data.service_type_id+'"]').val();  
                        data.return_time=$self.e('[name="chbs_return_time_service_type_'+data.service_type_id+'"]').val();  
                    }
                }
                
                if($.inArray($self.getServiceTypeId(),[2])>-1)
                {
                    data.duration=$self.e('[name="chbs_duration_service_type_'+data.service_type_id+'"]').val();  
                }
                
                var passengerAdult=$self.e('[name="chbs_passenger_adult_service_type_'+data.service_type_id+'"]');
                if(passengerAdult.length===1) data.passenger_adult=passengerAdult.val();
                
                var passengerChildren=$self.e('[name="chbs_passenger_children_service_type_'+data.service_type_id+'"]');
                if(passengerChildren.length===1) data.passenger_children=passengerChildren.val();
                
                data.widget_submit=1;

                /***/
                
                var url=$option.widget.booking_form_url;
                
                if(url.indexOf('?')===-1) url+='?';
                if(url.indexOf('&')!==-1) url+='&';
                
                url+=decodeURIComponent($.param(data));
                
                window.location=url;
            });
            
            /***/
            
            $self.e('.chbs-main-content-step-3').on('click','.chbs-button-sign-in',function(e)
            {
                e.preventDefault();
                
                $self.getGlobalNotice().addClass('chbs-hidden');
                
                $self.preloader(true);
            
                $self.setAction('user_sign_in');
       
                $self.post($self.e('form[name="chbs-form"]').serialize(),function(response)
                {
                    if(parseInt(response.user_sign_in,10)===1)
                    {
                        $self.e('.chbs-main-content-step-3 .chbs-client-form').html('');
                 
                        if(typeof(response.client_form_sign_up)!=='undefined')
                            $self.e('.chbs-main-content-step-3 .chbs-client-form').append(response.client_form_sign_up);  
       
                        if(typeof(response.summary)!=='undefined')
                            $self.e('.chbs-main-content-step-3>.chbs-layout-25x75 .chbs-layout-column-left:first').html(response.summary[0]);                        
                        
                        $self.createSelectMenu();
                    }
                    else
                    {
                        if(typeof(response.error.global[0])!=='undefined')
                            $self.getGlobalNotice().removeClass('chbs-hidden').html(response.error.global[0].message);
                    }
                    
                    $self.preloader(false);
                });
            });
            
            /***/
            
            $self.e('.chbs-main-content-step-3').on('click','.chbs-sign-up-password-generate',function(e)
            {
                e.preventDefault();
                
                var helper=new Helper();
                var password=helper.generatePassword(8);
                
                $self.e('input[name="chbs_client_sign_up_password"],input[name="chbs_client_sign_up_password_retype"]').val(password);
            });
            
            $self.e('.chbs-main-content-step-3').on('click','.chbs-sign-up-password-show',function(e)
            {
                e.preventDefault();
                
                var password=$self.e('input[name="chbs_client_sign_up_password"]');
                password.attr('type',(password.attr('type')==='password' ? 'text' : 'password'));
            });
            
            /***/
            
            $(document).bind('keypress',function(e) 
            {
                if(parseInt(e.which,10)===13) 
                {
                    switch($(e.target).attr('name'))
                    {
                        case 'chbs_client_sign_in_login':
                        case 'chbs_client_sign_in_password':
                        
                            $self.e('.chbs-main-content-step-3 .chbs-button-sign-in').trigger('click');
                        
                        break;
                    }
                }                   
            });
            
            $(document).unbind('keydown').bind('keydown',function(e) 
            {
                switch($(e.target).attr('name'))
                {
                    case 'chbs_passenger_adult_service_type_1':
                    case 'chbs_passenger_adult_service_type_2':
                    case 'chbs_passenger_adult_service_type_3':
                    case 'chbs_passenger_children_service_type_1':
                    case 'chbs_passenger_children_service_type_2':
                    case 'chbs_passenger_children_service_type_3':    

                        if($.inArray(parseInt(e.keyCode,10),[38,40])>-1)
                        {
                            var value=parseInt($(e.target).val(),10);
                            if(isNaN(value)) value=0;

                            if(parseInt(e.keyCode,10)===38)
                                value=(value+1)>99 ? 99 : value+1;
                            else if(parseInt(e.keyCode,10)===40)
                                value=(value-1)<0 ? 0 : value-1;

                            $(e.target).val(parseInt(value));
                        }
                    
                    break;
                } 
            });
            
            /***/
            
            $self.googleMapCreate();
            $self.googleMapInit();
            
            $self.googleMapCreateRoute(function()
            {
                if(parseInt(helper.urlParam('widget_submit'))===1)    
                {
                    $self.goToStep(1,function()
                    {
                        $this.removeClass('chbs-hidden');
                    });
                }
                else $this.removeClass('chbs-hidden');                
                $self.googleMapStartCustomizeHeight();  
            });
        };
        
        /**********************************************************************/
        
        this.setTimepicker=function(field)
        {
            $('.ui-timepicker-wrapper').css({opacity:1,'width':field.parent('div').outerWidth()+1});
        };
        
        /**********************************************************************/
        
        this.createSelectMenu=function()
        {
            $self.e('select').selectmenu(
            {
                open                                                            :   function(event,ui)
                {
                    var select=$(this);
                    var selectmenu=$('#'+select.attr('id')+'-menu').parent('div');
                    
                    var field=select.parents('.chbs-form-field:first');
                    
                    var left=parseInt(selectmenu.css('left'),10)-1;
                    
                    var borderWidth=parseInt(field.css('border-left-width'),10)+parseInt(field.css('border-right-width'),10);
                    
                    var width=field[0].getBoundingClientRect().width-borderWidth;
                    
                    selectmenu.css({width:width,left:left});
                },
                change                                                          :   function(event,ui)
                {
                    var name=$(this).attr('name');
                    
                    if(name==='chbs_route_service_type_3')
                    {
                        $self.googleMapCreate();
                        $self.googleMapCreateRoute();
                    }
                    
                    if($.inArray(name,['chbs_transfer_type_service_type_1','chbs_transfer_type_service_type_3'])>-1)
                    {
                        var section=$self.e('[name="chbs_return_date_service_type_'+$self.getServiceTypeId()+'"]').parent('div').parent('div');
                        
                        if(parseInt($(this).val())===3) section.removeClass('chbs-hidden');
                        else section.addClass('chbs-hidden');
                    }
                    
                    if($.inArray(name,['chbs_extra_time_service_type_1','chbs_transfer_type_service_type_1','chbs_duration_service_type_2','chbs_extra_time_service_type_3','chbs_transfer_type_service_type_3'])>-1)
                    {
                        $self.reCalculateRoute();
                    }
                    
                    if(name==='chbs_navigation_responsive')
                    {
                        var navigation=parseInt($(this).val(),10);
                        
                        var step=parseInt($self.e('input[name="chbs_step"]').val(),10);    
                
                        if(navigation-step===0) return;

                        $self.goToStep(navigation-step);
                    }
                    
                    if($.inArray(name,['chbs_vehicle_passenger_count','chbs_vehicle_bag_count','chbs_vehicle_category'])>-1)
                    {
                        $self.setAction('vehicle_filter');
                        
                        $self.post($self.e('form[name="chbs-form"]').serialize(),function(response)
                        {       
                            $self.getGlobalNotice().addClass('chbs-hidden');
                            
                            var vehicleList=$self.e('.chbs-vehicle-list>ul');
                            vehicleList.html('');
                            
                            if((typeof(response.error)!=='undefined') && (typeof(response.error.global)!=='undefined'))
                            {
                                $self.getGlobalNotice().removeClass('chbs-hidden').html(response.error.global[0].message);
                            }
                            else
                            {
                                vehicleList.html(response.html);
                            }
                            
                            $self.e('.chbs-vehicle-list').find('.chbs-button.chbs-button-style-2').removeClass('chbs-state-selected');
                            
                            $self.preloadVehicleImage();
                            
                            $self.e('input[name="chbs_vehicle_id"]').val(0);
                            $self.createSummaryPriceElement();
                        });
                    }
                }
            });
                        
            $self.e('.ui-selectmenu-button .ui-icon.ui-icon-triangle-1-s').attr('class','chbs-meta-icon-arrow-vertical-large');  
        };
        
        /**********************************************************************/
        /**********************************************************************/
        
        this.setMainNavigation=function()
        {
            var step=parseInt($self.e('input[name="chbs_step"]').val(),10);
     
            var element=$self.e('.chbs-main-navigation-default').find('li');
            
            element.removeClass('chbs-state-selected').removeClass('chbs-state-completed');
            
            element.filter('[data-step="'+step+'"]').addClass('chbs-state-selected');

            var i=0;
            element.each(function()
            {
                if((++i)>=step) return;
                
                $(this).addClass('chbs-state-completed');
            });
        };
        
        /**********************************************************************/
        
        this.getServiceTypeId=function()
        {
            return(parseInt($self.e('.ui-tabs .ui-tabs-active').attr('data-id'),10));
        };
        
        /**********************************************************************/
        
        this.setServiceTypeId=function(serviceTypeId)
        {
            $self.e('input[name="chbs_service_type_id"]').val(serviceTypeId);
        };
        
        /**********************************************************************/
        /**********************************************************************/

        this.setAction=function(name)
        {
            $self.e('input[name="action"]').val('chbs_'+name);
        };

        /**********************************************************************/
        
        this.e=function(selector)
        {
            return($this.find(selector));
        };

        /**********************************************************************/
        
        this.goToStep=function(stepDelta,callback)
        {   
            $self.preloader(true);
            
            $self.setAction('go_to_step');
            
            var step=$self.e('input[name="chbs_step"]');
            var stepRequest=$self.e('input[name="chbs_step_request"]');
            stepRequest.val(parseInt(step.val(),10)+stepDelta);
            
            $self.setServiceTypeId($self.getServiceTypeId());
            
            $self.post($self.e('form[name="chbs-form"]').serialize(),function(response)
            {                
                response.step=parseInt(response.step,10);
                
                $self.getGlobalNotice().addClass('chbs-hidden');
                
                $self.e('.chbs-main-content>div').css('display','none');
                $self.e('.chbs-main-content>div:eq('+(response.step-1)+')').css('display','block');
                
                $self.e('input[name="chbs_step"]').val(response.step);
                
                $self.setMainNavigation();
                
                $self.googleMapDuplicate(-1);
                
                google.maps.event.trigger($googleMap,'resize');
                
                $self.e('select[name="chbs_navigation_responsive"]').val(response.step);
                $self.e('select[name="chbs_navigation_responsive"]').selectmenu('refresh');
                  
                if(parseInt(response.step,10)===1)
                    $self.googleMapStartCustomizeHeight();
                else $self.googleMapStopCustomizeHeight();
             
                switch(parseInt(response.step,10))
                {
                    case 2:
                        
                        $self.e('.chbs-vehicle-filter>.chbs-form-field:first>div').html(response.vehicle_passenger_filter_field);
                        
                        if(typeof(response.vehicle)!=='undefined')
                            $self.e('.chbs-vehicle-list>ul').html(response.vehicle);
                        
                        if(typeof(response.booking_extra)!=='undefined')
                            $self.e('.chbs-booking-extra').html(response.booking_extra);                        
                        
                        if(typeof(response.summary)!=='undefined')
                            $self.e('.chbs-main-content-step-2>.chbs-layout-25x75 .chbs-layout-column-left:first').html(response.summary[0]);  
                        
                        $self.preloadVehicleImage();
                        
                        $self.createSelectMenu();
                        
                    break;
                        
                    case 3:
                        
                        if((typeof(response.client_form_sign_id)!=='undefined') && (typeof(response.client_form_sign_up)!=='undefined'))
                        {
                            $self.e('.chbs-main-content-step-3 .chbs-client-form').html('');

                            if(typeof(response.client_form_sign_id)!=='undefined')
                                $self.e('.chbs-main-content-step-3 .chbs-client-form').prepend(response.client_form_sign_id);                        

                            if(typeof(response.client_form_sign_up)!=='undefined')
                                $self.e('.chbs-main-content-step-3 .chbs-client-form').append(response.client_form_sign_up); 
                        }
                        
                        if(typeof(response.summary)!=='undefined')
                            $self.e('.chbs-main-content-step-3>.chbs-layout-25x75 .chbs-layout-column-left:first').html(response.summary[0]);
                        
                        $self.createSelectMenu();
                        
                    break;
                    
                    case 4:
                        
                        if(typeof(response.summary)!=='undefined')
                        {
                            $self.e('.chbs-main-content-step-4>.chbs-layout-33x33x33>.chbs-layout-column-left').html(response.summary[0]);
                        
                            $self.e('.chbs-main-content-step-4>.chbs-layout-33x33x33>.chbs-layout-column-center').html(response.summary[1]);
                        
                            $self.e('.chbs-main-content-step-4>.chbs-layout-33x33x33>.chbs-layout-column-right').html(response.summary[2]);
                        }
                        
                    break;
                }
                
                $self.createStickySidebar();
                $(window).scroll();
                
                if($.inArray(response.step,[4])>-1)   
                    $self.googleMapDuplicate(response.step);
                
                $('.qtip').remove();
                
                if(typeof(response.error)!=='undefined')
                {
                    if(typeof(response.error.local)!=='undefined')
                    {
                        for(var index in response.error.local)
                        {
                            var selector,object;
                            
                            var sName=response.error.local[index].field.split('-');

                            if(isNaN(sName[1])) selector='[name="'+sName[0]+'"]:eq(0)';
                            else selector='[name="'+sName[0]+'[]"]:eq('+sName[1]+')';                                    
                                    
                            object=$self.e(selector).prevAll('label');
                                 
                            object.qtip(
                            {
                                show            :	
                                { 
                                    target      :	$(this) 
                                },
                                style           :	
                                { 
                                    classes     :	(response.error===1 ? 'chbs-qtip chbs-qtip-error' : 'chbs-qtip chbs-qtip-success')
                                },
                                content         : 	
                                { 
                                    text        :	response.error.local[index].message 
                                },
                                position        : 	
                                { 
                                    my          :	($option.rtl_mode ? 'bottom right' : 'bottom left'),
                                    at          :	($option.rtl_mode ? 'top right' : 'top left'),
                                    container   :   object.parent()
                                }
                            }).qtip('show');	
                        }
                    }
                                       
                    if(typeof(response.error.global[0])!=='undefined')
                        $self.getGlobalNotice().removeClass('chbs-hidden').html(response.error.global[0].message);
                }
                
                if(parseInt(response.step,10)===5)
                {
                    $self.e('.chbs-main-navigation-default').addClass('chbs-hidden');
                    $self.e('.chbs-main-navigation-responsive').addClass('chbs-hidden');
                    
                    if(parseInt(response.payment_id,10)===1)
                    {
                        $self.e('.chbs-booking-complete-payment-cash').css('display','block');
                    }
                    else if(parseInt(response.payment_id,10)===2)
                    {
                        var paragraph=$self.e('.chbs-booking-complete-payment-stripe');
                        
                        paragraph.css('display','block');
                        
                        $self.e('form[name="chbs-form"]').after(response.form);
                                    
                        paragraph.find('a').on('click',function(e)
                        {
                            e.preventDefault();
                            $self.e('form[name="chbs-form-stripe"] button').click();
                        });
                    }
                    else if(parseInt(response.payment_id,10)===3)
                    {
                        var counter=5;
                        
                        $self.e('.chbs-booking-complete-payment-paypal').css('display','block');
                        
                        var interval=setInterval(function()
                        {
                            counter--;
                            $self.e('.chbs-booking-complete>p>span').html(counter);
                            
                            if(counter===0)
                            {
                                clearInterval(interval);
              
                                var form=$self.e('form[name="chbs-form-paypal"]');

                                for(var i in response.form)
                                    form.find('input[name="'+i+'"]').val(response.form[i]);

                                form.submit();
                            }

                        },1000);                        
                    }
                    else if(parseInt(response.payment_id,10)===4)
                    {
                        $self.e('.chbs-booking-complete-payment-wire-transfer').css('display','block');
                    }
                    else if(parseInt(response.payment_id,10)===-1)
                    {
                        $self.e('.chbs-booking-complete-payment-woocommerce').css('display','block');
                        $self.e('.chbs-booking-complete-payment-woocommerce>a').attr('href',response.payment_url);
                    }
                }
                
                var offset=20;
                
                if($('#wpadminbar').length===1)
                    offset+=$('#wpadminbar').height();
                
                $.scrollTo($this,{offset:-1*offset});
                
                if(typeof(callback)!=='undefined') callback();
                
                $self.preloader(false);
            });
        };
        
        /**********************************************************************/
        
		this.post=function(data,callback)
		{
			$.post($option.ajax_url,data,function(response)
			{ 
				callback(response); 
			},'json');
		};    
        
        /**********************************************************************/
        
        this.preloader=function(action)
        {
            $self.e('#chbs-preloader').css('display',(action ? 'block' : 'none'));
        };
        
        /**********************************************************************/
        
        this.preloadVehicleImage=function()
        {
            $self.e('.chbs-vehicle-list .chbs-vehicle-image img').one('load',function()
            {
                $(this).parent('.chbs-vehicle-image').animate({'opacity':1},300);
            }).each(function() 
            {
                if(this.complete) $(this).load();
            });
        };
        
        /**********************************************************************/
        /**********************************************************************/
       
        this.googleMapStartCustomizeHeight=function()
        {
            if(parseInt($option.widget.mode)===1) return;
            
            if($googleMapHeightInterval>0) return;
            
            $googleMapHeightInterval=window.setInterval(function()
            {
                $self.googleMapCustomizeHeight();
            },500);
        };
        
        /**********************************************************************/
       
        this.googleMapStopCustomizeHeight=function()
        {
            if(parseInt($option.widget.mode)===1) return;
            
            clearInterval($googleMapHeightInterval);
            $self.e('#chbs_google_map').height('420px');
            
            $googleMapHeightInterval=0;
        };        
        
        /**********************************************************************/
       
        this.googleMapCustomizeHeight=function()
        {
            if(parseInt($option.widget.mode)===1) return;
            
            var rideInfo=$self.e('.chbs-ride-info');
            var columnLeft=$self.e('.chbs-main-content-step-1>.chbs-layout-50x50>.chbs-layout-column-left');
            
            $self.e('#chbs_google_map').height(parseInt(columnLeft.actual('height'))-parseInt(rideInfo.actual('height')));
            
            google.maps.event.trigger($googleMap,'resize');
        };
       
        /**********************************************************************/
        /**********************************************************************/
       
        this.googleMapDuplicate=function(step)
        {
            if(step===4)
            {
                var map=$self.e('.chbs-google-map>#chbs_google_map');
                if(map.children('div').length)
                    $self.e('.chbs-google-map-summary').append(map);             
            }
            else
            {
                
                var map=$self.e('.chbs-google-map-summary>#chbs_google_map');
                if(map.children('div').length)
                    $self.e('.chbs-google-map').append(map);
            }
            
            google.maps.event.trigger($googleMap,'resize');
            
            try
            {
                $directionsRenderer.setDirections($directionsServiceResponse);
                $googleMap.setCenter($directionsRenderer.getDirections().routes[0].bounds.getCenter());
            }
            catch(e)
            {
        
            }
        };
        
        /**********************************************************************/
        
        this.googleMapAutocompleteCreate=function(text)
        {
            if(text.is('[readonly]')) return;
            
            var id='chbs_location_'+(new Helper()).getRandomString(16);
                
            text.attr('id',id).on('keypress',function(e)
            {
                if(e.which===13)
                {
                    e.preventDefault();
                    return(false);
                }
            });
            
            text.on('change',function()
            {
                if(!$.trim($(this).val()).length)
                {
                    text.siblings('input[type="hidden"]').val('');
                    
                    $self.googleMapCreate();
                    $self.googleMapCreateRoute();                    
                }
            });
            
            var option={};
            var helper=new Helper();
            var name=new String(text.attr('name'));
             
            if($.isArray($option.waypoint_country_available))
            {
                option.componentRestrictions={};
                option.componentRestrictions.country=$option.waypoint_country_available;
            }
            
            if(name.indexOf('pickup')>-1)
            {
                if(!helper.isEmpty($option.waypoint_pickup_area_available))
                {
                    var circle=new google.maps.Circle(
                    {
                        center                                                  :   new google.maps.LatLng($option.waypoint_pickup_area_available.center.lat,$option.waypoint_pickup_area_available.center.lng),
                        radius                                                  :   $option.waypoint_pickup_area_available.radius 
                    });

                    option.strictBounds=true;
                    option.bounds=circle.getBounds();
                }                
            }
            
            if(name.indexOf('dropoff')>-1)
            {
                if(!helper.isEmpty($option.waypoint_dropoff_area_available))
                {
                    var circle=new google.maps.Circle(
                    {
                        center                                                  :   new google.maps.LatLng($option.waypoint_dropoff_area_available.center.lat,$option.waypoint_dropoff_area_available.center.lng),
                        radius                                                  :   $option.waypoint_dropoff_area_available.radius 
                    });

                    option.strictBounds=true;
                    option.bounds=circle.getBounds();
                }                
            }
            
            var autocomplete=new google.maps.places.Autocomplete(document.getElementById(id),option);
            autocomplete.addListener('place_changed',function(id)
            {
                var place=autocomplete.getPlace();
                
                var placeData=
                {
                    lat                                                         :   place.geometry.location.lat(),
                    lng                                                         :   place.geometry.location.lng(),
                    formatted_address                                           :   text.val()
                };
                
                var field=text.siblings('input[type="hidden"]');
                
                field.val(JSON.stringify(placeData));
         
                $self.googleMapSetAddress(field,function()
                {
                    $self.googleMapCreate();
                    $self.googleMapCreateRoute();                    
                });
            });  
        };
        
        /**********************************************************************/
        /**********************************************************************/        
        
        this.googleMapInit=function()
        {
            if(!$self.googleMapExist()) return;
            
            if(parseInt($option.gooogle_map_option.default_location.type,10)===1)
            {
                if(navigator.geolocation) 
                {
                    navigator.geolocation.getCurrentPosition(function(position)
                    {
                        $startLocation=new google.maps.LatLng(position.coords.latitude,position.coords.longitude);
                        $googleMap.setCenter($startLocation);
                    },
                    function()
                    {
                        $self.googleMapSetDefaultLocation();
                    });
                } 
                else
                {
                    $self.googleMapSetDefaultLocation();
                }
            }
            else $self.googleMapSetDefaultLocation();
        };
        
        /**********************************************************************/
        
        this.googleMapSetDefaultLocation=function()
        {
            if(typeof($startLocation)==='undefined')
                $startLocation=new google.maps.LatLng($option.gooogle_map_option.default_location.coordinate.lat,$option.gooogle_map_option.default_location.coordinate.lng);
            
            if($self.getServiceTypeId()===3) return;
            
            var helper=new Helper();
                     
            var coordinate=[];
            
            coordinate[0]=$self.e('[name="chbs_pickup_location_coordinate_service_type_'+$self.getServiceTypeId()+'"]').val();
            coordinate[1]=$self.e('[name="chbs_dropoff_location_coordinate_service_type_'+$self.getServiceTypeId()+'"]').val();
            
            if((!helper.isEmpty(coordinate[0])) && (!helper.isEmpty(coordinate[1])))
                $startLocation=new google.maps.LatLng(coordinate[0],coordinate[1]);

            $googleMap.setCenter($startLocation); 
        };
        
        /**********************************************************************/
        
        this.googleMapCreate=function()
        {
            if($self.e('#chbs_google_map').length!==1) return;
            
            $directionsRenderer=new google.maps.DirectionsRenderer();
            $directionsService=new google.maps.DirectionsService();
                                  
            var option= 
            {
                draggable                                                       :	$option.gooogle_map_option.draggable.enable,
                scrollwheel                                                     :	$option.gooogle_map_option.scrollwheel.enable,
                mapTypeId                                                       :	google.maps.MapTypeId[$option.gooogle_map_option.map_control.id],
                mapTypeControl                                                  :	$option.gooogle_map_option.map_control.enable,
                mapTypeControlOptions                                           :	
                {
                    style                                                       :	google.maps.MapTypeControlStyle[$option.gooogle_map_option.map_control.style],
                    position                                                    :	google.maps.ControlPosition[$option.gooogle_map_option.map_control.position],
                },
                zoom                                                            :	$option.gooogle_map_option.zoom_control.level,
                zoomControl                                                     :	$option.gooogle_map_option.zoom_control.enable,
                zoomControlOptions                                              :	
                {
                    position                                                    :	google.maps.ControlPosition[$option.gooogle_map_option.zoom_control.position]
                },
                streetViewControl                                               :   false
            };
                     
            $googleMap=new google.maps.Map(document.getElementById('chbs_google_map'),option);
            
            if(parseInt($option.gooogle_map_option.traffic_layer.enable,10)===1)
            {
                var trafficLayer=new google.maps.TrafficLayer();
                trafficLayer.setMap($googleMap);
            }
            
            $directionsRenderer.setMap($googleMap);

            $directionsRenderer.setOptions(
            {
                draggable                                                       :   false,
                suppressMarkers                                                 :   true
            });    
        };
        
        /**********************************************************************/
        
        this.getCoordinate=function()
        {
            var helper=new Helper();
            var coordinate=new Array();
            
            var serviceTypeId=$self.getServiceTypeId();
            var panelField=$self.e('#panel-'+(serviceTypeId)).children('.chbs-form-field-location-autocomplete');
            
            if(serviceTypeId===1 || serviceTypeId===2)
            {
                panelField.each(function()
                {
                    if(serviceTypeId===2)
                    {
                        if($(this).children('input[name="chbs_dropoff_location_service_type_2"]').length===1) return(true);
                    }
                    
                    var c;
                    
                    try
                    {
                        c=JSON.parse($(this).children('input[type="hidden"]').val());
                    }
                    catch(e)
                    {
                        c={lat:'',lng:''};
                    }
                
                    if((!helper.isEmpty(c.lat)) && (!helper.isEmpty(c.lng)))
                        coordinate.push(new google.maps.LatLng(c.lat,c.lng));
                });
            }
            else
            {
                var option=$self.e('select[name="chbs_route_service_type_3"]>option:selected');
                
                if(option.length===1) 
                {
                    var data=JSON.parse(option.attr('data-coordinate'));

                    for(var i in data)
                    {
                        if((!helper.isEmpty(data[i].lat)) && (!helper.isEmpty(data[i].lng)))
                            coordinate.push(new google.maps.LatLng(data[i].lat,data[i].lng));                    
                    }
                }
            }            
            
            return(coordinate);
        };
        
        /**********************************************************************/
        
        this.googleMapExist=function()
        {
            return(typeof($googleMap)==='undefined' ? false : true); 
        };
        
        /**********************************************************************/
        
        this.googleMapCreateRoute=function(callback)
        {
            if(!$self.googleMapExist())
            {
                if(typeof(callback)!=='undefined') callback();
                return;
            }
            
            var request;
           
            var serviceTypeId=$self.getServiceTypeId();
            var panelField=$self.e('#panel-'+(serviceTypeId)).children('.chbs-form-field-location-autocomplete');
           
            var coordinate=$self.getCoordinate();
            var length=coordinate.length;
                 
            if(length===0)
            {
                $self.googleMapReInit();
                
                if(typeof(callback)!=='undefined') callback();
                return;
            }
            
            if(length>2)
            {
                var waypoint=new Array();
                for(var i in coordinate)
                {
                    if((i===0) && (i===length-1)) continue;
                    waypoint.push({location:coordinate[i],stopover:true});
                }
                
                request= 
                {
                    origin                                                      :   coordinate[0],
                    waypoints                                                   :   waypoint,
                    optimizeWaypoints                                           :   true,
                    destination                                                 :   coordinate[length-1],
                    travelMode                                                  :   google.maps.DirectionsTravelMode.DRIVING
                };                     
            }
            else if(length===2)
            {
                request= 
                {
                    origin                                                      :   coordinate[0],
                    destination                                                 :   coordinate[length-1],
                    travelMode                                                  :   google.maps.DirectionsTravelMode.DRIVING
                };          
            }
            else
            {
                request= 
                {
                    origin                                                      :   coordinate[length-1],
                    destination                                                 :   coordinate[length-1],
                    travelMode                                                  :   google.maps.DirectionsTravelMode.DRIVING
                };              
            }
          
            request.avoidTolls=$.inArray('tolls',$option.gooogle_map_option.route_avoid)>-1 ? true : false;
            request.avoidFerries=$.inArray('ferries',$option.gooogle_map_option.route_avoid)>-1 ? true : false;
            request.avoidHighways=$.inArray('highways',$option.gooogle_map_option.route_avoid)>-1 ? true : false;
            
            $directionsService.route(request,function(response,status)
            {
                $self.googleMapClearMarker();
                
                if(status===google.maps.DirectionsStatus.OK)
                {
                    $directionsRenderer.setDirections(response);
                    
                    $directionsServiceResponse=response;
 
                    for(var i in response.routes[0].legs)
                    {
                        var leg=response.routes[0].legs[i];

                        $self.googleMapCreateMarker(leg.start_location);
                        $self.googleMapCreateMarker(leg.end_location); 
                    }
                
                    $googleMap.setCenter($directionsRenderer.getDirections().routes[0].bounds.getCenter());
                    
                    $self.calculateRoute(response);
                }
                else if(status===google.maps.DirectionsStatus.ZERO_RESULTS)
                {
                    if(serviceTypeId===1)
                    {
                        alert($option.message.designate_route_error);
                        
                        panelField.each(function()
                        {
                            $(this).children('input[type="text"]').val('');
                            $(this).children('input[type="hidden"]').val('');
                        }); 
                        
                        $self.googleMapReInit();
                    }
                }
                
                if(typeof(callback)!=='undefined') callback();
            });            
        };
        
        /**********************************************************************/
        
        this.googleMapClearMarker=function()
        {
            for(var i in $marker)
                $marker[i].setMap(null);
            
            $marker=[];
        };
        
        /**********************************************************************/
        
        this.googleMapCreateMarker=function(position)
        {
            for(var i in $marker)
            {
                if(($marker[i].position.lat()==position.lat()) && ($marker[i].position.lng()==position.lng())) return;
            }
            
            var label=$marker.length+1;
            
            var marker=new google.maps.Marker(
            {
                position                                                        :   position,
                map                                                             :   $googleMap,
                label                                                           :   ''+label
            });        
            
            $marker.push(marker);
        };
        
        /**********************************************************************/
        
        this.googleMapReInit=function()
        {
            if(!$self.googleMapExist()) return;
            
            $directionsRenderer=new google.maps.DirectionsRenderer();
            $directionsService=new google.maps.DirectionsService();
            
            $directionsServiceResponse=null;
            
            $directionsRenderer.setDirections({routes:[]});
            
            $googleMap.setZoom($option.gooogle_map_option.zoom_control.level);
                            
            $self.calculateRoute();
                    
            if($startLocation!==null)
                $googleMap.setCenter($startLocation);
        };
        
        /**********************************************************************/
        
        this.googleMapSetAddress=function(field,callback)
        {
            var helper=new Helper();
            
            var coordinate=JSON.parse(field.val());
            
            if((helper.isEmpty(coordinate.lat)) || (helper.isEmpty(coordinate.lng))) return;
            
            var geocoder=new google.maps.Geocoder;
            
            geocoder.geocode({'location':new google.maps.LatLng(coordinate.lat,coordinate.lng)},function(result,status) 
            {
                if((status==='OK') && (result[0]))
                {
                    coordinate.address=result[0].formatted_address;
                    
                    if(helper.isEmpty(coordinate.formatted_address))
                        coordinate.formatted_address=result[0].formatted_address;
                    
                    field.val(JSON.stringify(coordinate));
                    callback();
                }
            });            
        };
        
        /**********************************************************************/
        
        this.calculateRoute=function(response)
        {
            var distance=0;
            var duration=0;
            
            if((typeof(response)!=='undefined') && (typeof(response.routes)!=='undefined'))
            {
                for(var i=0;i<response.routes[0].legs.length;i++) 
                {
                    distance+=response.routes[0].legs[i].distance.value;
                    duration+=response.routes[0].legs[i].duration.value;
                }
            }
            
            distance/=1000;
            duration=Math.ceil(duration/60);
            
            $self.e('input[name="chbs_distance_map"]').val(Math.round(distance*10)/10);
            $self.e('input[name="chbs_duration_map"]').val(duration);
            
            $self.reCalculateRoute();
        };
        
        /**********************************************************************/
        
        this.reCalculateRoute=function()
        {
            var duration=0;
            var distance=0;
            
            var serviceTypeId=parseInt($self.e('input[name="chbs_service_type_id"]').val(),10);
            
            distance=$self.e('input[name="chbs_distance_map"]').val();
            
            switch(serviceTypeId)
            {
                case 1:
                
                    duration=$self.e('select[name="chbs_extra_time_service_type_1"]').val();
                    if(isNaN(duration)) duration=0; 
                    
                    duration*=60;
                    
                break;
                
                case 2:
                    
                    duration=$self.e('select[name="chbs_duration_service_type_2"]').val();
                    if(isNaN(duration)) duration=0;
                    
                    duration*=60;
                    
                break;
                
                case 3:
                    
                    duration=$self.e('select[name="chbs_extra_time_service_type_3"]').val();
                    if(isNaN(duration)) duration=0; 
                    
                    duration*=60;
                    
                break;
            }
            
            if($.inArray(serviceTypeId,[1,3])>-1)
            {
                var transferType=$self.e('select[name="chbs_transfer_type_service_type_'+serviceTypeId+'"]');
                var transferTypeValue=transferType.length===1 ? (parseInt(transferType.val())===1 ? 1 : 2) : 1;
                
                duration+=(parseInt($self.e('input[name="chbs_duration_map"]').val())*transferTypeValue);
                distance*=transferTypeValue;
            }
            
            $self.e('input[name="chbs_distance_sum"]').val(distance);
            $self.e('input[name="chbs_duration_sum"]').val(duration);
            
            var sDuration=$self.splitTime(duration);
            
            distance=$self.formatLength(distance);
                
            $self.e('.chbs-ride-info>div:eq(0)>span:eq(2)>span:eq(0)').html(distance);
            $self.e('.chbs-ride-info>div:eq(1)>span:eq(2)>span:eq(0)').html(sDuration[0]);
            $self.e('.chbs-ride-info>div:eq(1)>span:eq(2)>span:eq(2)').html(sDuration[1]);  
            
            $self.calculateBaseLocationDistance();
        };
        
        /**********************************************************************/
        
        this.formatLength=function(length)
        {
            if($option.length_unit===2)
            {   
                length/=1.609344;
                length=Math.round(length*10)/10;
            }
            
            return(length);
        };
        
        /**********************************************************************/
        
        this.splitTime=function(time)
        {
            return([Math.floor(time/60),time%60]);
        };
        
		/**********************************************************************/
		
		this.setWidthClass=function()
		{
            if(parseInt($option.widget.mode)===1) return;
            
			var width=$this.parent().width();
			
			var className=null;
			var classPrefix='chbs-width-';
			
			if(width>=1220) className='1220';
			else if(width>=960) className='960';
			else if(width>=768) className='768';
			else if(width>=480) className='480';
			else if(width>=300) className='300';
            else className='300';
			
			var oldClassName=$self.getValueFromClass($this,classPrefix);
			if(oldClassName!==false) $this.removeClass(classPrefix+oldClassName);
			
			$this.addClass(classPrefix+className);
			
			if($self.prevWidth!==width)
            {
				$self.prevWidth=width;
                $(window).resize();
                                
                $self.createStickySidebar();
                
                if($.inArray(className,['300','480'])>-1)
                    $self.googleMapStopCustomizeHeight();
                else $self.googleMapStartCustomizeHeight();
            };
                        
			setTimeout($self.setWidthClass,500);
		};
       
		/**********************************************************************/
		
		this.getValueFromClass=function(object,pattern)
		{
			try
			{
				var reg=new RegExp(pattern);
				var className=$(object).attr('class').split(' ');

				for(var i in className)
				{
					if(reg.test(className[i]))
						return(className[i].substring(pattern.length));
				}
			}
			catch(e) {}

			return(false);		
		};
        
        /**********************************************************************/
        
        this.createSummaryPriceElement=function()
        {
            $self.setAction('create_summary_price_element');
  
            $self.post($self.e('form[name="chbs-form"]').serialize(),function(response)
            {    
                $self.e('.chbs-summary-price-element').replaceWith(response.html);
                $(window).scroll();
            });   
        };
        
        /**********************************************************************/
        
        this.createStickySidebar=function()
        {
            if(parseInt($option.summary_sidebar_sticky_enable,10)!==1) return;
            
            var className=$self.getValueFromClass($this,'chbs-width-');
            
            if($.inArray(className,['300','480','768'])>-1)
            {
                $self.removeStickySidebar();
                return;
            }       
            
            var step=parseInt($self.e('input[name="chbs_step"]').val(),10);
            
            var offset=30;
            var adminBar=$('#wpadminbar');
            
            if(adminBar.length===1)
                offset+=adminBar.actual('height');
            
            $self.e('.chbs-main-content>.chbs-main-content-step-'+step+'>.chbs-layout-25x75 .chbs-layout-column-left:first').stick_in_parent({offset_top:offset,recalc_every:1,bottoming:true});
        };
        
        /**********************************************************************/
        
        this.removeStickySidebar=function()
        {
            if(parseInt($option.summarySidebarStickyEnable,10)!==1) return;
            
            var step=parseInt($self.e('input[name="chbs_step"]').val(),10);
            $self.e('.chbs-main-content>.chbs-main-content-step-'+step+'>.chbs-layout-25x75 .chbs-layout-column-left:first').trigger('sticky_kit:detach');
        };
        
        /**********************************************************************/
        
        this.getGlobalNotice=function()
        {
            var step=parseInt($self.e('input[name="chbs_step"]').val(),10);
            return($self.e('.chbs-main-content-step-'+step+' .chbs-notice'));
        };
        
        /**********************************************************************/
        
        this.calculateBaseLocationDistance=function()
        {
            var helper=new Helper();
            
            $self.e('input[name="chbs_base_location_distance"]').val(0);
            $self.e('input[name="chbs_base_location_duration"]').val(0);
            $self.e('input[name="chbs_base_location_return_distance"]').val(0);
            $self.e('input[name="chbs_base_location_return_duration"]').val(0);
            
            if((helper.isEmpty($option.base_location.coordinate.lat)) || (helper.isEmpty($option.base_location.coordinate.lng))) return;
            
            var request;
            var coordinate=$self.getCoordinate();
            var directionsService=new google.maps.DirectionsService();
            
            /***/
            
            request= 
            {
                origin                                                          :   coordinate[0],
                destination                                                     :   new google.maps.LatLng($option.base_location.coordinate.lat,$option.base_location.coordinate.lng),
                travelMode                                                      :   google.maps.DirectionsTravelMode.DRIVING
            };   
            directionsService.route(request,function(response,status)
            {
                if(status===google.maps.DirectionsStatus.OK)
                {
                    var distance=0;
                    var duration=0;
                    
                    for(var i=0;i<response.routes[0].legs.length;i++) 
                    {
                        distance+=response.routes[0].legs[i].distance.value;
                        duration+=response.routes[0].legs[i].duration.value;
                    }
                    
                    distance/=1000;
                    duration=Math.ceil(duration/60);
            
                    $self.e('input[name="chbs_base_location_distance"]').val(Math.round(distance*10)/10);
                    $self.e('input[name="chbs_base_location_duration"]').val(duration);
                }
            });
            
            /***/
            
            if(coordinate.length>1)
            {
                request= 
                {
                    origin                                                          :   coordinate[coordinate.length-1],
                    destination                                                     :   new google.maps.LatLng($option.base_location.coordinate.lat,$option.base_location.coordinate.lng),
                    travelMode                                                      :   google.maps.DirectionsTravelMode.DRIVING
                };   
                directionsService.route(request,function(response,status)
                {
                    if(status===google.maps.DirectionsStatus.OK)
                    {
                        var distance=0;
                        var duration=0;

                        for(var i=0;i<response.routes[0].legs.length;i++) 
                        {
                            distance+=response.routes[0].legs[i].distance.value;
                            duration+=response.routes[0].legs[i].duration.value;
                        }

                        distance/=1000;
                        duration=Math.ceil(duration/60);

                        $self.e('input[name="chbs_base_location_return_distance"]').val(Math.round(distance*10)/10);
                        $self.e('input[name="chbs_base_location_return_duration"]').val(duration);
                    }
                });
            }
                   
            /***/
        };
                
        /**********************************************************************/
        /**********************************************************************/
	};
	
	/**************************************************************************/
	
	$.fn.chauffeurBookingForm=function(option) 
	{
		var form=new ChauffeurBookingForm(this,option);
        return(form);
	};
	
	/**************************************************************************/

})(jQuery,document,window);

/******************************************************************************/
/******************************************************************************/