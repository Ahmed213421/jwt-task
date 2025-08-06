<div class="wrapper">
    <nav class="topnav navbar navbar-light">
      <button type="button" class="navbar-toggler text-muted mt-2 p-0 mr-3 collapseSidebar">
        <i class="fe fe-menu navbar-toggler-icon"></i>
      </button>
      <form class="form-inline mr-auto searchform text-muted" method="GET" action="">
        @csrf
        <input class="form-control mr-sm-2 bg-transparent border-0 pl-4 text-muted" type="search" name="search" placeholder="Type something..." aria-label="Search">
      </form>
      <ul class="nav">
        <li class="nav-item">
          <a class="nav-link text-muted my-2" href="#" id="modeSwitcher" data-mode="light">
            <i class="fe fe-sun fe-16"></i>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link text-muted my-2" href="./#" data-toggle="modal" data-target=".modal-shortcut">
            <span class="fe fe-grid fe-16"></span>
          </a>
        </li>
        <li class="nav-item nav-notif position-relative">
            <a class="nav-link text-muted my-2 position-relative" href="./#" data-toggle="modal" data-target=".modal-notif">
              <span class="fe fe-bell fe-16"></span>
              <span class="badge badge-pill badge-success position-absolute top-0 start-100 translate-middle dot text-succes" style="font-size: 12px; padding: 4px 7px; border-radius: 50%;top:0px;color:black">
                  {{count(Auth::guard('admin')->user()->unreadnotifications)}}
            </span>
            </a>
          </li>




        @auth('admin')
        <li class="nav-item dropdown mt-2">
            <a class="nav-link dropdown-toggle text-muted pr-0" href="#" id="navbarDropdownMenuLink" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <span class="avatar avatar-sm mt-2">
                    {{-- <img src="{{ Auth::guard('admin')->user()->image ? asset(Auth::guard('admin')->user()->image->imagepath) : 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAOEAAADhCAMAAAAJbSJIAAAALVBMVEXQ0NCxsbG0tLTPz8/Hx8e3t7fAwMDMzMy9vb21tbW6urrBwcGurq7FxcXT09M4BFhyAAAE60lEQVR4nO2dW5OjIBCFVUSNEv7/z92YzQVjVIRmOKTO97IPUzXl2Ya+QNNTVYQQQgghhBBCCCGEEEIIIYQQQgghhBCSBDuT+yNSYfvrMF60voxD16rfk9kOTW3qN2Of+4tE6Qfjqrtj6jb3Z4nR6k91Lzv+wlq1alyZ781UvkQ17Oi7LVWd+wMjsdc9eXcuRXuc3QX6kqhyf2Ywtj+Wd6dUiXZ/BzroMiV6rdAHo6rK86l9461vptFDW5RK2/ob0DFlQW71GiJwjo6lJHJTmMBZYxF2tOECZ1r47RgpsDZDbgVHRAqcJUJb0U6R+tAl2i7WgneJ19w6toleog9QPWpYoP9Gk1vKBq2Qvts67XJr+YpvteQlEbLeEBRY14hG3DpRC+OSW84a74LXD8CIcR1FFdaAYd/2ohoBl2l1urLfBzPqWzl3A7gR7whKRIwXM3JhH7VOtIOUwjG3lC3EjAir0Er5U1iFlVRURN2HtyqYCj2ZcgvZREihwcxpZqRs+PMKgW/4j+/uvUBN2m50MgpxF6lQPARepDIKDfJNokjq3eCdYbyR2IfQJhSJFrhZ94xEtIA88X4TfXCKvUZvBWKsMzVXZDdTqU7HlsAdtMDqEn+4rzvgfKbqZW4vgDei0C0wcHEodc+Ne4QhpVDjehsZgcgKhc4SgRUGNl5+AlzhyxzqI+dtMtdrqB1DdyRKC9Tb0QcCCrGrp+jSoobsw1gQrxA3Z/tPdAsmcDB8ECsR3YTV/Gp0/SrWE2Ma4FjoEhj5p74AAz4IO8vADoQLwhoWkLO1FWFhsZw1GlgLN9DJzCchCnEPL74QVGSUtA2DDhbxk5klp30N8Bnid9RZheglxZqT7hSzs3uXk48tS1ujM6eq4bL86JMTHcNlCpyfXP64QO+4X54bfeIbMZBboPbx9qZFpdwu3p4GtyP4AO83e0VVFQt8K4zCwr1V7yklXvqeFxW2A75Wc5hHmL1vjzys+Jqi0Jcxiq+de2rMq+/neM6CeTXqzfmB0eCj+Npnz9BrZ9m22df4+s9QT8W4Gq1ycm0nxO3lbs1rIo2TAA2gGvtFcFhc5G5pbJx8zV3OZsDbj7b/DO+LVEy1w+diNbU7GMp+NDmgTY2yrV5vtuXxp1X9NOra/KcZh9ZdinY9VcM0Hc5i3XImX6c+9e06vm95XAhDWrVaf8528kypN0OKye51bHuQXXsY4Sie6GvGycqbI3TfXA4GlNneI+3JZEjV1T61gzHdzlrtv7iob78kw91wf2K8Za2nfj360Vbt5N82bS5/q/FgQvAXmrG7OVH74OZRp20PtaFR/+FEvoi3P3oc7xsvqJmh+aNCUuiNYQjmL847lJ9vSCUx/VReuaFlgaQ+ehR6UhFF0s0oNlcvhpRWPH3tmYSU4zFlp7IFk661SGR6pwTJOm9yC3uTKCxmjPSfJDJiblkuSdoYgUyYpnVDSY7UiyZFf1H2dG1JgkscsVFsMsh3wWEt0hTLFCHldpHPa4SGB8khvhGFh8zGMwpvRJtb0BpZgXDbsBYvhOG2obhCqUlzckg7UzhHI518i80lFUQ2XGAc0CyRVSj5lw+kkM1MIU4RP6DCc0DV9w+o8BSC4+TlED2q+X2FlW7wEL5JVM4/u7/53A9VxA9hWsIIIYQQQgghhBBCCCGEEEIIIYQQQgghhOPwDhx9CCdDcDFsAAAAASUVORK5CYII=' }}" alt="..." class="avatar-img rounded-circle rounded-circle"> --}}
                </span>
            </a>
            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdownMenuLink">
                <a class="dropdown-item" href="{{route('admin.profile.index')}}">{{ trans('general.settings') }}</a>
                <a class="dropdown-item" href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">{{ trans('general.logout') }}</a>
                <form id="logout-form" action="{{ route('admin.logout') }}" method="POST" style="display: none;">
                    @csrf
                </form>
            </div>
        </li>
        @endauth


    </ul>
    </nav>
