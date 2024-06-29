<?php
// Start the session
session_start();

// Include your database connection file
include 'db_connection.php';

// Fetch the student's name from the database based on the session ID
if (isset($_SESSION['ID'])) {
    $studentID = $_SESSION['ID'];
    $sql = "SELECT FirstName, LastName FROM personal_details WHERE StudentID = ?";
    $stmt = mysqli_prepare($connection, $sql);
    mysqli_stmt_bind_param($stmt, "s", $studentID);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    if ($result && mysqli_num_rows($result) == 1) {
        $row = mysqli_fetch_assoc($result);
        $studentName = $row['FirstName'] . ' ' . $row['LastName'];
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>User Dashboard</title>
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
  .dashboard-container {
    text-align: center;
  }
  .dashboard-container h2 {
    margin-bottom: 20px;
  }
  .dashboard-container button {
    padding: 10px 20px;
    margin: 10px;
    border: none;
    border-radius: 4px;
    background-color: #4CAF50;
    color: white;
    cursor: pointer;
  }
  .dashboard-container button:hover {
    background-color: #45a049;
  }
</style>
</head>
<body>

  <div class="dashboard-container">
    <h2>Welcome <?php echo $studentName; ?> to Your Dashboard</h2>
    <p>StudentID: <?php echo $_SESSION['ID']; ?> </p>
    <button onclick="window.location.href='personal_details.php'">Personal Details</button>
    <button onclick="window.location.href='academic_details.php'">Academic Details</button>
    <button onclick="window.location.href='financial_details.php'">Financial Details</button>
    <button onclick="window.location.href='attendance.php'">Attendance</button>
    <button onclick="window.location.href='gradesheet.php'">Gradesheet</button>
    <button onclick="window.location.href='course_details.php'">Course Details</button>
    <button onclick="window.location.href='department_details.php'">Department Details</button>
  </div>
</body>
</html>
