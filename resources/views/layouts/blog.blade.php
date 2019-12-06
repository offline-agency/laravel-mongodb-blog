<!DOCTYPE html>
<html lang="en">

<head>
    <meta name="author" content="Offline Agency">
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>OA Clean Blog - Start Bootstrap Theme</title>

    <!-- Custom styles for this template -->
    <link href="{{ asset('css/blog.css') }}" rel="stylesheet">

    <link href='https://fonts.googleapis.com/css?family=Lora:400,700,400italic,700italic' rel='stylesheet' type='text/css'>
    <link href='https://fonts.googleapis.com/css?family=Open+Sans:300italic,400italic,600italic,700italic,800italic,400,300,600,700,800' rel='stylesheet' type='text/css'>
</head>

<body>

<!-- Navigation -->
<nav class="navbar navbar-expand-lg navbar-light fixed-top" id="mainNav">
    <div class="container">
        <a class="navbar-brand" href="{{ route('blog') }}">
            OA Clean Blog
        </a>
        <button class="navbar-toggler navbar-toggler-right" type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
            Menu
            <i class="fas fa-bars"></i>
        </button>
        <div class="collapse navbar-collapse" id="navbarResponsive">
            <ul class="navbar-nav ml-auto">
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('blog') }}">
                        Blog
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('category-list') }}">
                        Categories
                    </a>
                </li>

                @auth
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('admin.dashboard') }}">
                            Dashboard
                        </a>
                    </li>
                @else
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('login') }}">
                            Login
                        </a>
                    </li>
                @endauth
            </ul>
        </div>
    </div>
</nav>
<!-- Page Header -->
@yield('header')

<!-- Main Content -->
@yield('content')

<!-- Footer -->
@include('partial.footer')

<!-- Custom scripts for this template -->
<script src="{{ asset('js/blog.js') }}"></script>
</body>
</html>

