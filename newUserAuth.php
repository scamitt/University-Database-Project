<?php
// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $userType = $_POST['userType'];
    $regNo = $_POST['regNo']; // Assuming this is the registration number field
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Validate form data (you may add more validation as needed)

    // Connect to MySQL database (replace these values with your own)
    $servername = "localhost";
    $dbusername = "root";
    $dbpassword = "";
    $dbname = "StudentLMS";

    // Create connection
    $conn = new mysqli($servername, $dbusername, $dbpassword, $dbname);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Prepare SQL statement to insert data into the credentials table
    $sql = "INSERT INTO credentials (userType, regNo, username, password) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("siss", $userType, $regNo, $username, $password);

    // Execute SQL statement
    if ($stmt->execute()) {
        echo "New record created successfully";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }

    // Close connection
    $stmt->close();
    $conn->close();
}
?>
