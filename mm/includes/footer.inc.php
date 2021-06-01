
		</div>  <!-- container -->

	</div>  <!-- content -->

		<footer class="footer center">
				<a class="btn btn-outline-secondary btn-sm" href="<?php echo $config['rewrite_base'] ?>/contact-form.php" role="button"> Contact Us </a> &nbsp;
				<a class="btn btn-outline-secondary btn-sm" href="<?php echo $config['rewrite_base'] ?>/about-cls.php" role="button"> About CLS </a> &nbsp;
				<a class="btn btn-outline-secondary btn-sm" href="<?php echo $config['rewrite_base'] ?>/disclaimer.php" role="button"> Disclaimer </a> &nbsp;
				<a class="btn btn-outline-secondary btn-sm" href="<?php echo $config['rewrite_base'] ?>/site-rules.php" role="button"> Rules </a>
		</footer>

		<script>
			$( document ).on( "pagecreate", function() {
				$( ".photopopup" ).on({
					popupbeforeposition: function() {
						var maxHeight = $( window ).height() - 60 + "px";
						$( ".photopopup img" ).css( "max-height", maxHeight );
					}
				});
			});


			// Choose from 3 different button styles.
			$(document).ready(function () {
			  $(".first-button").on("click", function () {
				 $(".animated-icon1").toggleClass("open");
			  });
			  $(".second-button").on("click", function () {
				 $(".animated-icon2").toggleClass("open");
			  });
			  $(".third-button").on("click", function () {
				 $(".animated-icon3").toggleClass("open");
			  });
			});
		</script>

		<?php // jQuery must be loaded in the header before the Lightcase code to work.  ?>
		<script src="<?php echo $config['rewrite_base'] ?>/vendor/lightcase/js/lightcase.js"></script>
		<script src="<?php echo $config['rewrite_base'] ?>/includes/js/javascript.js"></script>
		<script src="<?php echo $config['rewrite_base'] ?>/includes/js/functions.js"></script>

		<script>
			jQuery(document).ready(function($) {
				$('a[data-rel^=lightcase]').lightcase({
					swipe: true,
					fullscreenModeForMobile: true
				});
			});
		</script>

  </body>
</html>
