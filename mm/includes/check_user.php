<?php

require_once 'functions.inc.php';

if (isset($_POST['user'])) {
	$user = sanitize_string($_POST['user']);
	$stmt = $pdo->prepare("SELECT * FROM `members` WHERE `user`= :user");
	$stmt->execute(['user' => $user]);
	$row = $stmt->fetch();

	if ($row) {
		echo  "<span class='taken'>&nbsp;&#x2718; " .
		"Sorry &ndash; the username '$user' is taken</span>";
	}
	else {
		echo "<span class='available'>&nbsp;&#x2714; " .
		"The username '$user' is available</span>";
	}
}


__halt_compiler();

This file can't be named .inc.php
That prevents signup.php from using it since those files are banned from being called by browsers.
