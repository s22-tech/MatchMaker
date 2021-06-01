<?php

require_once 'includes/header.inc.php';

if (isset($_GET['view'])) $view = sanitize_string($_GET['view']);
else                      $view = $user;


// Comment out the following line if you wish the user's profile to not show here.
show_profile(strtolower($view), strtolower($user));

$foes = [];

$stmt = $pdo->prepare("SELECT * FROM `foes` WHERE `user`= :view");
$stmt->execute(['view' => $view]);

$i = 0;
while ($row = $stmt->fetch()) {
	$foes[$i] = $row['foe'];
	$i++;
}


$stmt = $pdo->prepare("SELECT * FROM `foes` WHERE `foe`= :view");
$stmt->execute(['view' => $view]);

$i = 0;
while ($row = $stmt->fetch()) {
	$following[$i] = $row['user'];
	$i++;
}


echo '<br>';

if ($foes) {
	echo '<span class="subhead">Members you\'ve blocked:</span> <br><br>
	<ul>';
	foreach ($foes as $foe) {
		if (file_exists($config['image_dir'] . '/members/'. strtolower($foe) .'/'. $foe .'.jpg')) {
			echo '<li><a href="'. $config['rewrite_base'] .'/member.php?view='.
				strtolower($foe) . '"> <img src="'. $config['rewrite_base'] .'/includes/get_image.php?view='. strtolower($foe) .'/'. $foe .'.jpg"> <br>'. $foe .'</a>';
		}
		else {
			echo '<li><a href="'. $config['rewrite_base'] .'/member.php?view='.
				$foe .'"> <img src="'. $config['rewrite_base'] .'/images/no_image_yet.png" width="90px" height="90px"><br>'. $foe .'</a>';
		}
		echo ' [<a href="'. $config['rewrite_base'] .'/members.php?unblock=' . $foe .'"><small>unblock</small></a>]';
		echo '</li>';
	}
	echo '</ul>'. PHP_EOL;
}
else {
	echo "<br><em>You haven't blocked anyone.</em>";
}

include 'includes/footer.inc.php';
