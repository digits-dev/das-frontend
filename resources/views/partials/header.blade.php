<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Mobile Metas -->
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Site Metas -->
    <title>Online Tracking</title>
    <meta name="keywords" content="warranty">
    <meta name="description" content="digital walker warranty">
    <meta name="author" content="digitstrading">

    <meta property="og:title" content="Warranty System" />
    <meta property="og:url" content="{{ url()->current() }}" />
    <meta property="og:image" content="{{ asset('images/ws.png') }}" />

    <!-- Site Icons -->
    <link rel="shortcut icon" href="{{ asset('images/ws.png') }}" type="image/x-icon">
    <link rel="apple-touch-icon" href="{{ asset('images/ws.png') }}">

    <!-- Bootstrap CSS -->

    <!-- Site CSS -->
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">

    <!-- Responsive CSS -->
    <link rel="stylesheet" href="{{ asset('css/responsive.css') }}">

    <!-- Custom CSS -->
    <link rel="stylesheet" href="{{ asset('css/custom.css') }}">

    <!-- Multiple Dropdown Select CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/chosen/1.4.2/chosen.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.0/css/select2.min.css">

    <link href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.0/css/bootstrap.min.css"
        rel="stylesheet" />
    <link
        href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.5.0/css/bootstrap-datepicker.css"rel="stylesheet">

    <style>
        .requiredField {
            color: red !important;
            font-weight: bold;
        }

        .error-content {
            display: block;
            margin-top: 5px;
            color: red !important;
            font-size: 0.8em;
            font-weight: bolder;
            margin-left: 3px;
            line-height: 1.5 !important;
        }

        input::-webkit-calendar-picker-indicator {
            display: none;
        }

        select {
            z-index: 1;
        }

        .select2-selection .select2-selection--multiple {
            width: auto;
        }

        .select2-container--default .select2-selection--single .select2-selection__arrow {
            display: none;
        }

        .select2-container--default .select2-selection--single {
            border: 1px solid #aaa !important;
            border-radius: 1px !important;
            min-height: 40px !important;
        }

        .select2-container .select2-selection--single .select2-selection__rendered {
            padding-top: 6px;
        }

        body {
            background: linear-gradient(rgba(0, 0, 0, 0.2), rgba(0, 0, 0, 0.2)), url('{{ asset('images/5.png') }}');
            -webkit-background-size: cover;
            -moz-background-size: cover;
            -o-background-size: cover;
            background-size: cover;
            background-attachment: fixed;
            background-position: center;
        }

        #back-to-top {
            background: #3C8DBC !important;
        }
    </style>
</head>

<body>
    <header class="main-header">
        <!-- Start Navigation -->
        <nav class="navbar navbar-expand-lg navbar-light bg-light navbar-default bootsnav"
            style="box-shadow: 5px 1px 5px 1px #6f6f6f;">
            <div class="container">
                <div class="navbar-header">
                    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbar-menu"
                        aria-controls="navbars-rs-food" aria-expanded="false" aria-label="Toggle navigation">
                        <i class="fa fa-bars"></i>
                    </button>
                    <a class="navbar-brand" href="/">Warranty</a>
                </div>
                <!-- End Header Navigation -->

                <!-- Collect the nav links, forms, and other content for toggling -->
                <div class="collapse navbar-collapse" id="navbar-menu"></div>
                <!-- /.navbar-collapse -->

                <!-- Start Atribute Navigation -->
                <div class="attr-nav"></div>
            </div>
        </nav>
        <!-- End Navigation -->
    </header>
