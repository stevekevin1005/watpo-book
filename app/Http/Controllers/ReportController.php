<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;

use App\Http\Requests;

use App\Models\Report;
use App\Models\Order;
use App\Models\Service;
use App\Models\Shop;
use App\Models\Room;
use App\Models\ServiceProvider;
use Carbon\Carbon;

use File;

class ReportController extends Controller
{

    private function insertValidation(Request $request){
        $v = Validator::make($request->all(), [
            'id' => 'required|max:255',
            'q0' => 'required|max:255',
            'q1' => 'required|max:255',
            'q2' => 'required|max:255',
            'q3' => 'required|max:255',
            'q4' => 'required|max:255',
            'q5' => 'required|max:255',
            'q6' => 'required|max:255',
            'q7' => 'required|max:255',
        ]);
    
        if ($v->fails())
        {
            return -1;
        }
        else
            return 1;
    }

    
    public function FinishedService(){
        $readyForQuiz = Order::where('status',6)->get();
        foreach($readyForQuiz as $mdata){
            $report = new Report;
            $report->order_id = $mdata->id;
            $report->status = 0;
            $report->save();
        }
        $log_file_path = storage_path('FinishedService.log');

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

    public function sendReport(Request $request){
        $query = Report::where('order_id', $request->id)->first();
        // $is_order = Report::where('order_id', $request->id)->belongsToOrder;
        $is_order = Order::where('id',$request->id)->first();
        if( !$query->updated_time || $this->insertValidation($request) == -1 || !$is_order){
            return response()->json([
                "res"=>-1,
                "validation"=>$this->insertValidation($request),
                "is_order"=>$is_order
                ]);
        }
        else{
            $report = new Report;
            $report->order_id = $request->id;
            $report->q0 = $request->q0;
            $report->q1 = $request->q1;
            $report->q2 = $request->q2;
            $report->q3 = $request->q3;
            $report->q4 = $request->q4;
            $report->q5 = $request->q5;
            $report->q6 = $request->q6;
            $report->q7 = $request->q7;

            $report->save();

            $is_order->status = 8;
            $is_order->save();

            return response()->json(["res"=>1]);
        }

        
    }


    public function getQuiz(Request $request){
        try{
            $segment = base64_decode( $request->jwt);
            return response()->json(["jwt"=>$segment]);
        }
        catch(Exception $e){
            return response()->json([]);
        }
        catch(\Illuminate\Database\QueryException $e){
            return response()->json([]);
        }
    }

    private function QuizIsFinished(){

    }
}
