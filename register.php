<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="https://fonts.googleapis.com/css?family=Quicksand:400,500,600,700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="./css/style.css">
    <title>Registration | Customer</title>
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
                Registration <br>
                <span>Customer Account</span> 
                <div class="loader"></div>
            </div>
            <div id="varification">
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
                    <div class="input-field" style="margin-top: 40px;">
                        <input type="password" id="cpassword" value="" required>
                        <label for="cpassword">Confirm password</label>
                        <span><img src="icon/padlock.png"></span>
                        <span style="margin-top: 30px; font-size: 12px;font-weight:700; color: red;" id="error3"></span>
                    </div>
                    <div class="input-field" style="margin-top: 40px;">
                        <select name="guide" id="guide" required>
                            <option value="" selected disabled>Select</option>
                            <option value="John">John</option>
                            <option value="Mark">Mark</option>
                        </select>
                        <span><img src="icon/guide.png"></span>
                        <span style="margin-top: 30px; font-size: 12px;font-weight:700; color: red;" id="error4"></span>
                    </div>
                    <div class="input-field" style="margin-top: 40px; display: none;" id="divotp">
                        <input type="number" id="otp" name="otp" value="" required>
                        <label for="otp">OTP</label>
                        <span><img src="icon/otp.png"></span>
                        <span style="margin-top: 30px; font-size: 12px;font-weight:700; color: red;" id="error5"></span>
                    </div>
                    <div class="forgot-number">
                        <span style="font-size: 12px;" class="forgot-link">Already Registred? <a href="index.php">Login</a></span>
                    </div>
                    <div class="buttons">
                        <button type="button" id="reg" class="next-button" onclick="register()">Register</button>
                        <button type="button" id="very" class="next-button" onclick="verify()" style="display: none;">Submit OTP</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script>
        function register()
        {
            let emailId = document.getElementById('email').value;
            let pass = document.getElementById('password').value;
            let cpass = document.getElementById('cpassword').value;
            var e = document.getElementById("guide");
            var selectval = e.options[e.selectedIndex].value;
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
            if(pass == cpass)
            {
                document.getElementById('error3').innerHTML = '';
                flag = flag + 1;
            }
            else 
            {
                flag = 0;
                document.getElementById('error3').innerHTML = 'Password and Confirm password doesn\'t match.';
            }
            if(selectval != "")
            {
                document.getElementById('error4').innerHTML = '';
                flag = flag + 1; 
            }
            else 
            {
                flag = 0;
                document.getElementById('error4').innerHTML = 'Please Guide from select List.';
            }
            if(flag == 4)
            {
                jQuery.ajax({
                    url:'sentotp.php',
                    cache: false,
                    type:'post',
                    data:'email='+emailId,
                    success:function(data){
                        var getData=$.parseJSON(data);
                        if(getData.status == 'exists')
                        {
                            document.getElementById('error1').innerHTML = 'Email is already Registred with us.';
                        }
                        else if(getData.status == 'fail')
                        {
                            document.getElementById('error1').innerHTML = 'Maybe email is worng please try with another email.';
                        }
                        else
                        {
                            alert('OTP has been sent please check Mail.');
                            document.getElementById('email').disabled = 'true';
                            document.getElementById('reg').style.display = 'none';
                            document.getElementById('divotp').style.display = 'block';
                            document.getElementById('very').style.display = 'block';
                        }
                    }
                });
            }
        }
        function verify()
        {
            var formate = "([0-9]){6}";
            var flag = 0;
            if(document.getElementById('otp').value.match(formate))
            {
                document.getElementById('error5').innerHTML = '';
                var email = document.getElementById('email').value;
                var otp = document.getElementById('otp').value;
                var password = document.getElementById('password').value;
                var e = document.getElementById("guide");
                var guide = e.options[e.selectedIndex].value;
                jQuery.ajax({
                url:'sentotp.php',
                type:'post',
                data: {em: email, otp: otp, password: password, guide: guide},
                success:function(data)
                {
                    var getData=$.parseJSON(data);
                    if(getData.status == 'success')
                    {
                        alert('Registration Successfully..Please Login')
                        window.location.href = 'index.php';
                    }
                    else
                    {
                        document.getElementById('error5').innerHTML = 'Incorrect OTP';
                    }
                }
                });
            }
            else
            {
                document.getElementById('error5').innerHTML = 'Enter 6 Digit OTP';
            }
        }
    </script>
</body>
</html>