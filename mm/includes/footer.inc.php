
		</div>  <!-- content -->

	</div>  <!-- container -->

		<footer class="footer center">
				<a class="btn btn-outline-secondary btn-sm" href="<?php echo $config['rewrite_base'] ?>/contact-form.php" role="button"> Contact Us </a> &nbsp;
				<a class="btn btn-outline-secondary btn-sm" href="<?php echo $config['rewrite_base'] ?>/about-us.php" role="button"> About Us </a> &nbsp;
				<a class="btn btn-outline-secondary btn-sm" href="<?php echo $config['rewrite_base'] ?>/disclaimer.php" role="button"> Disclaimer </a> &nbsp;
				<a class="btn btn-outline-secondary btn-sm" href="<?php echo $config['rewrite_base'] ?>/site-rules.php" role="button"> Rules </a>
		</footer>

		<script src="<?php echo $config['rewrite_base'] ?>/includes/js/fslightbox.js"></script>
		<script src="<?php echo $config['rewrite_base'] ?>/includes/js/bootstrap.min.js"></script>
		<script src="<?php echo $config['rewrite_base'] ?>/includes/js/functions.js"></script>

		<script>
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


			$(document).on("pagecreate", function() {
				$(".photopopup").on({
					popupbeforeposition: function() {
						var maxHeight = $( window ).height() - 60 + "px";
						$(".photopopup img").css("max-height", maxHeight);
					}
				});
			});
		</script>

  </body>
</html>
