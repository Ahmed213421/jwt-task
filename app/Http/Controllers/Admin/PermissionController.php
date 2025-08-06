<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use App\Http\Controllers\Controller;

class PermissionController extends Controller
{
    public function __construct()
    {
        // $this->middleware('permission:view permission', ['only' => ['index']]);
        // $this->middleware('permission:create permission', ['only' => ['create','store']]);
        // $this->middleware('permission:update permission', ['only' => ['update','edit']]);
        // $this->middleware('permission:delete permission', ['only' => ['destroy']]);
    }

    public function index()
    {
        $permissions = Permission::get();
        return view('dashboard.role-permission.permission.index', ['permissions' => $permissions]);
    }

    public function create()
    {
        return view('dashboard.role-permission.permission.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => [
                'required',
                'string',
                'unique:permissions,name'
            ]
        ]);

        Permission::create([
            'name' => $request->name
        ]);

        return redirect()->route('admin.permissions.index')->with('status','Permission Created Successfully');
    }

    public function edit(Permission $permission)
    {
        return view('dashboard.role-permission.permission.edit', ['permission' => $permission]);
    }

    public function update(Request $request, Permission $permission)
    {
        $request->validate([
            'name' => [
                'required',
                'string',
                'unique:permissions,name,'.$permission->id
            ]
        ]);

        $permission->update([
            'name' => $request->name
        ]);

        return redirect()->route('admin.permissions.index')->with('status','Permission Updated Successfully');
    }

    public function destroy($permissionId)
    {
        
        $permission = Permission::find($permissionId);
        $permission->delete();
        return redirect()->route('admin.permissions.index')->with('status','Permission Deleted Successfully');
    }
}
