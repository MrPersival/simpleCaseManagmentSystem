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
    $query = "SELECT * FROM `requests` WHERE id=?";
    $sql = $conn->prepare($query);
    $sql->bind_param("i", $caseId);
    $sql->execute();
    $result = $sql->get_result();
    if(mysqli_num_rows($result) == 0) $isCaseFinded = false;
    $case = $result->fetch_assoc();
    if($isCaseFinded) if($case["clientemail"] != $_SESSION["email"] && $case["assignedto"] != $_SESSION["email"] && $_SESSION["accesslevel"] < 10) $isHasAcess = false;
    
    $caseName = $case["titel"];
    $caseAuthor = $case["clientemail"];
    $caseCreationDay = $case["creationdate"];
    $caseAssginedTo = $case["assignedto"];

    $messagesTableName = "reqmessages" . $caseId;
    $result = $conn->execute_query("SELECT * FROM `$messagesTableName`");
    $messagesHTML = "";
    while($message = $result->fetch_assoc()){
        $author = $message["author"];
        $date = $message["dateSended"];
        $content = $message["content"];

        if($author == $_SESSION["email"]) $messageClass = "yourMessage";
        else $messageClass = "notYourMessage";

        $messagesHTML .= "<div class='$messageClass'>
            <h4>$author, $date</h4>
            <span>$content</span>
            </div>";
    }

    if(isset($_POST["sendMessage"])){
        $messageToSend = htmlspecialchars(string: strip_tags(trim(string: $_POST['messageText'])));
        $date = date("Y/m/d");

        $query = "INSERT INTO `$messagesTableName`(`author`, `dateSended`, `content`) VALUES (?, ?, ?)";
        $sql = $conn->prepare($query);
        $sql->bind_param("sss",  $_SESSION["email"], $date, $messageToSend);
        $sql->execute();
        
        $query = "UPDATE `requests` SET `lastupdate` = ? WHERE `requests`.`id` = $caseId";
        $sql = $conn->prepare($query);
        $sql->bind_param("s", $date);
        $sql->execute();
    }

}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Roboto:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <title>SwiftSupport</title>
</head>
<body class="caseView">
    <?php if($isLogedIn) { ?>
        <?php if(!$isHasAcess || !$isCaseFinded) echo "<p>Du har inte tillgång till det här ärande eller ärande existerar inte.</p>";
        else { ?>

        <div class="ticketHeader">
            <h1><?=$caseName?></h1>
            <h3><?=$caseAuthor?></h3>
            <h3><?=$caseCreationDay?></h3>
            <h3>Assigned to: <?=$caseAssginedTo?></h3>
        </div>
        <div class="ticketMessages">
            <?=$messagesHTML?>
        </div>
        <div class="newMessageTicket">
            <form action="caseView.php<?="?caseId=$caseId"?>" method="post">
                <textarea name="messageText" id="messageText" class="newMessage"></textarea>
                <input type="submit" value="Skicka" name="sendMessage" class="submitButton" onclick="updatePage()">
            </form>
        </div>
        <?php } ?>  
    <?php } else { ?>
            <p>Du är inte inllogad. <a href="index.php">Log in</a>.</p>
    <?php }?>
</body>
<script>
    function updatePage(){
        setTimeout(() => { //Too stupid, but works
            window.location.href = "caseView.php?caseId=<?=$caseId?>";
            window.scrollTo(0, 0);
            }, 1);
    }
</script>
</html>