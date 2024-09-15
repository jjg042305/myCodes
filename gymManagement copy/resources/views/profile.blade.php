<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title> Profile page </title>



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

        table, td, tr {
            border: 1px solid green;
            border-collapse: collapse;
                            
        }

        tr td {
         text-align: center;
         
        }


        h3{
            text-align: center;
            
        }

        button {
            border: 5px solid;
            font-family: 'Trebuchet MS', 'Lucida Sans Unicode', 'Lucida Grande', 'Lucida Sans', Arial, sans-serif;
            border-radius: 5px;
            border-block-color: rgb(0, 17, 128);
            width: 6.5%;
            height: 65px;
            margin: 10px auto;
            margin-left: 30px;


        }

        header p {
            margin-left: 20px;
        }
    </style>
</head>

<body>
    <header>
        <div class="video-container">

            <video width="320" height="240" autoplay muted playsinline>
                <source src="{{ asset('images/logo1.mp4') }}" type="video/mp4">
            </video>
            <video width="320" height="240" autoplay muted playsinline>
                <source src="{{ asset('images/logo2.mp4') }}" type="video/mp4">
            </video>

            <h1 style="color: rgb(255, 255, 255); text-align:left;"> Access your profile information, <span
                    style="color: rgb(17, 143, 24); font-weight: bold;"> {{ Auth::user()->name }} </span> !</h1>

             <a href="/"> <img style="margin-left: 1550px;"height="100" width="100" src="{{ asset ('images/PIC11.png')}}"> </a>
             <p style="margin-left: 1550px; color: white;"> Home page </p>
        </div>

        <h3 style="text-align:left; margin-left: 20px;"> Your information : </h3>
        <p> <br> - Username : {{ Auth::user()->name }} </p>
        <p> - Email : {{ Auth::user()->email }} </p>


       
        <?php
        $showContent = false; // Initialize the variable

        if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['bookings_table'])) {
            $showContent = true; // Update the variable when the button is clicked
        }
        if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['hide_bookings'])) {
            $showContent = true; // Update the variable when the button is clicked
        }
        ?>
        @if (!$showContent)

         <form method="POST" action="/profile">
            @csrf
            <button type="submit" name="bookings table"> View Booking details </button>
        </form> 
        @endif

        @if ($showContent)
        <form method="POST" action="/profile">
            @csrf
            <button type="submit" name="hide bookings "> Hide Booking details </button>
        </form> 



        <table style="width: 80%; margin-left: auto;
                margin-right: auto;">
            <caption> All bookings </caption>
            <tr style="font-weight: bold;"> 
            <td> Booking id  </td>
            <td> Price of booking </td>
            <td> Booking type </td>
            <td> Booking details </td>
             </tr>
            @foreach (Auth::user()->bookings as $booking)
            <tr>
                <td> {{ $booking->id }} </td>
                <td>  {{ $booking->price }}$ </td>
                <td> {{ $booking->type }} </td>
                @if ($booking->type == "day Pass")
             <td>  Day pass on {{ \Carbon\Carbon::parse($booking->start_time)->format('l, F j, Y') }} </td>
                @endif
                @if ($booking->type == "week Pass")
             <td> Week pass starting on {{ \Carbon\Carbon::parse($booking->start_time)->format('l, F j, Y') }} at midnight and ending on {{ \Carbon\Carbon::parse($booking->end_time)->format('l, F j, Y') }} at 23:59</td>
                @endif
                @if ($booking->type == "month Pass")
             <td> Month pass starting on {{ \Carbon\Carbon::parse($booking->start_time)->format('l, F j, Y') }} at midnight and ending on {{ \Carbon\Carbon::parse($booking->end_time)->format('l, F j, Y') }} at 23:59</td>
                @endif
            </tr>
            @endforeach
        
        </table>
        @endif


        
        @if (Auth::user()->first_name == null)
        
            <form method="POST" action ="/profile"> 
                @csrf
                    <button type="submit" name="add details"> Need to provide additional details ? </button>
            </form>
        @endif

        @if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_details'])) 
            <h2> Any of the below information are optional and don't have to be provided. All your information is secure in our database </h2>
            <form method="POST" action="/profile"> 
                @csrf 
                <input type="text" name="first name" placeholder="Enter your first name">
                <input type="text" name="last name" placeholder="Enter your last name">
                <input type="text" name="phone" placeholder="Enter your phone number (Canadian or US) in the (XXX)-XXX-XXXX format">
                <input type="text" name="birth" placeholder="Enter your date of birth in the DD/MM/YYYY format">
                <input type="text" name="postal" placeholder="Enter your postal code">
                <input type="text" name="address" placeholder="Enter your address">
                <input type="text" name="city" placeholder="Enter the city you reside in">
                <input type="text" name="province" placeholder="Enter the province (if any)">
                <input type="text" name="country" placeholder="Enter the country you reside in">
            </form>


                
        @endif


    </header>
</body>

</html>
