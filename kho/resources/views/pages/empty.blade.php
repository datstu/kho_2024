<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Loading</title>

    <style>
        .loader img {
            position: fixed;
            right: 50%;
            top: 50%;
            z-index: 999;
            text-align: center;
            height:50px;
        }
    </style>
</head>
<body>
    <div class="loader">
       <img src="{{asset('public/images/rocket.svg')}}">
    </div>
</body>
</html>