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
class SoundController extends BaseController
{

    public function Find(Request $request){
      $user = Auth::user();
      $sounds = Sound::where('user_id', $user->id)->where('del_flg', '<>', config('constants.ITEM_IS_DELETE'))->get();
      $res['success'] = true;
      $res['data'] = $sounds;
      return $res;
    }

    public function Add(Request $request){
      $user = Auth::user();
      $data['user_id'] = $user->id;
      $sound_path = '/public'.config('constants.SOUND_PATH');
      $file = $request->file('file');
      if (!is_null($file)) {
        $destinationPath = base_path().$sound_path; // upload path
        $extension = "wav";
        $fileName = 'ound_'.$user['id'].'_'.time().'.'.$extension; // renameing image
        $file->move($destinationPath, $fileName); // uploading file to given path
        $audio_format = new FFMpeg\Format\Audio\Wav();
        $audio_format->setAudioKiloBitrate(256);
        FFMpeg::fromDisk('local_public')
          ->open($sound_path.$fileName)
          ->export()
          ->toDisk('local_public')
          ->inFormat($audio_format)
          ->save($sound_path.'s'.$fileName);
        $fileName = 's'.$fileName;
        // $request->file('image')->move($destinationPath, $fileName); // uploading file to given path
        $data['file_url'] = config('constants.SOUND_PATH').$fileName;
        $data['name'] = $file->getClientOriginalName();
      }

      Sound::unguard();
      $sound = Sound::create($data);
      Sound::reguard();

      $res['success'] = true;
      $res['data'] = $sound;

      $option = Option::where('key', 'user_'.$user->id.'_updated_sound')->first();
      if($option == null){
        $option_data['key'] = 'user_'.$user->id.'_updated_sound';
        $option_data['value'] = config('constants.INTEGER_TRUE');
        Option::unguard();
        $option = Option::create($option_data);
        Option::reguard();
      }
      else{
        $option->value = config('constants.INTEGER_TRUE');
        $option->save();
      }
      return $res;
    }

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

      $option = Option::where('key', 'user_'.$user->id.'_updated_sound')->first();
      if($option == null){
        $option_data['key'] = 'user_'.$user->id.'_updated_sound';
        $option_data['value'] = config('constants.INTEGER_TRUE');
        Option::unguard();
        $option = Option::create($option_data);
        Option::reguard();
      }
      else{
        $option->value = config('constants.INTEGER_TRUE');
        $option->save();
      }
      return $res;
    }
}
