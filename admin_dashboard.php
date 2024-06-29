<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Department, Course, or Subject</title>
    <style>
        .container {
            width: 50%;
            margin: 0 auto;
            padding: 20px;
            border: 1px solid #ccc;
        }

        input[type="text"],
        select {
            width: 100%;
            padding: 8px;
            margin: 5px 0;
            box-sizing: border-box;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Create Department, Course, or Subject</h2>
        <form id="createForm" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
            <label for="actionType">Action Type:</label>
            <select name="actionType" id="actionType">
                <option value="Create Department">Create Department</option>
                <option value="Create Course">Create Course</option>
                <option value="Create Subject">Create Subject</option>
            </select><br><br>
            <!-- Other fields based on selected action type -->
            <!-- For simplicity, I'll leave it here as it's quite similar -->
            <label for="id">ID:</label>
            <input type="text" id="id" name="id"><br><br>
            <label for="name">Name:</label>
            <input type="text" id="name" name="name"><br><br>
            <!-- Additional fields based on the action type -->
            <input type="submit" value="Submit">
        </form>
    </div>
    <script>
        // You can add client-side validation here if needed
    </script>
</body>
</html>

<?php
// Include database connection file
include_once "db_connection.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data and sanitize
    $actionType = $_POST['actionType'];
    $id = $_POST['id'];
    $name = $_POST['name'];

    // Call the appropriate stored procedure based on action type
    switch ($actionType) {
        case "Create Department":
            $stmt = $pdo->prepare("CALL CreateDepartment(?, ?, ?, ?)");
            $stmt->execute([$adminID, $id, $name, $location, $hod]);
            break;
        case "Create Course":
            $stmt = $pdo->prepare("CALL CreateCourse(?, ?, ?, ?, ?)");
            $stmt->execute([$adminID, $id, $name, $credits, $deptID]);
            break;
        case "Create Subject":
            $stmt = $pdo->prepare("CALL CreateSubject(?, ?, ?, ?, ?, ?)");
            $stmt->execute([$adminID, $id, $name, $credits, $impartusLink, $courseID]);
            break;
        default:
            echo "Invalid action type";
    }

    // Redirect back to the form after execution
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}
?>
