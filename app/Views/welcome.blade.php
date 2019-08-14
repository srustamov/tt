<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="shortcut icon" href="/favicon.ico" type="image/x-icon">
    <title>{{ config('app.name') }}</title>
    <style>
        *{
          padding: 0;
          margin: 0;
          box-sizing: border-box;
          -webkit-box-sizing:border-box;
          -moz-box-sizing:border-box;
          list-style: none;
          text-decoration: none;
        }
        #container {
            text-align: center;
            padding-top: 17vw;
            font-weight: bold;
        }

        #welcome {
            color: #3d8651 !important;
            margin-bottom: 3vw;
            font-size: 5em;
            display: block;
        }

        #container span {
            display: inline-block;
            margin: 3vw auto;
            color: rgb(70, 9, 148);
            text-transform: uppercase;
        }

        span a {
            color: rgb(45, 55, 48);
            cursor: pointer;
            text-decoration: none !important;
        }
    </style>
</head>
<body>
<div id="container">
    <span id="welcome">{{ config('app.name') }}</span><br>
    <span><a href="{{url('/home')}}">HOME</a></span> |
    <span><a href="https://github.com/srustamov/TT" target="_blank">GITHUB</a></span>
</div>

@if ($errors->has('auth')))
  <script type="text/javascript">
    alert("{{$errors->first('auth')}}");
  </script>
@endif
</body>
</html>
