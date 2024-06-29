<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

include 'db_connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST['id'];
    $name = $_POST['name'];
    $phone = $_POST['phone'];
    $deptid = $_POST['deptid'];
    $mail = $_POST['mail'];
    $password = $_POST['password'];

    // Prepare and execute the SQL statement to insert data into the Faculty_Details table
    $sql_insert_faculty_details = "INSERT INTO `Faculty` (`FacultyID`, `Name`, `Phone`, `DeptID`, `Mail`) VALUES (?, ?, ?, ?, ?)";
    $stmt_insert_faculty_details = $connection->prepare($sql_insert_faculty_details);

    if (!$stmt_insert_faculty_details) {
        die('Error preparing statement: ' . $connection->error);
    }

    $stmt_insert_faculty_details->bind_param("sssss", $id, $name, $phone, $deptid, $mail);

    $insert_faculty_details_success = $stmt_insert_faculty_details->execute();

    if (!$insert_faculty_details_success) {
        die('Error executing statement: ' . $stmt_insert_faculty_details->error);
    }

    // Prepare and execute the SQL statement to insert data into the Faculty_Credentials table
    $sql_insert_credentials = "INSERT INTO `Faculty_Credentials` (`ID`, `Password`) VALUES (?, ?)";
    $stmt_insert_credentials = $connection->prepare($sql_insert_credentials);

    if (!$stmt_insert_credentials) {
        die('Error preparing statement: ' . $connection->error);
    }

    $stmt_insert_credentials->bind_param("ss", $id, $password);

    $insert_credentials_success = $stmt_insert_credentials->execute();

    if ($insert_faculty_details_success && $insert_credentials_success) {
        $_SESSION['registration_success'] = true;
        header("Location: registration_successful.php");
        exit();
    } else {
        $insert_error = "Failed to insert data into the database.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Register Faculty</title>
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

.register-container {
  background-color: #fff;
  padding: 40px;
  border-radius: 8px;
  box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
  width: 600px; /* Adjust width as needed */
  max-width: 100%;
}

.register-column {
  width: 100%; /* Adjust width as needed */
}

.register-column h2 {
  margin-bottom: 20px;
  text-align: center;
}

.register-section {
  margin-bottom: 20px;
  display: flex;
  flex-direction: column;
}

.register-section label {
  font-weight: bold;
  margin-bottom: 5px;
}

.register-section input[type="text"],
.register-section input[type="password"],
.register-section input[type="number"],
.register-section input[type="date"],
.register-section select {
  width: 100%;
  padding: 10px;
  border: 1px solid #ccc;
  border-radius: 4px;
  box-sizing: border-box;
  font-size: 16px;
  margin-bottom: 10px;
}

.register-container input[type="submit"] {
  width: 100%;
  padding: 10px;
  border: none;
  border-radius: 4px;
  background-color: #4CAF50;
  color: white;
  cursor: pointer;
  font-size: 16px;
}

.register-container input[type="submit"]:hover {
  background-color: #45a049;
}
</style>
</head>
<body>
<div class="register-container">
  <div class="register-column">
    <!-- Faculty Registration form -->
    <form id="registerForm" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
        <h2>Register Faculty</h2>
        <div class="register-section">
          <label for="id">ID</label>
          <input type="text" id="id" name="id" placeholder="Faculty ID" >
        </div>
        <div class="register-section">
          <label for="name">Name</label>
          <input type="text" id="name" name="name" placeholder="Name" >
        </div>
        <div class="register-section">
          <label for="phone">Phone Number</label>
          <input type="text" id="phone" name="phone" placeholder="Phone Number" >
        </div>
        <div class="register-section">
          <label for="deptid">Department ID</label>
          <input type="text" id="deptid" name="deptid" placeholder="Department ID" >
        </div>
        <div class="register-section">
          <label for="mail">Email</label>
          <input type="text" id="mail" name="mail" placeholder="Email" >
        </div>
        <div class="register-section">
          <label for="password">Password</label>
          <input type="password" id="password" name="password" placeholder="Password" >
        </div>
        <!-- Submit button -->
        <input type="submit" value="Register">
    </form>
  </div>
</div>
</body>
</html>
