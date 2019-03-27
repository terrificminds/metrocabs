<?php
/**
 * Header section for our theme
 *
 * @package WordPress
 * @subpackage Integral
 * @since Integral 1.0
 */
?>
<?php global $integral; ?>
<!DOCTYPE html>
<!--[if IE 7]>
<html class="ie ie7" <?php language_attributes(); ?>>
<![endif]-->
<!--[if IE 8]>
<html class="ie ie8" <?php language_attributes(); ?>>
<![endif]-->
<!--[if !(IE 7) | !(IE 8) ]><!-->
<html <?php language_attributes(); ?>>
<!--<![endif]-->
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="format-detection" content="telephone=no">
    <link rel="profile" href="http://gmpg.org/xfn/11">
    <link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">
    <link href="https://fonts.googleapis.com/css?family=Quicksand:300,400,500,700" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Josefin+Sans:400,600,700" rel="stylesheet">
    <style>
    .slider {
        width: 50%;
        margin: 100px auto;
    }
    .slick-slide {
      margin: 0px 20px;
    }
    .slick-slide img {
      width: 100%;
    }
    .slick-prev:before,
    .slick-next:before {
      color: black;
    }
    .slick-slide {
      transition: all ease-in-out .3s;
      opacity: 1;
    }
    .slick-active {
      opacity: 1;
    }
    .slick-current {
      opacity: 1;
    }
    </style>
    <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>

  <div class="top-container top_contact" id="top_docking">
    <div class="m-container">
      <ul>
        <li class="mail"> <a style="color:white;" href="mailto:<?php echo get_theme_mod('email') ?>" target="_top"> Mail : <span><?php echo get_theme_mod('email') ?></span> <a/> </li>
        <li style="cursor:pointer;" onclick="document.location.href = 'tel:<?php echo get_theme_mod('phone_1') ?>'" class="phone">Phone : <span><?php echo get_theme_mod('phone_1') ?></span> </li>
      </ul>
    </div>
  </div>
        <nav class="navbar navbar-default header" id="myHeader" role="navigation">
            <div class="container-fluid">
                <div class="m-container">

                    <div class="navbar-header">

                        <button type="button" class="navbar-toggle navbar-collapse" data-toggle="collapse" data-target="#navbar-ex-collapse">

                            <span class="sr-only"><?php _e( 'Toggle navigation', 'integral' ); ?></span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>

                        </button>

                        <div class="navbar-brand">
                        <a href="/">  <img src="<?php echo get_template_directory_uri(); ?>/images/metrocabs-logo.png"/></a>
                        </div>

                    </div>
                    <?php
				//Get menu object
				$locations = get_nav_menu_locations();
				$main_menu_object = get_term($locations["main-menu"], "nav_menu");
				if(has_nav_menu("main-menu") && $main_menu_object->count>0)
				{
					wp_nav_menu(array(
            'menu'              => 'main-menu',
						"theme_location"    => "main-menu",
						"menu_class"        => "navbar-right nav navbar-nav",
            'depth'             => 3,
            'container'         => 'div',
            'container_class'   => 'collapse navbar-collapse',
            'container_id'      => 'navbar-ex-collapse',
					));
				}
				?>
               </div>

            </div>

        </nav>
