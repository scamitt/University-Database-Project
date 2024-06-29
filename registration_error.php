<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Registration Error</title>
</head>
<body>
<h1>Registration Error</h1>
<?php
session_start();
if(isset($_SESSION['error_message'])) {
    echo "<p>{$_SESSION['error_message']}</p>";
    unset($_SESSION['error_message']); // Clear the error message from session
} else {
    echo "<p>An error occurred during registration.</p>";
}
?>
<p><a href="registration_form.php">Back to Registration Form</a></p>
</body>
</html>
