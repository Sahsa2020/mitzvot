<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Role;
use App\Models\Option;
use App\Models\Discount;
use Illuminate\Http\Request;
use Session;
use Validator;
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\IOFactory;
class DiscountsController extends Controller
{
    public function index()
    {
        $discounts = Discount::paginate(10);
        return view('admin.discounts.index', compact('discounts'));
    }
    
    /**
     * Show the form for creating a new resource.
     *
     * @return void
     */
    public function create()
    {
        return view('admin.discounts.create');
    }

    public function edit($id)
    {
        $discount = Discount::findOrFail($id);
        return view('admin.discounts.edit', compact('discount'));
    }

    public function store(Request $request)
    {
        $this->validate($request, ['code' => 'required', 'percent' => 'required']);

        $data = $request->only('code', 'percent');
        $discount = Discount::create($data);
        Session::flash('flash_message', 'Discount added!');

        return redirect('admin/discounts');
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
        $this->validate($request, ['code' => 'required', 'percent' => 'required']);

        $data = $request->only('code', 'percent');
        $discount = Discount::findOrFail($id);
        $discount->update($data);
        Session::flash('flash_message', 'Discount updated!');

        return redirect('admin/discounts');
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
        Discount::destroy($id);

        Session::flash('flash_message', 'Discount deleted!');

        return redirect('admin/discounts');
    }
}
