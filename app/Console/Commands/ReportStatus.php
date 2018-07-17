<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Order;
use App\Models\Report;
use Carbon\Carbon;


class ReportStatus extends Command
{
    // 命令名稱
    protected $signature = 'report:status';

    protected $description = '[Write] Status 6 to Report table';

    public function __construct()
    {
        parent::__construct();
    }

    // Console 執行的程式
    public function handle()
    {
        echo Carbon::now();
        app('App\Http\Controllers\SmsController')->schedulingSendReportSMS();
    }
}