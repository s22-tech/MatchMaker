<?php

$minimum_version = '8.0';
if (version_compare( phpversion(), $minimum_version, '<') ) {
	die( "WARNING: This script does not support PHP versions < $minimum_version <br><br>
••• Your PHP version is: ".phpversion()."\n\n" );
}

session_start();

// mb_internal_encoding('UTF-8');
// mb_http_output('UTF-8');

include_once 'config.inc.php';
include_once 'functions.inc.php';

$_SESSION['page'] = basename($_SERVER['REQUEST_URI']);

$user_str = 'Not logged in';

if (isset($_SESSION['user'])) {
	$user      = $_SESSION['user'];
	$user_str  = "You're logged in as: $user";
	$logged_in = TRUE;
}
else {
	$logged_in = FALSE;
}


if (!$logged_in && $_SESSION['page'] !== 'index.php'
                && $_SESSION['page'] !== 'signup.php'
                && $_SESSION['page'] !== 'login.php'
                && $_SESSION['page'] !== 'forgot-password.php'
   ) {
	header('Location: '. $config['rewrite_base'] .'/index.php');
	die();
}
