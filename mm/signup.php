<?php
	require_once 'includes/header.inc.php';
	require_once 'includes/config.inc.php';

	$icode = '';
	if (file_exists($config['image_dir'] .'/icode.txt')) {
		$icode = trim(file_get_contents($config['image_dir'] .'/icode.txt'));
	}

	$error = $user = $pass = '';
	if (isset($_SESSION['user'])) destroy_session();

	if (isset($_POST['user'])) {
		$user   = sanitize_string($_POST['user']);
		$pass   = sanitize_string($_POST['pass']);
		$gender = sanitize_string($_POST['gender']);

		if ($icode !== sanitize_string($_POST['icode']) && $config['privacy'] === 'private') {
			die('Please request your <a href="/info/signup.php">invitation code</a>.</div></body></html>');
		}

		if ($user == '' || $pass == '') {
			$error = 'Not all fields were entered! <br><br>';
		}
		else {
			$sql = "SELECT count(1) FROM `members` WHERE `user`='$user'";
			$cnt = (int)$pdo->query($sql)->fetchColumn();
			if ($cnt >= 1) {
				$error = '<span style="color:red"><b>Sorry &mdash; that username already exists ***</b></span><br><br>';
			}
			else {
				$secret_pass = encrypt_password($pass);
				$sql = "INSERT INTO `members` (`user`, `pass`, `gender`) VALUES (:user, :pass, :gender)";
				$pdo->prepare($sql)->execute(['user' => $user, 'pass' => $secret_pass, 'gender' => $gender]);

	// 			add_htpasswd_user($user, $secret_pass);

				$img_members   = $config['image_dir'] .'/members/'   . strtolower($user);
				$img_originals = $config['image_dir'] .'/originals/' . strtolower($user);
				if (!is_dir($img_members)) {
					mkdir($img_members, 0755, true);
				}
				if (!is_dir($img_originals)) {
					mkdir($img_originals, 0755, true);
				}

				die('<h4>Account created</h4> Please Log in. </div></body></html>');
			}
		}
	}
?>

	<div class="form">
      <form method="post"> <?= $error ?>
			<fieldset>
			<div class="form-group mb-3 col-lg-4 col-md-5 col-sm-7">
				<label> </label>
				<em>Enter your details to create an account.</em>
			</div>
			<div class="form-group mb-3 col-lg-4 col-md-5 col-sm-7">
				<label> Username: </label>
				<input type="text" class="form-control" minlength="3" maxlength="16" name="user" value="<?=$user?>" placeholder="Choose a good, unique screen name..." onblur="check_user(this, '<?=$config['base_url']?>')"><span id="info"></span>
			</div>
			<div class="form-group mb-3 col-lg-4 col-md-5 col-sm-7">
				<label> Password: </label>
				<input type="text" class="form-control" minlength="4" maxlength="16" name="pass" placeholder="Make it hard to guess..." value="<?=$pass?>">
			</div>
			<div class="form-group mb-3 col-lg-4 col-md-5 col-sm-7">
				<label> Gender: </label>
				<select class="form-control" name="gender">
				<option value=""> Select... </option>
				<option value="f"> Female </option>
				<option value="m"> Male </option>
			</select>
			</div>
			<?php
				if ($config['privacy'] === 'private') {
					echo '			<div class="form-group mb-3 col-lg-4 col-md-5 col-sm-7">
							<label> Invite Code: </label>
							<input type="text" class="form-control" minlength="4" maxlength="16" name="icode" value="" placeholder="Enter the special code you were sent...">
						</div>
			';
				}
			?>

			<div class="form-group mb-3 col-lg-4 col-md-5 col-sm-7 center">
				<label> </label>
				<button type="submit" class="btn btn-secondary"> Create Account </button>
			</div>
			<fieldset>
      </form>
   </div>


<?php include 'includes/footer.inc.php' ?>
