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
<li class="breadcrumb-item active" aria-current="page"></li>
@endsection

@section('content')
<div class="bg-white p-4">
    <h2 class="mb-2 page-title">Data table</h2>

    <div class="container mt-5">
        <div class="row">
            <div class="col-md-12">

                @if (session('status'))
                    <div class="alert alert-success">{{ session('status') }}</div>
                @endif

                <div class="card">
                    <div class="card-header">
                        <h4>Role : {{ $role->name }}
                            <a href="{{ url('dashboard/roles') }}" class="btn btn-danger float-end">Back</a>
                        </h4>
                    </div>
                    <div class="card-body">

                        <form action="{{ url('dashboard/roles/'.$role->id.'/give-permissions') }}" method="POST">
                            @csrf
                            @method('PUT')

                            <div class="mb-3">
                                @error('permission')
                                <span class="text-danger">{{ $message }}</span>
                                @enderror

                                <label for="">Permissions</label>

                                <div class="row">
                                    @foreach ($permissions as $permission)
                                    <div class="col-md-2">
                                        <label>
                                            <input
                                                type="checkbox"
                                                name="permission[]"
                                                value="{{ $permission->name }}"
                                                {{ in_array($permission->id, $rolePermissions) ? 'checked':'' }}
                                            />
                                            {{ $permission->name }}
                                        </label>
                                    </div>
                                    @endforeach
                                </div>

                            </div>
                            <div class="mb-3">
                                <button type="submit" class="btn btn-primary">Update</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('js')

@endsection
