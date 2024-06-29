<?php
session_start();
include 'db_connection.php';

if (isset($_SESSION['ID'])) {
    $studentID = $_SESSION['ID'];
    // Fetch financial details from the database
    $sql = "SELECT * FROM Financial_Details WHERE StudentID = ?";
    $stmt = mysqli_prepare($connection, $sql);
    mysqli_stmt_bind_param($stmt, "s", $studentID);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if ($result && mysqli_num_rows($result) > 0) {
        $financialDetails = mysqli_fetch_assoc($result);
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Financial Details</title>
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
  width: 500px;
}

.container h1 {
  text-align: center;
  margin-bottom: 20px;
}

.item {
  margin-bottom: 15px;
  border-bottom: 1px solid #ccc;
  padding-bottom: 10px;
}
</style>
</head>
<body>
  <div class="container">
    <h1>Financial Details</h1>
    <?php if (isset($financialDetails) && !empty($financialDetails)) { ?>
    <div class="row">
      <div class="item"><strong>Paid: </strong> <?php echo "₹ "; echo $financialDetails['Paid']; ?></div>
    </div>
    <div class="row">
      <div class="item"><strong>Outstanding: </strong> <?php echo "₹ "; echo $financialDetails['Outstanding']; ?></div>
    </div>
    <?php } else { ?>
    <p>No financial details found.</p>
    <?php } ?>
  </div>
</body>
</html>
