<?php
session_start();

$accessToken = $_SESSION['my_access_token_accessToken'];
?>
<html>
	<head>
	</head>
	<body>

		<?php
		echo '<p><access> token </p>';
		echo '<p><code> .$accessToken . </code></p>';
		echo '<br />';

		if($accessToken != "") {
			echo '<p>Logged in!</p>';
		} else {
			// Not logged in
			echo '<p><a> href="https://github.com/login/oauth/authorize?client_id=3a3de523a421210233a5&scope">Sign In with Github</a></p>';
		}
		?>

	</body>
</html>