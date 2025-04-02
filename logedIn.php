<?php
$isRequestSended = false;
$isLogedIn = false;
session_start();
if(isset($_SESSION["accesslevel"]))
{
    $isLogedIn = true;
    $email = $_SESSION["email"];
    $password = $_SESSION["password"];
    $lvl = $_SESSION["accesslevel"];
    $oppenedPanel = 1;
    if(isset($_GET["oppenedPanel"])) $oppenedPanel = $_GET["oppenedPanel"];    

    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbName = "swiftsupportdb";    
    // Create connection
    $conn = new mysqli($servername, $username, $password, $dbName);
}
if(isset($_POST["caseSend"]))
{
    $titel = htmlspecialchars(string: strip_tags(trim(string: $_POST['titel'])));
    $description = htmlspecialchars(string: strip_tags(trim(string: $_POST['description'])));
    $priority = htmlspecialchars(string: strip_tags(trim(string: $_POST['priority'])));
    $category = htmlspecialchars(string: strip_tags(trim(string: $_POST['category'])));
    $clientName = htmlspecialchars(string: strip_tags(trim(string: $_POST['clientName'])));
    $clientCompany = htmlspecialchars(string: strip_tags(trim(string: $_POST['clientCompany'])));
    $clientPhone = htmlspecialchars(string: strip_tags(trim(string: $_POST['clientPhone'])));
    $clientEmail = htmlspecialchars(string: strip_tags(trim(string: $_POST['clientEmail'])));
    $date = date("Y/m/d");
    $assigningStatus = "Not assigned";

    $query = "INSERT INTO `requests` 
    (`titel`, `description`, `creationdate`, `lastupdate`, `status`, `priority`, `category`, `clientname`, `clientemail`, `company`, `phonenumber`) 
    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

    //Because we using execute, SQL injections is impossible
    $sql = $conn->prepare($query);
    $sql->bind_param("sssssssssss",$titel, $description, $date, $date, $assigningStatus, $priority, $category, $clientName, $clientEmail, $clientCompany, $clientPhone);
    $sql->execute();
    $isRequestSended = true;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SwiftSupport</title>
    <link rel="stylesheet" href="style.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Roboto:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
</head>
<body id="loggedInPage">
    <?php if($isLogedIn) { ?>
        <div class="leftPanel">
            <div class="userInfo">
                <p id="textInfo">
                    Du inlogad som <br>
                    <?=$email ?> <br>
                    Access level <?=$lvl ?>
                </p>
            </div>
            <div class="menuButtonsHolder">
                <button class="menuButton" id="pannelButton1" onclick="pannelButtonPressed(1)">Ny ärende</button>
                <button class="menuButton" id="pannelButton2" onclick="pannelButtonPressed(2)">Mina ärende</button>

                <?php if($lvl >= 5) { ?>
                    <button class="menuButton" id="pannelButton3" onclick="pannelButtonPressed(3)">Tilldelat ärende</button>
                <?php } ?>
                <?php if($lvl >= 10) { ?>
                    <button class="menuButton" id="pannelButton4" onclick="pannelButtonPressed(4)">Tilldela ärende</button>
                    <button class="menuButton" id="pannelButton5" onclick="pannelButtonPressed(5)">Skappa nytt användare</button>
                <?php } ?>
            </div>
        </div>
        <div class="mainPanel">
            <div id="panelHeader"><h1>Ny ärende</h1></div>
            <div id="panelContent">
                <?php if($oppenedPanel == 1) {?>
                    <?php if($isRequestSended) { ?>
                        <h2 style="color: green; font-weight: 500; font-family: sans-serif;">Din ärande skickat!</h2>
                    <?php } ?>
                    <form action="logedIn.php" class="newCaseForm" method="post">
                        <label for="titel">Titel</label>
                        <input type="text" required name="titel" id="titel" placeholder="Kort beskrivning av ärendet (max 100 tecken)" maxlength="100">
                        <label for="description">Beskrivning</label>
                        <textarea required name="description" id="description" placeholder="Detaljerad beskrivning av problemet"></textarea>
                        <label for="priority">Prioritet</label>
                        <select name="priority" id="priority" required>
                            <option value="low" style="background-color: green; color: white;">Låg</option>
                            <option value="medium" style="background-color: orange; color: white;">Medel</option>
                            <option value="high" style="background-color: red; color: white;">Hög</option>
                            <option value="critical" style="background-color: black; color: white;">Akut</option>
                        </select>
                        <label for="category">Kategori</label>
                        <select name="category" id="category" required>
                            <option value="techincalIssue">Tekniskt problem</option>
                            <option value="invoicing">Fakturering</option>
                            <option value="genericQuestion">Allmän fråga</option>
                        </select>

                        <label for="clientName">Din namn:</label>
                        <input type="text" id="clientName" name="clientName" required>
                        <label for="clientEmail">Din email:</label>
                        <input type="clientEmail" id="clientEmail" name="clientEmail" required>
                        <label for="clientCompany">Din företag:</label>
                        <input type="text" id="clientCompany" name="clientCompany" placeholder="Valfritt">
                        <label for="clientPhone">Din telefonnummer:</label>
                        <input type="text" id="clientPhone" name="clientPhone" placeholder="Valfritt">
                        <input type="submit" value="Skicka ärande" name="caseSend">
                    </form>
                <?php } elseif  ($oppenedPanel == 2) { ?>
                    <p>Test panel 2</p>
                <?php } elseif  ($oppenedPanel == 3) { ?>
                    <p>Test panel 3</p>
                <?php } elseif  ($oppenedPanel == 4) { ?>
                    <p>Test panel 4</p>
                <?php } elseif  ($oppenedPanel == 5) { ?>
                    <p>Test panel 5</p>
                <?php } ?>
            </div>
        </div>

    <?php } else { ?>
        <p>Du är inte inllogad. <a href="index.php">Log in</a>.</p>
    <?php }?>
<script>
    function pannelButtonPressed(pannelNumber){
        window.location.href = "logedIn.php?oppenedPanel=" + pannelNumber;
    }
</script>

</body>
</html>