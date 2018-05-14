<?php

namespace App\Api\V1\Controllers;

use Auth;
use Config;
use DB;
use App\User;
use App\Models\State;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

class StatesController extends BaseController
{
    public function getStates(Request $request)
    {
        $data = $request->only(['country_id']);
        $states = State::where('country_id', $data['country_id'])->get();
        $res['success'] = true;
        $res['states'] = $states;
        return $res;
    }
}