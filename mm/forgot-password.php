<?php require_once 'includes/header.inc.php' ?>

			<div class="signup-align">
				<form name="login" action="" method="post" onsubmit="return loginValidation()">
					<h3 class="mb-4">Forgot Your Password?</h3>

				   <div class="row mb-3 col-lg-4 col-md-5 col-sm-7">
							<div class="form-label">
								Enter Your Username:<span class="required error" id="username-info"></span>
							</div>
							<input class="input-box-330" type="text" name="username" id="username">
					</div>
				   <div class="row mb-3 col-lg-4 col-md-5 col-sm-7">
							<div class="form-label">
								Enter Your Email:<span class="required error" id="email-info"></span>
							</div>
							<input class="input-box-330" type="text" name="email" id="email">
					</div>
					<div class="row mb-3 col-lg-4 col-md-5 col-sm-7">
						<input class="btn btn-primary" type="submit" name="forgot-btn" id="forgot-btn" value="Request New Password">
					</div>
				</form>
			</div>
		</div>
	</div>

<?php require_once 'includes/footer.inc.php' ?>
