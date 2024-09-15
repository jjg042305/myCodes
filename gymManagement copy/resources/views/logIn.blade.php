<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Log In</title>
    <style>
        .video-container {
            text-align: center;

            background-color: rgb(2, 45, 48);
        }

        body {
            font-family: 'Trebuchet MS', 'Lucida Sans Unicode', 'Lucida Grande', 'Lucida Sans', Arial, sans-serif;
        }

        video {
            display: inline-block;


        }


        form,
        h2 {
            text-align: center;

        }

        form input {
            border: 5px solid;
            border-block-color: green;
            font-family: 'Trebuchet MS', 'Lucida Sans Unicode', 'Lucida Grande', 'Lucida Sans', Arial, sans-serif;
            border-radius: 5px;
            display: block;
            margin: 10px auto;
        }


        button {
            border: 5px solid;
            border-block-color: rgb(0, 17, 128);
            font-family: 'Trebuchet MS', 'Lucida Sans Unicode', 'Lucida Grande', 'Lucida Sans', Arial, sans-serif;
            border-radius: 5px;
            display: block;
            margin: 10px auto;
            width: 6.5%;
            height: 65px;
            font-size: 20px;
        }
    </style>

</head>

<body>
    <div class="video-container">

        <video width="320" height="240" autoplay muted playsinline>
            <source src="{{ asset('images/logo1.mp4') }}" type="video/mp4">
        </video>
        <video width="320" height="240" autoplay muted playsinline>
            <source src="{{ asset('images/logo2.mp4') }}" type="video/mp4">
        </video>

        <h1 style="color: rgb(255, 255, 255); text-align:center;"> Log in now! </h1>
    </div>
    <h2> Enter jimCore credentials :</h2>
    <form action="/authenticateUser" method="POST">
        @csrf
        <input type="text" name="name" placeholder="Enter name">
        @if ($errors->has('name'))
            <span class="text-danger">{{ $errors->first('name') }}</span>
        @endif
        <input type="password" name="password" placeholder="Enter password">
        @if ($errors->has('password'))
            <span class="text-danger">{{ $errors->first('password') }}</span>
        @endif
        <button> Submit </button>
    </form>
</body>

</html>
