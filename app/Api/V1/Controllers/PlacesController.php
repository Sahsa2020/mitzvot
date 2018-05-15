<?php

namespace App\Api\V1\Controllers;

use Auth;
use Config;
use DB;
use App\User;
use App\Models\Place;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

class PlacesController extends BaseController
{
    public function getPlaces(Request $request)
    {
        $data = $request->only(['country_id', 'state_id']);
        $places = Place::where("country_id", $data['country_id'])->get();
        $places = Place::leftJoin('sponsors', 'sponsors.place_id', '=', 'places.id')
            ->leftJoin('users', 'users.id', '=', 'sponsors.user_id')
            ->where('country_id', $data['country_id'])
            ->where('state_id', $data['state_id'])
            ->select(['places.*', 'users.name as sponsor_name'])
            ->get();
        $res['success'] = true;
        $res['places'] = $places;
        return $res;
    }
    
    public function updatePlace($id, Request $request)
    {
        $data = $request->only(['field', 'value']);
        $place = Place::findOrFail($id);
        switch ($data['field']) {
            case 'city':
                $place->city = $data['value'];
                break;
            case 'district':
                $place->district = $data['value'];
                break;
            case 'population':
                $place->population = $data['value'];
                break;
            case 'units':
                $place->units = $data['value'];
                break;
            case 'cost_assumption':
                $place->cost_assumption = $data['value'];
                break;
            case 'profit_assumption':
                $place->profit_assumption = $data['value'];
                break;
        }
        $place->save();
        $res['success'] = true;
        return $res;
    }
}