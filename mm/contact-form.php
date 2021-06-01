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
   <form method="post" action="contact-form.php">

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


<?php include 'includes/footer.inc.php' ?>

<script>
$(function() {

    // Init the validator...
    // Validator files are included in the download package.
    // Otherwise download from http://1000hz.github.io/bootstrap-validator

    $('#contact-form').validator();


    // When the form is submitted...
    $('#contact-form').on('submit', function (e) {

        // If the validator does not prevent form submit...
        if (!e.isDefaultPrevented()) {
            var url = "contact-form.php";

            // POST values in the background the the script URL...
            $.ajax({
                type: "POST",
                url: url,
                data: $(this).serialize(),
                success: function (data)
                {
                    // data = JSON object that contact.php returns

                    // We recieve the type of the message: success x danger and apply it to the
                    var messageAlert = 'alert-' + data.type;
                    var messageText = data.message;

                    // Let's compose Bootstrap alert box HTML...
                    var alertBox = '<div class="alert ' + messageAlert + ' alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>' + messageText + '</div>';

                    // If we have messageAlert and messageText...
                    if (messageAlert && messageText) {
                        // inject the alert to .messages div in our form
                        $('#contact-form').find('.messages').html(alertBox);
                        // empty the form
                        $('#contact-form')[0].reset();
                    }
                }
            });
            return false;
        }
    })
});
</script>
