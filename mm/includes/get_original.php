<?php

include 'config.inc.php';

$member = $_GET['view'];

if (file_exists($config['image_dir'] . '/originals/'. $member)) {
	header('Content-Type: image/jpg');
	readfile($config['image_dir'] . '/originals/'. $member);
	exit;
}


__halt_compiler();

Nothing else can be echoed in this file.  It will prevent the image from being displayed.

This file can't have a .inc.php suffix.  That will prevent it from being called by the browser.
