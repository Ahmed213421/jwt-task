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
    <h2 class="mb-2 page-title">{{trans('spatie.users')}}</h2>

    <div class="container mt-5">
        <a href="{{ route('admin.roles.index') }}" class="btn btn-primary mx-1">Roles</a>
        <a href="{{ route('admin.permissions.index') }}" class="btn btn-info mx-1">Permissions</a>
        <a href="{{ route('admin.users.index') }}" class="btn btn-warning mx-1">Users</a>
    </div>

    <div class="container mt-2">
        <div class="row">
            <div class="col-md-12">

                @if (session('status'))
                    <div class="alert alert-success">{{ session('status') }}</div>
                @endif

                <div class="card mt-3">
                    <div class="card-header">
                        <h4>Users

                            @if (auth('admin')->user()->can('create-user'))
                            <a href="{{ route('admin.users.create') }}" class="btn btn-primary float-end">Add User</a>
                            @endif
                        </h4>
                    </div>
                    <div class="card-body">

                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>Id</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Roles</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($users as $user)

                                <tr>
                                    <td>{{ $user->id }}</td>
                                    <td>{{ $user->name }}</td>
                                    <td>{{ $user->email }}</td>
                                    <td>
                                        @if (!empty($user->getRoleNames()))
                                            @foreach ($user->getRoleNames() as $rolename)
                                                <label class="badge bg-primary mx-1">{{ $rolename }}</label>
                                            @endforeach
                                        @endif
                                    </td>
                                    <td>{{$user->status}}</td>
                                    <td>
                                        @if (auth('admin')->user()->can('create-role'))
                                        <a href="{{ route('admin.users.edit',$user->id) }}" class="btn btn-success">Edit</a>
                                                                                @endif

                                        @if (auth('admin')->user()->can('delete-user'))
                                        <a href="{{ url('users/'.$user->id.'/delete') }}" class="btn btn-danger mx-2">Delete</a>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('js')

@endsection
