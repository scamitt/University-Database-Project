<?php
// Start the session
session_start();

// Include your database connection file
include 'db_connection.php';

// Check if the user is logged in as faculty
if (isset($_SESSION['ID'])) {
    $facultyID = $_SESSION['ID'];

    // Fetch the subjects taught by the faculty member
    $sql_subjects = "SELECT DISTINCT gs.SubjID, s.Name AS SubjectName 
                     FROM GradeSheet gs 
                     INNER JOIN Subject s ON gs.SubjID = s.SubjID 
                     WHERE gs.FacultyID = ?";
    $stmt_subjects = mysqli_prepare($connection, $sql_subjects);
    mysqli_stmt_bind_param($stmt_subjects, "s", $facultyID);
    mysqli_stmt_execute($stmt_subjects);
    $result_subjects = mysqli_stmt_get_result($stmt_subjects);
    if ($result_subjects && mysqli_num_rows($result_subjects) > 0) {
        $subjects = mysqli_fetch_all($result_subjects, MYSQLI_ASSOC);
    }

    // Fetch students and their attendance records for the selected subject
    if (isset($_POST['subject'])) {
        $selectedSubject = $_POST['subject'];
        $sql_students = "SELECT a.StudentID, a.ClassesAttended, a.TotalClasses, p.FirstName, p.LastName 
                         FROM Attendance a 
                         JOIN Personal_Details p ON a.StudentID = p.StudentID 
                         JOIN GradeSheet gs ON a.StudentID = gs.StudentID AND a.SubjID = gs.SubjID 
                         WHERE gs.FacultyID = ? AND a.SubjID = ?";
        $stmt_students = mysqli_prepare($connection, $sql_students);
        mysqli_stmt_bind_param($stmt_students, "ss", $facultyID, $selectedSubject);
        mysqli_stmt_execute($stmt_students);
        $result_students = mysqli_stmt_get_result($stmt_students);
        if ($result_students && mysqli_num_rows($result_students) > 0) {
            $attendance = mysqli_fetch_all($result_students, MYSQLI_ASSOC);
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>View Attendance</title>
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
    text-align: center;
    background-color: #fff;
    padding: 20px;
    border-radius: 8px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
  }
  .container h2 {
    margin-bottom: 20px;
    color: #333;
  }
  .dropdown {
    margin-bottom: 20px;
  }
  .dropdown select, .dropdown button {
    padding: 10px;
    font-size: 16px;
    border: 1px solid #ccc;
    border-radius: 4px;
    margin-right: 10px;
  }
  .dropdown button {
    background-color: #4CAF50;
    color: white;
    border: none;
    cursor: pointer;
  }
  .dropdown button:hover {
    background-color: #45a049;
  }
  .attendance-table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 20px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
  }
  .attendance-table th, .attendance-table td {
    border: 1px solid #ddd;
    padding: 8px;
  }
  .attendance-table th {
    background-color: #f2f2f2;
  }
  .attendance-table td {
    background-color: #fff;
  }
  .attendance-table td:nth-child(odd) {
    background-color: #f9f9f9;
  }
  .no-records {
    color: #666;
    margin-top: 20px;
  }
</style>
</head>
<body>

  <div class="container">
    <h2>Welcome to View Attendance</h2>
    <form method="POST" class="dropdown">
      <label for="subject">Select Subject:</label>
      <select name="subject" id="subject">
        <option value="" selected disabled>Select Subject</option>
        <?php foreach ($subjects as $subject) { ?>
          <option value="<?php echo $subject['SubjID']; ?>"><?php echo $subject['SubjectName']; ?></option>
        <?php } ?>
      </select>
      <button type="submit">View Attendance</button>
    </form>
    <?php if (!empty($attendance)) { ?>
    <h3>Attendance Records for <?php echo $selectedSubject; ?></h3>
    <table class="attendance-table">
      <tr>
        <th>Student ID</th>
        <th>Student Name</th>
        <th>Classes Attended</th>
        <th>Total Classes</th>
        <th>Attendance Percentage</th>
      </tr>
      <?php foreach ($attendance as $record) { ?>
      <tr>
        <td><?php echo $record['StudentID']; ?></td>
        <td><?php echo $record['FirstName'] . ' ' . $record['LastName']; ?></td>
        <td><?php echo $record['ClassesAttended']; ?></td>
        <td><?php echo $record['TotalClasses']; ?></td>
        <td <?php if (($record['ClassesAttended'] / $record['TotalClasses']) * 100 < 75) echo 'style="color: red;"'; ?>>
    <?php echo round(($record['ClassesAttended'] / $record['TotalClasses']) * 100, 2); ?>%
</td>
        <td>
      </tr>
      <?php } ?>
    </table>
    <?php } else if (isset($_POST['subject'])) { ?>
    <p class="no-records">No attendance records found for selected subject.</p>
    <?php } ?>
  </div>
</body>
</html>
