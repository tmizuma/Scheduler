<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="css/app.css">
    <link rel="stylesheet" href="css/index.css">
    <script type="text/javascript">
        window.Laravel = window.Laravel || {};
        window.Laravel.csrfToken = "{{csrf_token()}}";
    </script>
    <title>{{ $title  }}</title>
</head>
<body>
<div id="page_top"></div>
<nav class="navbar navbar-light" style="background-color: #408eba;">
    <button class="navbar-toggler navbar-toggler-right" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <a class="navbar-brand" style="color:#fff;" href="/">会議室予約管理</a>
    <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav">
            <li class="nav-item active">
                <a class="nav-link" href="/">Home <span class="sr-only">(current)</span></a>
            </li>
        </ul>
    </div>
</nav>

<div id="show-alert-success" class="alert alert-success hubot-alert" role="alert" style="display: none"></div>
<div id="show-alert-failed" class="alert alert-danger hubot-alert" role="alert" style="display: none"></div>

@yield('content')
<div id="page_bottom"></div>
<script src="{{ asset('js/app.js') }}"></script>
</body>
</html>
