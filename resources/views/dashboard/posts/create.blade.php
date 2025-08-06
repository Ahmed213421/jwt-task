@extends('dashboard.partials.master')

@section('title')
{{ trans('dashboard.create.post') }}
@endsection

@section('css')

<link href="https://cdn.jsdelivr.net/npm/summernote@0.9.0/dist/summernote.min.css" rel="stylesheet">

@endsection

@section('breadcumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">{{ trans('dashboard.home') }}</a></li>
@endsection

@section('breadcumbactive')
    <li class="breadcrumb-item active" aria-current="page"><a href="{{route('admin.posts.create')}}">{{ trans('dashboard.create.post') }} </a></li>
@endsection

@section('content')
<div class="bg-white p-4">
<h2 class="mb-2 page-title ml-3">{{ trans('dashboard.create.post') }} </h2>
<form action="{{route('admin.posts.store')}}" method="post" enctype="multipart/form-data">

    @csrf
        <div class="col-md-12">
            <div class="form-group mb-3">
                <label for="simpleinput">title </label>
                <input type="text" id="simpleinput" class="form-control" name="title" value="{{old('title')}}">
            </div>
            <input type="hidden" name="admin_id" value="{{auth('admin')->user()->id}}">
            <div class="form-group mb-3">
                <label for="simpleinput">contact phone</label>
                <input type="text" id="simpleinput" class="form-control" name="contact_phone" value="{{ old('contact_phone') }}">
            </div>
            <div class="form-group mb-3">
                <label for="simpleinput">{{ trans('dashboard.desc') }} </label>
                <textarea class="form-control" id="editor" style="height: 300px" name="description">

                    {{ old('description') }}
                </textarea>

            </div>





            <button type="submit">{{ trans('general.submit') }}</button>

        </form>
    </div>
@endsection

@section('js')
<script src="https://cdn.jsdelivr.net/npm/summernote@0.9.0/dist/summernote.min.js"></script>


<script>
    $(document).ready(function() {
        $('#categoryselect').change(function() {
            var categoryId = $(this).val();

            // Reset student dropdown
            $('#subSelect').empty().append('<option value="">Select a subcategory</option>').prop('disabled', true);

            if (categoryId) {
                $.ajax({
                    url: '/select/' + categoryId + '/subcategory',
                    method: 'GET',
                    success: function(data) {
                        $.each(data, function(index, sub) {
                            $('#subSelect').append('<option value="' + sub.id + '">' + sub.name + '</option>');
                        });
                        $('#subSelect').prop('disabled', false); // Enable student dropdown
                    }
                });
            }
        });
    });
</script>
<script>
    $(document).ready(function() {
        $('#editor').summernote({
            tabsize: 2,
            height: 200
        });
    });
</script>
@endsection
