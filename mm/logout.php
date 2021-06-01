<?php
	require_once 'includes/header.inc.php';

	if (isset($_SESSION['user'])) {
		destroy_session();
		echo '<script> window.location.assign("'. $config['rewrite_base'] .'/index.php"); </script>';
	}
	else {
		echo '<div class="center">You can\'t log out because you\'re not logged in.</div>';
	}
	include_once 'includes/footer.inc.php';
?>
    </div>
  </body>
</html>
