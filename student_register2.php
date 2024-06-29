<?php
// Start the session
session_start();

// Include your database connection file
include 'db_connection.php';

// Fetch departments
$sql_departments = "SELECT DeptID, DeptName FROM Department";
$result_departments = mysqli_query($connection, $sql_departments);

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Extract data from the form
    $studentID = $_SESSION['ID'];
    $courseID = $_POST['courseID'];
    $semester = $_POST['semester'];
    $teacherGuardian = $_POST['teacherGuardian'];
    $cgpa = $_POST['cgpa'];

    // Prepare and execute the SQL statement to insert data into the Student table
    $sql_insert_student = "INSERT INTO Student (StudentID, CourseID, Semester, TeacherGuardian, CGPA) VALUES (?, ?, ?, ?, ?)";
    $stmt_insert_student = mysqli_prepare($connection, $sql_insert_student);
    mysqli_stmt_bind_param($stmt_insert_student, "ssisd", $studentID, $courseID, $semester, $teacherGuardian, $cgpa);

    // Check if the query executed successfully
    if (mysqli_stmt_execute($stmt_insert_student)) {
        // Data inserted successfully, redirect to next page or perform other actions
        header("Location: student_dashboard.php");
        exit();
    } else {
        // Error occurred while inserting data, handle the error
        $insert_error = "Failed to insert data into the database: " . mysqli_error($connection);
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
  
  .form-container {
    background-color: #fff;
    padding: 40px;
    border-radius: 8px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    width: 400px; /* Adjust width as needed */
    max-width: 100%;
  }
  
  .form-section {
    margin-bottom: 20px;
    display: flex;
    flex-direction: column;
  }
  
  .form-section label {
    font-weight: bold;
    margin-bottom: 5px;
  }
  
  .form-section input[type="text"],
  .form-section input[type="number"],
  .form-section select {
    width: 100%;
    padding: 10px;
    border: 1px solid #ccc;
    border-radius: 4px;
    box-sizing: border-box;
    font-size: 16px;
    margin-bottom: 10px;
  }
  
  .form-container input[type="submit"] {
    width: 100%;
    padding: 10px;
    border: none;
    border-radius: 4px;
    background-color: #4CAF50;
    color: white;
    cursor: pointer;
    font-size: 16px;
  }
  
  .form-container input[type="submit"]:hover {
    background-color: #45a049;
  }
</style>
</head>
<body>

<div class="form-container">
  <h2>Academic Details</h2>
  <form id="academicDetailsForm" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
    <div class="form-section">
      <label for="department">Department</label>
      <select id="department" name="department" required>
        <option value="">Select Department</option>
        <?php while ($row_department = mysqli_fetch_assoc($result_departments)) { ?>
          <option value="<?php echo $row_department['DeptID']; ?>"><?php echo $row_department['DeptName']; ?></option>
        <?php } ?>
      </select>
    </div>
    <div class="form-section">
      <label for="courseID">Course</label>
      <select id="courseID" name="courseID" required>
        <!-- Options will be filled dynamically based on selected department -->
      </select>
    </div>
    <div class="form-section">
      <label for="semester">Semester</label>
      <input type="number" id="semester" name="semester" placeholder="Semester" required>
    </div>
    <div class="form-section">
      <label for="teacherGuardian">Teacher Guardian</label>
      <select id="teacherGuardian" name="teacherGuardian" required>
        <!-- Options will be filled dynamically based on selected department -->
      </select>
    </div>
    <div class="form-section">
      <label for="cgpa">CGPA</label>
      <input type="text" id="cgpa" name="cgpa" placeholder="CGPA" required>
    </div>
    <input type="submit" value="Submit">
  </form>
</div>

<script>
// Fetch courses based on selected department
document.getElementById('department').addEventListener('change', function() {
    var departmentId = this.value;
    var courseSelect = document.getElementById('courseID');
    courseSelect.innerHTML = ''; // Clear previous options
    if (departmentId) {
        fetch('fetch_courses.php?dept_id=' + departmentId)
            .then(response => response.json())
            .then(data => {
                data.forEach(course => {
                    var option = document.createElement('option');
                    option.value = course.CourseID;
                    option.textContent = course.Name;
                    courseSelect.appendChild(option);
                });
            })
            .catch(error => console.error('Error fetching courses:', error));
    }
});

// Fetch teacher guardians based on selected department
document.getElementById('department').addEventListener('change', function() {
    var departmentId = this.value;
    var teacherSelect = document.getElementById('teacherGuardian');
    teacherSelect.innerHTML = ''; // Clear previous options
    if (departmentId) {
        fetch('fetch_teachers.php?dept_id=' + departmentId)
            .then(response => response.json())
            .then(data => {
                data.forEach(teacher => {
                    var option = document.createElement('option');
                    option.value = teacher.FacultyID;
                    option.textContent = teacher.Name;
                    teacherSelect.appendChild(option);
                });
            })
            .catch(error => console.error('Error fetching teachers:', error));
    }
});
</script>

</body>
</html>
