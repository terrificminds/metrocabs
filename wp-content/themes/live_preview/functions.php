<?php
if( !defined(THEME_IMG_PATH)){
   define( 'THEME_IMG_PATH', get_stylesheet_directory_uri() . '/images' );
 }
//dropdown menu
require_once("nav-menu-dropdown-walker.php");

//register menu
add_theme_support("menus");
if(function_exists("register_nav_menu"))
{
	register_nav_menu("main-menu", "Main Menu");
}

function style_files(){

	 wp_enqueue_style('mystyle',get_stylesheet_directory_uri(). '/css/mystyle.css',array(),'1.0','all');
   wp_enqueue_style('slick_css',get_stylesheet_directory_uri(). '/slick/slick.css',array(),'1.0','all');
   wp_enqueue_style('slick_theme',get_stylesheet_directory_uri(). '/slick/slick-theme.css',array(),'1.0','all');
	 wp_enqueue_style( 'bootstrapstyle', get_template_directory_uri() . '/css/bootstrap.min.css' );

  wp_enqueue_script('jquery',get_theme_file_uri('/js/jquery.min.js'),false,3.0,true);
	 wp_enqueue_script('bootstrapjs',get_theme_file_uri('/js/bootstrap.min.js'), '1.0.0', true );
   wp_enqueue_script('jquery');
   wp_enqueue_script( 'slick', get_stylesheet_directory_uri() . '/slick/slick.js', array( 'jquery' ), '1.0.0', true );
   wp_enqueue_script( 'slick_js', get_stylesheet_directory_uri() . '/slick/slick-query.js', '1.0.0', true );
	 }
	 add_action('wp_enqueue_scripts','style_files');


	 function my_theme_setup()
 	{
 			add_theme_support('menus');
 			register_nav_menu('home','Header Navigation');
 	}
 	add_action('init','my_theme_setup');
 register_nav_menu('footer','Footer Menu');

function custom_footer_menu() {
  wp_nav_menu(array(
    'container' => '',
    'menu_id' => 'footer_nav',
    'fallback_cb' => 'thesis_nav_menu',
    'theme_location' => 'footer',
  ));
}
add_action('thesis_hook_footer','custom_footer_menu');

//register sidebars
if(function_exists("register_sidebar"))
{
	register_sidebar(array(
		"id" => "footer",
		"name" => "Sidebar Footer",
		'before_widget' => '<div id="%1$s" class="widget %2$s footer_box">',
		'after_widget' => '</div>',
		'before_title' => '<h4 class="widget-header">',
		'after_title' => '</h4>'
	));
}

//enable custom background
add_theme_support("custom-background");

function theme_enqueue_scripts()
{
	$option=get_option('lp_options');

	//js
	wp_enqueue_script("jquery", false, array(), false, true);
	wp_enqueue_script("theme-main", get_template_directory_uri() . "/js/main.js", array("jquery"));

	//css
	wp_enqueue_style("main-style", get_stylesheet_uri());
	if(isset($option['css_main']))
		wp_add_inline_style('main-style',stripslashes($option['css_main']));

	wp_enqueue_style("responsive", get_template_directory_uri() . "/responsive.css");
	if(isset($option['css_responsive']))
		wp_add_inline_style('responsive',stripslashes($option['css_responsive']));

	wp_enqueue_style("google-font-open-sans", "//fonts.googleapis.com/css?family=Open+Sans:300,400,700");
}
add_action("wp_enqueue_scripts", "theme_enqueue_scripts");

//theme options
function theme_admin_menu()
{
	add_submenu_page("themes.php", ucfirst('live_preview'), "Theme Options", "edit_theme_options", "ThemeOptions", "live_preview_options");
}
add_action("admin_menu", "theme_admin_menu");

function theme_stripslashes_deep($value)
{
	$value = is_array($value) ?
				array_map('stripslashes_deep', $value) :
				stripslashes($value);

	return $value;
}

function live_preview_options()
{
	if(isset($_POST["action"]) && $_POST["action"]=="lp_save")
	{
		$theme_options = array(
			"plugin_title" => $_POST["plugin_title"],
			"plugin_url" => $_POST["plugin_url"],
			"purchase_button_label" => $_POST["purchase_button_label"],
			"layout_picker" => $_POST["layout_picker"],
			"layout_picker_param" => $_POST["layout_picker_param"],
			"css_main" => $_POST["css_main"],
			"css_responsive" => $_POST["css_responsive"],
		);

		update_option("lp_options", $theme_options);
	}
	$theme_options = theme_stripslashes_deep(get_option("lp_options"));
	?>
	<div class="wrap">
		<h2><?php _e("Theme Options", "live_preview"); ?></h2>
	</div>

	<form action="<?php echo $_SERVER['REQUEST_URI']; ?>" method="post">
		<table class="form-table">
			<tbody>
				<tr valign="top">
					<th scope="row">
						<label for="plugin_title">
							<?php _e("Plugin title", "live_preview"); ?>
						</label>
					</th>
					<td>
						<input type="text" class="regular-text" value="<?php (isset($theme_options['plugin_title']) ? esc_attr_e($theme_options['plugin_title']) : ''); ?>" id="plugin_title" name="plugin_title">
					</td>
				</tr>
				<tr valign="top">
					<th scope="row">
						<label for="plugin_url">
							<?php _e("Plugin url", "live_preview"); ?>
						</label>
					</th>
					<td>
						<input type="text" class="regular-text" value="<?php (isset($theme_options['plugin_url']) ? esc_attr_e($theme_options['plugin_url']) : ''); ?>" id="plugin_url" name="plugin_url">
					</td>
				</tr>
				<tr valign="top">
					<th scope="row">
						<label for="purchase_button_label">
							<?php _e("Purchase button label", "live_preview"); ?>
						</label>
					</th>
					<td>
						<input type="text" class="regular-text" value="<?php (isset($theme_options['purchase_button_label']) ? esc_attr_e($theme_options['purchase_button_label']) : ''); ?>" id="purchase_button_label" name="purchase_button_label">
					</td>
				</tr>
				<tr valign="top">
					<th scope="row">
						<label for="layout_picker">
							<?php _e("Layout picker", "live_preview"); ?>
						</label>
					</th>
					<td>
						<select name="layout_picker">
							<option value="1" <?php echo ($theme_options['layout_picker']==1 ? 'selected' : null); ?>>Yes</option>
							<option value="0" <?php echo ($theme_options['layout_picker']!=1 ? 'selected' : null); ?>>No</option>
						</select>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row">
						<label for="layout_picker_param">
							<?php _e("Layout picker param", "live_preview"); ?>
						</label>
					</th>
					<td>
						<input type="text" class="regular-text" value="<?php (isset($theme_options['layout_picker_param']) ? esc_attr_e($theme_options['layout_picker_param']) : ''); ?>" id="layout_picker_param" name="layout_picker_param">
					</td>
				</tr>
				<tr valign="top">
					<th scope="row">
						<label for="layout_picker_param">
							<?php _e("Main CSS", "live_preview"); ?>
						</label>
					</th>
					<td>
						<textarea rows="20" cols="60" name="css_main" id="css_main"><?php (isset($theme_options['css_main']) ? esc_attr_e($theme_options['css_main']) : ''); ?></textarea>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row">
						<label for="layout_picker_param">
							<?php _e("Responsive CSS", "live_preview"); ?>
						</label>
					</th>
					<td>
						<textarea rows="20" cols="60" name="css_responsive" id="css_responsive"><?php (isset($theme_options['css_responsive']) ? esc_attr_e($theme_options['css_responsive']) : ''); ?></textarea>
					</td>
				</tr>
			</tbody>
		</table>
		<p class="submit">
			<input type="hidden" name="action" value="lp_save">
			<input type="submit" value="Save Changes" class="button button-primary" id="submit" name="submit">
		</p>
	</form>
	<?php
}
function christmas_customize_register1( $wp_customize ) {

// ------------------------------SERVICE FORM----------------------

  $wp_customize->add_section(
      'service_form',
      array(
          'title' => __('Service Form'),
          'priority' => null,
          'description'	=> __('You can change the Form Header Icon and Title'),
      )
  );
  $wp_customize->add_setting('service-banner-img');
  $wp_customize->add_control(new WP_Customize_Cropped_Image_Control($wp_customize,'service-banner-img',array(
          'label'	=> __('Service Form Banner Image'),
          'section'	=> 'service_form',
          'settings' => 'service-banner-img',
          'height' => '1000',
          'width' => '2500'
  )));
  $wp_customize->add_setting('form-icon');
  $wp_customize->add_control(new WP_Customize_Cropped_Image_Control($wp_customize,'form-icon',array(
          'label'	=> __('Service Form Head Image'),
          'section'	=> 'service_form',
          'settings' => 'form-icon'
  )));
// ------------------------------ABOUT METROCABS----------------------

      $wp_customize->add_section(
      'about',
      array(
          'title' => __('About Metrocabs'),
          'priority' => null,
          'description'	=> __('You can change the About Us section Heading and Description and Image'),
      )
      );

      $wp_customize->add_setting('about-description',array(
          'default' => 'Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum',
      ));
      $wp_customize->add_control('about-description',array(
          'type'	=> 'textarea',
          'label'	=> __('About Us description'),
          'section'	=> 'about'
      ));
      $wp_customize->add_setting('about-img');
      $wp_customize->add_control(new WP_Customize_Cropped_Image_Control($wp_customize,'about-img',array(
          'label'	=> __('About Us Right Side Image'),
          'section'	=> 'about',
          'settings' => 'about-img',
          'height' => '1000',
          'width' => '1500'
      )));


// ------------------------------HOW IT WORKS----------------------

$wp_customize->add_section(
'testimonals',
array(
    'title' => __('How It Works'),
    'priority' => null,
    'description'	=> __('You can change the Testimonals heading and side image'),
)
);
$wp_customize->add_setting('test_side_image');
$wp_customize->add_control(new WP_Customize_Cropped_Image_Control($wp_customize,'test_side_image',array(
    'label'	=> __('Testimonals left side image'),
    'section'	=> 'testimonals',
    'settings' => 'test_side_image',
    'height' => '1000',
    'width' => '1500'
)));
$wp_customize->add_setting('test_head',array(
    'default' => 'We offer our customers</br> the <span>best services </br>& solutions</span> ',
));
$wp_customize->add_control('test_head',array(
    'type'	=> 'textarea',
    'label'	=> __('Testimonals Heading'),
    'section'	=> 'testimonals'
));

// ----------------------------------WHY CHOOSE US ----------------------------------------------------
$wp_customize->add_section(
'why_us',
array(
    'title' => __('Why CHoose US'),
    'priority' => null,
    'description'	=> __('You can change the WHY US Heading , Icon and Describtion'),
)
);
$wp_customize->add_setting('cust_head',array(
    'default' => '24 X 7 Customer<br>support',
));
$wp_customize->add_control('cust_head',array(
    'type'	=> 'text',
    'label'	=> __('1st Col Heading'),
    'section'	=> 'why_us',
));
$wp_customize->add_setting('customer_icon');
$wp_customize->add_control(new WP_Customize_Cropped_Image_Control($wp_customize,'customer_icon',array(
    'label'	=> __('1st Col Icon'),
    'section'	=> 'why_us',
    'settings' => 'customer_icon'
)));


$wp_customize->add_setting('driver_heading',array(
    'default' => 'Experienced<br>drivers',
));
$wp_customize->add_control('driver_heading',array(
    'type'	=> 'text',
    'label'	=> __('2nd Col Heading'),
    'section'	=> 'why_us'
));
$wp_customize->add_setting('2nd_icon');
$wp_customize->add_control(new WP_Customize_Cropped_Image_Control($wp_customize,'2nd_icon',array(
    'label'	=> __('2nd Col Icon'),
    'section'	=> 'why_us',
    'settings' => '2nd_icon'
)));


$wp_customize->add_setting('3rdcol_heading',array(
    'default' => 'Experienced<br>drivers',
));
$wp_customize->add_control('3rdcol_heading',array(
    'type'	=> 'text',
    'label'	=> __('3rd Col Heading'),
    'section'	=> 'why_us'
));
$wp_customize->add_setting('3rdcol_icon');
$wp_customize->add_control(new WP_Customize_Cropped_Image_Control($wp_customize,'3rdcol_icon',array(
    'label'	=> __('3rd Col Icon'),
    'section'	=> 'why_us',
    'settings' => '3rdcol_icon'
)));


$wp_customize->add_setting('4thcol_heading',array(
    'default' => 'Experienced<br>drivers',
));
$wp_customize->add_control('4thcol_heading',array(
    'type'	=> 'text',
    'label'	=> __('4th Col Heading'),
    'section'	=> 'why_us'
));
$wp_customize->add_setting('4thcol_icon');
$wp_customize->add_control(new WP_Customize_Cropped_Image_Control($wp_customize,'4thcol_icon',array(
    'label'	=> __('4th Col Icon'),
    'section'	=> 'why_us',
    'settings' => '4thcol_icon'
)));

$wp_customize->add_setting('5thcol_heading',array(
    'default' => 'Experienced<br>drivers',
));
$wp_customize->add_control('5thcol_heading',array(
    'type'	=> 'text',
    'label'	=> __('5th Col Heading'),
    'section'	=> 'why_us'
));
$wp_customize->add_setting('5thcol_icon');
$wp_customize->add_control(new WP_Customize_Cropped_Image_Control($wp_customize,'5thcol_icon',array(
    'label'	=> __('5th Col Icon'),
    'section'	=> 'why_us',
    'settings' => '5thcol_icon'
)));

$wp_customize->add_setting('6thcol_heading',array(
    'default' => 'Experienced<br>drivers',
));
$wp_customize->add_control('6thcol_heading',array(
    'type'	=> 'text',
    'label'	=> __('6th Col Heading'),
    'section'	=> 'why_us'
));
$wp_customize->add_setting('6thcol_icon');
$wp_customize->add_control(new WP_Customize_Cropped_Image_Control($wp_customize,'6thcol_icon',array(
    'label'	=> __('6th Col Icon'),
    'section'	=> 'why_us',
    'settings' => '6thcol_icon'
)));

$wp_customize->add_setting('7thcol_heading',array(
    'default' => 'Experienced<br>drivers',
));
$wp_customize->add_control('7thcol_heading',array(
    'type'	=> 'text',
    'label'	=> __('7th Col Heading'),
    'section'	=> 'why_us'
));
$wp_customize->add_setting('7thcol_icon');
$wp_customize->add_control(new WP_Customize_Cropped_Image_Control($wp_customize,'7thcol_icon',array(
    'label'	=> __('7th Col Icon'),
    'section'	=> 'why_us',
    'settings' => '7thcol_icon'
)));

$wp_customize->add_setting('8thcol_heading',array(
    'default' => 'Experienced<br>drivers',
));
$wp_customize->add_control('8thcol_heading',array(
    'type'	=> 'text',
    'label'	=> __('8th Col Heading'),
    'section'	=> 'why_us'
));
$wp_customize->add_setting('8thcol_icon');
$wp_customize->add_control(new WP_Customize_Cropped_Image_Control($wp_customize,'8thcol_icon',array(
    'label'	=> __('8th Col Icon'),
    'section'	=> 'why_us',
    'settings' => '8thcol_icon'
)));


// ------------------------- CONTACT US---------------------------------
$wp_customize->add_section(
'contact_us',
array(
    'title' => __('Contact Us'),
    'priority' => null,
    'description'	=> __('You can change the Contact US Details'),
)
);
$wp_customize->add_setting('Adress_1',array(
    'default' => 'T.C-97/C 848, Attinkuzhy,Attipra, ',
));
$wp_customize->add_control('Adress_1',array(
    'type'	=> 'text',
    'label'	=> __('Addres Line 1'),
    'section'	=> 'contact_us'
));
$wp_customize->add_setting('Adress_2',array(
    'default' => 'Thiruvananthapuram,',
));
$wp_customize->add_control('Adress_2',array(
    'type'	=> 'text',
    'label'	=> __('Addres Line 2'),
    'section'	=> 'contact_us'
));
$wp_customize->add_setting('Adress_3',array(
    'default' => 'Thiruvananthapuram,',
));
$wp_customize->add_control('Adress_3',array(
    'type'	=> 'text',
    'label'	=> __('Addres Line 3'),
    'section'	=> 'contact_us'
));
$wp_customize->add_setting('Adress_4',array(
    'default' => 'Kerala 695582',
));
$wp_customize->add_control('Adress_4',array(
    'type'	=> 'text',
    'label'	=> __('Addres Line 4'),
    'section'	=> 'contact_us'
));

$wp_customize->add_setting('phone_1',array(
    'default' => '+91 8888 888 888',
));
$wp_customize->add_control('phone_1',array(
    'type'	=> 'text',
    'label'	=> __('Enter the primary(Apper in Top Header) Phone NO.'),
    'section'	=> 'contact_us'
));
$wp_customize->add_setting('phone_2',array(
    'default' => '+91 0000 000 000',
));
$wp_customize->add_control('phone_2',array(
    'type'	=> 'text',
    'label'	=> __('Enter the secondary PHone NO.'),
    'section'	=> 'contact_us'
));
$wp_customize->add_setting('email',array(
    'default' => 'test@hotmail.com',
));
$wp_customize->add_control('email',array(
    'type'	=> 'text',
    'label'	=> __('Enter Your Email Address'),
    'section'	=> 'contact_us'
));

    }
    add_action( 'customize_register', 'christmas_customize_register1' );
