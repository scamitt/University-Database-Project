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

    // Fetch students and their grades for the selected subject
    if (isset($_POST['subject'])) {
        $selectedSubject = $_POST['subject'];
        $sql_students = "SELECT gs.StudentID, gs.Marks, gs.GradeValue, p.FirstName, p.LastName 
                         FROM GradeSheet gs 
                         JOIN Personal_Details p ON gs.StudentID = p.StudentID 
                         WHERE gs.FacultyID = ? AND gs.SubjID = ?";
        $stmt_students = mysqli_prepare($connection, $sql_students);
        mysqli_stmt_bind_param($stmt_students, "ss", $facultyID, $selectedSubject);
        mysqli_stmt_execute($stmt_students);
        $result_students = mysqli_stmt_get_result($stmt_students);
        if ($result_students && mysqli_num_rows($result_students) > 0) {
            $grades = mysqli_fetch_all($result_students, MYSQLI_ASSOC);
        }
    }
}

// Update grades if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_grades'])) {
    foreach ($_POST['studentID'] as $key => $studentID) {
        $marks = $_POST['marks'][$key];
        $gradeValue = $_POST['gradeValue'][$key];
        $subjectID = $_POST['subjectID'][$key];

        // Perform update query
        $sql_update_grades = "UPDATE GradeSheet SET Marks = ?, GradeValue = ? WHERE StudentID = ? AND SubjID = ?";
        $stmt_update_grades = mysqli_prepare($connection, $sql_update_grades);
        mysqli_stmt_bind_param($stmt_update_grades, "isss", $marks, $gradeValue, $studentID, $subjectID);
        
        // Execute the update query
        if (mysqli_stmt_execute($stmt_update_grades)) {
            $update_success = true;
        } else {
            // Display error message if update fails
            $update_error = "Failed to update grades: " . mysqli_error($connection);
        }
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Select Subject</title>
<link rel="stylesheet" href="styles.css">
<style>
  /* Center the container */
  .container {
    text-align: center;
    margin: 50px auto;
    max-width: 600px;
  }

  /* Style the form */
  .dropdown {
    display: flex;
    justify-content: center;
    align-items: center;
    margin-bottom: 20px;
  }

  .dropdown label {
    margin-right: 10px;
  }

  .dropdown select {
    padding: 10px;
    font-size: 16px;
    border: 1px solid #ccc;
    border-radius: 4px;
    margin-right: 10px;
  }

  .dropdown button {
    padding: 10px 20px;
    font-size: 16px;
    background-color: #4CAF50;
    color: white;
    border: none;
    border-radius: 4px;
    cursor: pointer;
  }

  .dropdown button:hover {
    background-color: #45a049;
  }

  /* Style the table */
  .grades-table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 20px;
  }

  .grades-table th,
  .grades-table td {
    border: 1px solid #ddd;
    padding: 8px;
  }

  .grades-table th {
    background-color: #f2f2f2;
  }

  .grades-table td {
    background-color: #fff;
  }

  .grades-table td:nth-child(odd) {
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
  <h2>Select Subject</h2>
  <form method="POST" class="dropdown">
    <label for="subject">Select Subject:</label>
    <select name="subject" id="subject">
      <option value="" selected disabled>Select Subject</option>
      <?php foreach ($subjects as $subject) { ?>
      <option value="<?php echo $subject['SubjID']; ?>"><?php echo $subject['SubjectName']; ?></option>
      <?php } ?>
    </select>
    <button type="submit">View Grades</button>
  </form>
  <?php if (!empty($grades)) { ?>
  <h3>Grade Records for <?php echo $selectedSubject; ?></h3>
    <table class="grades-table">
      <tr>
        <th>Student ID</th>
        <th>Student Name</th>
        <th>Marks</th>
        <th>Grade</th>
      </tr>
      <?php foreach ($grades as $record) { ?>
      <tr>
        <td><?php echo $record['StudentID']; ?></td>
        <td><?php echo $record['FirstName'] . ' ' . $record['LastName']; ?></td>
        <td><input type="number" value="<?php echo $record['Marks']; ?>" disabled></td>
        <td><input type="text" value="<?php echo $record['GradeValue']; ?>" disabled></td>
      </tr>
      <?php } ?>
    </table>
  <?php } else if (isset($_POST['subject'])) { ?>
  <p class="no-records">No grade records found for selected subject.</p>
  <?php } ?>
</div> <!-- Close the container -->

</body>
</html>
