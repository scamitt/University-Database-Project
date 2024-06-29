<?php
session_start();
include 'db_connection.php';

if (isset($_SESSION['ID'])) {
    $studentID = $_SESSION['ID'];
    // Fetch course details for the logged-in student from the database
    $sql = "SELECT CourseID FROM Academic_details WHERE StudentID = '$studentID'";
    $result = mysqli_query($connection, $sql);

    if ($result && mysqli_num_rows($result) > 0) {
        $courses = mysqli_fetch_all($result, MYSQLI_ASSOC);
        mysqli_free_result($result);
    }
}

// Fetch course details based on the selected course IDs
if (isset($courses) && !empty($courses)) {
    $courseDetails = array();
    foreach ($courses as $course) {
        $courseID = $course['CourseID'];
        $sql = "SELECT * FROM Course WHERE CourseID = '$courseID'";
        $result = mysqli_query($connection, $sql);
        if ($result && mysqli_num_rows($result) > 0) {
            $courseDetail = mysqli_fetch_assoc($result);
            // Fetch department details for the course
            $deptID = $courseDetail['DeptID'];
            $sqlDept = "SELECT * FROM Department WHERE DeptID = '$deptID'";
            $resultDept = mysqli_query($connection, $sqlDept);
            if ($resultDept && mysqli_num_rows($resultDept) > 0) {
                $deptDetail = mysqli_fetch_assoc($resultDept);
                $courseDetail['Department'] = $deptDetail;
                mysqli_free_result($resultDept);
            }
            $courseDetails[] = $courseDetail;
            mysqli_free_result($result);
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title> Department Details</title>
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
    <h1>Department Details</h1>
    <?php if (isset($courseDetails) && !empty($courseDetails)) { ?>
    <div class="row">
      <?php foreach ($courseDetails as $course) { ?>
      <div class="item"><strong>Department ID:</strong> <?php echo $course['DeptID']; ?></div>
      <?php if (isset($course['Department'])) { ?>
      <div class="item"><strong>Department Name:</strong> <?php echo $course['Department']['DeptName']; ?></div>
      <div class="item"><strong>Department Location:</strong> <?php echo $course['Department']['Location']; ?></div>
      <?php } ?>
      <?php } ?>
    </div>
    <?php } else { ?>
    <p>No course details found.</p>
    <?php } ?>
  </div>
</body>
</html>
