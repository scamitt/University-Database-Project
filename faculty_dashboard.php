<?php
// Start the session
session_start();

// Include your database connection file
include 'db_connection.php';

// Check if the user is logged in as faculty
if (isset($_SESSION['ID'])) {
    $facultyID = $_SESSION['ID'];
    // Fetch faculty details from the database
    $sql = "SELECT f.Name AS FacultyName, d.HOD AS HOD_ID FROM Faculty f
            JOIN Department d ON f.DeptID = d.DeptID
            WHERE f.FacultyID = ?";
    $stmt = mysqli_prepare($connection, $sql);
    mysqli_stmt_bind_param($stmt, "s", $facultyID);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    if ($result && mysqli_num_rows($result) == 1) {
        $row = mysqli_fetch_assoc($result);
        $facultyName = $row['FacultyName'];
        $hodID = $row['HOD_ID'];
        
        // Check if the faculty member is the HOD of their department
        $isHOD = ($facultyID == $hodID) ? true : false;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Faculty Dashboard</title>
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
    <h2>Welcome <?php echo $facultyName; ?> to Your Dashboard</h2>
    <p>Faculty ID: <?php echo $_SESSION['ID']; ?> </p>
    <button onclick="window.location.href='update_attendance.php'">Update Attendance</button>
    <button onclick="window.location.href='view_attendance.php'">View Attendance</button>
    <button onclick="window.location.href='update_grade.php'">Update Grade</button>
    <button onclick="window.location.href='view_grade.php'">View Grade</button>
    <?php if ($isHOD) { ?>
    <button onclick="window.location.href='faculty_register.php'">Create New Faculty Login</button>
    <?php } ?>
    <button onclick="window.location.href='student_register.php'">Create New Student Login</button>
  </div>
</body>
</html>
