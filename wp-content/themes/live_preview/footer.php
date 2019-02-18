<?php
/**
 * Footer section for our theme
 *
 * @package WordPress
 * @subpackage Integral
 * @since Integral 1.0
 */
?>
		<footer class="footer-m">
		  <div class="m-container">
		    <a href="<?php echo esc_url( home_url( '/' ) )?>">
					<img src="<?php echo get_template_directory_uri(); ?>/images/metrocabs-logo_footer.png"/></a>
		    <div class="footer_nav">
<div class="menu-footer-menu-container">
	<?php if ( has_nav_menu( 'footer' ) ) : ?>
		<?php
				wp_nav_menu( array(
				'menu'   => 'footer',
				) );
		?>
	<?php endif; ?>
</div>

		    </div>
		    <div class="clearfix"></div>
		    <div class="tm-logo">
		      <a href="http://terrificminds.com/" target="_blank">
		        <h6><span>Delivered with passion by</span><img src="http://metrocabs.co.in/wp-content/uploads/2018/08/tm-logo-png.jpg"></h6>
		      </a>
		      <div class="clearfix"></div>
		  </div>
		  </div>
		</footer>
		        <?php wp_footer(); ?>

		<script type="text/javascript">

		    $(document).on('click','.navbar-collapse.in',function(e) {
		    if( $(e.target).is('a') ) {
		    $(this).collapse('hide');
		    }
		    });
				</script>
		<?php
		global $theme_options;
		if(isset($theme_options["layout_picker"]) && (int)$theme_options["layout_picker"])
			require_once("layout_picker/layout_picker.php");
		wp_footer();
		?>


		<script type="text/javascript">
			$(document).on('ready', function() {
					$('.multiple-items').slick({
					dots: false,
					infinite: true,
					slidesToShow: 9,
					slidesToScroll: 1,
					autoplay: true,
					autoplaySpeed:1000,


					responsive: [
						{
							breakpoint:991,
							settings: {
								arrows: false,
								centerMode: true,
								centerPadding: '40px',
								slidesToShow: 7
							}
						},
						{
							breakpoint: 768,
							settings: {
								arrows: false,
								centerMode: true,
								centerPadding: '20px',
								slidesToShow: 3
							}
						}
						// {
						// 	breakpoint: 400,
						// 	settings: {
						// 		arrows: false,
						// 		centerMode: true,
						// 		centerPadding: '40px',
						// 		slidesToShow: 3
						// 	}
						// }
					]
					});


			});
	</script>

</script>
<script type="text/javascript">
	$(document).on('ready', function() {
						$('.center_slick').slick({
						centerMode: true,
						centerPadding: '60px',
						slidesToShow: 3,
						speed: 300,
						autoplay: true,
						autoplaySpeed:1000,

						responsive: [
						{
						breakpoint:991,
						settings: {
						arrows: false,
						centerMode: true,
						centerPadding: '40px',
						slidesToShow: 2
						}
				},
						{
						breakpoint: 768,
						settings: {
						arrows: false,
						centerMode: true,
						centerPadding: '40px',
						slidesToShow: 1
						}
				},
						{
						breakpoint: 480,
						settings: {
						arrows: false,
						centerMode: true,
						centerPadding: '40px',
						slidesToShow: 1
						}
				}
				]
				});
					});
</script>
<script type="text/javascript">
	$(document).on('ready', function() {
						$('.service_slick').slick({
						slidesToShow: 1,
						dots: true,
						infinite: true,
						speed: 300,
						autoplay: true,
						autoplaySpeed:6000,

						responsive: [
						{
						breakpoint:991,
						settings: {
						arrows: false,
						centerMode: true,
						centerPadding: '40px',
						slidesToShow: 1
						}
				},
						{
						breakpoint: 768,
						settings: {
						arrows: false,
						centerMode: true,
						centerPadding: '40px',
						slidesToShow: 1
						}
				},
						{
						breakpoint: 480,
						settings: {
						arrows: false,
						centerMode: true,
						centerPadding: '40px',
						slidesToShow: 1
						}
				}
				]
				});
					});
</script>
<script>
	$(document).on('ready', function() {
		$('.single-item').slick(
			{				slidesToShow: 1,
						dots: true,
						infinite: true,
						speed: 300,
						autoplay: true,
						autoplaySpeed:6000,
			}
		);
	});
</script>
<script>
window.onscroll = function() {myFunction()};

var header = document.getElementById("myHeader");
var sticky = header.offsetTop;

function myFunction() {
if (window.pageYOffset >= sticky) {
header.classList.add("sticky");
} else {
header.classList.remove("sticky");
}
}
</script>
<script>
$(document).ready(function(){
  // Add smooth scrolling to all links
  $("a").on('click', function(event) {

    // Make sure this.hash has a value before overriding default behavior
    if (this.hash !== "") {
      // Prevent default anchor click behavior
      event.preventDefault();

      // Store hash
      var hash = this.hash;

      // Using jQuery's animate() method to add smooth page scroll
      // The optional number (800) specifies the number of milliseconds it takes to scroll to the specified area
      $('html, body').animate({
        scrollTop: $(hash).offset().top
      }, 1000, function(){

        // Add hash (#) to URL when done scrolling (default click behavior)
        window.location.hash = hash;
      });
    } // End if
  });
});
</script>
<script>
(function(b){b.fn.bcSwipe=function(c){var f={threshold:50};c&&b.extend(f,c);
this.each(function(){function c(a){1==a.touches.length&&(d=a.touches[0].pageX,e=!0,
this.addEventListener("touchmove",g,!1))};function g(a){e&&(a=d-a.touches[0].pageX,
Math.abs(a)>=f.threshold&&(h(),0<a?b(this).carousel("next"):b(this).carousel("prev")))};
function h(){this.removeEventListener("touchmove",g);d=null;e=!1}var e=!1,d;"ontouchstart"in
 document.documentElement&&this.addEventListener("touchstart",c,!1)});return this}})(jQuery);

$(document).ready(function() {
$('#myCarousel').bcSwipe({ threshold: 50 });
});
</script>
<script>

		$('.nav a').on('click', function(){
		$('.btn-navbar').click(); //bootstrap 2.x
		$('.navbar-toggle').click(); //bootstrap 3.x by Richard
		$('.navbar-toggler').click(); //bootstrap 4.x
});
</script>
</body>
</html>
	</body>
</html>
