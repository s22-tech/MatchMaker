<?php require_once 'init.inc.php' ?>
<!doctype html>
<html lang="en-US">
<head>
<meta charset="utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<?php
	if ($config['privacy'] === 'private') {
		echo '<meta name="robots" content="noindex,nofollow,noarchive" />
';
	}
?>
<title> <?php echo $config['app_name'] .' | '. $user_str ?> </title>

<base href="<?php echo $config['base_url'] ?>" />

<link rel="stylesheet" href="<?php echo $config['rewrite_base'] ?>/includes/css/normalize.css" type="text/css" />
<link rel="stylesheet" href="<?php echo $config['rewrite_base'] ?>/includes/css/font-awesome.min.css" type="text/css" />
<link rel="stylesheet" href="<?php echo $config['rewrite_base'] ?>/vendor/lightcase/css/lightcase.css" type="text/css">
<link rel="stylesheet" href="<?php echo $config['rewrite_base'] ?>/includes/css/mm.css" type="text/css" />
<link rel="stylesheet" href="<?php echo $config['rewrite_base'] ?>/includes/css/bootstrap.css" media="screen" />

</head>

<body>

<div class="content">

<?php
	$page = basename($_SERVER['PHP_SELF']);
// 	echo $page.'<br>';

	if (!$logged_in) {
		$home_link = '<a class="nav-link" href="'. $config['base_url'] .'"> Home <span class="visually-hidden">(current)</span></a>';
	}
	else if (str_contains($page, 'members.php?view=' . $user) || $page == 'index.php') {
		$home_link = '<span class="nav-link active"> Home <span class="visually-hidden">(current)</span></span>';
	}
	else {
		$home_link = '<a class="nav-link" href="javascript:ahref_go(\''. $config['rewrite_base'] .'/members.php?view='. $user .'\');"> Home </a>';
	}
	if (!$logged_in) {
		$profile_link = '<span class="nav-link"> Edit Profile <span class="visually-hidden">(current)</span></span>';
	}
	else if ($page === 'profile.php') {
		$profile_link = '<span class="nav-link active"> Edit Profile <span class="visually-hidden">(current)</span></span>';
	}
	else {
		$profile_link = '<a class="nav-link" href="javascript:ahref_go(\''. $config['rewrite_base'] .'/profile.php\');"> Edit Profile </a>';
	}
	if (!$logged_in) {
		$members_link = '<span class="nav-link"> Members <span class="visually-hidden">(current)</span></span>';
	}
	else if ($page === 'members.php') {
		$members_link = '<span class="nav-link active"> Members <span class="visually-hidden">(current)</span></span>';
	}
	else {
		$members_link = '<a class="nav-link" href="javascript:ahref_go(\''. $config['rewrite_base'] .'/members.php\');"> Members </a>';
	}
	if (!$logged_in) {
		$friends_link = '<span class="nav-link"> Friends <span class="visually-hidden">(current)</span></span>';
	}
	else if ($page === 'friends.php') {
		$friends_link = '<span class="nav-link active"> Friends <span class="visually-hidden">(current)</span></span>';
	}
	else {
		$friends_link = '<a class="nav-link" href="javascript:ahref_go(\''. $config['rewrite_base'] .'/friends.php\');"> Friends </a>';
	}
	if (!$logged_in) {
		$foes_link = '<span class="nav-link"> Foes <span class="visually-hidden">(current)</span></span>';
	}
	else if ($page === 'foes.php') {
		$foes_link = '<span class="nav-link active"> Foes <span class="visually-hidden">(current)</span></span>';
	}
	else {
		$foes_link = '<a class="nav-link" href="javascript:ahref_go(\''. $config['rewrite_base'] .'/foes.php\');"> Foes </a>';
	}
	if (!$logged_in) {
		$messages_link = '<span class="nav-link"> Messages <span class="visually-hidden">(current)</span></span>';
	}
	else if ($page === 'messages.php') {
		$messages_link = '<span class="nav-link active"> Messages <span class="visually-hidden">(current)</span></span>';
	}
	else {
		$messages_link = '<a class="nav-link" href="javascript:ahref_go(\''. $config['rewrite_base'] .'/messages.php\');"> Messages </a>';
	}
	if (!$logged_in) {
		$logout_link = '<span class="nav-link"> Logout <span class="visually-hidden">(current)</span></span>';
	}
	else if ($page === 'logout.php') {
		$logout_link = '<span class="nav-link active"> Logout <span class="visually-hidden">(current)</span></span>';
	}
	else {
		$logout_link = '<a class="nav-link" href="javascript:ahref_go(\''. $config['rewrite_base'] .'/logout.php\');"> Logout </a>';
	}
?>

<div class="container">
	<header class="site-header" style="background:#fbfbfb">

<?php
	echo <<<"_HEAD"
			  <div id="logo-txt" class="center"> {$config['app_name']} <img id="logo-img" src="{$config['logo']}"> </div>
			  <div class="center" style="font-size: 1.5em;"> {$config['site_name']} </div>

	_HEAD;
?>

<div class="bs-component">
	<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
		<div class="container-fluid">
			<a class="navbar-brand">Navbar</a>
			<button class="navbar-toggler second-button" type="button" data-bs-toggle="collapse" data-bs-target="#navbarColor02" aria-controls="navbarColor02" aria-expanded="false" aria-label="Toggle navigation">
			<span class="navbar-toggler-icon animated-icon2"></span>
			</button>

			<div class="collapse navbar-collapse" id="navbarColor02">
				<ul class="navbar-nav me-auto">
					<li class="nav-item">
						<?php echo $home_link . PHP_EOL ?>
					</li>
					<li class="nav-item">
						<?php echo $profile_link . PHP_EOL ?>
					</li>
					<li class="nav-item">
						<?php echo $members_link . PHP_EOL ?>
					</li>
					<li class="nav-item">
						<?php echo $friends_link . PHP_EOL ?>
					</li>
					<li class="nav-item">
						<?php echo $foes_link . PHP_EOL ?>
					</li>
					<li class="nav-item">
						<?php echo $messages_link . PHP_EOL ?>
					</li>
					<li class="nav-item">
						<?php echo $logout_link . PHP_EOL ?>
					</li>
				</ul>

<!--
	  <form class="d-flex">
		<input class="form-control me-sm-2" type="text" placeholder="Search">
		<button class="btn btn-secondary my-2 my-sm-0" type="submit">Search</button>
	  </form>
 -->

 				<?php echo '<span class="nav-link close" style="color:white">'. $user_str .'</span>' ?>

			</div>  <!-- navbar-collapse -->
		</div>  <!-- container-fluid -->
	</nav>
</div>  <!-- bs-component -->
</header>

<?php if (!$logged_in) { ?>
	<p> &nbsp; </p>
	<div class="center">
		<a class="btn btn-outline-secondary" href="<?php echo $config['rewrite_base'] ?>/signup.php" role="button"> Sign Up </a> &nbsp;
		<a class="btn btn-outline-secondary" href="<?php echo $config['rewrite_base'] ?>/login.php" role="button"> Log In </a>
	</div>

<?php } ?>
<p> &nbsp; </p>

