<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">

    <title>Forgot Password | {{ config('app.name') }}</title>

    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: 'Inter', sans-serif;
            box-sizing: border-box;
            background-color: #f5f5f5;
            width: 100%;
            height: 100%;
        }

        .container {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .wrapper {
            background-color: white;
            border-radius: 10px;
            width: 80%;
            max-width: 500px;
            overflow: hidden;
        }

        p {
            font-size: 16px;
            color: #000;
            margin: 2px 0;
        }

        .name-text {
            font-size: 18px;
            font-weight: 600;
            margin-bottom: 10px;
        }

        button {
            background-color: #2a9df4;
            color: #fff;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            margin: 10px 0;
            font-weight: 600;
            font-size: 16px;
            cursor: pointer;
        }

        a {
            text-decoration: none;
            color: #fff;
        }

        .link {
            color: #2a9df4;
            text-decoration: underline;
        }

        .content {
            padding: 2rem 3rem;
        }

        .header {
            margin: 1rem 0;
        }

        .detail {
            margin: 1rem 0;
        }

        .footer {
            margin-top: 1rem;
        }

        .bottom-info {
            margin-top: 1rem;
            font-size: 12px;
            padding: 2rem 3rem;
            background-color: #171717;
        }

        .bottom-info p {
            color: #fff;
        }

        @media screen and (max-width: 500px) {
            .wrapper {
                width: 100%;
            }

            .content {
                padding: 2rem 1rem;
            }

            .bottom-info {
                padding: 2rem 1rem;
            }
        }
    </style>
</head>
<body>
<div class="container">
    <div class="wrapper">
        <div class="content">
            <div class="header">
                <p>Hi, <span class="name-text">{{ $name }}</span></p>
            </div>
            <div class="detail">
                <p style="margin-bottom: 6px;">You have requested to reset your password.</p>
                <p>Please click on the link below to reset your password.</p>
            </div>
            <button><a href="{{ $link }}">Reset Password</a></button>
            <div class="footer">
                <p>Thank you</p>
                <p>{{ config('app.name') }}</p>
            </div>
        </div>

        <div class="bottom-info">
            <p>If the above link does not work, please copy and paste the link below into your browser.</p>
            <p style="margin: 1rem 0"><a href="{{ $link }}" class="link">Klik disini untuk reset password</a>
            </p>
            <p>This is an automated email. Please do not reply to this email.</p>
        </div>
    </div>
</div>
</body>
</html>
