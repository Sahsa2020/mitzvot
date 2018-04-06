<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Role;
use App\User;
use App\Models\MessageHistory;
use Illuminate\Http\Request;
use Session;
use Mail;

class MessageHistoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return void
     */
    public function index()
    {
        $messages = MessageHistory::where('replied', config('constants.MESSAGE_UN_REPLIED'))->paginate(10);

        return view('admin.message_history.index', compact('messages'));
    }
    /**
     * Reply the specified resource.
     *
     * @param  int  $id
     *
     * @return void
     */
    public function reply($id)
    {
        $message = MessageHistory::findOrFail($id);

        return view('admin.message_history.reply', compact('message'));
    }
    
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     *
     * @return void
     */
    public function replyMessage($id, Request $request)
    {
        $message = MessageHistory::findOrFail($id);
        $param = $request->only(['subject', 'message']);
        $mail_to = $message['email'];
        $data = array('contact'=>$param);
        Mail::send('mail/customer_mail', $data, function($message) use($param, $mail_to) {
            $message->to($mail_to, 'The user wants contact.')->subject
                ($param['subject']);
            $message->from('noreply@milionmitzvot.com','MilionMitzvot');
        });
        $message->replied = config('constants.MESSAGE_REPLIED');
        $message->save();
        Session::flash('flash_message', 'The message has been successfully sent to the customer!');
        return redirect('admin/message_history');
    }
}
