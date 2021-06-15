<?php

	require_once 'includes/header.inc.php';

	if (isset($_POST['submit_button'])) {
		if (!empty($_POST['message'])) {
			$stmt = $pdo->prepare("SELECT `email` FROM `members` WHERE `user`= :user");
			$stmt->execute(['user' => $user]);
			$member = $stmt->fetch();

			$to_address = $config['email'];
			$subject = 'Message from '. $config['app_name'] .' Contact Form';
			$message = $_POST['message'] . '<br><br>Sent from: ' . $user;
			$message = str_replace("\n", "\n<br>", $message);
			$headers['MIME-Version'] = '1.0';
			$headers['Content-type'] = 'text/html;charset=UTF-8';
// 			$headers['Return-Path']  = 'bounced@'. $config['tld'];
			$headers['From']         = $member['email'];  // This is the members email address.
			mail($to_address, $subject, $message, $headers);

			echo "<span style='color:green'><b>SUCCESS!</b><br>Your email has been sent.  Please allow at least 24 hours for a response.</span> <br><br>";
		}
		else {
			echo "<span style='color:red'><b>ERROR</b><br>Nothing was sent.  Please try again.</span> <br><br>";
		}
	}
?>

<h3>Contact Us</h3>

<div class="form">
   <form method="post" action="">

   	<div class="messages"></div>

		<fieldset>
			<div class="form-group">
				<label for="message"> Questions? Suggestions?</label>
				<textarea type="textarea" class="form-control" name="message" id="message" placeholder="Let us know what's on your mind..."></textarea>
			</div>

			<p> &nbsp; </p>

			<div class="center">
				<button type="submit" class="btn btn-secondary" name="submit_button"> Send email to admin </button>
			</div>

			<p> &nbsp; </p>

		</fieldset>
	</form>
</div>  <!-- class="form" -->


<?php include 'includes/footer.inc.php'; ?>
