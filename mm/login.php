<?php

require_once 'includes/header.inc.php';
$error = $user = $pass = '';

if (isset($_POST['user'])) {
	$user = sanitize_string($_POST['user']);
	$pass = sanitize_string($_POST['pass']);

	if ($user == '' || $pass == '') {
		$error = 'Not all fields were entered';
	}
	else {
		$stmt = $pdo->prepare("SELECT `pass` FROM `members` WHERE `user`= :user");
		$stmt->execute(['user' => $user]);
		$stored = $stmt->fetchColumn();

		if (password_verify($pass, $stored)) {
			$my_time = new DateTime('now', new DateTimeZone($config['tz']));
			file_put_contents('visitors_log.txt', date($my_time->format('y-m-d @ h:i:s')) . ' -- ' . $user . PHP_EOL , FILE_APPEND | LOCK_EX);

			$_SESSION['user'] = $user;
			$_SESSION['pass'] = $pass;

// 				echo "<script> window.location.assign('{$config['rewrite_base']}/members.php?view=$user'); </script>";
			echo "<script> window.location.assign('{$config['rewrite_base']}/profile.php'); </script>";
			exit;
		}
		else {
			$error = 'Invalid login attempt';
		}
	}
}

echo <<<_END
	<div class="form">
		<form method="post" action="{$config['rewrite_base']}/login.php">
			<fieldset>
			<span class="error"> $error </span>
			<div class="form-group mb-3 col-lg-4">
				<label> </label>
				Please enter your login details...
			</div>
			<div class="form-group mb-3 col-lg-4 col-md-5 col-sm-7">
				<label> Username </label>
				<input type="text" class="form-control" maxlength="16" name="user" value="$user">
			</div>
			<div class="form-group mb-3 col-lg-4 col-md-5 col-sm-7">
				<label> Password </label>
				<input type="password" class="form-control" maxlength="16" name="pass" value="$pass">
			</div>
			<div class="form-group mb-3 col-lg-4 col-md-5 col-sm-7">
				<label> </label>
				<input type="submit" class="btn btn-secondary" value="Login">
			</div>
			<fieldset>
		</form>
	</div>  <!-- form -->

_END;

include_once 'includes/footer.inc.php';
