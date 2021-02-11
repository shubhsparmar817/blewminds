<?php 
    include('connection.php');
    require("./PHPMailer-master/src/PHPMailer.php");
    require("./PHPMailer-master/src/SMTP.php");
    require("./PHPMailer-master/src/Exception.php");
    require("./vendor/autoload.php");
    if(isset($_POST['email']))
    { 
        $email = $_POST['email'];
        $otp = strval(rand(111111,999999));
        $error = array();
        $query = "SELECT * FROM user WHERE email='".$email."'";
        $result = mysqli_query($con, $query);
        if(mysqli_num_rows($result))
        {
            $error['status'] = 'exists';
        }
        else
        {
            $htmlcode = '<!DOCTYPE html>
            <html lang="en">
            <head>
                <meta charset="UTF-8">
                <meta http-equiv="X-UA-Compatible" content="IE=edge">
                <meta name="viewport" content="width=device-width, initial-scale=1.0">
            </head>
            <body>
                <h1>Dear, User</h1>
                <h2>Find your Email veryfication code below to verify your account with us.</h2>
                <h2>'.$otp.'</h2>
            </body>
            </html>';
            $mail = new PHPMailer\PHPMailer\PHPMailer();        
            $mail->IsSMTP(); // enable SMTP
        
            $mail->SMTPDebug = 0; // debugging: 1 = errors and messages, 2 = messages only
            $mail->Host = 'smtp.gmail.com'; 
            $mail->SMTPAuth = true;                   
            $mail->Username   = "mailsend817@gmail.com"; 
            $mail->Password   = "Abc@1234";
            $mail->SMTPSecure = 'ssl';                          
            $mail->Port = 465;

            $mail->From = 'mailsend817@gmail.com';
            $mail->FromName = 'Mail';
            $mail->addAddress($email);   
            $mail->isHTML(true);                                 
            $mail->Subject = 'Email Verification';
            $mail->Body    = $htmlcode;
            $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

            if(!$mail->send())
            {
                $error['status'] = 'fail';
            }
            else
            {
                $error['status'] = 'success';
                $query1 = "SELECT * FROM verify WHERE email='".$email."'";
                $result1 = mysqli_query($con, $query1);
                if(mysqli_num_rows($result1))
                {
                    $sql = "UPDATE verify SET otp='".$otp."' WHERE email='".$email."'";
                    mysqli_query($con, $sql);
                }
                else 
                {
                    $sql = "INSERT INTO verify (email, otp) VALUES ('".$email."','".$otp."')";
                    mysqli_query($con, $sql);
                }
            }
        }
        echo json_encode($error);
    }
    else if(isset($_POST['em']) && isset($_POST['otp']) && isset($_POST['password']) && isset($_POST['guide']))
    {
        $email = $_POST['em'];
        $otp = $_POST['otp'];
        $password = $_POST['password'];
        $guide = $_POST['guide'];
        $arr = array();
        $query = "SELECT * FROM verify WHERE email='".$email."'";
        $result = mysqli_query($con, $query);
        $data = mysqli_fetch_assoc($result);
        if($data['otp'] == $otp)
        {
            $arr['status'] = 'success';
            $htmlcode = '<!DOCTYPE html>
            <html lang="en">
            <head>
                <meta charset="UTF-8">
                <meta http-equiv="X-UA-Compatible" content="IE=edge">
                <meta name="viewport" content="width=device-width, initial-scale=1.0">
            </head>
            <body>
                <h1>Dear, User</h1>
                <h2>Thanks for Registration.</h2>
                <h2>Find below credentials to login with us.</h2>
                <h2>Username: '.$email.'</h2>
                <h2>Password: '.$password.'</h2>
            </body>
            </html>';
            $mail = new PHPMailer\PHPMailer\PHPMailer();        
            $mail->IsSMTP(); // enable SMTP
        
            $mail->SMTPDebug = 0; // debugging: 1 = errors and messages, 2 = messages only
            $mail->Host = 'smtp.gmail.com'; 
            $mail->SMTPAuth = true;                   
            $mail->Username   = "mailsend817@gmail.com"; 
            $mail->Password   = "Abc@1234";
            $mail->SMTPSecure = 'ssl';                          
            $mail->Port = 465;

            $mail->From = 'mailsend817@gmail.com';
            $mail->FromName = 'Mail';
            $mail->addAddress($email);   
            $mail->isHTML(true);                                 
            $mail->Subject = 'Registration Success';
            $mail->Body    = $htmlcode;
            $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

            if(!$mail->send())
            {
            }
            $guidemail = '';
            if($guide == 'John')
            {
                $guidemail = 'shubhamparmar817@gmail.com';
            }
            else if($guide == 'Mark')
            {
                $guidemail = 'parmarshubhamglc123@gmail.com';
            }
            $sql = "INSERT INTO user (email, password, guide, guideemail) VALUES ('".$email."','".$password."','".$guide."','".$guidemail."')";
            mysqli_query($con, $sql);
        }
        else
        {
            $arr['status'] = 'fail';
        }
        echo json_encode($arr);
    }
    else if(isset($_POST['username']) && isset($_POST['password']))
    {
        $email = $_POST['username'];
        $password = $_POST['password'];
        $arr = array();
        $query = "SELECT * FROM user WHERE email='".$email."' AND password='".$password."'";
        $result = mysqli_query($con, $query);
        if(mysqli_num_rows($result))
        {
            session_start();
            $data = mysqli_fetch_assoc($result);
            $_SESSION['username'] = $data['email'];
            $arr['status'] = 'success';
        }
        else 
        {
            $arr['status'] = 'fail';
        }
        echo json_encode($arr);
    }
?>