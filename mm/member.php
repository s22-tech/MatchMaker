<?php

require_once 'includes/header.inc.php';

if (isset($_GET['view'])) {
	$view = sanitize_string($_GET['view']);

	if ($view == $user) $name = "Your";
	else                $name = "$view's";
	$name = ucwords($name);

	echo "<h3>$name Profile</h3>";
	$info = show_profile($view, $user);

	$relocate = $info['relocate'] ? ucwords($info['relocate']) : '<small><em>Not given</em></small>';
	$info['info'] = $info['info'] ?: '<small><em>No info yet</em></small>';

// 	echo '<pre>'; print_r($info); echo '</pre>';

	echo '<br>';

	echo '<div class="row">
				<div class="form-group col-lg-4 col-md-5 col-sm-7"> <b>Willing to Relocate?</b> '. $relocate;
	echo '</div>
			</div>';

	echo '<div class="row">
			<div class="form-group col-lg-4 col-md-5 col-sm-7"> <b>In their own words:</b> <br>';
	echo $info['info'];
	echo '</div>
		</div>
		<br>
		<br>
		<div class="row">
			<div class="form-group col-lg-4 col-md-5 col-sm-7">
					<a class="btn btn-outline-success btn-sm" role="button" href="'. $config['rewrite_base'] .'/messages.php?view='. $view .'"> View '. $name  .' messages </a>
			</div>
		</div>
		';
}
else {
	echo 'No member was selected. <br>';
}

include 'includes/footer.inc.php';
