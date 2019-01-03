<?php
get_header();
?>
<!-- <div class="theme_page relative">
	<div class="clearfix">
		<?php
		if(have_posts()) : while (have_posts()) : the_post();
			the_content();
			echo do_shortcode(shortcode_unautop(get_the_content())); // zakomentowane bo nie dzialala globalna konwersja tooltipow 22.06.2016 Sew
		endwhile; endif;
		?>
	</div>
</div> -->
<div class="content">
	<div class="service">
	  <img class="banner_img" class="img-responsive" src="<?php echo wp_get_attachment_url(get_theme_mod('service-banner-img')) ?>"/>
	    <div class="m-container">
	        <div class="m-form">
	            <div class="form-head text-center">
	              	<h1>Lorem Ipsum <br> is simply<br> dummy <br> text</h1>
	             </div>
	            <div class="s-form">
									<input type="submit" value="BOOK THE CAB" />

	            </div>
	        </div>
					<!-- <div class="m-form">
						 <div class="form-head text-center">
							 <img style="width:62px" class="img-responsive" src="<?php echo wp_get_attachment_url(get_theme_mod('form-icon')) ?>"/>
								 <h4><span>Ride</span> with Metro Cabs</h4>
							</div>
						 <div class="s-form">

							 <form method="post" action="<?php echo get_site_url()."/booking";?>">
						 <label style="" class="full-field full-border" type="text" name="fname">
						 <input type="text" name="fname" value="" size="40" aria-required="true" aria-invalid="false" placeholder="Name" />
						 </label>
						 <label style=""  class="full-field full-border" type="text" name="date">
						 <input type="text" name="date" value="" size="40" aria-required="true" aria-invalid="false" placeholder="Date" />
						 </label>
						 <label style=""  class="full-field full-border" type="text" name="time">
						 <input type="text" name="time" value="" size="40" aria-required="true" aria-invalid="false"	placeholder="Time" />
						 </label>
						 <label style=""  class="full-field full-border" type="text" name="phone">
						 <input type="text" name="phone" value="" size="40" aria-required="true" aria-invalid="false" placeholder="Phone Number" />
						 </label>
						 <input type="submit" value="BOOK THE CAB" />
						 <p class="bot">or need a custom travel plan? <br /><a href="#contact-us">Contact us</a></p>
							 </form>
						 </div>
				 </div> -->
	    </div>
	</div>
	<div class="clients">
	    <!-- <div class="m-container"> -->
	        <div class="client-list">
	            <h1>Our <span>happy</span> corporate clientele</h1>
							<section class="multiple-items">
											<?php
											$carousel = new WP_Query(array(
											'post_type' => 'client',
											));
											while($carousel -> have_posts()): $carousel -> the_post(); ?>
											<div>
												<?php echo(types_render_field("client-logo",array('row' => true))); ?>
											</div>
											<?php endwhile; ?>
							</section>
	        </div>
	    <!-- </div> -->
	</div>

	<div class="why-us" id="why-us">
	  <div class="m-container">
	      <h1 class="why-us-head">Why <span>choose us</span></h1>
	      <div class="row sections">
	          <div class="col-sm-3 col-xs-4 single_col_why">
	              <div class="row">
	                  <div class="col-md-12">
	                      <img class="img-responsive" src="<?php echo wp_get_attachment_url(get_theme_mod('customer_icon')) ?>"/>
	                  </div>
	                  <div class="col-md-12 text-center">
	                      <h1><?php echo get_theme_mod('cust_head') ?></h1>
	                  </div>
	              </div>
	          </div>
	          <div class="col-sm-3 col-xs-4 single_col_why">
	              <div class="row">
	                  <div class="col-md-12">
	                      <img class="img-responsive" src="<?php echo wp_get_attachment_url(get_theme_mod('2nd_icon')) ?>"/>
	                  </div>
	                  <div class="col-md-12 text-center">
	                    <h1><?php echo get_theme_mod('driver_heading') ?></h1>
	                  </div>
	              </div>
	          </div>
	          <div class="col-sm-3 col-xs-4 single_col_why">
	              <div class="row">
	                  <div class="col-md-12">
	                      <img class="img-responsive" src="<?php echo wp_get_attachment_url(get_theme_mod('3rdcol_icon')) ?>"/>
	                  </div>
	                  <div class="col-md-12 text-center">
	                      <h1><?php echo get_theme_mod('3rdcol_heading') ?></h1>
	                  </div>
	              </div>
	          </div>
	          <div class="col-sm-3 col-xs-4 single_col_why">
	              <div class="row">
	                  <div class="col-md-12">
	                      <img class="img-responsive" src="<?php echo wp_get_attachment_url(get_theme_mod('4thcol_icon')) ?>"/>
	                  </div>
	                  <div class="col-md-12 text-center">
	                    <h1><?php echo get_theme_mod('4thcol_heading') ?></h1>
	                  </div>
	              </div>
	          </div>

	          <div class="col-sm-3 col-xs-4 single_col_why">
	              <div class="row">
	                  <div class="col-md-12">
	                      <img class="img-responsive" src="<?php echo wp_get_attachment_url(get_theme_mod('5thcol_icon')) ?>"/>
	                  </div>
	                  <div class="col-md-12 text-center">
	                      <h1><?php echo get_theme_mod('5thcol_heading') ?></h1>
	                  </div>
	              </div>
	          </div>
	          <div class="col-sm-3 col-xs-4 single_col_why">
	              <div class="row">
	                  <div class="col-md-12">
	                      <img class="img-responsive" src="<?php echo wp_get_attachment_url(get_theme_mod('6thcol_icon')) ?>"/>
	                  </div>
	                  <div class="col-md-12 text-center">
	                    <h1><?php echo get_theme_mod('6thcol_heading') ?></h1>
	                  </div>
	              </div>
	          </div>
	          <div class="col-sm-3 col-xs-4 col-xs-offset-2 single_col_why">
	              <div class="row">
	                  <div class="col-md-12">
	                      <img class="img-responsive" src="<?php echo wp_get_attachment_url(get_theme_mod('7thcol_icon')) ?>"/>
	                  </div>
	                  <div class="col-md-12 text-center">
	                      <h1><?php echo get_theme_mod('7thcol_heading') ?></h1>
	                  </div>
	              </div>
	          </div>
	          <div class="col-sm-3 col-xs-4 single_col_why">
	              <div class="row">
	                  <div class="col-md-12">
	                      <img class="img-responsive" src="<?php echo wp_get_attachment_url(get_theme_mod('8thcol_icon')) ?>"/>
	                  </div>
	                  <div class="col-md-12 text-center">
	                    <h1><?php echo get_theme_mod('8thcol_heading') ?></h1>
	                  </div>
	              </div>
	          </div>
	       </div>
	  </div>
	</div>
	<div class="back_wrapper">
	<div class="how_it_works idea" id="service-block">
	  <div class="how_it_back"></div>
	    <div class="m-container">
	        <div class="hw_left">
	            <img class="img-responsive" src="<?php echo wp_get_attachment_url(get_theme_mod('test_side_image')) ?>"/>
	        </div>
	        <div class="how-right">
	            <h1><?php echo get_theme_mod('test_head') ?></h1>
	            <div class="service_slick">
								<?php
								$carousel = new WP_Query(array(
								'post_type' => 'services',
								'order' => 'ASC',
								));
								while($carousel -> have_posts()): $carousel -> the_post(); ?>
								<div class="how-right-contents">
									<div class="index_col">
											<h2><?php the_title(); ?></h2>
									</div>
									<div class="desp_txt">
											<?php the_content(); ?>
									</div>
								</div>
								<?php endwhile; ?>
	        </div>
	    </div>
	  </div>
	</div>
</div>
	<div class="clearfix"></div>

	<div class="testimonal-carousel">
	  <h1 class="test_head text-center">What <span>people</span> are saying about us</h1>
	  <section class="center_slick">
	          <?php
	          $carousel = new WP_Query(array(
	          'post_type' => 'testimonal',
	          ));
	          while($carousel -> have_posts()): $carousel -> the_post(); ?>
	          <div class="single_testimonals">
	            <?php echo(types_render_field("profile-picture",array('row' => true))); ?>
	            <h1 class="head"><?php the_title(); ?></h1>
	              <h3><?php echo(types_render_field("desigination",array('row' => true))); ?></h3>
	            <h2 class="desp"><?php the_content(); ?></h2>
	          </div>
	          <?php endwhile; ?>
	  </section>
	</div>

	<div class="contact-us" id="contact-us">
	  <div class="m-container">
	    <h1>Contact us</h1>
	      <div class="row">
	          <div class="col-md-4 col-sm-4 single_row">
	              <div class="addres_block">
	                <!-- <img src="<?php echo get_template_directory_uri(); ?>-child/img/c1.png"> -->
	                  <img src="<?php echo THEME_IMG_PATH; ?>/c1.png"/>
	                  <div class="data_block">
	                    <p><?php echo get_theme_mod('Adress_1') ?></p>
	                    <p><?php echo get_theme_mod('Adress_2') ?></p>
	                    <p><?php echo get_theme_mod('Adress_3') ?></p>
	                    <p><?php echo get_theme_mod('Adress_4') ?></p>

	                  </div>
	              </div>
	          </div>
	          <div class="col-md-3 col-sm-4 single_row">
	              <div class="phone_block">
	                <!-- <img src="<?php echo get_template_directory_uri(); ?>-child/img/c2.png"> -->
	                      <img src="<?php echo THEME_IMG_PATH; ?>/c2.png"/>
	                  <div class="data_block contact-align">
	                      <p onclick="document.location.href = 'tel:<?php echo get_theme_mod('phone_1') ?>'"><?php echo get_theme_mod('phone_1') ?></p>
	                      <p onclick="document.location.href = 'tel:<?php echo get_theme_mod('phone_2') ?>'"><?php echo get_theme_mod('phone_2') ?></p>
	                  </div>
	              </div>
	          </div>
	          <div class="col-md-3 col-sm-4 single_row">
	              <div class="email_block">
	                <!-- <img src="<?php echo get_template_directory_uri(); ?>-child/img/c3.png"> -->
	                      <img src="<?php echo THEME_IMG_PATH; ?>/c3.png"/>
	                  <div class="contact-align3 data_block">
	                    <a href="mailto:<?php echo get_theme_mod('email') ?>" id="tm-mail-footer" target="_top"><p><?php echo get_theme_mod('email') ?></p></a>
	                  </div>
	              </div>
	          </div>
	        </div>
	    </div>
	</div>
</div>
<?php
get_footer();
?>
