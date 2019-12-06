@extends('layouts.blog')

@section('header')
    <header class="masthead" style="background-image: url({{ asset('images/home-bg.jpg') }})">
        <div class="overlay"></div>
        <div class="container">
            <div class="row">
                <div class="col-lg-8 col-md-10 mx-auto">
                    <div class="site-heading">
                        <h1>All Categories</h1>
                        <span class="subheading">
                            The list of categories that a post can have
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </header>
@endsection

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-lg-8 col-md-10 mx-auto">
                <h2 class="h1">
                    All categories
                </h2>
            </div>
            <div class="col-lg-8 col-md-10 mx-auto">

                @foreach($categories as $category)

                    <div class="post-preview">
                        <a href="{{ route('category-detail', [getTranslatedContent($category['slug'])]) }}">
                            <h2 class="post-title">
                                @ml($category['name'])
                            </h2>
                            <h3 class="post-subtitle">
                                @ml($category['description'])
                            </h3>
                        </a>
                    </div>
                    <hr>
                @endforeach

            </div>
        </div>
    </div>

    <hr>
@endsection

@section('footer')

    <footer>
        <div class="container">
            <div class="row">
                <div class="col-lg-8 col-md-10 mx-auto">
                    <ul class="list-inline text-center">
                        <li class="list-inline-item">
                            <a href="#">
                <span class="fa-stack fa-lg">
                  <i class="fas fa-circle fa-stack-2x"></i>
                  <i class="fab fa-twitter fa-stack-1x fa-inverse"></i>
                </span>
                            </a>
                        </li>
                        <li class="list-inline-item">
                            <a href="#">
                <span class="fa-stack fa-lg">
                  <i class="fas fa-circle fa-stack-2x"></i>
                  <i class="fab fa-facebook-f fa-stack-1x fa-inverse"></i>
                </span>
                            </a>
                        </li>
                        <li class="list-inline-item">
                            <a href="#">
                <span class="fa-stack fa-lg">
                  <i class="fas fa-circle fa-stack-2x"></i>
                  <i class="fab fa-github fa-stack-1x fa-inverse"></i>
                </span>
                            </a>
                        </li>
                    </ul>
                    <p class="copyright text-muted">Copyright &copy; Your Website 2019</p>
                </div>
            </div>
        </div>
    </footer>

@endsection
