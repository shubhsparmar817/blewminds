<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="https://fonts.googleapis.com/css?family=Quicksand:400,500,600,700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="./css/style.css">
    <title>Login | Customer</title>
    <style>

    #varification-box p{
        font-size: 14px;
    }

    /* forgot number link */

    .forgot-number{
        display: block;
        width: 100%;
        text-align: left;
    }

    .forgot-link{
        margin-left: 15px;
    }

    .forgot-link a{
        font-size: 12px;
        text-decoration: none;
        color: #00A0E3;
        cursor: pointer;
        margin-left: 5px;
    }

    .forgot-link a:hover{
        font-weight: 700;
    }

    /* google captcha */

    .google-captcha{
        margin-top: 10px;
    }

    .g-recaptcha > div{
        margin: auto;
        /* width: 200px !important1 */
        position: relative;
    }

    .g-recaptcha > div iframe{
        position: absolute;
        /* width: 200px !important;
        height: 50px !important; */
        left: 0;
        bottom: 0;
    }

    #captcha{
        font-family: inherit;
        font-size: 12px;
        font-weight: 700;
        display: block;
        text-align: center;
    }
    </style>
</head>
<body>
    <div class="container">
        <div class="wrapper">
            <div id="header">
                Login <br>
                <span>Customer Account</span> 
                <div class="loader"></div>
            </div>
            <div id="varification">
                <p style="font-size: 14px; font-weight:700 ;">Enter your registered <span style="color: #00A0E3; font-weight:700 ;">Email ID</span></p>
                <form id="varification-box" data-group-name="phone-number" method="POST" autocomplete="off">
                <div class="input-field">
                        <input type="text" id="email" name="email" value="" required>
                        <label for="email">Email ID</label>
                        <span><img src="icon/email.png"></span>
                        <span style="margin-top: 30px; font-size: 12px;font-weight:700; color: red;" id="error1"></span>
                    </div>
                    <div class="input-field" style="margin-top: 40px;">
                        <input type="password" id="password" name="password" value="" required>
                        <label for="password">Password</label>
                        <span><img src="icon/padlock.png"></span>
                        <span style="margin-top: 30px; font-size: 12px;font-weight:700; color: red;" id="error2"></span>
                    </div>
                    <div class="forgot-number" style="margin-top: 40px;">
                        <span style="font-size: 12px;" class="forgot-link">Haven't Registred yet? <a href="register.php">Register</a></span>
                    </div>
                    <div class="buttons">
                        <button type="button" class="next-button" onclick="login()">Login</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script>
        function login()
        {
            let emailId = document.getElementById('email').value;
            let pass = document.getElementById('password').value;
            let reg = /^([A-Za-z0-9_\-\.])+\@([A-Za-z0-9_\-\.])+\.([A-Za-z]{2,4})$/;
            let passw = /^(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{6,20}$/;
            var flag = 0;
            if(reg.test(emailId))
            {
                document.getElementById('error1').innerHTML = '';
                flag = flag + 1;
            }
            else 
            {
                flag = 0;
                document.getElementById('error1').innerHTML = 'Please enter email in valid formate.';
            }
            if(passw.test(pass))
            {
                document.getElementById('error2').innerHTML = '';
                flag = flag + 1;
            }
            else 
            {
                flag = 0;
                document.getElementById('error2').innerHTML = 'Password must contain least one numeric digit, one uppercase and one lowercase letter.';
            }
            if(flag == 2)
            {
                jQuery.ajax({
                    url:'sentotp.php',
                    cache: false,
                    type:'post',
                    data: {username: emailId, password: pass},
                    success:function(data){
                        var getData=$.parseJSON(data);
                        if(getData.status == 'fail')
                        {
                            alert('Username and Password invalid');
                        }
                        else
                        {
                            window.location.href = 'booking.php';
                        }
                    }
                });
            }
        }
    </script>
</body>
</html>