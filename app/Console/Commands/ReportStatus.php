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
        $readyForQuiz = Order::where('status',5)->whereDate('end_time','<',Carbon::now())->where('name','test')->get();
        foreach($readyForQuiz as $mdata){
            $had_report = Report::where('order_id',$mdata->id);
            // if($had_report->get)
            $report = new Report;
            $report->order_id = $mdata->id;
            $report->status = 0;
            $report->save();
        }

        
    }
}