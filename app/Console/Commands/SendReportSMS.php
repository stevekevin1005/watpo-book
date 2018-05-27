<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Order;
use App\Models\Report;

// use File;

class SendReportSMS extends Command
{
    // 命令名稱
    protected $signature = 'report:send';

    protected $description = '[Send] Report SMS';


    public function __construct()
    {
        parent::__construct();

    }

    // Console 執行的程式
    public function handle()
    {
        app('App\Http\Controllers\SmsController')->schedulingSendReportSMS();
        
        
    }
}