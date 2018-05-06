<?php

namespace App\Api\V1\Controllers;

use App\Http\Controllers\Controller;
use App\Role;
use App\User;
use Illuminate\Http\Request;
use Session;
use Validator;
use Auth;

use Config;
use App\Models\Box;
use App\Models\Follow;
use App\Models\MessageHistory;
use DB;
use Mail;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return void
     */
    public function index()
    {
        $users = User::paginate(10);

        return view('admin.users.index', compact('users'));
    }

    public function getAll(Request $request)
    {
        $data = $request->only(['search', 'start', 'length']);
        $validator = Validator::make($data, [
            'start'       => 'required|numeric',
            'length'      => 'required|numeric'
          ]);          
        if ($validator->fails()) {
        $res["success"] = false;
        $res["message"] = "The data is not correct.";
        return $res;
        }

        $data['search'] = is_null($data['search'])?"":$data['search'];        

        $total_query = User::
                    where('del_flg', '<>', config('constants.ITEM_IS_NONE'))
                    ->where('name', 'LIKE', '%'.$data['search'].'%')
                    ->select('name', 'school', 'id');

        $total = count($total_query->get());                    
        $users = $total_query->offset($data['start'])->take($data['length'])->get();

        $res['data'] = $users;
        $res['success'] = true;
        $res['total_user_count'] = $total;
        return $res;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return void
     */
    public function create()
    {
        $roles = Role::select('id', 'name', 'label')->get();
        $roles = $roles->pluck('label', 'name');

        return view('admin.users.create', compact('roles'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     *
     * @return void
     */
    public function store(Request $request)
    {
        $this->validate($request, ['name' => 'required', 'email' => 'required', 'password' => 'required', 'roles' => 'required']);

        $data = $request->except('password');
        $data['password'] = bcrypt($request->password);
        $user = User::create($data);

        foreach ($request->roles as $role) {
            $user->assignRole($role);
        }

        Session::flash('flash_message', 'User added!');

        return redirect('admin/users');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     *
     * @return void
     */
    public function show($id)
    {
        $user = User::findOrFail($id);

        return view('admin.users.show', compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     *
     * @return void
     */
    public function edit($id)
    {
        $roles = Role::select('id', 'name', 'label')->get();
        $roles = $roles->pluck('label', 'name');

        $user = User::with('roles')->select('id', 'name', 'email')->findOrFail($id);
        $user_roles = [];
        foreach ($user->roles as $role) {
            $user_roles[] = $role->name;
        }

        return view('admin.users.edit', compact('user', 'roles', 'user_roles'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int      $id
     * @param  \Illuminate\Http\Request  $request
     *
     * @return void
     */
    public function update($id, Request $request)
    {
        $this->validate($request, ['name' => 'required', 'email' => 'required', 'roles' => 'required']);

        $data = $request->except('password');
        if ($request->has('password')) {
            $data['password'] = bcrypt($request->password);
        }

        $user = User::findOrFail($id);
        $user->update($data);

        $user->roles()->detach();
        foreach ($request->roles as $role) {
            $user->assignRole($role);
        }

        Session::flash('flash_message', 'User updated!');

        return redirect('admin/users');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     *
     * @return void
     */
    public function destroy($id)
    {
        User::destroy($id);

        Session::flash('flash_message', 'User deleted!');

        return redirect('admin/users');
    }
}
