<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>jimCore Home Page</title>


    <style>
        * {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
        }

        body {
            font-family: 'Trebuchet MS', 'Lucida Sans Unicode', 'Lucida Grande', 'Lucida Sans', Arial, sans-serif;
        }

        video {
            display: inline-block;


        }


        .profile {
            display: flex;
            align-items: center;


        }

        nav h4,
        nav a {
            color: white;
        }

        .video-container {
            text-align: center;

            background-color: rgb(2, 45, 48);
        }


        .flex {
            display: flex;
            height: 100%;
        }


        section {
            flex-grow: 1;
        }



        .background-work {
            background-size: contain;
            background-position: center;
            background-repeat: repeat;

        }
    </style>
</head>

<body>

    <header>

        @auth
            @if (session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif
            <div class="video-container">

                <video width="320" height="240" autoplay muted playsinline>
                    <source src="{{ asset('images/logo1.mp4') }}" type="video/mp4">
                </video>
                <video width="320" height="240" autoplay muted playsinline>
                    <source src="{{ asset('images/logo2.mp4') }}" type="video/mp4">
                </video>

                <h1 style="color: rgb(255, 255, 255); text-align:left;"> Welcome to the official jimCore home page, <span
                        style="color: rgb(17, 143, 24); font-weight: bold;"> {{ Auth::user()->name }} </span> !</h1>
            </div>

        </header>
       



        <div class="background-work" style="height : 400px; background-color : black; position: relative; ">
            <img height="400" width ="600" style="float: right; margin-right : 40px;" src="{{ asset ('images/PIC7.jpg')}}">
            <p style="color:green; margin-left: 40px; font-size : 12px ; position:absolute ; margin-top: 10px; font-style= ">   Become mentally great </p>
            <img height="400" width ="615"  src="{{ asset ('images/PIC8.jpg')}}"> 
            <p style="color: green; margin-left: 575px; margin-top: 10px; font-size : 12px ; position:absolute; bottom: 20px; left:0px; "> <br> Reach new heights  </p> 
            <img height="400" width ="425" style="float: left; " src="{{ asset ('images/PIC6.jpg')}}">
            <p style="color: green; left: 1150px; top: 5px; font-size : 12px ; position:absolute; "> <br> Yourself againt yourself.  </p> 
            <div style=" position: absolute ; left: 1500px; top: 10px;">
                <a href="/profile"> <img height="100" width="150" src="{{ asset ('images/PIC9.jpg')}} "> </a>
                <p style= "color: white; "> Access your profile here </p>
            </div>

        </div>



        <div class=flex>
            <nav style="width: 200px; background-color:rgb(26, 7, 64); height: 100%;">
                <h4> <a href= "/jimCoreBranches"> <b> Our branches : <br> </b> </a> </h4>
                <h4> <a href="/memberships"> <br> <b> Our memberships : </b> </a> </h4>
                <h4> <a href="/logOut"> <br> Log out here  </a> </h4>
            </nav>


            <section style="background-color:rgb(2, 45, 48);">
                <h1> Hi </h1>
            </section>
        </div>

    @endauth


    @guest
        @if (session('logOutSuccess'))
            <div class="alert alert-success">
                {{ session('logOutSuccess') }}
            </div>
        @endif
        <div class="video-container">

            <video width="320" height="240" autoplay muted playsinline>
                <source src="{{ asset('images/logo1.mp4') }}" type="video/mp4">
            </video>
            <video width="320" height="240" autoplay muted playsinline>
                <source src="{{ asset('images/logo2.mp4') }}" type="video/mp4">
            </video>

            <h1 style="color: rgb(255, 255, 255); text-align:left;"> Welcome to the official jimCore home page! </h1>
        </div>

        </header>
        <div class="profile background-work">
            <img width="100" height="100" style= "margin-right: 300px;" src="{{ asset('images/profile.png') }}">
            <h3 style="color: rgb(0, 0, 0);  "> Details on our facilities, bookings, PT applications, locations of our
                branches and memberships all below! </h3>

        </div>
        <nav style="width: 200px; background-color:rgb(26, 7, 64); height: 100%;">

            <h4> <b> Our branches : <br> </b> <a href= "/jimCoreBranches"> </a> </h4>
            <h4> <a href='/logInPage'> Log in here <br> </a> </h4>
            <h4> <b> Not a member yet? Sign up now! </b> <a href= "/jimCoreRegister"> Sign up </a> </h4>
        </nav>

    @endguest
</body>

</html>
