@extends('layouts.blog')

@section('header')
    <header class="masthead" style="background-image: url({{ asset('/images/home-bg.jpg') }})">
        <div class="overlay"></div>
        <div class="container">
            <div class="row">
                <div class="col-lg-8 col-md-10 mx-auto">
                    <div class="site-heading">
                        <h1>
                            OA Clean Blog
                        </h1>
                        <span class="subheading">
                            A Blog Theme by Offline Agency
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
                    Last Articles
                </h2>
            </div>
            <div class="col-lg-8 col-md-10 mx-auto">


                @foreach($articles as $article)

                    <div class="post-preview">
                        <a href="{{ route('article-detail', [getTranslatedContent($article['slug'])]) }}">
                            <h2 class="post-title">
                                @ml($article['title'])
                            </h2>
                            <h3 class="post-subtitle">
                                @ml($article['excerpt'])
                            </h3>
                        </a>
                        <p class="post-meta">Posted by
                            <span class="text-info">
                                {{ $article['author']  }}
                            </span>
                            on
                            <span class="text-info">
                                {{ formatDate($article['publication_date']) }}
                            </span>
                        </p>
                        <p>
                            @foreach($article->categories as $category)
                                <a href="{{ route('category-detail', [getTranslatedContent($category->slug)]) }}" class="badge badge-info" style="text-decoration: none;">
                                    @ml($category->name)
                                </a>
                            @endforeach
                        </p>
                    </div>
                    <hr>
            @endforeach
            {{ $articles->links() }}
            </div>
        </div>
    </div>

    <hr>
@endsection
