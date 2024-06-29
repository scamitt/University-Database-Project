<?php

session_start();

include 'db_connection.php';

if (isset($_SESSION['ID'])) {
    $facultyID = $_SESSION['ID'];

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

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_attendance'])) {
    foreach ($_POST['studentID'] as $key => $studentID) {
        $classesAttended = $_POST['classesAttended'][$key];
        $totalClasses = $_POST['totalClasses'][$key];
        $subjectID = $_POST['subjectID'][$key];

        $sql_update_attendance = "UPDATE Attendance SET ClassesAttended = ?, TotalClasses = ? WHERE StudentID = ? AND SubjID = ?";
        $stmt_update_attendance = mysqli_prepare($connection, $sql_update_attendance);
        mysqli_stmt_bind_param($stmt_update_attendance, "iiss", $classesAttended, $totalClasses, $studentID, $subjectID);
        mysqli_stmt_execute($stmt_update_attendance);
    }
    $update_success = true;
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>View and Update Attendance</title>
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
  .dropdown select, .dropdown button, .update-button {
    padding: 10px;
    font-size: 16px;
    border: 1px solid #ccc;
    border-radius: 4px;
    margin-right: 10px;
  }
  .dropdown button, .update-button {
    background-color: #4CAF50;
    color: white;
    border: none;
    cursor: pointer;
  }
  .dropdown button:hover, .update-button:hover {
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
  .update-form {
    display: flex;
    justify-content: center;
    align-items: center;
    margin-top: 20px;
  }
  .update-form input[type="number"] {
    width: 50px;
    padding: 8px;
    font-size: 14px;
  }
  .update-button, .increment-button {
    padding: 8px 16px;
    font-size: 14px;
    border: none;
    border-radius: 4px;
    cursor: pointer;
  }
  .update-button {
    background-color: #4CAF50;
    color: white;
    margin-right: 10px;
  }
  .update-button:hover {
    background-color: #45a049;
  }
  .increment-button {
    background-color: #007bff;
    color: white;
  }
  .increment-button:hover {
    background-color: #0056b3;
  }
  .error-message {
    color: red;
    margin-top: 10px;
  }
  .success-message {
    color: green;
    margin-top: 10px;
  }
</style>
</head>
<body>

  <div class="container">
    <h2>Welcome to Update Attendance</h2>
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
    <form method="POST">
    <table class="attendance-table">
      <tr>
        <th>Student ID</th>
        <th>Student Name</th>
        <th>Classes Attended</th>
        <th>Total Classes</th>
        <th>Attendance Percentage</th>
        <th>Action</th>
      </tr>
      <?php foreach ($attendance as $record) { ?>
      <tr>
        <td><?php echo $record['StudentID']; ?></td>
        <td><?php echo $record['FirstName'] . ' ' . $record['LastName']; ?></td>
        <td>
          <input type="hidden" name="studentID[]" value="<?php echo $record['StudentID']; ?>">
          <input type="hidden" name="subjectID[]" value="<?php echo $selectedSubject; ?>">
          <input type="number" name="classesAttended[]" value="<?php echo $record['ClassesAttended']; ?>" required>
        </td>
        <td><?php echo $record['TotalClasses']; ?></td>
        <td <?php if (($record['ClassesAttended'] / $record['TotalClasses']) * 100 < 75) echo 'style="color: red;"'; ?>>
    <?php echo round(($record['ClassesAttended'] / $record['TotalClasses']) * 100, 2); ?>%
</td>
        <td>
          <input type="number" name="totalClasses[]" value="<?php echo $record['TotalClasses']; ?>" required>
          <input type="checkbox" name="update_total[]" value="<?php echo $record['StudentID']; ?>" checked> Update Total
        </td>
      </tr>
      <?php } ?>
    </table>
      <button type="submit" name="update_attendance" class="update-button">Update Attendance</button>
    </form>
    <form method="POST" class="update-form">
      <input type="hidden" name="selectedSubject" value="<?php echo $selectedSubject; ?>">
      <button type="button" class="increment-button" id="increment-all">Increment All</button>
    </form>
    <?php } else if (isset($_POST['subject'])) { ?>
    <p class="no-records">No attendance records found for selected subject.</p>
    <?php } ?>
    <?php if (isset($update_success)) { ?>
    <p class="success-message">Attendance updated successfully.</p>
    <?php } ?>
    <?php if (isset($update_error)) { ?>
    <p class="error-message"><?php echo $update_error; ?></p>
    <?php } ?>
    </div> 

<script>
document.getElementById('increment-all').addEventListener('click', function() {
    var rows = document.querySelectorAll('.attendance-table tr');
    rows.forEach(function(row, index) {
        if (index !== 0) { 
            var inputs = row.querySelectorAll('input[type="number"]');
            var updateCheckbox = row.querySelector('input[type="checkbox"]');
            if (updateCheckbox.checked) {
                inputs.forEach(function(input, i) {
                    if (i === 0) {
                        input.value = parseInt(input.value) + 1;
                    } else {
                        input.value = parseInt(input.value) + 1;
                    }
                });
            }
        }
    });
});
</script>

</body>
</html>
