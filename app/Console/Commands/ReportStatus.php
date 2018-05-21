<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Order;
use App\Models\Report;
use File;

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
        $readyForQuiz = Order::where('status',6)->get();
        foreach($readyForQuiz as $mdata){
            $report = new Report;
            $report->order_id = $mdata->id;
            $report->status = FALSE;
            $report->save();
        }
        $log_file_path = storage_path('test.log');

        // 記錄當時的時間
        $log_info = [
            'date'=>date('Y-m-d H:i:s'),
            'report_data'=>$readyForQuiz
        ];

        // 記錄 JSON 字串
        $log_info_json = json_encode($log_info) . "\r\n";

        // 記錄 Log
        File::append($log_file_path, $log_info_json);
        
    }
}