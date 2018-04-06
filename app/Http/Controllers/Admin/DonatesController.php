<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Role;
use App\Models\Donate;
use Illuminate\Http\Request;
use Session;

class DonatesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return void
     */
    public function index()
    {
        $donates = Donate::paginate(10);

        return view('admin.donates.index', compact('donates'));
    }

    public function approve($id)
    {
        $donate = Donate::findOrFail($id);
        $donate->del_flg = 0;
        $donate->save();
        return redirect('admin/donates');
    }

    public function unapprove($id)
    {
        $donate = Donate::findOrFail($id);
        $donate->del_flg = 1;
        $donate->save();
        return redirect('admin/donates');
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
        Donate::destroy($id);

        Session::flash('flash_message', 'Donation deleted!');

        return redirect('admin/donates');
    }
}
