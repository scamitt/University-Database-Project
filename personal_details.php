<?php
// Start the session
session_start();

// Include your database connection file
include 'db_connection.php';

// Initialize variables
$studentID = '';
$firstName = '';
$middleName = '';
$lastName = '';
$birthDate = '';
$addressCity = '';
$parentName = '';
$parentPhone = '';
$parentEmail = '';
$studentEmail = '';

// Check if session ID is set
if (isset($_SESSION['ID'])) {
    $studentID = $_SESSION['ID'];
    
    // Prepare and execute SQL query to fetch personal details
    $sql = "SELECT * FROM personal_details WHERE StudentID = ?";
    $stmt = mysqli_prepare($connection, $sql);
    mysqli_stmt_bind_param($stmt, "s", $studentID);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    
    // Fetch data if a single row is found
    if ($result && $row = mysqli_fetch_assoc($result)) {
        $firstName = $row['FirstName'];
        $middleName = $row['MiddleName'];
        $lastName = $row['LastName'];
        $birthDate = $row['BirthDate'];
        $addressCity = $row['AddressCity'];
        $parentName = $row['ParentName'];
        $parentPhone = $row['ParentPhone'];
        $parentEmail = $row['ParentEmail'];
        $studentEmail = $row['StudentEmail'];
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Personal Details</title>
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

.container {
  background-color: #fff;
  padding: 30px;
  border-radius: 8px;
  box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
  width: 500px;
}

.container h1 {
  text-align: center;
  margin-bottom: 20px;
}

.item {
  margin-bottom: 15px;
  border-bottom: 1px solid #ccc;
  padding-bottom: 10px;
}

</style>
</head>
<body>
  <div class="container">
    <h1>Personal Details</h1>
    <div class="item"><strong>Student ID:</strong> <?php echo $studentID; ?></div>
    <div class="item"><strong>First Name:</strong> <?php echo $firstName; ?></div>
    <div class="item"><strong>Middle Name:</strong> <?php echo $middleName; ?></div>
    <div class="item"><strong>Last Name:</strong> <?php echo $lastName; ?></div>
    <div class="item"><strong>Birth Date:</strong> <?php echo $birthDate; ?></div>
    <div class="item"><strong>Address City:</strong> <?php echo $addressCity; ?></div>
    <div class="item"><strong>Parent's Name:</strong> <?php echo $parentName; ?></div>
    <div class="item"><strong>Parent's Phone:</strong> <?php echo $parentPhone; ?></div>
    <div class="item"><strong>Parent's Email:</strong> <?php echo $parentEmail; ?></div>
    <div class="item"><strong>Student's Email:</strong> <?php echo $studentEmail; ?></div>
  </div>
</body>
</html>
