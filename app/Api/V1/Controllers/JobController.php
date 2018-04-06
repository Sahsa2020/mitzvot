<?php

namespace App\Api\V1\Controllers;

use DB;
use Mail;

class JobController extends BaseController
{
    public function __construct()
    {
        // TODO: Constructor code goes here...
    }
    
    /**
    * Send Mail to the member with deposit information
    * This method is called every saturday night.
    * @param Request $request
    * @return Array for JSON Response
    */
    public function sendMailToMembersWeekly() {
        $organizations = DB::table('users')
            ->join('role_user', function ($join) {
                $join->on('users.id', '=', 'role_user.user_id');
            })->join('roles', function ($join) {
                $join->on('roles.id', '=', 'role_user.role_id');
            })->select('users.id', 'users.weekly_mail_ignore')
            ->where('users.weekly_mail_ignore', '<>', config('constants.WEEKLY_MAIL_IGNORE'))
            ->where(function ($query) {
                  $query->orWhere('roles.name', config('constants.INSTITUTION_USER'));
                  $query->orWhere('roles.name', config('constants.SCHOOL_USER'));
            })->get();

        foreach ($organizations as $key => $org) {
            $members = DB::table('users')
                ->join('role_user', function ($join) {
                    $join->on('users.id', '=', 'role_user.user_id');
                })->join('roles', function ($join) {
                    $join->on('roles.id', '=', 'role_user.role_id');
                })->join('cbox_member_boxes', function ($join) {
                    $join->on('cbox_member_boxes.member_id', '=', 'users.id');
                })->leftjoin('cbox_deposits', function ($join) {
                    $join->on('cbox_member_boxes.device_id', '=', 'cbox_deposits.device_id');
                    $join->where('cbox_deposits.created_at', '>=' , date('Y-m-d', strtotime('monday this week')));
                })->select(
                    'users.email',
                    'users.weekly_mail_video',
                    'users.goal_weekly',
                    DB::raw('count(cbox_deposits.id) as deposit_count')
                )->where('roles.name', config('constants.MEMBER_USER'))
                ->where('parent_id', $org->id)
                ->groupBy('users.id')
                ->get();

            foreach ($members as $member) {
                $mailData = [
                 'weekly_goal'=>$member->goal_weekly,
                 'weekly_count' => $member->deposit_count,
                 'video'=> $member->weekly_mail_video
                ];

                Mail::send('mail/weekly_mail', $mailData, function ($message) use($member) {
                 $message->to($member->email)->subject('Millionmitzvot Weekly Digest');
                 $message->from('noreply@milionmitzvot.com', 'MilionMitzvot');
                });
            }
        }

        return [
            'success' => true
        ];
    }
}
