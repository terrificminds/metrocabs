/******************************************************************************/
/******************************************************************************/

;(function($,doc,win) 
{
	"use strict";
	
	var ChauffeurBookingFormAdmin=function(object,option)
	{
		/**********************************************************************/
		
        var $self=this;
		var $this=$(object);
		
        var $map;
        
        var $drawingManager;
        
        var $circle;
        
        var $startLocation;
        
		var $optionDefault;
		var $option=$.extend($optionDefault,option);
        
		/**********************************************************************/
		
        this.init=function()
        {
            if(navigator.geolocation) 
            {
                navigator.geolocation.getCurrentPosition(function(position)
                {
                    var data=$self.getWaypointArea();
                    if(data===false)
                    {
                        $startLocation=new google.maps.LatLng(position.coords.latitude,position.coords.longitude);
                        $map.setCenter($startLocation);
                    }
                },
                function()
                {
                    $self.useDefaultLocation();
                });
            } 
            else
            {
                $self.useDefaultLocation();
            } 
        };
        
        /**********************************************************************/
        
        this.useDefaultLocation=function()
        {
            var data=$self.getWaypointArea();
            
            if(data===false)
            {
                $startLocation=new google.maps.LatLng($option.userDefaultCoordinate.lat,$option.userDefaultCoordinate.lng);
                $map.setCenter($startLocation);
            }
        };
        
        /**********************************************************************/
        
		this.create=function()
		{
            var option= 
            {
                zoom                                                            :   6,
                mapTypeId                                                       :   google.maps.MapTypeId.ROADMAP
            };
            
            var circleOption= 
            {
                clickable                                                       :   false,
                editable                                                        :   true
            };
            
            $map=new google.maps.Map(document.getElementById($this.attr('id')),option);
            
            $drawingManager=new google.maps.drawing.DrawingManager(
            {
                drawingMode                                                     :   null,
                drawingControl                                                  :   true,
                drawingControlOptions                                           : 
                {
                    position                                                    :   google.maps.ControlPosition.TOP_CENTER,
                    drawingModes                                                :   ['circle']
                },
                circleOptions                                                   :   circleOption
            });
            
            var data=$self.getWaypointArea();
            
            if(data!==false)
            {
                $circle=new google.maps.Circle(
                {
                    center                                                      :   new google.maps.LatLng(data.center.lat,data.center.lng),
                    radius                                                      :   data.radius 
                });

                $circle.setMap($map);

                $drawingManager.setMap(null);
                $drawingManager.setOptions({drawingControl:false});

                $map.setCenter($circle.getBounds().getCenter());
                $map.fitBounds($circle.getBounds());
            }
            
            google.maps.event.addListener($drawingManager,'overlaycomplete',function(e) 
            {
                function setCoordinate(object)
                {
                    var data={center:{lat:object.center.lat(),lng:object.center.lng()},radius:object.getRadius()};
                    $self.getFieldCircleData().val(JSON.stringify(data));          
                }
                
                setCoordinate(e.overlay);

                $circle=e.overlay;
                $circle.type=e.type;
     
                google.maps.event.addListener($circle,'radius_changed',function(e)
                {
                    setCoordinate($circle);
                });

                google.maps.event.addListener($circle,'center_changed',function(e)
                {
                   setCoordinate($circle);
                });
                
                $drawingManager.setMap(null);
                $drawingManager.setOptions({drawingControl:false});
            });
            
            $this.parent(':first').find('.to-google-map-circle-remove').bind('click',function(e)
            {
                e.preventDefault();
                
                $circle.setMap(null);
                
                $self.getFieldCircleData().val('');     
                
                $drawingManager.setMap($map);
                $drawingManager.setOptions({drawingControl:true});
            });
            
            $drawingManager.setMap($map);
        };
        
        /**********************************************************************/
        
        this.getWaypointArea=function()
        {
            var helper=new Helper();
            var data=$self.getFieldCircleData().val();
            
            if(!helper.isEmpty(data))
            {
                try
                {
                    data=JSON.parse(data);
                }
                catch(e)
                {
                    data=false;
                }
                
                return(data);
            }
            
            return(false);
        };
        
        /**********************************************************************/
        
        this.getFieldCircleData=function()
        {
            return($this.parent(':first').find('input.to-google-map-circle-data'));
        };

        /**********************************************************************/
	};
	
	/**************************************************************************/
	
	$.fn.chauffeurBookingFormAdmin=function(option) 
	{
        var chauffeurBookingFormAdmin=new ChauffeurBookingFormAdmin(this,option);
        return(chauffeurBookingFormAdmin);            
	};
	
	/**************************************************************************/

})(jQuery,document,window);

/******************************************************************************/
/******************************************************************************/