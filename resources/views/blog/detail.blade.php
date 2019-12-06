@extends('layouts.blog')

@section('header')
    <header class="masthead" style="background-image: url({{ asset('images/blog/' . $article->media_path ) }})">
        <div class="overlay"></div>
        <div class="container">
            <div class="row">
                <div class="col-lg-8 col-md-10 mx-auto">
                    <div class="site-heading">
                        <h1>
                            @ml($article->title)
                        </h1>
                        <h2 class="subheading">
                            @ml($article->excerpt)
                        </h2>
                        <span class="meta">
                            Posted by <span>{{ $article->author }}</span> on {{ formatDate($article->publication_date) }}
                        </span>
                        <p>
                            @foreach($article->categories as $category)
                                <a href="{{ route('category-detail', [getTranslatedContent($category->slug)]) }}" class="badge badge-info" style="text-decoration: none;">
                                    @ml($category->name)
                                </a>
                            @endforeach
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </header>
@endsection

@section('content')
    <article>
        <div class="container">
            <div class="row">
                <div class="col-lg-8 col-md-10 mx-auto">
                    <p>@ml($article->content)</p>
                </div>
            </div>
        </div>
    </article>

    <hr>
@endsection
