<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Role;
use App\Models\Option;
use Illuminate\Http\Request;
use Session;
use Validator;
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\IOFactory;
class AdminController1 extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return void
     */
    public function index()
    {
        return view('admin.uploads.rule');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     *
     * @return void
     */
    public function upload_rule(Request $request)
    {
        $rule_path = '/public/rule/';
        $file = $request->file('rule');
        // dd($file);
        if (!is_null($file)) {
            $destinationPath = base_path().$rule_path; // upload path
            $fileName = 'rule.docx';
            $file->move($destinationPath, $fileName); // uploading file to given path
            $phpWord = new PHPWord();
            $document = $phpWord->loadTemplate($destinationPath.$fileName);
            $document->saveAs('temp');
            $phpWord = \PhpOffice\PhpWord\IOFactory::load('temp'); 
            $objWriter = IOFactory::createWriter($phpWord, 'HTML');
            $objWriter->save(base_path().'/resources/views/'.'rule.blade.php');
        }
        Session::flash('flash_message', 'Rule has been uploaded successfully!');
        return view('admin.uploads.rule');
    }

    public function upload_firmware(Request $request){
        return view('admin.uploads.firmware');
    }

    public function upload_firmware_post(Request $request){
        $data = $request->only(['major_version', 'minor_version']);
        $validator = Validator::make($data, [
            'major_version'    => 'required|numeric',
            'minor_version' => 'required|numeric'
        ]);
        if ($validator->fails()) {
            Session::flash('flash_message', 'Please fill the form with correct data.');
            return view('admin.uploads.firmware');
        }
        $file_path = '/firmware/';
        $fileName = '';
        $file = $request->file('firmware');
        if (!is_null($file)) {
            $destinationPath = base_path().$file_path; // upload path
            $fileName = 'CBox_firmware_v_'.$data['major_version'].'_'.$data['minor_version'];
            $file->move($destinationPath, $fileName); // uploading file to given path
        }
        Option::where('key', 'firmware_major_version')->update(array('value' => $data['major_version'] ));
        Option::where('key', 'firmware_minor_version')->update(array('value' => $data['minor_version'] ));
        Option::where('key', 'firmware_file_name')->update(array('value' => $fileName ));
        Session::flash('flash_message', 'Firmware has been uploaded successfully!');
        return view('admin.uploads.firmware');
    }
}
