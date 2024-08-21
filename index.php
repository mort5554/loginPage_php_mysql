<?php
    session_start();

    if(isset($_SESSION['logedIn'])) header("Location: account.php");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <style>
        .error{
            color: red;
        }
    </style>
</head>
<body>
    <a href="register.php">Create new account!</a><br/><br/>
    <?php
        if(isset($_SESSION['succesful_register'])){
            echo "<h1>Thank you for register ! </h1><br/><br/>";
            unset($_SESSION['succesful_register']);
        }
    ?>
    <form action="login.php" method="post">
        <!-- Email -->
        <label for="email">Email</label><br>
        <input type="email" name="email" id="email" placeholder="Email.."
            value="<?php
                if(isset($_SESSION['re_email'])){
                    echo $_SESSION['re_email'];
                    unset($_SESSION['re_email']);
                }
            ?>"
        >
        <br/>

            <!-- Check for Errors-->
            <?php 
                if(isset($_SESSION['e_email'])){
                    echo '<p class="error">'.$_SESSION['e_email'].'</p>';
                    unset($_SESSION['e_email']);
                }
            ?>
        <br/>
        
        <!-- Password -->
        <label for="password">Password</label><br/>
        <input type="password" name="password" id="password" placeholder="Password..."
        
        >
        <br/>

            <!-- Check for Errors-->
            <?php
                if(isset($_SESSION['e_password'])){
                    echo '<p class="error">'.$_SESSION['e_password'].'</p>';
                    unset($_SESSION['e_password']);
                }
            ?>
        <br/>

            <!-- SUBMIT -->
        <input type="submit" value="Log in">
    </form>
</body>
</html>