<?php

require_once 'includes/header.inc.php';

if (isset($_GET['view'])) $view = sanitize_input($_GET['view']);
else                      $view = $user;

if ($view == $user) {
	$name1 = $name2 = 'Your';
	$name3 = 'You are';
}
else {
	$name1 = '<a href="member.php?view='.$view.'>'.$view.'</a>\'s';
	$name2 = "$view's";
	$name3 = "$view is";
}

show_profile($view, $user);

$followers = array();
$following = array();

$stmt = $pdo->prepare("SELECT * FROM `friends` WHERE `user`= :view");
$stmt->execute(['view' => $view]);

$i = 0;
while ($row = $stmt->fetch()) {
	$followers[$i] = $row['friend'];
	$i++;
}

$stmt = $pdo->prepare("SELECT * FROM `friends` WHERE `friend`= :view");
$stmt->execute(['view' => $view]);

$i = 0;
while ($row = $stmt->fetch()) {
	$following[$i] = $row['user'];
	$i++;
}

$mutual    = array_intersect($followers, $following);
$followers = array_diff($followers, $mutual);
$following = array_diff($following, $mutual);
$friends   = FALSE;

echo '<br>';

if (sizeof($mutual)) {
	echo '<span class="subhead">'. $name2 .' mutual friends:</span> <br><br>
	<ul class="grid">' . PHP_EOL;
	foreach ($mutual as $friend) {
		if ( file_exists($config['image_dir'] . '/members/'. strtolower($friend) .'/'. $friend .'.jpg') ) {
			echo '<li><a href="'. $config['rewrite_base'] .'/member.php?view='.
				$friend . '"> <img src="'. $config['rewrite_base'] .'/includes/get_image.php?view='. strtolower($friend) .'/'. $friend .'.jpg"><br>'. $friend .'</a>';
		}
		else {
			echo '<li><a href="'. $config['rewrite_base'] .'/member.php?view=' .
				$friend .'"> <img src="'. $config['rewrite_base'] .'/images/no_image_yet.png" width="90px" height="90px"><br>'. $friend . '</a>';
		}
		echo '</li>'. PHP_EOL;
	}
	echo '</ul>'. PHP_EOL;
	$friends = TRUE;
}

if (sizeof($followers)) {
	echo '<span class="subhead">'. $name2 .' followers:</span> <br><br>
	<ul class="grid">' . PHP_EOL;
	foreach ($followers as $friend) {
		if ( file_exists($config['image_dir'] . '/members/'. strtolower($friend) .'/'. $friend .'.jpg') ) {
			echo '<li><a href="'. $config['rewrite_base'] .'/member.php?view='.
				$friend . '"> <img src="'. $config['rewrite_base'] .'/includes/get_image.php?view='. strtolower($friend) .'/'. $friend .'.jpg"><br>'. $friend .'</a>';
		}
		else {
			echo '<li><a href="'. $config['rewrite_base'] .'/member.php?view='.
				$friend .'"> <img src="'. $config['rewrite_base'] .'/images/no_image_yet.png" width="90px" height="90px"><br>'. $friend .'</a>';
		}
		echo '</li>'. PHP_EOL;
	}
	echo '</ul>'. PHP_EOL;
	$friends = TRUE;
}

if (sizeof($following)) {
	echo '<span class="subhead">'. $name3 .' following:</span> <br><br>
	<ul class="grid">' . PHP_EOL;
	foreach ($following as $friend) {

		if ( file_exists($config['image_dir'] . '/members/'. strtolower($friend) .'/'. $friend .'.jpg') ) {
			echo '<li><a href="member.php?view='.
				$friend . '"> <img src="'. $config['rewrite_base'] .'/includes/get_image.php?view='. strtolower($friend) .'/'. $friend .'.jpg"><br>'. $friend .'</a>';
		}
		else {
			echo '<li><a href="member.php?view='.
				$friend .'"> <img src="images/no_image_yet.png" width="90px" height="90px"><br>'. $friend .'</a>';
		}
		echo '</li>'. PHP_EOL;
	}
	echo '</ul>'. PHP_EOL;
	$friends = TRUE;
}

if (!$friends) echo "<br><em>You don't have any friends yet.</em>";

include 'includes/footer.inc.php';
