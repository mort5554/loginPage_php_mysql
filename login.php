<?php
    session_start();
    
    //Pamietaj wprowadzone dane
    $_SESSION['re_email'] = $_POST['email'];
    $_SESSION['re_login_password'] = $_POST['password'];

    //Laczenie z baza danych
    require_once "connect.php";
    try{
        $connect = new mysqli($host, $db_user, $db_password, $db_name);
    }
    catch(Exception $e){
        echo '<h1 style="color: red">Something went wrong! Please register another time!</h1>';
        echo '<br/> Code error'.$e;
        echo "</body></html>";
        exit();
    }

    if($connect->connect_errno!=0){
        echo "Error code ".$connect->connect_errno;
    }
    //Sprawdzanie czy email istnieje w bazie 
    else{
        $email = $_POST['email'];
        $password = $_POST['password'];

        $password = $connect->real_escape_string($password);
        $email = $connect->real_escape_string($email);

        $checkUserQuery = "SELECT * FROM users WHERE email='$email'";

        if($result = $connect->query($checkUserQuery)){
            $rowCount = $result->num_rows;
            if($rowCount > 0){
                $rowData = $result->fetch_assoc();
                /*echo $rowData['password'];
                echo password_verify($password, $rowData['password']);
                echo "<br>";
                echo $password;
                $passwordHash = password_verify($password, $rowData['password']);
                echo $passwordHash;*/

                //Porownywanie hasla
                if(crypt($password, $rowData['password']) == $rowData['password']){
                    $_SESSION['logedIn'] = true;

                    $_SESSION['user_id'] = $rowData['id'];
                    $_SESSION['user_nick'] = $rowData['nick'];
                    $_SESSION['user_email'] = $rowData['email'];

                    $connect->close();

                    header("Location: account.php");
                }
                else{
                    $_SESSION['e_password'] = "Wrong Password!";
                    header("Location: index.php");
                }
            }
            else{
                $_SESSION['e_email'] = "Wrong email!";
                header("Location: index.php");
            }
        }
        $connect->close();
    }
    