@extends('layouts.backend')

@section('styles')
    <link href="{{ asset('/css/admin.post.css') }}" rel="stylesheet">
@endsection

@section('content')
    <div class="container">
        <div class="row">
            @include('admin.sidebar')

            <div class="col-md-9">
                <div class="panel panel-default">
                    <div class="panel-heading">Posts</div>

                    <div class="panel-body">
                        @forelse($posts as $post)
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="user-image">
                                        <img src="{{ $post->postedBy->image_url ? $post->postedBy->image_url : '/assets/images/userimg.jpg' }}"
                                             class="img-responsive"/>
                                    </div>
                                    <div class="post">
                                        <div class="user-name">
                                            <span>{{ $post->postedBy->name }}</span>
                                            @if (! $post->is_approved)
                                                <span style="font-weight: 600">(This post needs approval)</span>
                                            @endif
                                            <div class="dropdown pull-right">
                                                <span class="posted-at">{{ $post->created_at }}</span>
                                                <button id="actionButtons" class="btn btn-default btn-sm dropdown-toggle" style="border: none" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                                                    <i class="fa fa-caret-down"></i>
                                                </button>
                                                <br />
                                                <ul class="dropdown-menu dropdown-menu-right" aria-labelledby="actionButtons">
                                                    <li>
                                                        <a href="{{ $post->is_approved ? route('admin.posts.disapprove.patch', ['id' => hashEncode($post->id)]) : route('admin.posts.approve.put', ['id' => hashEncode($post->id)]) }}"
                                                           onclick="event.preventDefault();
                                                                document.getElementById('approveDisapprovePost{{ $loop->index }}').submit();">
                                                            {{ $post->is_approved ? 'Disapprove' : 'Approve' }}
                                                        </a>
                                                        <form id="approveDisapprovePost{{ $loop->index }}" action="{{ $post->is_approved ? route('admin.posts.disapprove.patch', ['id' => hashEncode($post->id)]) : route('admin.posts.approve.put', ['id' => hashEncode($post->id)]) }}" method="POST" style="display: none;">
                                                            {{ csrf_field() }}
                                                            {{ $post->is_approved ? method_field('PATCH') : method_field('PUT') }}
                                                        </form>
                                                    </li>
                                                    <li>
                                                        <a href="{{ route('admin.posts.delete.delete', ['id' => hashEncode($post->id)]) }}"
                                                           onclick="event.preventDefault();
                                                                document.getElementById('deletePost{{ $loop->index }}').submit();">
                                                            Delete
                                                        </a>
                                                        <form id="deletePost{{ $loop->index }}" action="{{ route('admin.posts.delete.delete', ['id' => hashEncode($post->id)]) }}" method="POST" style="display: none;">
                                                            {{ csrf_field() }}
                                                            {{ method_field('DELETE') }}
                                                        </form>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-12">
                                                <p>{{ $post->content }}</p>
                                            </div>
                                            @if ($post->postedImage())
                                                <div class="col-md-12">
                                                    <div class="image">
                                                        <img src="{{  $post->postedImage()->media_content_path }}"
                                                             alt=""
                                                             class="img-responsive">
                                                    </div>
                                                </div>
                                            @endif
                                            @if ($post->postedVideo())
                                                <div class="col-md-12">
                                                    <video width="100%" controls>
                                                        <source src="{{  $post->postedVideo()->media_content_path }}"
                                                                type="video/mp4">
                                                        <source src="{{  $post->postedVideo()->media_content_path }}"
                                                                type="video/ogg">
                                                        Your browser does not support the video tag.
                                                    </video>
                                                </div>
                                            @endif
                                        </div>

                                        <div class="pull-right">
                                            <ul>
                                                <li>
                                                    <i class="fa fa-thumbs-o-up" aria-hidden="true"></i>
                                                    {{ count($post->likes->toArray()) }} Likes
                                                </li>
                                                <li>
                                                    <i class="fa fa-comment-o" aria-hidden="true"></i>
                                                    {{ count($post->comments->toArray()) }} Comments
                                                </li>
                                            </ul>
                                        </div>

                                    </div>
                                </div>
                            </div>
                            <div class="clearfix">
                                <br /><br />
                            </div>
                        @empty
                            <p>Sorry, there are no posts available.</p>
                        @endforelse
                        <div class="text-center">
                            {{ $posts->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <!-- Load Moment.JS -->
    <script src="{{ asset('js/moment.min.js') }}"></script>
    <script>
        $(document).ready(function () {
            /* Change posted at time to user's local time */
            $('.posted-at').each(function () {
                $(this).text(moment.utc($(this).text()).local().format('MMM DD, YYYY hh:mm:ss A'));
            });
        });
    </script>
@endsection
