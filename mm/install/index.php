<?php include '../includes/config.inc.php'; ?>
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
			<div class="mb-3">
				<label> Your Domain: </label>
				<input type="text" class="form-control" minlength="3" maxlength="36" name="tld" value="" required>
				<div id="db_name_help" class="form-text">Enter the domain for this script.</div>
			</div>
				<label> DB Name: </label>
				<input type="text" class="form-control" minlength="3" maxlength="16" name="db_name" value="" required>
				<div id="db_name_help" class="form-text">Enter the name of the database you just created for this script.</div>
			</div>
			<div class="mb-3">
				<label> DB Host: </label>
				<input type="text" class="form-control" name="db_host" value="localhost" required>
				<div id="db_host_help" class="form-text">Change the host, if required.</div>
			</div>
			<div class="mb-3">
				<label> Port: </label>
				<input type="text" class="form-control" minlength="4" maxlength="4" name="port" value="3306" required>
				<div id="port_help" class="form-text">Change the port number, only if different than the default.</div>
			</div>
			<div class="mb-3">
				<label> DB Username: </label>
				<input type="text" class="form-control" minlength="4" maxlength="16" name="db_user" value="" required>
				<div id="db_user_help" class="form-text">Enter your database username.</div>
			</div>
			<div class="mb-3">
				<label> DB Password: </label>
				<input type="text" class="form-control" minlength="4" maxlength="16" name="db_pass" value="" required>
				<div id="db_pass_help" class="form-text">Enter your database password.</div>
			</div>
			<div class="mb-3">
				<label> Timezone: </label>
				<select class="form-control" name="timezone">
				<option value=""> Select... </option>
				<option value="-11"> (GMT -11:00) Midway Island, Samoa </option>
				<option value="-10"> (GMT -10:00) Hawaii </option>
				<option value="-9">  (GMT -9:00)  Alaska </option>
				<option value="-8">  (GMT -8:00)  Pacific Time (US & Canada) </option>
				<option value="-7">  (GMT -7:00)  Mountain Time (US & Canada) </option>
				<option value="-6">  (GMT -6:00)  Central Time (US & Canada) </option>
				<option value="-5">  (GMT -5:00)  Eastern Time (US & Canada) </option>
				<option value="-4">  (GMT -4:00)  Atlantic Time (Canada), Caracas </option>
				<option value="-3">  (GMT -3:30)  Newfoundland </option>
				<option value="-3">  (GMT -3:00)  Brazil, Buenos Aires, Georgetown </option>
				<option value="-2">  (GMT -2:00)  Mid-Atlantic </option>
				<option value="-1">  (GMT -1:00)  Azores, Cape Verde Islands </option>
				<option value="0">   (GMT) Western Europe Time, London, Lisbon </option>
				<option value="1">   (GMT +1:00)  Brussels, Copenhagen, Madrid, Paris </option>
				<option value="2">   (GMT +2:00)  Kaliningrad, South Africa </option>
				<option value="3">   (GMT +3:00)  Moscow, St. Petersburg </option>
				<option value="4">   (GMT +4:00)  Baku, Tbilisi </option>
				<option value="5">   (GMT +5:00)  Ekaterinburg, Karachi, Tashkent </option>
				<option value="5.5"> (GMT +5:30)  Bombay, Calcutta, Madras, New Delhi </option>
				<option value="6">   (GMT +6:00)  Dhaka, Colombo </option>
				<option value="7">   (GMT +7:00)  Bangkok, Hanoi, Jakarta </option>
				<option value="8">   (GMT +8:00)  Perth, Singapore, Hong Kong </option>
				<option value="9">   (GMT +9:00)  Tokyo, Seoul, Osaka, Yakutsk </option>
				<option value="8.5"> (GMT +9:30)  Adelaide, Darwin </option>
				<option value="10">  (GMT +10:00) Eastern Australia, Guam, Vladivostok </option>
				<option value="11">  (GMT +11:00) Magadan, Solomon Islands, Caledonia </option>
				<option value="12">  (GMT +12:00) Auckland, Wellington, Fiji </option>
			</select>
				<div id="db_tz_help" class="form-text">Enter the timezone of your server.</div>
			</div>
			<div class="mb-3">
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
