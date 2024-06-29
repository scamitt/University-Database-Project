<?php
session_start();
include 'db_connection.php';

if (isset($_SESSION['ID'])) {
    $studentID = $_SESSION['ID'];
    // Fetch grade details from the database for the logged-in student
    $sql = "SELECT GradeSheet.*, Subject.Credits AS SubjectCredits, Faculty.Name AS FacultyName 
            FROM GradeSheet 
            INNER JOIN Faculty ON GradeSheet.FacultyID = Faculty.FacultyID 
            INNER JOIN Subject ON GradeSheet.SubjID = Subject.SubjID 
            WHERE StudentID = '$studentID'";
    $result = mysqli_query($connection, $sql);

    if ($result && mysqli_num_rows($result) > 0) {
        $grades = mysqli_fetch_all($result, MYSQLI_ASSOC);
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Grade Details</title>
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
    width: 800px;
}

.container h1 {
    text-align: center;
    margin-bottom: 20px;
}

.row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 10px;
    border-bottom: 1px solid #ccc;
    padding-bottom: 5px;
}

.item {
    flex: 1;
    text-align: center;
}

.item strong {
    font-weight: bold;
}

.red {
    color: red;
}

</style>
</head>
<body>
<div class="container">
    <h1>Grade Details</h1>
    <?php if (isset($grades) && !empty($grades)) { ?>
    <div class="row">
        <div class="item"><strong>Subject</strong></div>
        <div class="item"><strong>Credits</strong></div>
        <div class="item"><strong>Marks</strong></div>
        <div class="item"><strong>Grade</strong></div>
        <div class="item"><strong>Faculty</strong></div>
        <div class="item"><strong>Semester</strong></div>
    </div>
    <?php foreach ($grades as $grade) { ?>
    <div class="row">
        <div class="item"><?php echo $grade['SubjID']; ?></div>
        <div class="item"><?php echo $grade['SubjectCredits']; ?></div>
        <div class="item"><?php echo $grade['Marks']; ?></div>
        <div class="item"><?php echo $grade['GradeValue']; ?></div>
        <div class="item"><?php echo $grade['FacultyName']; ?></div>
        <div class="item"><?php echo $grade['Semester']; ?></div>
    </div>
    <?php } ?>
    <?php } else { ?>
    <p>No grade details found.</p>
    <?php } ?>
</div>
</body>
</html>
