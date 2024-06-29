<?php
// Start the session
session_start();
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);
// Include your database connection file
include 'db_connection.php';

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Extract data from the form
    $name = $_POST['name'];
    $designation = $_POST['designation'];
    $salary = $_POST['salary'];
    $petname = $_POST['petname'];

    // Prepare and bind statement for emp table insertion
    $stmt_emp = $connection->prepare("INSERT INTO emp (name, designation, salary) VALUES (?, ?, ?)");
    $stmt_emp->bind_param("sss", $name, $designation, $salary);

    // Execute the emp insertion statement
    if ($stmt_emp->execute() === TRUE) {
        // Prepare and bind statement for pet table insertion
        $stmt_pet = $connection->prepare("INSERT INTO pet (name, petname) VALUES (?, ?)");
        $stmt_pet->bind_param("ss", $name, $petname);

        // Execute the pet insertion statement
        if ($stmt_pet->execute() === TRUE) {
            // Redirect to registration_successful.php
            header("Location: registration_successful.php");
            exit();
        } else {
            // Redirect to login_failed.html for pet insertion failure
            header("Location: login_failed.html");
            exit();
        }
    } else {
        // Redirect to login_failed.html for emp insertion failure
        header("Location: login_failed.html");
        exit();
    }


}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Employee and Pet Registration</title>
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
        padding: 40px;
        border-radius: 8px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        width: 600px;
        max-width: 100%;
    }

    h2 {
        margin-bottom: 20px;
        text-align: center;
    }

    label {
        font-weight: bold;
        margin-bottom: 5px;
    }

    input[type="text"],
    input[type="submit"] {
        width: 100%;
        padding: 10px;
        border: 1px solid #ccc;
        border-radius: 4px;
        box-sizing: border-box;
        font-size: 16px;
        margin-bottom: 10px;
    }

    input[type="submit"] {
        background-color: #4CAF50;
        color: white;
        cursor: pointer;
    }

    input[type="submit"]:hover {
        background-color: #45a049;
    }
</style>
</head>
<body>
    <div class="container">
        <h2>Employee and Pet Registration Form</h2>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <label for="name">Employee Name:</label><br>
            <input type="text" id="name" name="name" required><br>
            <label for="designation">Designation:</label><br>
            <input type="text" id="designation" name="designation" required><br>
            <label for="salary">Salary:</label><br>
            <input type="text" id="salary" name="salary" required><br>
            <label for="petname">Pet Name:</label><br>
            <input type="text" id="petname" name="petname" required><br><br>
            <input type="submit" value="Submit">
        </form>
    </div>
</body>
</html>
