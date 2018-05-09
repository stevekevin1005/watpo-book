<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\BlackList;

class unlockBlackList extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'blackList:unlock';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Unlock more than three months without overtime guests';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }
// ->where('status', 1)->where('overtime', '>=', 5)
    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $blackList = BlackList::where('description', null)->get();
        foreach ($blackList as $key => $user) {
            $diff_month = floor((strtotime(Date('Y-m-d H:i:s')) - strtotime($user->updated_at))/(60*60*24*30));
            if($diff_month >= 3){
                $user->status = 0;
                $user->overtime = 0;
                $user->save();
            }
        }
    }
}
