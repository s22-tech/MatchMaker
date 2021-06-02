<?php	/* --- ۞---> text { encoding:utf-8; linebreaks:unix; tabs:3sp; } */

include 'config.inc.php';

/////////////
// GLOBALS //
/////////////
$htpasswd = $config['rewrite_base'] .'/.htpasswd';

$dsn = "mysql:host={$config['db_host']};port={$config['db_port']};dbname={$config['db_name']};charset={$config['charset']}";
$options = [
	 PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
	 PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
	 PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
	$pdo = new PDO($dsn, $config['db_user'], $config['db_pass'], $options);
}
catch (PDOException $e) {
	throw new PDOException($e->getMessage(), (int)$e->getCode());
}

/////////////

function destroy_session () {
   $_SESSION = array();
   if (session_id() !== '' || isset($_COOKIE[session_name()])) {
//       setcookie( session_name(), '', time() - 2592000, '/' );
   }
    session_destroy();
}


function sanitize_string ($var) {
	$var = trim($var);
   $var = strip_tags($var);
   $var = htmlentities($var);
   return $var;
}


////////////////////
// This echo's a summary, plus returns the $row array.
////////////////////
function show_profile ($view, $user) {
	global $pdo, $config;
	echo PHP_EOL . '<div class="row">' . PHP_EOL;
	echo PHP_EOL . '<div class="col-lg-4">' . PHP_EOL;
	$img_src = $config['rewrite_base'] .'/includes/get_image.php?view='. strtolower($view) .'/'. $view .'.jpg';

	$originals = $config['image_dir'] .'/originals/'. strtolower($view);
	if (is_dir($originals)) {
		$files = scandir($originals, SCANDIR_SORT_DESCENDING);
		$newest_img = $files[0];
	}
	if (file_exists($config['image_dir'] .'/members/'. strtolower($view) .'/'. $view .'.jpg')) {
		echo '<a href="'. $config['rewrite_base'] .'/images/originals/'. strtolower($view) .'/'. $newest_img .'" data-rel="lightcase"> <img src="'. $img_src .'" style="float:left;" alt="This is the default caption text" /> </a>';
	}
	else {
		echo '<img src="'. $config['rewrite_base'] .'/images/no_image_yet.png" width="90px" height="90px" style="float:left;" />';
	}

   echo '</div>' . PHP_EOL;

	$stmt = $pdo->prepare("SELECT * FROM `foes` WHERE `user`= :user AND `foe`= :view");
	$stmt->execute(['view' => $view, 'user' => $user]);
	$foe_row = $stmt->fetch();

	$stmt = $pdo->prepare("SELECT * FROM `members` WHERE `user`= :view");
	$stmt->execute(['view' => $view]);
	$row = $stmt->fetch();

   if ($row) {
   	$birth_year = $row['birth_year'] == 0 ? '' : $row['birth_year'];
   	echo '<div class="col-lg-6">';
      echo '<b>Marital Status:</b> '. ucwords($row['status']) . '<br>'. PHP_EOL;  // never married
   	$month = $row['birth_month'] ? DateTime::createFromFormat('!m', $row['birth_month'])->format('F') : '';
      echo '<b>Birthdate:</b> '. $month . ' ' . $birth_year . '<br>'. PHP_EOL;
      echo '<b>State:</b> '. $row['state'] . '<br>'. PHP_EOL;

		$age = '';
		if ($row['birth_year'] && $row['birth_month']) {
			$birth_date = DateTime::createFromFormat('Y-m-d', $row['birth_year'] . '-' . $row['birth_month'] . '-01');
			$today = new DateTime();
			$age_diff = $today->diff($birth_date);
			$age = $age_diff->format('%y years old');
		}

      echo '<b>Age:</b> '. $age . '<br><br>'. PHP_EOL;

      if ($view !== $user) {
			if ($foe_row) {
				echo '<a class="btn btn-outline-danger btn-sm" href="'. $config['rewrite_base'] .'/members.php?unblock=' . $view .'"> Unblock this user </a>';
			}
			else {
				echo '<a class="btn btn-outline-danger btn-sm" href="'. $config['rewrite_base'] .'/members.php?block=' . $view .'"> Block this user </a>';
			}
		}
// 		echo ' &nbsp; &nbsp; <a class="btn btn-outline-secondary btn-sm" role="button" onclick="location.reload(true);"> Refresh Profile </a>';

		if ($view !== $user) {
			echo '<br><br><a class="btn btn-outline-secondary btn-sm" href="'. $config['rewrite_base'] .'/members.php" role="button"> View All Members </a>';
      }
		echo '</div>'. PHP_EOL .'</div> <!-- row -->'. PHP_EOL . PHP_EOL;
   }
   else {
      echo '<small>Profile is empty.  ';
      if (isset($_GET['view']) && $view !== $_GET['view']) {
      	echo 'Send a private message and ask them to post a picture.</small></div><br>';
      }
      echo '</div> <!-- row -->' . PHP_EOL;
   }
   echo '<br>'. PHP_EOL;
   return $row;
}


function dynamic_select_menu ($the_array, $element_name, $label = '', $init_value = '', $required = '') {
   $menu = '';
   if ($label !== '') {
   	$menu .= '<label for="'.$element_name.'">'.$label.'</label>'. PHP_EOL;
   }
   $required = $required ? ' required' : '';
   $menu .= '<select class="form-control" name="'. $element_name .'" id="'. $element_name .'"'. $required .'>'. PHP_EOL;
   if (empty($_REQUEST[$element_name])) {
      $curr_val = $init_value;
   }
   else {
      $curr_val = $_REQUEST[$element_name];
   }
   foreach ($the_array as $key => $value) {
   	$key = is_numeric($value) ? $value : $key;
      $menu .= '
      <option value="'.$key.'"';
      if ($key == $curr_val) $menu .= ' selected="selected"';
         $menu .= '>'.$value.'</option>';
      }
      $menu .= '
      </select>'. PHP_EOL;
   return $menu;
}


function encrypt_password ($password) {
	## https://www.virendrachandak.com/techtalk/using-php-bcrypt-algorithm-for-htpasswd-generation/
	return password_hash($password, PASSWORD_DEFAULT);
}


function verify_password ($input, $stored) {
	$hash_default_salt  = password_hash($input, PASSWORD_DEFAULT);
	return password_verify($input, $stored );  // Returns the hashed password, or false on failure.
}


function add_htpasswd_user ($username, $password) {
	global $htpasswd;
	if (!file_exists($htpasswd)) {
		touch($htpasswd);
	}
	delete_htpasswd_user($username);
	$secret = $username .':'. $password . PHP_EOL;
	file_put_contents($htpasswd, $secret, FILE_APPEND | LOCK_EX);
}


function delete_htpasswd_user ($username) {
	global $htpasswd;
	$lines = file($htpasswd, FILE_IGNORE_NEW_LINES);
	foreach ($lines as $key => $line) {
		if (str_starts_with($line.':', $username)) unset($lines[$key]);
		$to_delete = 'yes';
	}
	if ($to_delete === 'yes') {
		$contents = implode(PHP_EOL, $lines);
		file_put_contents($htpasswd, $contents . PHP_EOL, LOCK_EX);
	}
}


function scale_image ($orig_width, $orig_height, $max_width, $max_height) {
	$orientation = $orig_height > $orig_width ? 'tall' : 'wide';
	$new_height = $used_height = $orig_height;
	$used_width = $orig_width;
	$scale = $orig_width / $orig_height;
	if ($orig_width > $max_width) {
		$new_height = round(($max_width / $orig_width) * $orig_height);
		$new_width  = round(($max_height / $orig_height) * $orig_width);
		if ($orientation == 'tall') {
			if ($new_height > $max_height) {
				$used_width = $new_width;
				$used_height = $max_height;
			}
			else {
				$used_width = $max_width;
				$used_height = $new_height;
			}
		}
		else {
			// If $orientation = wide ...
// 			$new_height = round($max_width * $scale);
			$used_width = $max_width;
			$used_height = $new_height;
		}
	}
	return [$used_width, $used_height];
}


function resizer ($source, $destination, $size, $quality=null) {
// $source - Original image file
// $destination - Resized image file name
// $size - Single number for percentage resize
// Array of 2 numbers for fixed width + height
// $quality - Optional image quality. JPG & WEBP = 0 to 100, PNG = -1 to 9

  // (A) FILE CHECKS
  // Allowed image file extensions.
	$ext = strtolower(pathinfo($source)['extension']);
	if (!in_array($ext, ['bmp', 'gif', 'jpg', 'jpeg', 'png', 'webp'])) {
		throw new Exception('Invalid image file type');
	}

  // Source image not found!
	if (!file_exists($source)) {
		throw new Exception('Source image file not found');
	}

  // (B) IMAGE DIMENSIONS
	$dimensions = getimagesize($source);
	$width  = $dimensions[0];
	$height = $dimensions[1];

	if (is_array($size)) {
		$new_width  = $size[0];
		$new_height = $size[1];
	}
	else {
		$new_width  = ceil(($size / 100) * $width);
		$new_height = ceil(($size / 100) * $height);
	}

  // (C) RESIZE
  // Respective PHP image functions.
	$fnCreate = 'imagecreatefrom' . ($ext == 'jpg' ? 'jpeg' : $ext);
	$fnOutput = 'image' . ($ext == 'jpg' ? 'jpeg' : $ext);

  // Image objects.
	$original = $fnCreate($source);
	$resized = imagecreatetruecolor($new_width, $new_height);

  // Transparent images only.
	if ($ext == 'png' || $ext == 'gif') {
		imagealphablending($resized, false);
		imagesavealpha($resized, true);
		imagefilledrectangle(
      $resized, 0, 0, $new_width, $new_height,
      imagecolorallocatealpha($resized, 255, 255, 255, 127)
		);
	}

  // Copy & resize.
	imagecopyresampled( $resized, $original, 0, 0, 0, 0, $new_width, $new_height, $width, $height );

  // (D) OUTPUT & CLEAN UP
	if (is_numeric($quality)) {
		$fnOutput($resized, $destination, $quality);
	}
	else {
		$fnOutput($resized, $destination);
	}
	imagedestroy($original);
	imagedestroy($resized);
}

// Does this work?
function resize2 ($file, $width, $height) {
	$img = match(pathinfo($file)['extension']) {
		'png'   => imagepng(imagescale(imagecreatefrompng($file), $width, $height), $file),
		'gif'   => imagegif(imagescale(imagecreatefromgif($file), $width, $height), $file),
		default => imagejpeg(imagescale(imagecreatefromjpeg($file), $width, $height), $file),
	};
	return $img;
}


// function get_setting () {
//
// }


function send_email ($user) {
	global $pdo, $config;
	if ($user === 'admin') {
		$to_address = $config['email'];
	}
	else {
		$stmt = $pdo->prepare("SELECT `email` FROM `members` WHERE `user` = :user");
		$stmt->execute(['user' => $user]);
		$to_address = $stmt->fetchColumn();
	}
	$subject  = 'You have a message on the MatchMaker site!';
	$message  = 'Hello ' . ucwords($user) . ',<br><br>';
	$message .= '&nbsp; &nbsp; Someone has sent you a message on the MatchMaker site.  <a href="'. $config['base_url'] .'">  Please login </a> to view your private message.<br><br>';
	$message .= 'The Admin,<br>';
	$message .= '<a href="'. $config['base_url'] .'">'. $config['site_name'] .'</a>';
	$headers['MIME-Version'] = '1.0';
	$headers['Content-type'] = 'text/html;charset=UTF-8';
	$headers['Return-Path']  = 'bounced@'. $config['tld'];
	$headers['From']         = $config['email'];
	$headers['Bcc']          = $config['email'];  // Use only for testing.
	mail($to_address, $subject, $message, $headers);
}


function copy_mkdir ($original, $save_to) {
    $path = pathinfo($save_to);
    if (!file_exists($path['dirname'])) {
        mkdir($path['dirname'], 0755, true);
    }
    if (!copy($original, $save_to)) {
        echo 'ERROR: copy failed.' . PHP_EOL;
    }
}


// This won't work since the variable names or placeholders in $exe_str and $var_str are sometimes different.
function query_pdo ($type, $table, $variables, $fetch) {
	global $pdo;
	$exe_str = $var_str = '';
	$view = $user = '';
	foreach ($variables as $key => $var) {
// 		$exe_str .= "'$key' => '$var', ";
// 		$var_str .= "`$key`= :$key AND ";
		$var_str .= "`$key`= '$var' AND ";
	}
	$var_str = rtrim($var_str, ' AND ');
	echo '<br>';
	echo 'exe_str: ' . $exe_str . '<br>';
	echo 'var_str: ' . $var_str . '<br>';

	$sql = strtoupper($type) . ' * FROM `' . $table . '` WHERE ' . $var_str;
	echo $sql . '<br>';
	$stmt = $pdo->prepare($sql);
// 	$stmt->execute([$exe_str]);

	$result = match($fetch) {
		'row'    => $stmt->fetch(),
		'rows'   => $stmt->fetchAll(),
		'column' => $stmt->fetchColumn(),
	};
	return $result;
}
