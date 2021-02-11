<?php
    include('connection.php');
    session_start();
    if(!isset($_SESSION['username']))
    {
        header('location: index.php');
    }
    $email = $_SESSION['username'];
    $query = "SELECT * FROM user WHERE email='".$email."'";
    $result = mysqli_query($con, $query);
    $data = mysqli_fetch_assoc($result);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking</title>
    <style>
        h1 
        {
            display: flex;
            justify-content: center;
            text-decoration: underline;
        }
        .row 
        {
            margin: 40px 40px 0px 40px;
            display: grid;
            grid-template-columns: auto auto auto;
        }
        .row input 
        {
            margin: 10px;
            height: 30px;
        }
        .row img 
        {
            display: flex;
            margin-left: 100px;
            margin-top: 10px;
            height: 30px;
            width: 150px;
        }
        h2 
        {
            margin: 40px 0px 0px 50px;
            text-decoration: underline;
        }
        .button 
        {
            margin: 40px 40px 0px 40px;
        }
        .buttons button 
        {
            box-sizing: inherit;
            width: 80px;
            height: 20px;
            padding: 5px;
            border-radius: 5px;
            outline: none;
            cursor: pointer;
            font-family: inherit;
        }
        .book 
        {
            float: left;   
            color: white;
            background-color: #00A0E3;
            border: none;
            outline: none;
            border: 2px solid white;
            font-weight: 700;
            font-size: 14px;
            cursor: pointer;
        }
        .guide 
        {
            float: right;   
            color: white;
            background-color: #00A0E3;
            border: none;
            outline: none;
            border: 2px solid white;
            font-weight: 700;
            font-size: 14px;
            cursor: pointer;
        }
    </style>
</head>
<body>
    <form action="" method="post">
        <h1>Book your Ticket</h1>
        <div class="row">
            <input type="text" name="name" id="name" placeholder="Enter Name" required>
            <input type="text" name="gender" id="gender" placeholder="Enter Gender" required>
            <input type="text" name="guide" id="guide" disabled value="<?php echo $data['guide']; ?>" required>
        </div>
        <h2>Select Date</h2>
        <div class="row">
            <input type="date" name="date1" id="date1" required>
            <img src="icon/right.png">
            <input type="date" name="date2" id="date2" required>
        </div>
        <div class="row">
            <input type="text" name="email" id="email" disabled value="<?php echo $data['email']; ?>" required>
            <input type="number" name="mobile" id="mobile" placeholder="Enter Mobile" required>
            <input type="text" name="place" id="place" placeholder="Enter Location/ Place" required>
        </div>
        <div class="button">
            <button type="submit" name="submit" class="book">Book</button>
            <button type="button" class="guide">Guide Form</button>
        </div>
    </form>
    <script>
        const picker1 = document.getElementById('date1');
        const picker2 = document.getElementById('date2');
        picker1.addEventListener('input', function(e){
        var day = new Date(this.value).getUTCDay();
        if([6,0].includes(day)){
            e.preventDefault();
            this.value = '';
            alert('Weekends not allowed');
        }
        });
        picker2.addEventListener('input', function(e){
        var day = new Date(this.value).getUTCDay();
        if([6,0].includes(day)){
            e.preventDefault();
            this.value = '';
            alert('Weekends not allowed');
        }
        });
    </script>
</body>
</html>
<?php 
    if(isset($_POST['submit']))
    {
        require("./PHPMailer-master/src/PHPMailer.php");
        require("./PHPMailer-master/src/SMTP.php");
        require("./PHPMailer-master/src/Exception.php");
        require("./vendor/autoload.php");
        $htmlcode = '<!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <meta http-equiv="X-UA-Compatible" content="IE=edge">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
        </head>
        <body>
            <h1>Dear, '.$_POST['name'].'</h1>
            <h2>Thanks for Booking.</h2>
            <h2>Your booking is confirm with us.</h2>
            <h2>Your trip date is '.$_POST['date1'].' to '.$_POST['date2'].' with your guide is '.$data['guide'].' at '.$_POST['place'].'.</h2>
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
        $mail->AddCC($data['guideemail']);  
        $mail->isHTML(true);                                 
        $mail->Subject = 'Booking Confirm';
        $mail->Body    = $htmlcode;
        $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

        if(!$mail->send())
        {
        }
        echo "<script>alert('Booking Confirm..Please check mail.');</script>";
    }
?>