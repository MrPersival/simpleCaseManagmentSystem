<?php
$isRequestSended = false;
$isLogedIn = false;
$isUserCreated = false;

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

    if(isset($_POST["caseSend"]))
    {
        $titel = htmlspecialchars(string: strip_tags(trim(string: $_POST['titel'])));
        $description = htmlspecialchars(string: strip_tags(trim(string: $_POST['description'])));
        $priority = htmlspecialchars(string: strip_tags(trim(string: $_POST['priority'])));
        $category = htmlspecialchars(string: strip_tags(trim(string: $_POST['category'])));
        $clientName = htmlspecialchars(string: strip_tags(trim(string: $_POST['clientName'])));
        $clientCompany = htmlspecialchars(string: strip_tags(trim(string: $_POST['clientCompany'])));
        $clientPhone = htmlspecialchars(string: strip_tags(trim(string: $_POST['clientPhone'])));
        $clientEmail = $email;
        $date = date("Y/m/d");
        $assigningStatus = "Not assigned";

        $query = "INSERT INTO `requests` 
        (`titel`, `description`, `creationdate`, `lastupdate`, `status`, `priority`, `category`, `clientname`, `clientemail`, `company`, `phonenumber`) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

        //Because we binding parameters, SQL injections is impossible
        $sql = $conn->prepare($query);
        $sql->bind_param("sssssssssss",$titel, $description, $date, $date, $assigningStatus, $priority, $category, $clientName, $clientEmail, $clientCompany, $clientPhone);
        $sql->execute();

        $insertedId = $conn->insert_id;
        $messageTableName = "reqmessages" .$insertedId;

        $query = "CREATE TABLE `$messageTableName` (
            `id` int(11) NOT NULL,
            `author` varchar(100) NOT NULL,
            `dateSended` date NOT NULL,
            `content` text NOT NULL
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_swedish_ci;";

        $conn->execute_query($query);
        $conn->execute_query("ALTER TABLE `$messageTableName`
            ADD PRIMARY KEY (`id`);");
        $conn->execute_query("ALTER TABLE `$messageTableName`
            MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;");
        $conn->execute_query("COMMIT;");

        $query = "INSERT INTO `$messageTableName`(`author`, `dateSended`, `content`) VALUES (?, ?, ?);";
        $sql = $conn->prepare($query);
        $sql->bind_param("sss", $clientEmail, $date, $description);
        $sql->execute();
        $isRequestSended = true;
    }

    if(isset($_POST["userCreated"])){
        $newUserEmail = htmlspecialchars(string: strip_tags(string: trim(string: $_POST['newAccountEmail'])));
        $newUserPassword = md5(htmlspecialchars(string: strip_tags(string: trim(string: $_POST['newAccountPassword']))));
        $newUserAccessLevel = (int)$_POST['newAccountAccessLevel'];

        $query = "INSERT INTO `users`(`email`, `password`, `accesslevel`) VALUES (?, ?, ?)";

        $sql = $conn->prepare($query);
        $sql->bind_param("ssi", $newUserEmail, $newUserPassword, $newUserAccessLevel);
        $sql->execute();
        $isUserCreated = true;
    }

    if($oppenedPanel == 2) //Loading only if right panel is oppened
    {
        $query = "SELECT `id`, `titel`, `status`, `assignedto`, 'clientemail' FROM `requests` WHERE clientemail='$email'";
        $result = $conn->execute_query(query: $query);

        $userRequestsHTML = "";

        while($row = $result->fetch_assoc())
        {
            $rowId = $row['id'];
            $rowTitel = $row['titel'];
            $rowAssignedTo = $row['assignedto'];
            if($rowAssignedTo == "") $rowAssignedTo = "Support ännu inte tilldelad"; 
            $rowStatus = $row['status'];
            match ($rowStatus) {
                "In Progress" => $rowStatus = "<span style='background-color: green;'>$rowStatus</span>",
                "Closed" => $rowStatus = "<span style='background-color: red; color: white;'>$rowStatus</span>",
                "Not assigned" => $rowStatus = "<span style='background-color: orange;'>$rowStatus</span>"
            };


            $userRequestsHTML .="
                        <a class='caseShortShow changeColorOnHover' href='caseView.php?caseId=$rowId' target='_blank'>
                            <div class='id'>
                                <span>
                                    Request #$rowId
                                </span>
                            </div>
                            <div class='titel'>
                                <span>
                                    $rowTitel
                                </span>
                            </div>
                            <div class='assignedSupport'>
                                <span>
                                    $rowAssignedTo
                                </span>
                            </div>
                            <div class='status'>
                                $rowStatus
                            </div>
                        </a>
            ";
        }
    }

    if($oppenedPanel == 3){
        if($lvl == 10) $query = "SELECT `id`, `titel`, `status`, `assignedto`, 'clientemail' FROM `requests` WHERE NOT assignedto=''";
        else $query = "SELECT `id`, `titel`, `status`, `assignedto`, 'clientemail' FROM `requests` WHERE assignedto='$email'";
        $result = $conn->execute_query(query: $query);

        $supportsRequestsHTML = "";

        while($row = $result->fetch_assoc())
        {
            $rowId = $row['id'];
            $rowTitel = $row['titel'];
            $rowAssignedTo = $row['assignedto'];
            if($rowAssignedTo == "") $rowAssignedTo = "Support ännu inte tilldelad"; 
            $rowStatus = $row['status'];
            match ($rowStatus) {
                "In Progress" => $rowStatus = "<span style='background-color: green;'>$rowStatus</span>",
                "Closed" => $rowStatus = "<span style='background-color: red; color: white;'>$rowStatus</span>",
                "Not assigned" => $rowStatus = "<span style='background-color: orange;'>$rowStatus</span>"
            };


            $supportsRequestsHTML .="
                        <a class='caseShortShow changeColorOnHover' href='caseView.php?caseId=$rowId' target='_blank'>
                            <div class='id'>
                                <span>
                                    Request #$rowId
                                </span>
                            </div>
                            <div class='titel'>
                                <span>
                                    $rowTitel
                                </span>
                            </div>
                            <div class='assignedSupport'>
                                <span>
                                    $rowAssignedTo
                                </span>
                            </div>
                            <div class='status'>
                                $rowStatus
                            </div>
                        </a>
            ";
        }
    }

    if($oppenedPanel == 4){

        if(isset($_POST["ticketsIdToAssign"])){
            $ticketId = $_POST["ticketsIdToAssign"];
            $assignedSupport = $_POST["supportToAssignToTicket"];
            $assignedDate = date("Y/m/d");
            $query = "UPDATE `requests` SET `assignedTo` = '$assignedSupport', `status` = 'In Progress', `lastupdate` = $assignedDate WHERE `id` = $ticketId";
            $result = $conn->execute_query($query);
        }

        $query = "SELECT * FROM `users` WHERE accesslevel='10' OR accesslevel='5'";
        $supportsQueryResult = $conn->execute_query(query: $query);


        $query = "SELECT `id`, `titel`, `status`, `assignedto`, 'clientemail' FROM `requests` WHERE assignedto=''";
        $result = $conn->execute_query(query: $query);        

        $notAssignedRequests = "";

        while($row = $result->fetch_assoc())
        {
            $rowId = $row['id'];

            $supportButtonsInString = "";
            while($supportRow = $supportsQueryResult->fetch_assoc()){
                $support = $supportRow['email'];
                $supportButtonsInString .= "<button onclick='assignSupport(\"$rowId\", \"$support\")'>$support</button>";
            }

            $rowTitel = $row['titel'];
            $rowAssignedTo = "
            <div class='dropdown'>
            <button onclick='dropdownFunction()' class='dropbtn'>Tilldela</button>
                <div id='assigningDropdown' class='dropdown-content'>
                    <input type='text' placeholder='Search..' id='assigningDropdownInput' onkeyup='filterDropdown()'>
                    $supportButtonsInString
                </div>
            </div>"; 
            $rowStatus = $row['status'];
            match ($rowStatus) {
                "In Progress" => $rowStatus = "<span style='background-color: green;'>$rowStatus</span>",
                "Closed" => $rowStatus = "<span style='background-color: red; color: white;'>$rowStatus</span>",
                "Not assigned" => $rowStatus = "<span style='background-color: orange;'>$rowStatus</span>"
            };

            $notAssignedRequests .="
                        <div class='caseShortShow changeColorOnHover'>
                            <div class='id'>
                                <a href='caseView.php?caseId=$rowId' target='_blank'>
                                    Request #$rowId
                                </a>
                            </div>
                            <div class='titel'>
                                <span>
                                    $rowTitel
                                </span>
                            </div>
                            <div class='assignedSupport'>
                                $rowAssignedTo
                            </div>
                            <div class='status'>
                                $rowStatus
                            </div>
                        </div>
            ";
        }
    }

    if($oppenedPanel == 5){
        if(isset($_POST["userToDelete"])){
            $id = $_POST['userToDelete'];
            $query = "DELETE FROM `users` WHERE `id` = $id";
            $conn->execute_query($query);
        }


        $query = "SELECT * FROM `users` WHERE accesslevel='0' OR accesslevel='5'";
        $accountsQueryResult = $conn->execute_query(query: $query);
        
        $accountsHTML = "";

        while($row = $accountsQueryResult->fetch_assoc())
        {
            $rowId = $row['id'];
            $rowEmail = $row['email'];
            $rowAccessLevel = $row['accesslevel'];

            $accountsHTML .="
                        <div class='caseShortShow'>
                            <div class='id'>
                                <span>
                                    User #$rowId
                                </span>
                            </div>
                            <div class='titel'>
                                <span>
                                    $rowEmail
                                </span>
                            </div>
                            <div class='acessLevel'>
                                <span>
                                    $rowAccessLevel
                                </span>
                            </div>
                            <button class='deleteUserButton'  onclick='deleteUser($rowId)'>Ta bort</button>
                        </div>
            ";
        }
    }

    $conn->close();
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
                <button class="menuButton" id="pannelButton1" onclick="pannelButtonPressed(1)">Nytt ärende</button>
                <button class="menuButton" id="pannelButton2" onclick="pannelButtonPressed(2)">Mina ärende</button>

                <?php if($lvl >= 5) { ?>
                    <button class="menuButton" id="pannelButton3" onclick="pannelButtonPressed(3)">Tilldelat ärende</button>
                <?php } ?>
                <?php if($lvl >= 10) { ?>
                    <button class="menuButton" id="pannelButton4" onclick="pannelButtonPressed(4)">Tilldela ärende</button>
                    <button class="menuButton" id="pannelButton5" onclick="pannelButtonPressed(5)">Skapa ny användare</button>
                <?php } ?>
            </div>
        </div>
        <div class="mainPanel">
            <div id="panelContent">
                <?php if($oppenedPanel == 1) {?>
                        <div id="panelHeader"><h1>Nytt ärende</h1></div>
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

                        <label for="clientName">Ditt namn:</label>
                        <input type="text" id="clientName" name="clientName" required>
                        <label for="clientCompany">Ditt företag:</label>
                        <input type="text" id="clientCompany" name="clientCompany" placeholder="Valfritt">
                        <label for="clientPhone">Ditt telefonnummer:</label>
                        <input type="text" id="clientPhone" name="clientPhone" placeholder="Valfritt">
                        <input type="submit" value="Skicka ärande" name="caseSend">
                    </form>
                <?php } elseif  ($oppenedPanel == 2) { ?>
                    <div id="panelHeader"><h1>Mina ärende</h1></div>
                    <div class="caseHolder">
                        <?=$userRequestsHTML?>
                    </div>
                <?php } elseif  ($oppenedPanel == 3) { ?>
                    <?php if($lvl >= 5) {?>
                    <div id="panelHeader"><h1>Tilldelat ärende</h1></div>
                    <div class="caseHolder">
                        <?=$supportsRequestsHTML?>
                        <?php
                        if($supportsRequestsHTML == "") echo "<span style='font-family: sans-serif; font-weight: 600'>Ingen ärende är skapad av.</span>";
                        ?>
                    </div>
                    <?php }else{ ?>
                        <p>Du har inte tillgång till det här sida</p>
                    <?php } ?>
                <?php } elseif  ($oppenedPanel == 4) { ?>
                    <?php if($lvl >= 10) {?>
                        <div id="panelHeader"><h1>Tilldela ärende</h1></div>
                        <?=$notAssignedRequests?>
                            <?php
                            if($notAssignedRequests == "") echo "<span style='font-family: sans-serif; font-weight: 600'>   </span>";
                        ?>
                    <?php }else{ ?>
                        <p>Du har inte tillgång till det här sida</p>
                    <?php } ?>
                <?php } elseif  ($oppenedPanel == 5) { ?>
                    <?php if($lvl >= 10) {?>
                    <div id="panelHeader"><h1>Skapa ny användare</h1></div>
                    <?php if($isUserCreated) { ?>
                        <h2 style="color: green; font-weight: 500; font-family: sans-serif;">Ny användare skapad!</h2>
                    <?php } ?>
                    <form action="logedIn.php?oppenedPanel=5" class="newCaseForm" method="post">
                        <label for="newAccountEmail">Email</label>
                        <input type="email" required name="newAccountEmail" id="newAccountEmail" maxlength="100">
                        <label for="newAccountPassword">Lösenord</label>
                        <input type="password" required name="newAccountPassword" id="newAccountPassword" maxlength="100">
                        <label for="newAccountAccessLevel">Roll</label>
                        <select name="newAccountAccessLevel" id="newAccountAccessLevel" required>
                            <option value="0">Användare</option>
                            <option value="5">Support</option>
                        </select>
                        <br>
                        <input type="submit" value="Skappa konto" name="userCreated">
                    </form>
                    <br>
                    <div class="caseHolder">
                        <?=$accountsHTML?>
                    </div>
                    <?php }else{ ?>
                        <p>Du har inte tillgång till det här sida</p>
                    <?php } ?>
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

    function dropdownFunction() {
        document.getElementById("assigningDropdown").classList.toggle("show");
    }

    function filterDropdown() {
        const input = document.getElementById("assigningDropdownInput");
        const filter = input.value.toUpperCase();
        const div = document.getElementById("assigningDropdown");
        const button = div.getElementsByTagName("button");
        for (let i = 0; i < button.length; i++) {
            txtValue = button[i].textContent || button[i].innerText;
            if (txtValue.toUpperCase().indexOf(filter) > -1) {
            button[i].style.display = "";
            } else {
            button[i].style.display = "none";
            }
        }
    }

    function assignSupport(ticketId, supportToAssign)
    {
        $.ajax({
                type: "POST", 
                url: 'logedIn.php?oppenedPanel=4',
                data: { 
                    ticketsIdToAssign: ticketId, 
                    supportToAssignToTicket: supportToAssign
                },
                success: function(response) {
                    //console.info("Request sended: " + response);
                    window.location.href = "logedIn.php?oppenedPanel=4";

                },
                error: function(error) {
                    console.error("Error:", error);
                }
            });
    }

    function deleteUser(userId){
        var answer = confirm("Är du säker att du vill ta bort användare? Denna åtgärd kan inte återkallas")
        if(answer){
            $.ajax({
                type: "POST", 
                url: 'logedIn.php?oppenedPanel=5',
                data: { 
                    userToDelete: userId, 
                },
                success: function(response) {
                    window.location.href = "logedIn.php?oppenedPanel=5";
                },
                error: function(error) {
                    console.error("Error:", error);
                }
            });
        }
    }
</script>

</body>
</html>