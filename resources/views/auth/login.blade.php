<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@lang('site.mimic')</title>
    <link href="{{asset('uploads/logo.ico')}}" rel="icon">

    <style>
        @import url("https://fonts.googleapis.com/css2?family=Recursive&display=swap");

        * {
            box-sizing: border-box;
        }

        html {
            font-size: 100px;
        }

        @media (max-width: 768px) {
            html {
                font-size: calc(100vw / 768 * 100);
            }
        }

        body {
            min-height: 100vh;
            padding: 0.5rem 1rem;
            display: flex;
            align-items: center;
            font-family: "Recursive", sans-serif;
            font-size: 0.2rem;
            position: relative;
            -webkit-user-select: none;
            -moz-user-select: none;
            -ms-user-select: none;
            user-select: none;
            z-index: 1;
        }

        .form {
            margin: auto;
            width: 5rem;
            padding: 0.5rem;
            color: #fff;
        }

        .form label {
            display: block;
            font-weight: bold;
        }

        .form input {
            -webkit-appearance: none;
            -moz-appearance: none;
            appearance: none;
            display: block;
            width: 100%;
            margin: 0.1rem 0 0.2rem;
            border: none;
            border-radius: 0.1rem;
            padding: 0.1rem 0.3rem;
            padding-left: 0.5rem;
            font-family: inherit;
        }

        .form input:focus,
        .form input:focus-within {
            outline: none;
            box-shadow: 0 0 5px 2px rgba(255, 255, 255, 0.5);
        }

        .form ion-icon {
            font-size: 1.2em;
        }

        .form .input-box {
            position: relative;
        }

        .form .prefix {
            position: absolute;
            color: #8b8b8b;
            top: 0;
            bottom: 0;
            left: 0.2rem;
            margin: auto;
        }

        .form .switch-btn {
            position: absolute;
            top: 0;
            bottom: 0;
            right: 0;
            padding: 0.2rem;
            margin: auto;
            cursor: pointer;
        }

        .form .send-btn {
            display: inline-block;
            padding: 0.1rem 0.3rem;
            margin: 0.1rem 0 0.2rem;
            color: #fff;
            background-color: #ff4747;
            border-radius: 0.1rem;
            cursor: pointer;
            width: 100%;
            font-size: medium;
        }

        .form .send-btn ion-icon {
            vertical-align: middle;
        }

        @-webkit-keyframes fadeinBottom {
            from {
                opacity: 0;
                transform: translateY(3rem);
            }

            to {
                opacity: 1;
                transform: translateY(1rem);
            }
        }

        @keyframes fadeinBottom {
            from {
                opacity: 0;
                transform: translateY(3rem);
            }

            to {
                opacity: 1;
                transform: translateY(1rem);
            }
        }

        @-webkit-keyframes fadeinTop {
            from {
                opacity: 0;
                transform: translateY(-3rem);
            }

            to {
                opacity: 1;
                transform: translateY(-1rem);
            }
        }

        @keyframes fadeinTop {
            from {
                opacity: 0;
                transform: translateY(-3rem);
            }

            to {
                opacity: 1;
                transform: translateY(-1rem);
            }
        }
        #logo{
            width: 156px;
            height: 100px;
            margin: auto;
            display: block;
        }
    </style>
        <link rel="stylesheet" href="{{ asset('dashboard_files/css/custom.css') }}">
</head>

<body>
    <div class="forml">
        <div class="form">
            <img src="{{asset('uploads/logo.png')}}" alt="" id="logo">
            <form action="{{ route('login') }}" method="POST">
                @csrf
                @method("POST")

                <label for="account">Email</label>
                <div class="input-box">
                    <ion-icon class="prefix" name="person-outline"></ion-icon>
                    <input id="account" name="email" type="text" spellcheck="false" />
                </div>
                <label for="password">Password</label>
                <div class="input-box">
                    <ion-icon class="prefix" name="lock-closed-outline"></ion-icon>
                    <input id="password" name="password" type="password" spellcheck="false" />
                    <ion-icon class="switch-btn" name="eye-off-outline" style="color: #2799fa;"></ion-icon>
                </div>
                <button style="background: transparent; border: none; width: 100%">
                <div class="send-btn">login
                    <ion-icon name="arrow-forward-circle-outline"></ion-icon>
                </div></button>
                <br>
            </form>
        </div>
    </div>


</body>
<script src="https://unpkg.com/ionicons@5.0.0/dist/ionicons.js"></script>
<script>
    {{--  const pswInput = document.querySelector('#password');
const switchBtn = document.querySelector('.switch-btn');
switchBtn.addEventListener('click', togglePswVisibility);

function togglePswVisibility() {
  const isHide = pswInput.type === 'password';
  pswInput.setAttribute('type', isHide ? 'text' : 'password');
  switchBtn.setAttribute('name', isHide ? 'eye-outline' : 'eye-off-outline');
}  --}}



</script>

</html>
