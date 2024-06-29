<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Registration Successful</title>
<style>
  body {
    font-family: Arial, sans-serif;
    background-color: #f2f2f2;
    margin: 0;
    padding: 0;
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100vh;
  }
  
  .success-message {
    background-color: #fff;
    padding: 40px;
    border-radius: 8px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    width: 400px; /* Adjust width as needed */
    max-width: 100%;
    text-align: center;
  }
  
  .success-message h2 {
    margin-bottom: 20px;
    color: #4CAF50;
  }
  
  .success-message p {
    margin-bottom: 20px;
    font-size: 18px;
  }
</style>
</head>
<body>
<div class="success-message">
  <h2>Registration Successful!
  <?php
        echo $_SESSION['Test'];

    ?>
  </h2>
  <p>Thank you for registering.</p>
  <p>You can now login to your account.</p>
  
</div>
</body>
</html>
