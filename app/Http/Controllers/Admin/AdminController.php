<?php

namespace App\Http\Controllers\Admin;

use App\Models\Admin;
use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\Controller;

class AdminController extends Controller
{
    // public function __construct()
    // {
    //     $this->middleware('permission:view-user', ['only' => ['index']]);
    //     $this->middleware('permission:create-user', ['only' => ['create','store']]);
    //     $this->middleware('permission:update-user', ['only' => ['update','edit']]);
    //     $this->middleware('permission:delete-user', ['only' => ['destroy']]);
    // }

    public function index()
    {
        // $id = auth('admin')->user()->id;
        // $admin = Admin::find($id);
        // if ($admin->can('create-role')) {
        //     return 'User can view roles';
        // } else {
        //     return 'User cannot view roles';
        // }
        // dd([
        //     'roles' => $admin->getRoleNames(), // Roles assigned to the user
        //     'permissions_via_roles' => $admin->getPermissionsViaRoles(), // Permissions inherited through roles
        //     'all_permissions' => $admin->getAllPermissions(), // All permissions (direct + via roles)
        // ]);
        $users = Admin::get();
        return view('dashboard.role-permission.user.index', ['users' => $users]);
    }

    public function create()
    {
        $roles = Role::pluck('name','name')->all();
        return view('dashboard.role-permission.user.create', ['roles' => $roles]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email',
            'password' => 'required|string|min:8|max:20',
            'roles' => 'required'

        ]);

        $user = Admin::create([
                        'name' => $request->name,
                        'email' => $request->email,
                        'password' => Hash::make($request->password),
                        'status' => 1,
                    ]);

        $user->syncRoles($request->roles);

        return redirect()->route('admin.users.index')->with('status','User created successfully with roles');
    }

    public function edit(Admin $user)
    {
        $roles = Role::pluck('name','name')->all();
        $userRoles = $user->roles->pluck('name','name')->all();
        return view('dashboard.role-permission.user.edit', [
            'user' => $user,
            'roles' => $roles,
            'userRoles' => $userRoles
        ]);
    }

    public function update(Request $request, Admin $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'password' => 'nullable|string|min:8|max:20',
            'roles' => 'required',
            'status' => 'required|in:active,unactive',
        ]);

        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'status' => $request->status,
        ];

        if(!empty($request->password)){
            $data += [
                'password' => Hash::make($request->password),
            ];
        }

        $user->update($data);
        $user->syncRoles($request->roles);

        return redirect()->route('admin.users.index')->with('status','User Updated Successfully with roles');
    }

    public function destroy($userId)
    {
        $user = Admin::findOrFail($userId);
        $user->delete();

        return redirect()->route('admin.users.index')->with('status','User Delete Successfully');
    }
}
