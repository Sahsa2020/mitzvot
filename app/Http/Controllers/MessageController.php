<?php

namespace App\Http\Controllers;

use App\User;
use App\Models\Conversation;
use App\Models\Message;
use App\Models\Ticket;
use Illuminate\Http\Request;
use Nahid\Talk\Facades\Talk;
use Auth;
use View;
use DB;
use Config;
use Validator;
use Mail;

class MessageController extends Controller
{
    protected $authUser;

    public function __construct()
    {
        $this->middleware('auth');
        $user = Auth::user();

        if($user == null)
            return redirect('/');

        Talk::setAuthUserId(Auth::user()->id);

        View::composer('partials.peoplelist', function($view) {
            $threads = Talk::threads();
            $view->with(compact('threads'));
        });
    }

    public function removeChatHistory($id)
    {
        $user = Auth::user();
        $conversation = Conversation::where(function($query) use($user, $id){
                        $query->where('conversations.user_one', $user->id);
                        $query->where('conversations.user_two', $id);
                    })
                    ->orwhere(function($query) use($user, $id){
                        $query->where('conversations.user_one', $id);
                        $query->where('conversations.user_two', $user->id);
                    })
                    ->first()->id;
        Message::where('conversation_id', $conversation)
            ->where('user_id', '<>', $user->id)
            ->update([
                'is_seen' => config('constants.MESSAGE_READ')
            ]);

        return response()->json([
            'success' => true
        ], 200);
    }

    public function chatHistory($id)
    {
        $conversations = Talk::getMessagesByUserId($id);
        $user = '';
        $messages = [];
        if(!$conversations) {
            $user = User::find($id);
        } else {
            $user = $conversations->withUser;
            $messages = $conversations->messages;
        }

        return view('messages.conversations', compact('messages', 'user', 'id'));
    }

    public function contactAdmin(Request $request){
        $data = $request->only(['id','message']);
        $validator = Validator::make($data, [
          'id'       => 'required|numeric',
          'message'      => 'required'
        ]);
        if ($validator->fails()) {
          dd("Invalid http request.");
        }
        //Send email to the admin
        // Mail::send('mail/contact_mail', $data, function($message) use($contact, $mail_to) {
        //     $message->to($mail_to, 'Tutorials Point')->subject
        //         ("The user is contacting to you.");
        //     $message->from('noreply@milionmitzvot.com','MilionMitzvot');
        // });
        if ($message = Talk::sendMessageByUserId($data['id'], $data['message'])) {
            return redirect('message/'.$data['id']);
        }
    }

    public function index()
    {
        $user = Auth::user();

        $first = Conversation::join('users', function ($join) {
                    $join->on('conversations.user_two', '=', 'users.id');
                })->leftjoin('messages', function ($join) use ($user) {
                      $join->on('messages.conversation_id', '=', 'conversations.id');
                      $join->where('messages.user_id', '<>', $user->id);
                      $join->where('messages.is_seen', config('constants.MESSAGE_UNREAD'));
                })->where('conversations.user_one', $user->id)
                ->select('users.id', 'users.name', 'users.image_url', DB::raw('count(messages.id) as unread_messages'))
                ->groupby('conversations.id');

        $users = Conversation::join('users', function ($join) {
                    $join->on('conversations.user_one', '=', 'users.id');
                })->leftjoin('messages', function ($join) use ($user) {
                      $join->on('messages.conversation_id', '=', 'conversations.id');
                      $join->where('messages.user_id', '<>', $user->id);
                      $join->where('messages.is_seen', config('constants.MESSAGE_UNREAD'));
                })->where('conversations.user_two', $user->id)
                ->select('users.id', 'users.name', 'users.image_url', DB::raw('count(messages.id) as unread_messages'))
                ->groupby('conversations.id')
                ->union($first)
                ->get();

        foreach ($users as $user) {
            $user->image_url = $user->image_url ? url('/') . $user->image_url : null;
        }
        //dd($users);

        return view('messages.home', compact('users'));
    }

    public function ajaxSendMessage(Request $request)
    {
        if ($request->ajax()) {
            $rules = [
                'message-data'=>'required',
                '_id'=>'required'
            ];

            $this->validate($request, $rules);

            $body = $request->input('message-data');
            $userId = $request->input('_id');

            if ($message = Talk::sendMessageByUserId($userId, $body)) {
                $html = view('ajax.newMessageHtml', compact('message'))->render();
                return response()->json(['status'=>'success', 'html'=>$html], 200);
            }
        }
    }

    public function ajaxDeleteMessage(Request $request, $id)
    {
        if ($request->ajax()) {
            if(Talk::deleteMessage($id)) {
                return response()->json(['status'=>'success'], 200);
            }

            return response()->json(['status'=>'errors', 'msg'=>'something went wrong'], 401);
        }
    }

    public function customerSupport(Request $request){
        $user = Auth::user();
        $ticket = Ticket::where('requester_id', $user->id)->where('del_flg', config('constants.ITEM_IS_LIVE'))->where('accepter_id', 0)->first();
        if($ticket != null){
            $mail_to = [];
            $admins = User::join('role_user', function($join){
                        $join->on('users.id', '=', 'role_user.user_id');
                        })
                        ->join('roles', function($join){
                        $join->on('roles.id', '=', 'role_user.role_id');
                        })
                        ->where('roles.name', '=', config('constants.ADMIN_USER'))
                        ->get();
            foreach($admins as $admin){
                $mail_to[] = $admin->email;
            }
            $_message = $ticket->message;
            $url = url('admin/acceptticket/'.$ticket->id);
            $data = array('url'=>$url, 'message_1'=>$_message, 'user' => $user);
            $requester = User::where('id', $ticket->requester_id)->first();
            Mail::send('mail/chat_request_mail', $data, function($message) use($mail_to, $ticket, $requester) {
                $message->to($mail_to, 'The user is connecting to customer service.')->subject
                    ("CHAT".$ticket->id);
                $message->from($requester->email, $requester->name);
            });
        }
        return view('messages.customer_support', compact('ticket'));
    }

    public function newMessage(Request $request){
        $params = $request->only(['message']);
        $user = Auth::user();
        $ticketData = [];
        $ticketData['requester_id'] = $user->id;
        $ticketData['message'] = $params['message'];
        Ticket::unguard();
        $ticket = Ticket::create($ticketData);
        Ticket::reguard();
        // return view('messages.customer_support');
        return redirect('/customer_support');
    }

    public function isTicketClosed($id){
        $user = Auth::user();
        $ticket = Ticket::where('requester_id', $user->id)->where('id', $id)->where('del_flg', config('constants.ITEM_IS_LIVE'))->where('accepter_id', '<>', 0)->first();
        if($ticket != null)
            return response()->json(['success'=>true, 'accepter'=>$ticket->accepter_id], 200);
        else
            return response()->json(['success'=>false], 200);
    }

    public function tests()
    {
        dd(Talk::channel());        
    }
    
}
