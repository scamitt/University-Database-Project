<?php
session_start();
include 'db_connection.php';

if (isset($_SESSION['ID'])) {
    $studentID = $_SESSION['ID'];
    // Fetch academic details including the name of the teacher/guardian from the database
    $sql = "SELECT ad.*, f.Name AS TeacherName
            FROM academic_details ad
            INNER JOIN Faculty f ON ad.TeacherGuardian = f.FacultyID
            WHERE ad.StudentID = ?";
    $stmt = mysqli_prepare($connection, $sql);
    mysqli_stmt_bind_param($stmt, "s", $studentID);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if ($result && mysqli_num_rows($result) > 0) {
        $academicDetails = mysqli_fetch_assoc($result);
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Academic Details</title>
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
  margin-top: 20px; /* Updated */
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
    <h1>Academic Details</h1>
    <?php if (isset($academicDetails) && !empty($academicDetails)) { ?>
    <div class="row">
      <div class="item"><strong>Student ID:</strong> <?php echo $academicDetails['StudentID']; ?></div>
      <div class="item"><strong>Course ID:</strong> <?php echo $academicDetails['CourseID']; ?></div>
    </div>
    <div class="row">
      <div class="item"><strong>Semester:</strong> <?php echo $academicDetails['Semester']; ?></div>
      <div class="item"><strong>Teacher/Guardian:</strong> <?php echo $academicDetails['TeacherName']; ?></div>
    </div>
    <div class="row">
      <div class="item"><strong>CGPA:</strong> <?php echo $academicDetails['CGPA']; ?></div>
    </div>
    <?php } ?>
</div>


</body>
</html>

</body>
</html>

</body>
</html>
