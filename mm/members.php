<?php

require_once 'includes/header.inc.php';

if (isset($_GET['add'])) {
	$add = sanitize_input($_GET['add']);
	$stmt = $pdo->prepare("SELECT * FROM `friends` WHERE `user`= :add AND `friend`= :user");
	$stmt->execute(['add' => $add, 'user' => $user]);
	$row = $stmt->fetch();
	if (!$row) {
		$sql = "INSERT INTO `friends` (`friend`, `user`) VALUES (:add, :user)";
		$pdo->prepare($sql)->execute(['add' => $add, 'user' => $user]);
	}
}
else if (isset($_GET['recip'])) {
	$recip = sanitize_input($_GET['recip']);
	$stmt = $pdo->prepare("SELECT * FROM `friends` WHERE `user`= :user AND `friend`= :recip");
	$stmt->execute(['recip' => $recip, 'user' => $user]);
	$row = $stmt->fetch();
	if (!$row) {
		$sql = "INSERT INTO `friends` (`friend`, `user`) VALUES (:recip, :user)";
		$pdo->prepare($sql)->execute(['recip' => $recip, 'user' => $user]);
	}
}
else if (isset($_GET['drop'])) {
	$drop = sanitize_input($_GET['drop']);
	$sql = "DELETE FROM `friends` WHERE `user`= :user AND `friend`= :drop";
	$pdo->prepare($sql)->execute([$user, $drop]);
}
else if (isset($_GET['block'])) {
	$block = sanitize_input($_GET['block']);
	$stmt = $pdo->prepare("SELECT * FROM `foes` WHERE `user`= :user AND `foe`= :block");
	$stmt->execute(['block' => $block, 'user' => $user]);
	$row = $stmt->fetch();
	if (!$row) {
		$sql = "INSERT INTO `foes` (`foe`, `user`) VALUES (:block, :user)";
		$pdo->prepare($sql)->execute(['block' => $block, 'user' => $user]);
	}
}
else if (isset($_GET['unblock'])) {
	$unblock = sanitize_input($_GET['unblock']);
	$sql = "DELETE FROM `foes` WHERE `user`= :user AND `foe`= :unblock";
	$pdo->prepare($sql)->execute(['user' => $user, 'unblock' => $unblock]);
}

$gender = $_GET['gender'] ?? '';
$filter = 'no';

if ($gender != '' ) {
	$gender = sanitize_input($gender);
	$filter = 'yes';
}

$checked_f = $checked_m = $checked_a = $active_f = $active_m = $active_a = '';
if      ($gender == 'f') { $checked_f = 'checked'; $active_f = 'active'; }
else if ($gender == 'm') { $checked_m = 'checked'; $active_m = 'active'; }
else if ($gender == '' ) { $checked_a = 'checked'; $active_a = 'active'; }

echo '<h4>View Other Members</h4>';
echo <<<"_FILTER"
		Filter by: <br>
	<form method="get" id="filter" action="{$config['rewrite_base']}/members.php?gender=$gender" onchange="document.getElementById('filter').submit()">
		  <input type="radio" class="btn-check" name="gender" id="m" value="m" autocomplete="off" $checked_m>
		  <label class="btn btn-outline-secondary btn-sm $active_m" for="m"> Male </label>

		  <input type="radio" class="btn-check" name="gender" id="f" value="f" autocomplete="off" $checked_f>
		  <label class="btn btn-outline-secondary btn-sm $active_f" for="f"> Female </label>

		  <input type="radio" class="btn-check" name="gender" id="a" value="" autocomplete="off" $checked_a>
		  <label class="btn btn-outline-secondary btn-sm $active_a" for="a"> Everyone </label>
	</form>
	<br>

      <ul class="grid">
_FILTER;

if ($filter == 'yes' && $gender !== '') {
	$stmt = $pdo->prepare("SELECT `user` FROM `members` WHERE `gender`= :gender ORDER BY `user`");
	$stmt->execute(['gender' => $gender ]);
}
else {
	$stmt = $pdo->query("SELECT `user` FROM `members` ORDER BY `user`");
}

$foe_row = $pdo->query("SELECT * FROM `foes`")->fetchAll();

while ($row = $stmt->fetch()) {
	$banned = false;
	if ($row['user'] == $user) continue;    // Don't view yourself on this page.
	if ($row['user'] == 'admin') continue;  // Don't show the admin.

  // Look for banned users.
	foreach ($foe_row as $fr) {
		if ( ($user.$row['user']) == ($fr['user'].$fr['foe']) ) {
			$banned = true;
			continue;  // Exit the loop.
		}
	}
	if ($banned) continue;  // Don't view banned users.

	$image = $config['image_dir'] .'/members/'. strtolower($row['user']) .'/'. $row['user'] .'.jpg';
	$img_src = $config['rewrite_base'] .'/includes/get_image.php?view='. strtolower($row['user']) .'/'. $row['user'] .'.jpg';
	if (file_exists($image)) {
		[$w, $h] = getimagesize($image);

		[$used_width, $used_height] = scale_image(orig_width: $w, orig_height: $h, max_width: '200', max_height: '200');

		echo '<li><a href="'. $config['rewrite_base'] .'/member.php?view=' .
			$row['user'] . '"> <img src="'. $img_src .'" width="'. $used_width .'px" height="'. $used_height .'px"><br>'. $row['user'] .'</a>';
	}
	else {
		echo '<li><a href="'. $config['rewrite_base'] .'/member.php?view=' .
			$row['user'] . '"> <img src="'. $config['rewrite_base'] .'/images/no_image_yet.png" width="90px" height="90px"><br>' . $row['user'] . '</a>';
	}

  // These counts happen in the loop when a single user is selected, so each count will either be 0 or 1.
	$sql1 = "SELECT count(1) FROM `friends` WHERE `user` = '". $row['user'] ."' AND `friend` = '$user'";
	$followed = (int)$pdo->query($sql1)->fetchColumn();
	$followed = $followed >= 1 ? 1 : 0;

	$sql2 = "SELECT count(1) FROM `friends` WHERE `user` = '$user' AND `friend` = '". $row['user'] ."'";
	$following = (int)$pdo->query($sql2)->fetchColumn();
	$following = $following >= 1 ? 1 : 0;

	$sql3 = "SELECT count(1) FROM `foes` WHERE `user` = '$user' AND `foe` = '". $row['user'] ."'";
	$foe = (int)$pdo->query($sql3)->fetchColumn();
	$foe = $foe >= 1 ? 1 : 0;

	if ($foe) { echo ' <i class="fa fa-ban" aria-hidden="true"></i>'; }
	else if (($following + $followed) > 1) { echo ' &harr; <i class="fa fa-star" aria-hidden="true"></i>'; }
	else if ($following) { echo ' &larr; <i class="fa fa-star-half-o" aria-hidden="true"></i>'; }
	else if ($followed)  { echo ' &rarr; <i class="fa fa-star-half-o" aria-hidden="true"></i>'; }

	echo '<br>';

	if ($foe) { echo ' [<a href="'. $config['rewrite_base'] .'/members.php?unblock=' . $row['user'] .'"><small>unblock</small></a>]'; }
	else if (($following + $followed) > 1) { echo ' [<a href="'. $config['rewrite_base'] .'/members.php?drop=' . strtolower($row['user']) .'"><small>drop</small></a>]'; }
	else if ($followed)  { echo ' [<a href="'. $config['rewrite_base'] .'/members.php?recip=' . strtolower($row['user']) .'"><small>reciprocate</small></a>]'; }
	else if ($following) { echo ' [<a href="'. $config['rewrite_base'] .'/members.php?drop=' . strtolower($row['user']) .'"><small>drop</small></a>]'; }
	else                 { echo ' [<a href="'. $config['rewrite_base'] .'/members.php?add=' . $row['user'] .'"><small>follow</small></a>]'; }

	echo '</li>'. PHP_EOL;
}
echo '</ul>'. PHP_EOL;

echo '
<p> &nbsp; </p>
<div class="card bg-light mb-3">
<div class="card-header">
<h4 class="card-title"> Legend </h4>
</div>
<div class="card-body">
&larr; <i class="fa fa-star-half-o" aria-hidden="true"></i> means that you are following a member. <small> (Good) </small> <br>
&rarr; <i class="fa fa-star-half-o" aria-hidden="true"></i> means that a member is following you. <small> (Better) </small> <br>
&harr; <i class="fa fa-star" aria-hidden="true"></i> means that both of you are interested in each other. <small> <em> (Woohoo!!!) </em> </small> <br>
</div>
</div>
';

include 'includes/footer.inc.php';

/*

PDO
fetchColumn() returns a scalar value.
fetch() returns a single row as an array.
fetchArray() returns all rows as an array.

*/
