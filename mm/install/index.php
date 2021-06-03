<?php
require_once '../includes/init.inc.php'; ?>
<!doctype html>
<html>
  <head>
	<meta name="robots" content="noindex,nofollow,noarchive" />
	<title>Install MatchMaker</title>
	<base href="<?php echo $config['base_url'] ;?>">
	<link rel="stylesheet" href="<?php echo $config['rewrite_base'] ;?>/includes/css/normalize.css" />
	<link rel="stylesheet" href="<?php echo $config['rewrite_base'] ;?>/includes/css/font-awesome.min.css" />
	<link rel="stylesheet" href="<?php echo $config['rewrite_base'] ;?>/includes/css/bootstrap.css" />
	<link rel="stylesheet" href="<?php echo $config['rewrite_base'] ;?>/includes/css/mm.css" />

	<script src="<?php echo $config['rewrite_base'] ;?>/includes/js/javascript.js"></script>
	<script src="<?php echo $config['rewrite_base'] ;?>/includes/js/jquery-3.6.0.min.js"></script>
  </head>
  <body>

	<div class="container">
	<div class="content">
	<br>
	<h1>MatchMaker Installer</h1>
	<h4>Enter your details to create the db tables.</h4>
	<br>

	<div class="form">
      <form method="post" action="<?php echo $config['rewrite_base'] ;?>/install/create_tables.php">
			<div class="mb-3 col-lg-5 col-md-7 col-sm-9">
				<label> Your Domain: </label>
				<input type="text" class="form-control" minlength="3" maxlength="36" name="tld" value="" required>
				<div id="db_name_help" class="form-text">Enter the domain for this script.</div>
			</div>
			<div class="mb-3 col-lg-5 col-md-7 col-sm-9">
				<label> DB Name: </label>
				<input type="text" class="form-control" minlength="3" maxlength="16" name="db_name" value="" required>
				<div id="db_name_help" class="form-text">Enter the name of the database you just created for this script.</div>
			</div>
			<div class="mb-3 col-lg-5 col-md-7 col-sm-9">
				<label> DB Host: </label>
				<input type="text" class="form-control" name="db_host" value="localhost" required>
				<div id="db_host_help" class="form-text">Change the host, if required.</div>
			</div>
			<div class="mb-3 col-lg-5 col-md-7 col-sm-9">
				<label> Port: </label>
				<input type="text" class="form-control" minlength="4" maxlength="4" name="port" value="3306" required>
				<div id="port_help" class="form-text">Change the port number, only if different than the default.</div>
			</div>
			<div class="mb-3 col-lg-5 col-md-7 col-sm-9">
				<label> DB Username: </label>
				<input type="text" class="form-control" minlength="4" maxlength="16" name="db_user" value="" required>
				<div id="db_user_help" class="form-text">Enter your database username.</div>
			</div>
			<div class="mb-3 col-lg-5 col-md-7 col-sm-9">
				<label> DB Password: </label>
				<input type="text" class="form-control" minlength="4" maxlength="16" name="db_pass" value="" required>
				<div id="db_pass_help" class="form-text">Enter your database password.</div>
			</div>

			<?php
				include_once '../includes/data/timezones.php';

				echo '<div class="row">
							 <div class="mb-3 col-lg-5 col-md-7 col-sm-9">'. PHP_EOL;
				echo dynamic_select_menu( the_array: $timezones,
												  element_name: 'timezone',
												  label: 'Timezone:',
												  init_value: $tz
												);
				echo PHP_EOL . '</div>'. PHP_EOL;
				echo '<div id="db_tz_help" class="form-text">Enter the timezone of your server.</div>';
				echo '</div>'. PHP_EOL;
			?>

			<div class="mb-3 col-lg-5 col-md-7 col-sm-9">
				<label> Type: </label>
				<select class="form-control" name="privacy">
				<option value=""> Select... </option>
				<option value="private"> Private - invitation only </option>
				<option value="public">  Public - open to all </option>
			</select>
				<div id="db_tz_type" class="form-text">Is this an open-to-all site, or is it a private, membership only site?</div>
			</div>
			<div class="mb-3">
				<button type="submit" name="submit" class="btn btn-secondary"> Create Tables </button>
			</div>
      </form>
   </div>

   </div>
   </div>

  </body>
</html>
