<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="{{ asset('css/app.css')  }}">
    <link rel="icon" href="{{ asset('favicon.ico')  }}">
    <title>360</title>
  </head>
  <body>
    <noscript>
      <strong>We're sorry but 360 doesn't work properly without JavaScript enabled. Please enable it to continue.</strong>
    </noscript>
    <div id="app"></div>

    <script src="{{ asset('js/app.js')  }}"></script>
  </body>
</html>
