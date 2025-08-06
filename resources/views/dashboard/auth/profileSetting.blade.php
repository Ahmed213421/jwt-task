@extends('dashboard.partials.master')

@section('title')
@endsection

@section('css')
    <style>
        .avatar-wrapper {
            position: relative;
            width: 150px;
            /* Adjust the size as needed */
            height: 150px;
            border-radius: 50%;
            overflow: hidden;
            cursor: pointer;
        }

        .avatar {
            width: 100%;
            height: 100%;
            object-fit: cover;
            border-radius: 50%;
        }

        .hover-text {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            background: rgba(0, 0, 0, 0.5);
            color: #fff;
            font-size: 16px;
            font-weight: bold;
            text-align: center;
            border-radius: 50%;
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .avatar-wrapper:hover .hover-text {
            opacity: 1;
        }
    </style>
@endsection

@section('titlepage')
@endsection

@section('breadcumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">{{ trans('dashboard.home') }}</a></li>
@endsection

@section('breadcumbactive')
    {{-- {{-- <li class="breadcrumb-item active" aria-current="page"><a href="{{route('admin.category.index')}}">{{ trans('dashboard.all_cat') }}</a></li> --}}
    <li class="breadcrumb-item active" aria-current="page"><a
            href="{{ route('admin.profile.index') }}">{{ trans('general.update_profile') }}</a></li>
@endsection

@section('content')
    <div class="bg-white p-4">
        <h2 class="mb-2 page-title">{{ trans('general.update_profile') }}</h2>

        <div class="card">
            <div class="my-4 container">
                <form action="{{ route('admin.profile.update', Auth::guard('admin')->user()->id) }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="row mt-5">
                        <div class="col-md-3 text-center mb-5">
                            {{-- @dump(Auth::guard('admin')->user()->image) --}}
                            <div class="avatar-wrapper">
                                <div class="avatar avatar-xl">
                                    <img src="{{ Auth::guard('admin')->user()->image? asset(Auth::guard('admin')->user()->image->imagepath): 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAOEAAADhCAMAAAAJbSJIAAAALVBMVEXQ0NCxsbG0tLTPz8/Hx8e3t7fAwMDMzMy9vb21tbW6urrBwcGurq7FxcXT09M4BFhyAAAE60lEQVR4nO2dW5OjIBCFVUSNEv7/z92YzQVjVIRmOKTO97IPUzXl2Ya+QNNTVYQQQgghhBBCCCGEEEIIIYQQQgghhBCSBDuT+yNSYfvrMF60voxD16rfk9kOTW3qN2Of+4tE6Qfjqrtj6jb3Z4nR6k91Lzv+wlq1alyZ781UvkQ17Oi7LVWd+wMjsdc9eXcuRXuc3QX6kqhyf2Ywtj+Wd6dUiXZ/BzroMiV6rdAHo6rK86l9461vptFDW5RK2/ob0DFlQW71GiJwjo6lJHJTmMBZYxF2tOECZ1r47RgpsDZDbgVHRAqcJUJb0U6R+tAl2i7WgneJ19w6toleog9QPWpYoP9Gk1vKBq2Qvts67XJr+YpvteQlEbLeEBRY14hG3DpRC+OSW84a74LXD8CIcR1FFdaAYd/2ohoBl2l1urLfBzPqWzl3A7gR7whKRIwXM3JhH7VOtIOUwjG3lC3EjAir0Er5U1iFlVRURN2HtyqYCj2ZcgvZREihwcxpZqRs+PMKgW/4j+/uvUBN2m50MgpxF6lQPARepDIKDfJNokjq3eCdYbyR2IfQJhSJFrhZ94xEtIA88X4TfXCKvUZvBWKsMzVXZDdTqU7HlsAdtMDqEn+4rzvgfKbqZW4vgDei0C0wcHEodc+Ne4QhpVDjehsZgcgKhc4SgRUGNl5+AlzhyxzqI+dtMtdrqB1DdyRKC9Tb0QcCCrGrp+jSoobsw1gQrxA3Z/tPdAsmcDB8ECsR3YTV/Gp0/SrWE2Ma4FjoEhj5p74AAz4IO8vADoQLwhoWkLO1FWFhsZw1GlgLN9DJzCchCnEPL74QVGSUtA2DDhbxk5klp30N8Bnid9RZheglxZqT7hSzs3uXk48tS1ujM6eq4bL86JMTHcNlCpyfXP64QO+4X54bfeIbMZBboPbx9qZFpdwu3p4GtyP4AO83e0VVFQt8K4zCwr1V7yklXvqeFxW2A75Wc5hHmL1vjzys+Jqi0Jcxiq+de2rMq+/neM6CeTXqzfmB0eCj+Npnz9BrZ9m22df4+s9QT8W4Gq1ycm0nxO3lbs1rIo2TAA2gGvtFcFhc5G5pbJx8zV3OZsDbj7b/DO+LVEy1w+diNbU7GMp+NDmgTY2yrV5vtuXxp1X9NOra/KcZh9ZdinY9VcM0Hc5i3XImX6c+9e06vm95XAhDWrVaf8528kypN0OKye51bHuQXXsY4Sie6GvGycqbI3TfXA4GlNneI+3JZEjV1T61gzHdzlrtv7iob78kw91wf2K8Za2nfj360Vbt5N82bS5/q/FgQvAXmrG7OVH74OZRp20PtaFR/+FEvoi3P3oc7xsvqJmh+aNCUuiNYQjmL847lJ9vSCUx/VReuaFlgaQ+ehR6UhFF0s0oNlcvhpRWPH3tmYSU4zFlp7IFk661SGR6pwTJOm9yC3uTKCxmjPSfJDJiblkuSdoYgUyYpnVDSY7UiyZFf1H2dG1JgkscsVFsMsh3wWEt0hTLFCHldpHPa4SGB8khvhGFh8zGMwpvRJtb0BpZgXDbsBYvhOG2obhCqUlzckg7UzhHI518i80lFUQ2XGAc0CyRVSj5lw+kkM1MIU4RP6DCc0DV9w+o8BSC4+TlED2q+X2FlW7wEL5JVM4/u7/53A9VxA9hWsIIIYQQQgghhBBCCCGEEEIIIYQQQgghhOPwDhx9CCdDcDFsAAAAASUVORK5CYII=' }}"
                                        alt="Avatar" class="avatar avatar-img rounded-circle" id="avatar">
                                    <div class="hover-text" id="hoverText">{{ trans('general.change_pic') }}</div>

                                    <input type="file" name="photo" id="fileInput" style="display: none;" />
                                </div>
                            </div>

                        </div>
                        <div class="col">
                            <div class="row align-items-center">
                                <div class="col-md-7">
                                    <h4 class="mb-1">{{ Auth::guard('admin')->user()->name }}</h4>
                                </div>
                            </div>

                        </div>
                    </div>
                    <hr class="my-4">
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="firstname">{{ trans('dashboard.name') }}</label>
                            <input type="text" id="firstname"
                                value="{{ old('name', Auth::guard('admin')->user()->name) }}" class="form-control">
                        </div>

                    </div>
                    <div class="form-group">
                        <label for="inputEmail4">{{ trans('general.email') }}</label>
                        <input type="email" class="form-control"
                            value="{{ old('name', Auth::guard('admin')->user()->email) }}" id="inputEmail4"
                            placeholder="brown@asher.me">
                    </div>

            <hr class="my-4">
            <div class="row mb-4">
                <div class="col-md-6 col-lg-12">
                    <div class="form-group">
                        <label for="inputPassword4">{{ trans('general.password') }}</label>
                        <input type="password" class="form-control" id="inputPassword5" name="oldpassword">
                    </div>
                    <div class="form-group">
                        <label for="inputPassword5">{{ trans('general.new') }}
                            {{ trans('general.password') }}</label>
                        <input type="password" class="form-control" id="inputPassword5" name="password">
                    </div>
                    <div class="form-group">
                        <label for="inputPassword6">{{ trans('general.confirm_password') }}</label>
                        <input type="password" class="form-control" id="inputPassword6" name="password_confirmation">
                    </div>
                </div>
            </div>
            <button type="submit" class="btn btn-primary">{{ trans('general.submit') }}</button>
            </form>
        </div>
    </div>


    </div>
@endsection

@section('js')
    <script>
        document.querySelector('.avatar-wrapper').addEventListener('click', function() {
            document.getElementById('fileInput').click();
        });

        document.getElementById('fileInput').addEventListener('change', function(event) {
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('avatar').src = e.target.result; // Update avatar preview
                };
                reader.readAsDataURL(file);
            }
        });
    </script>
@endsection
