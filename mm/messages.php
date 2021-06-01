<?php
require_once 'includes/header.inc.php';

$stmt = $pdo->prepare("SELECT `timezone` FROM `members` WHERE `user`= :user");
$stmt->execute(['user' => $user]);
$tz = $stmt->fetchColumn();

// Produce an array of foes to block their messages.
$stmt = $pdo->prepare("SELECT `foe` FROM `foes` WHERE `user`= :user");
$stmt->execute(['user' => $user]);
while ($row = $stmt->fetch()) {
	$foes[] = $row['foe'];
}


if (isset($_GET['view'])) $view = sanitize_string($_GET['view']);
else                      $view = $user;

if (isset($_POST['message'])) {
	$message = sanitize_string($_POST['message']);
	if ($message != '') {
		$pm   = substr(sanitize_string($_POST['pm']), 0, 1);
		$time = time();
		$sql = "INSERT INTO `messages` (`auth`, `recip`, `pm`, `time`, `message`) VALUES (:auth, :recip, :pm, :time, :message)";
		$pdo->prepare($sql)->execute(['auth' => $user, 'recip' => $view, 'pm' => $pm, 'time' => $time, 'message' => $message]);
	}
}

$audience = '0';
if ($view != '') {
	if ($view == $user) {
		$name1 = $name2 = 'Your';
	}
	else {
		$name1 = "<a href='{$config['rewrite_base']}/members.php?view=$view'>" . ucwords($view) . "</a>'s";
		$name2 = ucwords($view) ."'s";
		$audience = '1';
	}

	echo "<h3>$name2 Messages</h3>";
	show_profile($view, $user);

	$button_text = isset($_GET['view']) && $_GET['view'] != $user ? 'Private' : 'Public';
	$viewee = isset($_GET['view']) && $audience == '1' ? ucwords($_GET['view']) : 'Everyone';

	echo PHP_EOL;
	echo <<<"_END1"
		<br>
		<form method="post" action="{$config['rewrite_base']}/messages.php?view=$view&email=$view">
			<legend>Create a new message for $viewee:</legend>
	_END1;

	$confirm = '';
	if ($audience == '0') {
		echo '<em>( To send a private message, first <a href="'. $config['rewrite_base'] .'/members.php">select a member</a> )</em><br><br>';
		$confirm = 'onclick="return confirm(\'Are you sure you want to send a message to ALL members???\');"';
	}
	echo PHP_EOL;

	echo <<<"_END2"
			<input type="hidden" name="pm" id="audience" value="$audience">
			<textarea class="form-control" name="message" rows="6" placeholder="Type your message here..." required></textarea>
			<br>
			<button type="submit" class="btn btn-secondary" $confirm>Send $button_text Message to $viewee</button>
		</form>

	_END2;

	if (isset($_GET['email']) && isset($_GET['view'])) {  // && $view == $user
		send_email(sanitize_string($_GET['view']));
		echo '<span class="text-warning bg-black">&nbsp; Your message was sent! &nbsp;</span> <br>' . PHP_EOL;
	}

	if (isset($_GET['hide'])) {
		$hide = sanitize_string($_GET['hide']);
		$sql = "UPDATE `messages` SET `hide` = 'yes' WHERE `id` = :hide AND `recip` = :user";
		$pdo->prepare($sql)->execute(['hide' => $hide, 'user' => $user]);
    }

    if ($audience == '0') {
		$stmt = $pdo->prepare("SELECT * FROM `messages`
		                       WHERE `recip` = :user1 OR `auth` = :user2 OR `pm` = '0'
		                       AND `time` BETWEEN DATE_SUB(NOW(), INTERVAL 30 DAY) AND NOW()
		                       ORDER BY `time` DESC");
		$stmt->execute(['user1' => $user, 'user2' => $user]);
	}
	else {
		// Private messages from a single member.
		$stmt = $pdo->prepare("SELECT * FROM `messages` WHERE `recip` = :user1 AND `auth` = :view1 OR `auth` = :user2 AND `recip` = :view2 ORDER BY `time` DESC");
		$stmt->execute(['user1' => $user, 'user2' => $user, 'view1' => $view, 'view2' => $view]);
	}

	echo '<hr><button type="button" class="btn btn-outline-success" onclick="location.reload(true);">Refresh Messages</button>';

	echo '<table>' . PHP_EOL;

	while ($row = $stmt->fetch()) {
		echo '<tr>' . PHP_EOL;

		// Don't show messages to/from blocked members.
		if (isset($foes)) {
			if (in_array($row['auth'],  $foes)) continue;
			if (in_array($row['recip'], $foes)) continue;
		}
		// Don't show hidden messages.
		if ($row['hide'] == 'yes') continue;

		if ( $row['auth'] == $user ) { $author = 'You'; }
		else { $author = $row['auth']; }

		echo '<td>' . PHP_EOL;
		if ($row['pm'] == 0 || $row['auth'] === $user || $row['recip'] === $user) {
			echo date('M j, Y @ g:ia ', $row['time'] + (int)$tz * 60 * 60);
			echo '</td>'. PHP_EOL .'<td>&nbsp;</td>'. PHP_EOL .'<td>' . PHP_EOL;
			echo " <a href='{$config['rewrite_base']}/messages.php?view=". $row['auth'] ."'>". ucwords($author). '</a> ';
		}

		$recip = $row['recip'] == $user ? 'you' : ucwords($row['recip']);
		if ($row['pm'] == 0) {
			echo 'sent a public message: &quot;' . $row['message'] . '&quot; ';
		}
		else {
			echo 'whispered to '. $recip .': <span class="whisper">&quot;' . $row['message']. '&quot;</span> ';
			if ($row['recip'] == $user)
				echo "[<a href='{$config['rewrite_base']}/messages.php?view=$view&hide=". $row['id'] ."'><small>hide</small></a>]";
		}
		echo '</td></tr>' . PHP_EOL;
		echo '<tr><td> &nbsp; </td></tr>' . PHP_EOL;
	}
	echo '</table>' . PHP_EOL;
}
else {
	echo '<br><span class="info">No messages to/from '. ucwords($view) .' yet.</span><br><br>';
}

include 'includes/footer.inc.php';
