<?php

require_once 'includes/header.inc.php';

echo '<h3>Your Profile</h3>';

///////////////////////////////
// Pull form fields from the db.
///////////////////////////////
$stmt = $pdo->prepare("SELECT * FROM `members` WHERE `user` = :user");
$stmt->execute(['user' => $user]);
$row = $stmt->fetch();

$birth_month = $row['birth_month'] ?? '';
$birth_year  = $row['birth_year']  ?? '';
$gender      = $row['gender']      ?? '';
$status      = $row['status']      ?? '';
$email       = $row['email']       ?? '';
$info        = $row['info']        ?? '';
// $tz       = $row['timezone']    ?? '';
$state       = $row['state']       ?? 'XX';
$relocate    = $row['relocate']    ?? '';
///////////////////////////////

if (isset($_POST['email']) && isset($_POST['gender'])) {
   $state       = sanitize_string( $_POST['state'] );
   $relocate    = sanitize_string( $_POST['relocate'] );
//    $tz       = sanitize_string( $_POST['timezone'] );
   $info        = sanitize_string( $_POST['info'] );
   $info        = preg_replace('/\s\s+/', ' ', $info);
   $email       = sanitize_string( $_POST['email'] );
   $gender      = sanitize_string( $_POST['gender'] );
   $status      = sanitize_string( $_POST['status'] );
   $birth_month = sanitize_string( $_POST['birth_month'] );
   $birth_year  = sanitize_string( $_POST['birth_year'] );

    if ($row) {
			$sql = "UPDATE `members`
			        SET info = :info, birth_month = :birth_month, birth_year = :birth_year, email = :email, status = :status,
			            gender = :gender, state = :state, relocate = :relocate
                 WHERE user = user
                 AND   user = :user";
			$pdo->prepare($sql)->execute(['user' => $user, 'info' => $info, 'gender' => $gender, 'status' => $status, 'birth_month' => $birth_month, 'birth_year' => $birth_year, 'state' => $state, 'email' => $email, 'relocate' => $relocate]);
    }
    else {
			$sql_p = "INSERT INTO `members` (`user`, `info`, `email`, `birth_month`, `birth_year`, `state`, `relocate`)
			          VALUES (:user, :info, :email, :birth_month, :birth_year, :state, :relocate)";
			$pdo->prepare($sql_p)->execute(['user' => $user, 'info' => $info,  'email' => $email, 'birth_month' => $birth_month, 'birth_year' => $birth_year, 'status' => $status]);

			$sql_m = "INSERT INTO `members` (`user`, `gender`)
			          VALUES (:user, :gender)";
			$pdo->prepare($sql_m)->execute(['user' => $user, 'gender' => $gender]);
    }
}

$info = stripslashes( preg_replace('/\s\s+/', ' ', $info) );

// echo '<pre>';
// echo 'upload_max_filesize: '. ini_get('upload_max_filesize') .'<br>';
// echo 'post_max_size: '. ini_get('post_max_size') .'<br>';
// print_r($_FILES);
// print_r($_POST);
// echo '</pre>';
## https://www.php.net/manual/en/features.file-upload.post-method.php
# $_FILES will be empty if a user attempts to upload a file greater than post_max_size in your php.ini
# post_max_size should be >= upload_max_filesize in your php.ini.

if (isset($_FILES['upload_image']['name']) && !empty($_FILES['upload_image']['name']) && isset($_POST['submit_button']) ) {
	$originals_dir = $config['image_dir'] .'/originals/'. strtolower($user);
	if (!is_dir($originals_dir)) {
		mkdir($originals_dir, 0755, true);  // true makes it recursive.
	}
	$original = $originals_dir .'/'. strtolower($user) .'-'. time() .'.jpg';
	move_uploaded_file($_FILES['upload_image']['tmp_name'], $original);
	$type_ok = TRUE;

	$src = match($_FILES['upload_image']['type']) {
		'image/jpeg', 'image/pjpeg' => imagecreatefromjpeg($original),
		'image/png' => imagecreatefrompng($original),
		'image/gif' => imagecreatefromgif($original),
		 default    => $type_ok = false,
	};

	$_FILES['upload_image']['name'] = '';
// 	unset($_FILES);
	unset($_FILES['upload_image']);

	if ($type_ok) {
		$save_to = $config['image_dir'] .'/members/'. strtolower($user) .'/'. strtolower($user) .'.jpg';
		copy_mkdir($original, $save_to);
		[$w, $h] = getimagesize($save_to);

		$max = 300;
		$tw  = $w;
		$th  = $h;

		if ($w > $h && $max < $w) {
			$th = $max / $w * $h;
			$tw = $max;
		}
		elseif ($h > $w && $max < $h) {
			$tw = $max / $h * $w;
			$th = $max;
		}
		elseif ($max < $w) {
			$tw = $th = $max;
		}

		$tmp = imagecreatetruecolor($tw, $th);
		imagecopyresampled($tmp, $src, 0, 0, 0, 0, $tw, $th, $w, $h);
		imageconvolution($tmp, array(array(-1, -1, -1),
		array(-1, 16, -1), array(-1, -1, -1)), 8, 0);
		imagejpeg($tmp, $save_to);
		imagedestroy($tmp);
		imagedestroy($src);
	}
	else {
// 		unlink($save_to);
		echo '<br><span class="error">&bull; ERROR &mdash; image must be either a jpg, png, or gif</span><br><br>';
	}
}

show_profile(strtolower($user), strtolower($user));

echo '<div class="clearfix"> &nbsp; </div>';

echo <<<"_FORM1"
<p> &nbsp; </p>
<div class="form">
	<form method="post" enctype="multipart/form-data">
	<fieldset>
	<h3>Enter / edit your details</h3>
_FORM1;

echo '<div class="row">
          <div class="form-group mb-3 col-lg-4 col-md-5 col-sm-7">' . PHP_EOL;

echo dynamic_select_menu( the_array: ['0' => 'Select&hellip;', '1' => 'January', '2' => 'February', '3' => 'March', '4' => 'April', '5' => 'May', '6' => 'June', '7' => 'July', '8' => 'August', '9' => 'September', '10' => 'October', '11' => 'November', '12' => 'December'],
                          element_name: 'birth_month',
                          label: 'Birth Month:',
                          init_value: $birth_month,
                          required: 'yes'
                        );
echo PHP_EOL . '</div>' . PHP_EOL;

$to    = date('Y') - 80;  // Max age.
$from  = date('Y') - 30;  // Min age.
$range = range($from, $to);
array_unshift($range, 'Select&hellip;');

echo '<div class="form-group mb-3 col-lg-4 col-md-5 col-sm-7">' . PHP_EOL;
echo dynamic_select_menu( the_array: $range,
                          element_name: 'birth_year',
                          label: 'Birth Year:',
                          init_value: $birth_year,
                          required: 'yes'
                        );
echo PHP_EOL . '</div>' . PHP_EOL;
echo '</div>' . PHP_EOL;


echo '<div class="row">
          <div class="form-group mb-3 col-lg-4 col-md-5 col-sm-7">' . PHP_EOL;
echo dynamic_select_menu( the_array: ['' => 'Select&hellip;', 'f' => 'Female', 'm' => 'Male'],
                          element_name: 'gender',
                          label: 'Gender:',
                          init_value: $gender,
                          required: 'yes'
                        );
echo PHP_EOL . '</div>' . PHP_EOL;

echo '<div class="form-group mb-3 col-lg-4 col-md-5 col-sm-7">' . PHP_EOL;
echo dynamic_select_menu( the_array: ['' => 'Select&hellip;', 'single' => 'Single', 'divorced' => 'Divorced', 'widowed' => 'Widowed'],
                          element_name: 'status',
                          label: 'Marital Status:',
                          init_value: $status,
                          required: 'yes'
                        );
echo PHP_EOL . '</div>' . PHP_EOL;
echo '</div>' . PHP_EOL;

// echo '<p> &nbsp; </p>';


// $timezones = array(
// 	''     => 'Select&hellip;',
// 	'-11'  => '(GMT -11:00) Midway Island, Samoa',
// 	'-10'  => '(GMT -10:00) Hawaii',
// 	'-9'   => '(GMT -9:00)  Alaska',
// 	'-8'   => '(GMT -8:00)  Pacific Time (US &amp; Canada)',
// 	'-7'   => '(GMT -7:00)  Mountain Time (US &amp; Canada)',
// 	'-6'   => '(GMT -6:00)  Central Time (US &amp; Canada)',
// 	'-5'   => '(GMT -5:00)  Eastern Time (US &amp; Canada)',
// 	'-4'   => '(GMT -4:00)  Atlantic Time (Canada), Caracas',
// 	'-3.5' => '(GMT -3:30)  Newfoundland',
// 	'-3'   => '(GMT -3:00)  Brazil, Buenos Aires, Georgetown',
// 	'-2'   => '(GMT -2:00)  Mid-Atlantic',
// 	'-1'   => '(GMT -1:00)  Azores, Cape Verde Islands',
// 	'0'    => '(GMT) Western Europe Time, London, Lisbon',
// 	'1'    => '(GMT +1:00)  Brussels, Copenhagen, Madrid, Paris',
// 	'2'    => '(GMT +2:00)  Kaliningrad, South Africa',
// 	'3'    => '(GMT +3:00)  Moscow, St. Petersburg',
// 	'4'    => '(GMT +4:00)  Baku, Tbilisi',
// 	'5'    => '(GMT +5:00)  Ekaterinburg, Karachi, Tashkent',
// 	'5.5'  => '(GMT +5:30)  Bombay, Calcutta, Madras, New Delhi',
// 	'6'    => '(GMT +6:00)  Dhaka, Colombo',
// 	'7'    => '(GMT +7:00)  Bangkok, Hanoi, Jakarta',
// 	'8'    => '(GMT +8:00)  Perth, Singapore, Hong Kong',
// 	'9'    => '(GMT +9:00)  Tokyo, Seoul, Osaka, Yakutsk',
// 	'9.5'  => '(GMT +9:30)  Adelaide, Darwin',
// 	'10'   => '(GMT +10:00) Eastern Australia, Guam, Vladivostok',
// 	'11'   => '(GMT +11:00) Magadan, Solomon Islands, Caledonia',
// 	'12'   => '(GMT +12:00) Auckland, Wellington, Fiji'
// );
//
// echo '<div class="row">
//           <div class="form-group mb-3 col-lg-5 col-md-7 col-sm-9">'. PHP_EOL;
// echo dynamic_select_menu( the_array: $timezones,
//                           element_name: 'timezone',
//                           label: 'Timezone:',
//                           init_value: $tz
//                         );
// echo '</div>'. PHP_EOL;
// echo '</div>'. PHP_EOL;

include_once 'includes/data/states.php';

echo '<div class="row">
          <div class="form-group mb-3 col-lg-4 col-md-5 col-sm-7">'. PHP_EOL;
echo dynamic_select_menu( the_array: $states,
                          element_name: 'state',
                          label: 'State:',
                          init_value: $state,
                          required: 'yes'
                        );
echo '</div>'. PHP_EOL;

echo '<div class="form-group mb-3 col-lg-4 col-md-5 col-sm-7">' . PHP_EOL;
echo dynamic_select_menu( the_array: ['' => 'Select&hellip;', 'yes' => 'Yes', 'no' => 'No', 'maybe' => 'Maybe'],
                          element_name: 'relocate',
                          label: 'Willing to relocate?',
                          init_value: $relocate,
                          required: 'yes'
                        );
echo PHP_EOL . '</div>' . PHP_EOL;
echo '</div>'. PHP_EOL;

echo <<<"_FORM2"
	<div class="form-group mb-3">
		<label for="info"> About Me: </label>
		<textarea class="form-control" name="info" id="info" rows="6" placeholder="Tell us a little about yourself&hellip;">$info</textarea>
	</div>
_FORM2;

echo '
   <div class="form-group mb-3">
		<label for="password"> Change Your Password: </label>
		<div class="input-group">
			<input type="password" class="form-control" name="password" value="" placeholder="Enter Password" autocomplete="off">
			&nbsp;
			<input type="password" class="form-control" name="password2" value="" placeholder="Verify Password" autocomplete="off">
		</div>
	</div>
';

echo <<<"_FORM3"
	<div class="row">
		<div class="form-group mb-3 col-lg-6  col-md-7 col-sm-9">
			<label for="email"> Email: </label>
			<input type="email" class="form-control" id="email" name="email" value="$email" aria-describedby="email_help" required>
			<small id="email_help" class="form-text text-muted">We will never share your email with anyone else.</small>
		</div>
	</div>

	<div class="form-group mb-3">
		<label for="upload_image"> Photo: </label>
		<input type="file" class="form-control-file" name="upload_image" id="upload_image" aria-describedby="file_help">
		<small id="file_help" class="form-text text-muted">Choose a good, clear photo of yourself to upload.</small>
	</div>

	<p> &nbsp; </p>

	<div class="form-group mb-3">
		<label> &nbsp; </label>
		<button type="submit" class="btn btn-secondary" name="submit_button"> Save / Update Your Profile </button>
	</div>

	</fieldset>
	</form>
</div>  <!-- class="form" -->
_FORM3;

clearstatcache();

include 'includes/footer.inc.php';
