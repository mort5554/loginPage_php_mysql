<?php
    session_start();

    if((isset($_POST['email']) || (isset($_POST['nick'])))){
        //Czy wszstko ok
        $allGood = true;
        
        //Sprawdzanie nicku
        $nick = $_POST['nick'];
        //Sprawdzanie długości nicku 
        if(strlen($nick) < 3 || strlen($nick) > 15){
            $allGood = false;
            $_SESSION['e_nick'] = "The nickname must be between 4 and 15 characters long!";
        }
        //Sprawdzanie poprawności nicku
        if(ctype_alnum($nick) == false){
            $allGood = false;
            $_SESSION['e_nick'] = "Nick can only contain letters and numbers!";
        }

        //Sprawdzanie emaila
        $email = $_POST['email'];
        //filtrowanie emaila usuwający niedozwolone znaki
        $emailCheck = filter_var($email, FILTER_SANITIZE_EMAIL);
        if((filter_var($emailCheck, FILTER_VALIDATE_EMAIL) == false) || ($emailCheck!=$email)){
            $allGood = false;
            $_SESSION['e_email'] = "Enter a valid email address!";
        }

        //Sprawdzanie hasła
        $password = $_POST['password'];
        $passwordConfirm = $_POST['passwordConfirm'];
        if((strlen($password) < 5) || (strlen($password) > 20)){
            $allGood = false;
            $_SESSION['e_password'] = "Password must be between 5 and 200 characters long!";
        }
        if($password != $passwordConfirm){
            $allGood = false;
            $_SESSION['e_password'] = "Passwords are not the same";
        }
        $passwordHash = crypt($password, PASSWORD_DEFAULT);

        //Pamietaj wprowadzone dane
        $_SESSION['re_nick'] = $nick;
        $_SESSION['re_email'] = $email;
        $_SESSION['re_password'] = $password;
        $_SESSION['re_passwordConfirm'] = $passwordConfirm;

        require_once "connect.php";
        mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
        try{
            $connect = new mysqli($host, $db_user, $db_password, $db_name);
            if($connect->connect_errno!=0){
                throw new Exception(mysqli_connect_errno());
            }
            else{
                //Czy email istnieje 
                $emailQuery = "SELECT id FROM users WHERE email='$email'";

                $result = $connect->query($emailQuery);
                if(!$result) throw new Exception($connect->error);
                $otherEmails = $result->num_rows;
                if($otherEmails > 0){
                    $allGood = false;
                    $_SESSION['e_email'] = "This email already exist!";
                }
                
                //Czy nick istnieje
                $nickQuery = "SELECT id FROM users WHERE nick='$nick'";
                $result = $connect->query($nickQuery);
                if(!$result) throw new Exception($connect->error);
                $otherNicks = $result->num_rows;
                if($otherNicks > 0){
                    $allGood = false;
                    $_SESSION['e_nick'] = "This nick already exist!";
                } 

                //Jesli wszystko dobrze
                if($allGood == true){
                    $addUserQuery = "INSERT INTO users VALUES 
                    (NULL, '$nick', '$email', '$passwordHash')";
                    if($connect->query($addUserQuery)){
                        $_SESSION['succesful_register'] = true;
                        header("Location: index.php");
                    } else{
                        throw new Exception($connect->error);
                    }
                }
            }
            $connect->close();
        } 
        catch(Exception $e){
            echo '<h1 style="color: red">Something went wrong! Please register another time!</h1>';
            echo '<br/> Code error'.$e;
            echo "</body></html>";
            exit();
        }
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <style>
        .error{
            color: red;
        }
    </style>
</head>
<body>
    <a href="index.php">Already have account?</a><br/><br/>
    <form method="post">

    <!-- Nick -->
        <label for="nick">Nickname</label><br>
        <input type="text" name="nick" id="nick" placeholder="Nick.." 
            value="<?php 
                if(isset($_SESSION['re_nick'])){
                echo $_SESSION['re_nick'];
                unset($_SESSION['re_nick']);
            }
            ?>"
        >

        <!-- Check for Errors-->
        <br/>
            <?php if(isset($_SESSION['e_nick'])){
                echo "<p class='error'>".$_SESSION['e_nick']."</p>";
                unset($_SESSION['e_nick']);
            }
        ?>
        <br/>

    <!-- Email -->
        <label for="email">Email</label><br>
        <input type="email" name="email" id="email" placeholder="Email.."
            value="<?php if(isset($_SESSION['re_email'])){
                echo $_SESSION['re_email'];
                unset($_SESSION['re_email']);
            }
            ?>"
        >
        
        <!-- Check for Errors-->
        <br/>
            <?php if(isset($_SESSION['e_email'])){
                echo "<p class='error'>".$_SESSION['e_email']."</p>";
                unset($_SESSION['e_email']);
            }
            ?>
        <br/>

    <!--- Password -->
        <label for="password">Password</label><br/>
        <input type="password" name="password" id="password" placeholder="Password..."
            value="<?php if(isset($_SESSION['re_password'])){
                    echo $_SESSION['re_password'];
                    unset($_SESSION['re_password']);
                }
            ?>"
        >
        
        <!-- Check for Errors-->
        <br/>
            <?php
                if(isset($_SESSION['e_password'])){
                    echo "<p class='error'>".$_SESSION['e_password']."</p>";
                    unset($_SESSION['e_password']);
                }
            ?>
        <br/>

    <!--- Password Confirm-->
        <label for="passwordConfirm">Confirm password</label><br/>
        <input type="password" name="passwordConfirm" id="passwordConfirm" placeholder="Password..."
            value="<?php if(isset($_SESSION['re_passwordConfirm'])){
                    echo $_SESSION['re_passwordConfirm'];
                    unset($_SESSION['re_passwordConfirm']);
                }
            ?>"
        >
        <br/>
        
        <br/>

    <!--- Submit -->
        <input type="submit" value="Register">
    </form>
</body>
</html>