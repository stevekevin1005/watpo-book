<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Order;
use App\Models\Report;
use File;

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
        $sendQuiz = Report::where('status',FALSE)->get();
        foreach($sendQuiz as $mdata){
            $person_data = Order::where('id',$mdata->order_id)->get();
            
            $report->order_id = $mdata->id;
            $report->status = FALSE;
            $report->save();
        }
        $log_file_path = storage_path('SMS_report.log');

        // 記錄當時的時間
        $log_info = [
            'date'=>date('Y-m-d H:i:s'),

            
        ];

        // 記錄 JSON 字串
        $log_info_json = json_encode($log_info) . "\r\n";

        // 記錄 Log
        File::append($log_file_path, $log_info_json);
        
    }
}