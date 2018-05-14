<?php
namespace App\Http\Controllers\Admin;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Country;
use App\Models\State;
use App\Models\Place;
use Session;

class SponsorsController extends Controller
{
    //
    public function index(Request $request)
    {
        $data = $request->only(['country_id', 'state_id']);
        $countries = Country::all();
        if(isset($data['country_id'])){
            $country = Country::where('id', $data['country_id'])->first();
        } else {
            $country = Country::first();
        }
        $states = State::where('country_id', $country->id)->get();
        if(isset($data['state_id'])){
            $state = State::where('id', $data['state_id'])->first();
        } else {
            $state = State::where('country_id', $country->id)->first();
        }
        $places = [];
        if(isset($state)){
            $places = Place::leftJoin('sponsors', 'sponsors.place_id', '=', 'places.id')
                ->leftJoin('users', 'users.id', '=', 'sponsors.user_id')
                ->where('country_id', $country->id)
                ->where('state_id', $state->id)
                ->select(['places.*', 'users.name as sponsor_name'])
                ->get();
        }
        return view('admin.sponsor.index', compact('countries', 'country', 'state', 'states', 'places'));
    }

    public function storeCountry(Request $request)
    {
        $this->validate($request, ['name' => 'required']);
        $data = $request->only('name');
        $country = Country::where('name', $data['name'])->first();
        if($country == null){
            $country = Country::create($data);
            Session::flash('flash_message', 'New country has been registered!');
        }
        return redirect('admin/sponsors');
    }

    public function destroyCountry($id)
    {
        Country::destroy($id);
        Session::flash('flash_message', 'Country deleted!');
        return redirect('admin/sponsors');
    }

    public function storeState(Request $request)
    {
        $this->validate($request, ['name' => 'required', 'country_id' => 'required']);
        $data = $request->except('_token');
        $data['country_id'] = intval($data['country_id']);
        $state = State::where('name', $data['name'])->where('country_id', $data['country_id'])->first();
        if ($state == null) {
            $state = State::create($data);
            Session::flash('flash_message', 'New state has been registered!');
        }
        return redirect('admin/sponsors?country_id='.$state->country_id.'&state_id='.$state->id);
    }

    public function destroyState($id)
    {
        $state = State::findOrFail($id);
        $country_id = $state->country_id;
        State::destroy($id);
        Session::flash('flash_message', 'State deleted!');
        return redirect('admin/sponsors?country_id='.$country_id);
    }

    public function storePlace(Request $request)
    {
        $this->validate($request, ['country_id' => 'required', 'state_id' => 'required', 'city' => 'required', 'district' => 'required']);
        $data = $request->except('_token');
        $data['country_id'] = intval($data['country_id']);
        $data['state_id'] = intval($data['state_id']);
        $place = Place::create($data);
        Session::flash('flash_message', 'New state has been registered!');
        return redirect('admin/sponsors?country_id='.$place->country_id.'&state_id='.$place->state_id);
    }

    public function destroyPlace($id)
    {
        $place = Place::findOrFail($id);
        $country_id = $place->country_id;
        $state_id = $place->state_id;
        Place::destroy($id);
        Session::flash('flash_message', 'State deleted!');
        return redirect('admin/sponsors?country_id='.$country_id.'&state_id='.$state_id);
    }
}
