@extends('dashboard.partials.master')

@section('title')
@endsection

@section('css')
@endsection

@section('titlepage')
@endsection

@section('breadcumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">{{ trans('dashboard.home') }}</a></li>
@endsection

@section('breadcumbactive')
    <li class="breadcrumb-item active" aria-current="page"><a
            href="{{ route('admin.posts.index') }}">posts</a></li>
@endsection

@section('content')
    <div class="bg-white p-4">
        <h2 class="mb-2 page-title"> posts</h2>

        <a href="{{route('admin.posts.create')}}" class="btn btn-primary">
            {{ trans('dashboard.create.post') }}
        </a>
        <div class="row my-4">
            <!-- Small table -->
            <div class="col-md-12 col-sm-6">
                <div class="card shadow">
                    <div class="card-body">


                        <!-- table -->
                        <div id="dataTable-1_wrapper" class="dataTables_wrapper dt-bootstrap4 no-footer">

                            <div class="row">
                                <div class="col-sm-6 col-md-12  overflow-auto">
                                    <table class="table datatables dataTable no-footer" id="dataTable-1" role="grid"
                                        aria-describedby="dataTable-1_info" style="width:100%">
                                        <thead>
                                            <tr role="row">
                                                <td>
                                                    <input type="checkbox" id="checkAll">

                                                </td>
                                                <td>#</th>
                                                <td>title</td>
                                                <td>description</td>
                                                <td>contact number</td>
                                                <td>admin</td>
                                                <td>created at</td>
                                                <td>{{ trans('dashboard.actions') }}</td>
                                            </tr>

                                        </thead>
                                        <tbody>
                                            @foreach ($posts as $post)
                                            <tr role="row" class="even">
                                                <td>
                                                    <input type="checkbox" class="delete-checkbox" name="delete[]"
                                                        value="{{ $post->id }}">
                                                </td>
                                                    <td>{{ $loop->iteration }}</td>
                                                    <td>{{$post->title}}</td>
                                                    <td>{{ Str::limit($post->description, 512) }}</td>
                                                    <td>{{$post->contact_phone}}</td>
                                                    <td>{{$post->admin->name}}    </td>
                                                    <td>{{ $post->created_at->diffForHumans() }}</td>
                                                    <td>
                                                    <button class="btn btn-sm dropdown-toggle more-horizontal"
                                                        type="button" data-toggle="dropdown" aria-haspopup="true"
                                                        aria-expanded="false">
                                                        <span class="text-muted sr-only">{{ trans('dashboard.actions') }}</span>
                                                    </button>
                                                    <div class="dropdown-menu dropdown-menu-right">
                                                        <a class="dropdown-item" href="#" data-toggle="modal"
                                                            data-target="#edit{{ $post->id }}">
                                                            {{ trans('dashboard.edit') }}
                                                        </a>
                                                        <a class="dropdown-item" href="#" data-toggle="modal"
                                                            data-target="#modal{{ $post->id }}">
                                                            {{ trans('dashboard.delete') }}
                                                        </a>
                                                    </div>
                                                </td>
                                            </tr>
                                            @include('dashboard.posts.delete')
                                            @include('dashboard.posts.edit')
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div> <!-- simple table -->


        </div>

    </div>


@endsection

@section('js')
<script>
    var currentLocale = '{{ app()->getLocale() }}';
        console.log(currentLocale);

        $('#dataTable-1').DataTable(
        {
            "language": {
                "url": currentLocale === 'ar' ? 'https://cdn.datatables.net/plug-ins/2.2.1/i18n/ar.json' : ''
            },
          autoWidth: true,
          "lengthMenu": [
            [16, 32, 64, -1],
            [16, 32, 64, "All"]
          ]
        });
    </script>

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

<script language="javascript">
    $("#checkAll").click(function() {
        $('input:checkbox').not(this).prop('checked', this.checked);
    });



    //     $(document).ready(function(){
    // $(".btn").click(function(){
    //     $("#btn-delete-all").modal('show');
    // });
    // });
</script>

<script type="text/javascript">
    $(document).ready(function () {
    $("#confirm-delete").click(function (e) {
        e.preventDefault();

        var selected = [];
        $("input[type=checkbox]:checked").each(function () {
            selected.push(this.value);
        });

        if (selected.length > 0) {
            $('#delete_all_id').val(JSON.stringify(selected));
            $("#bulk-delete-form").submit();         } else {
            alert("Please select at least one item to delete.");
        }
    });
});

</script>
@endsection
