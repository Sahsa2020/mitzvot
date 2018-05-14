<?php

namespace App\Api\V1\Controllers;

use Auth;
use Config;
use DB;
use App\User;
use App\Models\Country;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

class CountriesController extends BaseController
{
    public function getCountries(Request $request)
    {
        $countries = Country::all();
        $res['success'] = true;
        $res['countries'] = $countries;
        return $res;
    }
}