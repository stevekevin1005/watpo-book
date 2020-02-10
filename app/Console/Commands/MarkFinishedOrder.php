<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Order;
use DB, Datetime;


class MarkFinishedOrder extends Command
{
    protected $signature = 'order:MarkFinishedOrder';

    protected $description = '[Mark] Mark finished order';

    public function __construct()
    {
        parent::__construct();
    }

    // Console 執行的程式
    public function handle()
    {
        DB::table('Order')
            ->where('end_time', '<', new Datetime())
            ->update(['is_finished' => true]);
    }
}