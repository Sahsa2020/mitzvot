<?php

namespace App\Console\Commands;

use DB;
use Illuminate\Console\Command;
use Mail;

class SendWeeklyEmail extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'email:weekly';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send Weekly Email Digest';

    /**
     * Create a new command instance.
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
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
    }
}
