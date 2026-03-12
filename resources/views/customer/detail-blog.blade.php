@extends('customer/layout',array(
'title' => $blog->post_title,
'content'=> strip_tags($blog->post_content),
'image'=> url('storage/blogs/'.$blog->post_thumbnail))
)

@section('content')

    <!-- start page title area-->
    <div class="page-title-area ptb-100">
        <div class="container">
            <div class="page-title-content">
                <h1>@lang('Blog')</h1>
                <ul>
                    <li class="item"><a href="index.html">@lang('Blog')</a></li>
                    <li class="item"><a href="#"><i class='bx bx-chevrons-right'></i>Details</a></li>
                </ul>
            </div>
        </div>
        <div class="bg-image">
            <img src="{{ url('customer/img/page-title-area/blog-style3.jpg') }}" alt=" Demo Image">
        </div>
    </div>


    <!-- start blog details section -->
    <section class="blog-details-section pt-100 pb-70">
        <div class="container">

            <div class="row">
                <div class="col-lg-8 col-md-12">
                    <div class="blog-details-desc mb-30">
                        <div class="image mb-20">
                            <img src="{{ url('storage/blogs/' . $blog->post_thumbnail) }}" alt="image" />
                        </div>
                        <ul class="info-list mb-20">
                            <li><i class='bx bx-calendar'></i> {{ $blog->created_at }}</li>
                            <li><i class='bx bx-tag'></i>Tour, Tourism, Travel</li>
                        </ul>
                        <div class="content mb-20">
                            <h3>{{ $blog->post_title }}</h3>

                            {!! $blog->post_content !!}

                        </div>

                        <!-- Comments Area -->
                        <div class="comments-area mt-5">
                            <h3 class="comments-title">{{ count($comments) }} Comments:</h3>
                            <ol class="comment-list" style="list-style: none; padding-left: 0;">
                                @foreach($comments as $comment)
                                <li class="comment" style="margin-bottom: 20px; border-bottom: 1px solid #eee; padding-bottom: 20px;">
                                    <article class="comment-body" style="display: flex;">
                                        <div class="comment-author vcard" style="margin-right: 15px;">
                                            <img src="{{ $comment->users && $comment->users->avatar ? url('storage/users/'.$comment->users->avatar) : asset('customer/img/user1.jpg') }}" class="avatar" alt="image" style="width: 50px; height: 50px; border-radius: 50%; object-fit: cover;">
                                        </div>
                                        <div class="comment-content">
                                            <div class="comment-metadata">
                                                <b class="fn">{{ $comment->users ? $comment->users->name : 'Anonymous' }}</b>
                                                <span class="says" style="color: #666; font-size: 14px;"> - {{ $comment->created_at->format('F d, Y h:i a') }}</span>
                                            </div>
                                            <div class="comment-text" style="margin-top: 5px;">
                                                <p>{{ $comment->comment }}</p>
                                            </div>
                                        </div>
                                    </article>
                                </li>
                                @endforeach
                            </ol>
                            
                            <div class="comment-respond mt-5">
                                @if(session('success'))
                                    <div class="alert alert-success">{{ session('success') }}</div>
                                @endif
                                @if(session('error'))
                                    <div class="alert alert-danger">{{ session('error') }}</div>
                                @endif

                                @auth
                                    <h3 class="comment-reply-title">Leave a Reply</h3>
                                    <form class="comment-form" action="{{ url('blog/comment/'.$blog->slug) }}" method="POST">
                                        @csrf
                                        <div class="form-group">
                                            <label for="comment">Comment</label>
                                            <textarea name="comment" class="form-control" id="comment" cols="45" rows="5" required="required"></textarea>
                                        </div>
                                        <div class="form-submit mt-3">
                                            <input type="submit" name="submit" id="submit" class="btn btn-primary" value="Post Comment">
                                        </div>
                                    </form>
                                @else
                                     <div class="alert alert-info">Please <a href="{{ url('user/login') }}">Login</a> to leave a comment.</div>
                                @endauth
                            </div>
                        </div>
                        </div>
                    </div>
                

                <div class="col-lg-4 col-md-12">
                    <aside class="widget-area">

                        <div class="widget widget-search mb-30">
                            <form class="search-form search-top">
                                <input type="search" class="form-control" placeholder="Search..." />
                                <button type="submit" class="btn-text-only">
                                    <i class='bx bx-search-alt'></i>
                                </button>
                            </form>
                        </div>
                        <div class="widget widget-video mb-30">
                            <div class="video-image">
                                <img src="https://img.youtube.com/vi/kIFVo2qgI-g/sddefault.jpg" width="900px" width="600px"
                                    alt="video" />
                            </div>
                            <a href="https://www.youtube.com/watch?v=kIFVo2qgI-g" class="youtube-popup video-btn">
                                <i class='bx bx-right-arrow'></i>
                            </a>
                        </div>
                        <div class="widget widget-article mb-30">
                            <h3 class="sub-title">Popular Blog</h3>
                            @foreach ($recent as $rec)

                                <article class="article-item">
                                    <div class="image">
                                        <img src="{{ url('storage/blogs/' . $rec->post_thumbnail) }}" alt="Demo Image" />
                                    </div>
                                    <div class="content">
                                        <h3>
                                            <a href="{{ url('blog/' . $rec->slug) }}">{{ $rec->post_title }}</a>
                                        </h3>
                                        <ul class="list">
                                            <li>
                                                <div class="author">
                                                    <img src="{{ url('storage/users/' . $rec->user->avatar) }}"
                                                        alt="Demo Image">
                                                    <span>By - {{ $rec->user->name }}</span>
                                                </div>
                                            </li>
                                        </ul>
                                    </div>
                                </article>
                            @endforeach
                        </div>


                        <div class="widget widget-gallery mb-30">
                            <h3 class="sub-title">Instagram Post</h3>
                             <blockquote class="instagram-media" data-instgrm-permalink="https://www.instagram.com/godestinationvillage/" data-instgrm-version="13"></blockquote>
                            <script async src="//www.instagram.com/embed.js"></script>
                        </div>
                    </aside>
                </div>
            </div>
        </div>
    </section>
@endsection
