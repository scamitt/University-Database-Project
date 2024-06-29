<?php
session_start();
include 'db_connection.php';

if (isset($_SESSION['ID'])) {
    $studentID = $_SESSION['ID'];
    // Fetch attendance details from the database for the logged-in student and all subjects
    $sql = "SELECT * FROM Attendance WHERE StudentID = '$studentID'";
    $result = mysqli_query($connection, $sql);

    if ($result && mysqli_num_rows($result) > 0) {
        $attendances = mysqli_fetch_all($result, MYSQLI_ASSOC);
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Attendance Details</title>
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
    width: 600px;
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
    <h1>Attendance Details</h1>
    <?php if (isset($attendances) && !empty($attendances)) { ?>
    <div class="row">
        <div class="item"><strong>Subject</strong></div>
        <div class="item"><strong>Classes Attended</strong></div>
        <div class="item"><strong>Total Classes</strong></div>
        <div class="item"><strong>Classes Missed</strong></div>
        <div class="item"><strong>Attendance Percentage</strong></div>
    </div>
    <?php foreach ($attendances as $attendance) { ?>
    <div class="row">
        <div class="item"><?php echo $attendance['SubjID']; ?></div>
        <div class="item"><?php echo $attendance['ClassesAttended']; ?></div>
        <div class="item"><?php echo $attendance['TotalClasses']; ?></div>
        <div class="item"><?php echo $attendance['ClassesMissed']; ?></div>
        <div class="item <?php echo ($attendance['AttendancePercentage'] < 75) ? 'red' : ''; ?>"><?php echo $attendance['AttendancePercentage']; ?>%</div>
    </div>
    <?php } ?>
    <?php } else { ?>
    <p>No attendance details found.</p>
    <?php } ?>
</div>


</body>
</html>
