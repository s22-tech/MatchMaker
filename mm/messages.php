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


if (isset($_GET['view'])) $view = sanitize_input(trim($_GET['view']));
else                      $view = trim($user);

if (isset($_POST['message'])) {
	$message = sanitize_input($_POST['message']);
	if ($message != '') {
		$pm   = substr(sanitize_input($_POST['pm']), 0, 1);
		$date_time = new DateTime('NOW', new DateTimeZone('UTC'));
		$sql = "INSERT INTO `messages` (`auth`, `recip`, `pm`, `date_time`, `message`) VALUES (:auth, :recip, :pm, :date_time, :message)";
		$pdo->prepare($sql)->execute(['auth' => $user, 'recip' => $view, 'pm' => $pm, 'date_time' => $date_time, 'message' => $message]);
	}
}

$audience = '';  // messages -- 'all' equals public, '' equals private.
if ($view != '') {
	if ($view == $user) {
		$name1 = $name2 = 'Your';
	}
	else {
		$name1 = "<a href='{$config['rewrite_base']}/members.php?view=$view'>" . ucwords($view) . "</a>'s";
		$name2 = ucwords($view) ."'s";
		$audience = 'all';
	}

	echo "<h3>$name2 Messages</h3>";
	show_profile($view, $user);

	$button_text = isset($_GET['view']) && $_GET['view'] != $user ? 'Private' : 'Public';
	$viewee = isset($_GET['view']) && $audience == 'all' ? ucwords($_GET['view']) : 'Everyone';

	echo PHP_EOL;
	echo <<<"_END1"
		<br>
		<form method="post" action="{$config['rewrite_base']}/messages.php?view=$view&email=$view">
			<legend>Create a new message for $viewee:</legend>
	_END1;

	$confirm = '';
	if ($audience == '') {
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
		send_email(sanitize_input($_GET['view']));
		echo '<span class="text-warning bg-black">&nbsp; Your message was sent! &nbsp;</span> <br>' . PHP_EOL;
	}

	if (isset($_GET['hide'])) {
		$hide = sanitize_input($_GET['hide']);
		$sql = "UPDATE `messages` SET `hide` = 'yes' WHERE `id` = :hide AND `recip` = :user";
		$pdo->prepare($sql)->execute(['hide' => $hide, 'user' => $user]);
    }

    if ($audience == 'all') {
		// Private messages from a single member.
		$stmt = $pdo->prepare("SELECT * FROM `messages`
		                       WHERE `recip` = :user1 AND `auth` = :view1 OR `auth` = :user2 AND `recip` = :view2
		                       ORDER BY `date_time` DESC");
		$stmt->execute(['user1' => $user, 'user2' => $user, 'view1' => $view, 'view2' => $view]);
	}
	else {
		$stmt = $pdo->prepare("SELECT * FROM `messages`
		                       WHERE `pm` = '0'
		                       AND `date_time` BETWEEN DATE_SUB(UTC_TIMESTAMP(), INTERVAL {$config['public_message_interval']} DAY) AND UTC_TIMESTAMP()
		                       ORDER BY `date_time` DESC");
		$stmt->execute();
	}

	echo '<hr><button type="button" class="btn btn-outline-success" onclick="location.reload(true);">Refresh Messages</button>';

	echo '<table>' . PHP_EOL;

  // Fetch one row at a time.
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
		if ($row['pm'] === 0 || $row['auth'] === $user || $row['recip'] === $user) {
			$date = new DateTime($row['date_time'], new DateTimeZone('UTC'));
			$date->setTimezone(new DateTimeZone($tz));  // Change to members timezone.
			echo $date->format('M j, Y @ g:ia');

			echo '</td>'. PHP_EOL .'<td>&nbsp;</td>'. PHP_EOL .'<td>' . PHP_EOL;
			if ($author === 'You') {
				echo ucwords($author);
			}
			else {
				echo " <a href='{$config['rewrite_base']}/messages.php?view=". $row['auth'] ."'>". ucwords($author). '</a> ';
			}
			$recip = $row['recip'] == $user ? 'you' : ucwords($row['recip']);
			if ($row['pm'] === 0) {
				echo ' sent a public message: &quot;' . $row['message'] . '&quot; ';
			}
			else {
				echo ' whispered to '. $recip .': <span class="whisper">&quot;' . $row['message']. '&quot;</span> ';
				if ($row['recip'] == $user)
					echo "[<a href='{$config['rewrite_base']}/messages.php?view=$view&hide=". $row['id'] ."'><small>hide</small></a>]";
			}
			echo '</td></tr>' . PHP_EOL;
		}

// 		echo '<tr><td> &nbsp; </td></tr>' . PHP_EOL;  // Adds extra spacing between table rows.
	}  // END while
	echo '</table>' . PHP_EOL;
}
else {
	echo '<br><span class="info">No messages to/from '. ucwords($view) .' yet.</span><br><br>';
}

include 'includes/footer.inc.php';
