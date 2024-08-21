<?php
    session_start();

    if(!isset($_SESSION['logedIn'])){
        header("Location: index.php");
        exit();
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Account</title>
    <style>
        .userData{
            text-decoration: underline;
            color: brown;
        }
    </style>
</head>
<body>
    <h1>Welcome</h1><br>
    <h1>Your account id: 
        <h1 class="userData"><?= $_SESSION['user_id'] ?></h1>
    </h1>
    <br>
    <h1>Your account nick: 
        <h1 class="userData"><?= $_SESSION['user_nick'] ?></h1>
    </h1>
    <br>
    <h1>Your account email: 
        <h1 class="userData"><?= $_SESSION['user_email'] ?></h1>
    </h1>
    <br><br>
    
    <h1><a href="logout.php">Logout!</a></h1>
</body>
</html>