
<?php
// Start the session
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Include your database connection file
include 'db_connection.php';

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Extract data from the form
    $userType = $_POST['userType'];
    $phone = $_POST['phone'];
    $firstName = $_POST['firstName'];
    $middleName = $_POST['middleName'];
    $lastName = $_POST['lastName'];
    $tenthMarks = $_POST['tenthMarks'];
    $twelfthMarks = $_POST['twelfthMarks'];
    $regNo = $_POST['regNo'];
    $birthDate = $_POST['birthDate'];
    $addressCity = $_POST['addressCity'];
    $parentName = $_POST['parentName'];
    $parentPhone = $_POST['parentPhone'];
    $parentEmail = $_POST['parentEmail'];
    $studentEmail = $_POST['studentEmail'];
    $sem = $_POST['sem'];
    $cgpa = $_POST['cgpa'];
    $courses = $_POST['courses']; // Added courses field
    $password = $_POST['password']; // Added password field

    $facultyID = $_SESSION['ID'];
    $studentID = $regNo;

    // $userType = 'Student';
    // $phone = '932442012';
    // $firstName = 'firstName';
    // $middleName = 'middleName';
    // $lastName = 'lastName';
    // $tenthMarks = 12;
    // $twelfthMarks = 12;
    // $regNo = '1';
    // $birthDate = '2022-08-08';
    // $addressCity = 'addressCity';
    // $parentName = 'parent Name';
    // $parentPhone = '999999999';
    // $parentEmail = 'parentEmail@gmail.com';
    // $studentEmail = 'studentEmail@gmail.com';
    // $sem = 3;
    // $cgpa = 3.1;
    // $courses = 'BTB'; // Added courses field
    // $password = 'password';

    // Prepare and execute the SQL statement to insert data into the Personal_Details table
    $sql_insert_details = "INSERT INTO `Personal_Details` (`StudentID`, `FirstName`, `MiddleName`, `LastName`, `BirthDate`, `Phone`, `AddressCity`, `TenthMarks`, `TwelfthMarks`, `ParentName`, `ParentPhone`, `ParentEmail`, `StudentEmail`) VALUES (?, ?, ?, ?, STR_TO_DATE(?, '%Y-%m-%d'), ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt_insert_details = $connection->prepare($sql_insert_details);

    // Check for errors in prepare()
    if (!$stmt_insert_details) {
        die('Error preparing statement: ' . $connection->error);
    }

    // Bind parameters
    $stmt_insert_details->bind_param("sssssssiissss", $regNo, $firstName, $middleName, $lastName, $birthDate, $phone, $addressCity, $tenthMarks, $twelfthMarks, $parentName, $parentPhone, $parentEmail, $studentEmail);

    // Execute the statement
    $insert_details_success = $stmt_insert_details->execute();

    // Check for errors in execute()
    if (!$insert_details_success) {
        die('Error executing statement: ' . $stmt_insert_details->error);
    }

    // Insert academic details into Academic_Details table
    $sql_insert_academic_details = "INSERT INTO `Academic_Details` (`StudentID`, `CourseID`, `Semester`, `TeacherGuardian`, `CGPA`) VALUES (?, ?, ?, ?, ?)";
    $stmt_insert_academic_details = $connection->prepare($sql_insert_academic_details);

    // Check for errors in prepare()
    if (!$stmt_insert_academic_details) {
        die('Error preparing statement: ' . $connection->error);
    }

    // Bind parameters
    $stmt_insert_academic_details->bind_param("ssisd", $regNo, $courses, $sem, $_SESSION['ID'], $cgpa);

    // Execute the statement
    $insert_academic_details_success = $stmt_insert_academic_details->execute();

    // Check for errors in execute()
    if (!$insert_academic_details_success) {
        die('Error executing statement: ' . $stmt_insert_academic_details->error);
    }

    // Prepare and execute the SQL statement to insert data into the student_credentials table
    $sql_insert_credentials = "INSERT INTO `student_credentials` (`ID`, `Password`) VALUES (?, ?)";
    $stmt_insert_credentials = $connection->prepare($sql_insert_credentials);

    // Check for errors in prepare()
    if (!$stmt_insert_credentials) {
        die('Error preparing statement: ' . $connection->error);
    }

    // Bind parameters
    $stmt_insert_credentials->bind_param("ss", $regNo, $password);

    // Execute the statement
    $insert_credentials_success = $stmt_insert_credentials->execute();

    // Check if all insertions were successful
    

    

if ($insert_details_success && $insert_academic_details_success && $insert_credentials_success) {
  // Data inserted successfully, redirect to registration_successful.php
  $_SESSION['registration_success'] = true;
  // Make sure to exit after redirection
  header("Location: registration_successful.php");
  exit();
} else {
  // Handle the case where at least one insertion failed
  // You can redirect back to the registration page with an error message
  $insert_error = "Failed to insert data into the database.";
  // Alternatively, you can display an error message and stay on the same page
  // echo $insert_error;
}
}
  
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Register</title>
<style>
body {
  font-family: Arial, sans-serif;
  background-color: #f2f2f2;
  margin: 0;
  padding: 0;
}

.register-container {
  background-color: #fff;
  padding: 40px;
  border-radius: 8px;
  box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
  width: 80%; 
  max-width: 600px; 
  margin: 40px auto; 
}

.register-section {
  margin-bottom: 20px;
}

.register-section label {
  font-weight: bold;
  margin-bottom: 5px;
}

.register-section input[type="text"],
.register-section input[type="password"],
.register-section input[type="number"],
.register-section input[type="date"],
.register-section select {
  width: 100%;
  padding: 10px;
  border: 1px solid #ccc;
  border-radius: 4px;
  box-sizing: border-box;
  font-size: 16px;
}

.phone-container {
  display: flex;
  align-items: baseline; 
}

.phone-prefix {
  padding: 10px;
  border: 1px solid #ccc;
  border-radius: 4px 0 0 4px;
  background-color: #f9f9f9;
  font-size: 16px;
}

.phone-input {
  flex: 1;
  padding: 10px;
  border: 1px solid #ccc;
  border-left: none;
  border-radius: 0 4px 4px 0;
  box-sizing: border-box;
  font-size: 16px;
}

.register-container input[type="submit"] {
  width: 100%;
  padding: 10px;
  border: none;
  border-radius: 4px;
  background-color: #4CAF50;
  color: white;
  cursor: pointer;
  font-size: 16px;
}

.register-container input[type="submit"]:hover {
  background-color: #45a049;
}

</style>
</head>
<body>
<div class="register-container">
  <div class="register-column">
    <!-- Registration form -->
    <form id="registerForm" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
        <!-- User details -->
        <div class="register-section">
          <label for="userType">User Type</label>
          <select id="userType" name="userType" required>
            <option value="student">Student</option>
            <!-- Add more options for other user types if needed -->
          </select>
        </div>
        <div class="register-section">
          <label for="phone">Phone Number</label>
          <input type="text" id="phone" name="phone" placeholder="Phone Number" >
        </div>
        <div class="register-section">
          <label for="firstName">First Name</label>
          <input type="text" id="firstName" name="firstName" placeholder="First Name" >
        </div>
        <div class="register-section">
          <label for="middleName">Middle Name</label>
          <input type="text" id="middleName" name="middleName" placeholder="Middle Name" >
        </div>
        <div class="register-section">
          <label for="lastName">Last Name</label>
          <input type="text" id="lastName" name="lastName" placeholder="Last Name" >
        </div>
        <div class="register-section">
          <label for="tenthMarks">Tenth Marks</label>
          <input type="number" id="tenthMarks" name="tenthMarks" placeholder="Tenth Marks" min="0" max="100" >
        </div>
        <div class="register-section">
          <label for="twelfthMarks">Twelfth Marks</label>
          <input type="number" id="twelfthMarks" name="twelfthMarks" placeholder="Twelfth Marks" min="0" max="100" >
        </div>
        <div class="register-section">
          <label for="regNo">Registration Number</label>
          <input type="text" id="regNo" name="regNo" placeholder="Registration Number" >
        </div>
        <div class="register-section">
          <label for="birthDate">Birth Date</label>
          <input type="date" id="birthDate" name="birthDate" >
        </div>
        <div class="register-section">
          <label for="addressCity">City</label>
          <input type="text" id="addressCity" name="addressCity" placeholder="City" >
        </div>
        <!-- Parent details -->
        <div class="register-section">
          <label for="parentName">Parent's Name</label>
          <input type="text" id="parentName" name="parentName" placeholder="Parent's Name" >
        </div>
        <div class="register-section">
          <label for="parentPhone">Parent's Phone Number</label>
          <div class="phone-container">
            <div class="phone-prefix">+91</div>
            <input type="text" id="parentPhone" name="parentPhone" class="phone-input" placeholder="Enter parent's phone number" >
          </div>
        </div>
        <div class="register-section">
          <label for="parentEmail">Parent's Email</label>
          <input type="text" id="parentEmail" name="parentEmail" placeholder="Parent's Email" >
        </div>
        <div class="register-section">
          <label for="studentEmail">Student's Email</label>
          <input type="text" id="studentEmail" name="studentEmail" placeholder="Student's Email" >
        </div>
        <div class="register-section">
          <label for="courses">Courses</label>
          <input type="text" id="courses" name="courses" placeholder="Enter Course ID" >
        </div>
        <div class="register-section">
          <label for="password">Password</label>
          <input type="password" id="password" name="password" placeholder="Password" >
        </div>
        <div class="register-section">
          <label for="cpga">CGPA</label>
          <input type="number" id="cgpa" step="any" name="cgpa" placeholder="CGPA" >
        </div>
        <div class="register-section">
          <label for="sem">Semester</label>
          <input type="number" id="sem"  name="sem" placeholder="SEMESTER" >
        </div>
        <!-- Submit button -->
        <input type="submit" value="Register">
    </form>
  </div>
</div>
</body>
</html>