<?php
session_start(); // Start the session to store session variables

include 'db_connection.php'; // Include your database connection file

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $userType = $_POST['userType'];
    $ID = $_POST['ID'];
    $password = $_POST['password'];
    $idField = 'ID';

    if($userType === 'admin'){
        if($ID=='admin'&& $password == 'admin'){
            header("Location: http://localhost/phpmyadmin/index.php?route=/database/structure&db=StudentLMS");
        exit();
        }
    }

    if ($userType === 'student') {
        $tableName = 'student_credentials';
    } elseif ($userType === 'faculty') {
        $tableName = 'faculty_credentials';
    }
    // Prepare and execute the SQL query using prepared statements
    $sql = "SELECT * FROM $tableName WHERE $idField = ? AND Password = ?";
    $stmt = mysqli_prepare($connection, $sql);
    mysqli_stmt_bind_param($stmt, "ss", $ID, $password);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if ($result && mysqli_num_rows($result) == 1) {
        $row = mysqli_fetch_assoc($result);
        // Store ID in session variable
        $_SESSION['ID'] = $ID;
        if ($userType === 'student') {
            header("Location: student_dashboard.php");
            exit();
        } elseif ($userType === 'faculty') {
            header("Location: faculty_dashboard.php");
            exit();
        }
    }
    

    // Login failed
    header("Location: login_failed.html");
    exit();
} else {
    // Redirect back to login page if accessed directly
    header("Location: index.html");
    exit();
}
?>
