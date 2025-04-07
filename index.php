<?php
$isTryingToLogIn = false;
$isLogInSuccessful = false;
if(isset($_POST["logInButton"])){
    $isTryingToLogIn = true;

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

    $email = htmlspecialchars(string: strip_tags(trim(string: $_POST['username']))); //Because we using execute_query, SQL injections is impossible
    $password = md5(htmlspecialchars(string: strip_tags(trim(string: $_POST['password'])))); //Because we using execute_query, SQL injections is impossible

    $query = "SELECT * FROM users WHERE email=?";
    $result = $conn->execute_query( $query, [$email]);
    if($result->num_rows != 0){
        while($row = $result->fetch_assoc()){
            if($row["password"] == $password) {
                $isLogInSuccessful = true;
                session_start();
                $_SESSION["email"] = $email;
                $_SESSION["password"] = $password;
                $_SESSION["accesslevel"] = $row["accesslevel"];
            }
        }
    }
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
</head>
<body class="fullHeight">
<div id="shadownBlur">
    <header>
        <h1 class="logo">SwiftSupport</h1>
    </header>
    <div id="loginField">
            <form action="index.php" method="post" class="loginForm">
                <label for="username">E-mail:</label><br>
                <input type="email" id="username" name="username"><br>
                <label for="password">Password:</label><br>
                <input type="password" id="password" name="password"><br>
                <input type="submit" value="Log in" name="logInButton">
                <p style="color: red; font-weight: 600; display: none;" id="errorMessage">Fel e-post eller lösenord</p>
            </form>
    </div>
</div>

<script>
    if(<?=json_encode($isTryingToLogIn) ?>){
        if(<?=json_encode($isLogInSuccessful) ?>){
            document.getElementById("errorMessage").style.display = "none";
            window.open("logedIn.php", "_self");
        }
        else{
            document.getElementById("errorMessage").style.display = "block";
        }
    }
</script>
</body>
</html>