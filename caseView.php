<?php
$isLogedIn = false;
$isHasAcess = true;
$isCaseFinded = true;

session_start();
if(isset($_SESSION["accesslevel"]))
{
    $isLogedIn = true;

    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbName = "swiftsupportdb";    
    // Create connection
    $conn = new mysqli($servername, $username, $password, $dbName);
    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $caseId = (int)htmlspecialchars(string: strip_tags(trim(string: $_GET['caseId'])));
    $query = "SELECT * FROM `requests` WHERE caseId=?";
    $sql = $conn->prepare($query);
    $sql->bind_param("i", $caseId);
    $sql->execute();
    $result = $sql->get_result();
    if(mysqli_num_rows($result) == 0) $isCaseFinded = false;
    $case = $result->fetch_assoc();
    if($case["clientmail"] != $_SESSION["email"] && $case["assignedto"] != $_SESSION["email"] && $_SESSION["accesslevel"] < 10) $isHasAcess = false;

}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SwiftSupport</title>
</head>
<body>
    <?php if($isLogedIn) { ?>
        <?php if(!$isHasAcess || !$isCaseFinded) echo "<p>Du har inte tillgång till det här ärande eller ärande existerar inte.</p>";
        else { ?>

        <div class="ticketHeader">
        </div>
        <div class="ticketMessages">
        </div>
        <div class="newMessageTicket">
        </div>

        <?php } ?>
    <?php } else { ?>
            <p>Du är inte inllogad. <a href="index.php">Log in</a>.</p>
    <?php }?>
</body>
</html>