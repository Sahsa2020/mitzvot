<?php

namespace App\Api\V1\Controllers;

use Config;
use Validator;
use App\User;
use App\Models\Box;
use App\Models\Sound;
use App\Models\Option;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use Auth;
use FFMpeg;
use File;
class SoundController extends BaseController
{

    /**
    * Get Sound information
    * 
    * @param Request $request
    * @return Array for JSON Response
    */
    public function Find(Request $request){
      $user = Auth::user();
      $sounds = Sound::where('user_id', $user->id)->where('del_flg', '<>', config('constants.ITEM_IS_DELETE'))->get();
      $res['success'] = true;
      $res['data'] = $sounds;
      return $res;
    }

    /**
    * Add sound file
    * 
    * @param Request $request
    * @return Array for JSON Response
    */
    public function Add(Request $request){
      $user = Auth::user();
      $data['user_id'] = $user->id;
      $sound_path = '/public/';
      $file = $request->file('file');
      if (!is_null($file)) {
        $destinationPath = storage_path('app').$sound_path; // upload path
        $extension = "wav";
        $fileName = 'ound_'.$user['id'].'_'.time().'.'.$extension; // renameing image
        $file->move($destinationPath, $fileName); // uploading file to given path
        shell_exec("/usr/local/bin/ffmpeg/ffmpeg -i ".storage_path('app').$sound_path.$fileName." -acodec pcm_s16le -ar 22050 ".storage_path('app').$sound_path.'s'.$fileName);
        $fileName = 's'.$fileName;
        $move = File::move($destinationPath.$fileName, base_path().$sound_path.config('constants.SOUND_PATH').$fileName);
        $data['file_url'] = config('constants.SOUND_PATH').$fileName;
        $data['name'] = $file->getClientOriginalName();
      }

      Sound::unguard();
      $sound = Sound::create($data);
      Sound::reguard();

      $res['success'] = true;
      $res['data'] = $sound;
      return $res;
    }

    /**
    * Remove sound
    * 
    * @param Request $request
    * @return Array for JSON Response
    */
    public function Remove(Request $request){
      $user = Auth::user();
      $data = $request->only(['sound_id']);
      $validator = Validator::make($data, ['sound_id'   => 'required|numeric']);
      if ($validator->fails()) {
        $res["success"] = false;
        $res["message"] = "The data is not correct.";
        return $res;
      }

      // check exist device
      $sound = Sound::where('del_flg'  , '<>'  , config('constants.ITEM_IS_DELETE'))
            ->where('id'   ,  '='  , $data['sound_id'])
            ->first();

      if ($sound === null) {
          $res["success"] = false;
          $res["message"] = "The sound doesn't exist.";
          return $res;
      }
      $sound['del_flg'] = config('constants.ITEM_IS_DELETE');
      $sound->save();
      $res['success'] = true;
      return $res;
    }
}
