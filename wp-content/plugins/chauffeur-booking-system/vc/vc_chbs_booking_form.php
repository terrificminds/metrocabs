<?php

/******************************************************************************/
/******************************************************************************/

class WPBakeryShortCode_VC_CHBS_Booking_Form
{
    /**************************************************************************/
    
    function __construct() 
    {
        add_action('init',array($this,'vcMapping'));
    }
     
    /**************************************************************************/     

    public function vcMapping()
    {
        $BookingForm=new CHBSBookingForm();
        $VisualComposer=new CHBSVisualComposer();
        
        vc_map
        ( 
            array
            (
                'base'                                                          =>  CHBSBookingForm::getShortcodeName(),
                'name'                                                          =>  __('Chauffeur Booking Form','chauffeur-booking-system'),
                'description'                                                   =>  __('Displays booking from.','chauffeur-booking-system'), 
                'category'                                                      =>  __('Content','templatica'),  
                'params'                                                        =>  array
                (   
                    array
                    (
                        'type'                                                  =>  'dropdown',
                        'param_name'                                            =>  'booking_form_id',
                        'heading'                                               =>  __('Booking form','chauffeur-booking-system'),
                        'description'                                           =>  __('Select booking form which has to be displayed.','chauffeur-booking-system'),
                        'value'                                                 =>  $VisualComposer->createParamDictionary($BookingForm->getDictionary()),
                        'admin_label'                                           =>  true
                    )  
                )
            )
        );         
    } 
    
    /**************************************************************************/
} 
 
new WPBakeryShortCode_VC_CHBS_Booking_Form(); 