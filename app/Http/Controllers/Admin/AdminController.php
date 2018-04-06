<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Permission;
use App\Role;
use Illuminate\Http\Request;
use Session;
use App\Models\Ticket;
use Config;
use Auth;
use Nahid\Talk\Facades\Talk;
class AdminController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return void
     */
    public function index()
    {
        return view('admin.dashboard');
    }

    /**
     * Display given permissions to role.
     *
     * @return void
     */
    public function getGiveRolePermissions()
    {
        $roles = Role::select('id', 'name', 'label')->get();
        $permissions = Permission::select('id', 'name', 'label')->get();

        return view('admin.permissions.role-give-permissions', compact('roles', 'permissions'));
    }

    /**
     * Store given permissions to role.
     *
     * @param  \Illuminate\Http\Request  $request
     *
     * @return void
     */
    public function postGiveRolePermissions(Request $request)
    {
        $this->validate($request, ['role' => 'required', 'permissions' => 'required']);

        $role = Role::with('permissions')->whereName($request->role)->first();
        $role->permissions()->detach();

        foreach ($request->permissions as $permission_name) {
            $permission = Permission::whereName($permission_name)->first();
            $role->givePermissionTo($permission);
        }

        Session::flash('flash_message', 'Permission granted!');

        return redirect('admin/roles');
    }

    public function acceptTicket($id){
        $ticket = Ticket::where('id', $id)->first();
        if($ticket == null || $ticket->del_flg == config('constants.ITEM_IS_DELETE'))
        {
            Session::flash('flash_message', 'This ticket is not available now.');
            return view('admin.dashboard');
        }
        if($ticket->accepter_id > 0)
        {
            Session::flash('flash_message', 'This ticket is assigned to other customer.');
            return view('admin.dashboard');
        }
        $user = Auth::user();
        Talk::setAuthUserId($ticket->requester_id);
        Talk::sendMessageByUserId($user->id, $ticket->message);
        $ticket->accepter_id = $user->id;
        $ticket->save();
        return redirect('message/'.$ticket->requester_id);
    }
}
