<?php

// error_reporting(E_ALL);
// ini_set('display_errors', 1);

require_once 'includes/header.inc.php';

echo '<div class="center">';

if ($logged_in) echo "$user, you are logged in!";
else            echo 'Please sign up or log in to access our site.';
echo '</div>';


include_once 'includes/footer.inc.php';
