<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Book day pass</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.0/css/bootstrap.min.css"/>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.5.0/css/bootstrap-datepicker.css" rel="stylesheet">
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.5.0/js/bootstrap-datepicker.js"></script>

</head>
<body>
    @if (session('passBooked'))
    <div class="alert alert-success">
        {{ session('passBooked') }}
    </div>
    
    @endif
    <h1> Select the date for which you want to book a day pass at jimCore </h1>
    <div class="container">
        <form action="/dayPicked" method="POST">
            @csrf
        <input class="date form-control" type="text" name="dayPassBooking">
        <button> Submit </button>
        </form>
      </div>
    
      <script type="text/javascript">
         $('.date').datepicker({
            format: 'dd-mm-yyyy'
          });
      </script>
</body>
</html>