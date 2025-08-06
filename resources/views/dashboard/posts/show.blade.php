@extends('dashboard.partials.master')

@section('title')

@endsection

@section('css')

@endsection

@section('titlepage')

@endsection

@section('breadcumb')
<li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">{{ trans('dashboard.home') }}</a></li>
@endsection

@section('breadcumbactive')
<li class="breadcrumb-item active" aria-current="page"><a href="{{route('admin.news.index')}}">{{ trans('dashboard.news') }}</a></li>
<li class="breadcrumb-item active" aria-current="page"><a href="{{route('admin.news.show',$post->id)}}">{{$post->title}}</a></li>
@endsection

@section('content')
<div class="bg-white p-4">
    <h2 class="mb-2 page-title">{{ $post->title }}</h2>

    <div class="card">

        <div class="card shadow-sm">
            <img src="{{asset($post->imagepath)}}" class="card-img-top" alt="Article Image" style="height: 400px;">
            <div class="card-body">
                <h2 class="card-title">{{$post->title}}</h2>
                <p class="text-muted">By {{$post->admin->name}} | {{$post->created_at->diffForHumans()}}</p>
                <p class="card-text">
                    {!! $post->description !!}
                </p>

                <!-- Article Footer (e.g., tags, categories, or social links) -->
                <div class="d-flex justify-content-between">
                    <div>
                        @foreach ($post->tags as $tag)
                            <span class="badge badge-primary">{{$tag->name}}</span>
                        @endforeach
                    </div>

                    <!-- Edit and Delete buttons -->
                    <div>
                        <a href="{{route('admin.news.edit',$post->id)}}" class="btn btn-sm btn-warning text-white mr-2">
                            <i class="fa fa-edit"></i> {{ trans('dashboard.edit') }}
                        </a>

                        <a href="#" href="#" class="btn btn-sm btn-primary text-white mr-2" data-toggle="modal"
                        data-target="#modal{{ $post->id }}">
                        {{ trans('dashboard.delete') }}
                    </a>
                    @include('dashboard.news.delete')
                    </div>
                </div>
            </div>
        </div>


        <!-- Comments Section -->
         <div class="card mt-4">
            <div class="card-header">
                <h5>{{ trans('general.comments') }} ({{ $post->comments->count() }})</h5>
            </div>
            <div class="card-body">
                @foreach ($post->comments as $comment)
                    <div class="media mb-3">
                        <div class="media-body">
                            <h6 class="mt-0">{{ $comment->name }} <small class="text-muted">{{ $comment->created_at->diffForHumans() }}</small></h6>
                            <div class="d-flex">
                                <p>
                                    {{ $comment->message }}

                                    <!-- Status Form -->
                                    <form action="{{ route('admin.stauts.change', $comment->id) }}" method="POST">
                                        @csrf
                                        @method('POST')
                                        <input type="hidden" name="status" value="comment">
                                        <input type="hidden" name="commentid" value="{{$comment->id}}">
                                        <button type="submit" class="btn btn-link p-0">
                                            <span class="badge badge-secondary">
                                                @if ($comment->status == 1)
                                                    <span class="btn btn-sm btn-primary text-white">{{ trans('general.active') }}</span>
                                                @else
                                                    <span class="btn btn-sm btn-dark text-white">{{ trans('general.unactive') }}</span>
                                                @endif
                                            </span>
                                        </button>
                                    </form>

                                    <!-- Delete Form -->
                                    <form action="{{ route('admin.comments.destroy', $comment->id) }}" method="POST" style="display: inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger text-white">{{ trans('dashboard.delete') }}</button>
                                    </form>
                                </p>
                            </div>
                            @endforeach


                        </div>
                    </div>


            </div>

          </div>

    </div>

</div>
@endsection

@section('js')

@endsection
