<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;

use App\Http\Requests;
use Illuminate\Support\Facades\Log;

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
    // private function encodeToken($id){
    //     $len = $id.length();
    //     return base64_encode( $id.md5(rand()).$len) ;
        
    // }
    // private function decodeToken($token){
    //     $decode = base64_decode($token);
    //     $len = substr($decode,-1);
    //     $id = substr($decode,0,$len);
    //     return $id;
    // }

    private function insertValidation(Request $request){
        $v = Validator::make($request->all(), [
            'jwt' => 'required|max:255',
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
        $readyForQuiz = Order::where('status',5)->whereDate('end_time','<',Carbon::now())->get();
        foreach($readyForQuiz as $mdata){
            $had_report = Report::where('order_id',$mdata->id);
            // if($had_report->get)
            $report = new Report;
            $report->order_id = $mdata->id;
            $report->status = 0;
            $report->save();
        }

    }

    public function sendReport(Request $request){
            
        $id = base64_decode($request->jwt);
        $query = Report::where('order_id', $id)->where('status','2')->first();

        // $is_order = Report::where('order_id', $request->id)->belongsToOrder;
        $is_order = Order::where('id',$id)->first();
        if( !$query || $this->insertValidation($request) == -1 || !$is_order){
            return response()->json([
                "res"=>-1,
                "validation"=>$this->insertValidation($request),
                "is_order"=>$is_order,
                "query"=>$query,
                "data"=>$request->all()
                ]);
        }
        else{
            // $report = new Report;
            $report = Report::where('order_id', $id)->update(
                ["q0" => $request->q0,
                "q1" => $request->q1,
                "q2" => $request->q2,
                "q3" => $request->q3,
                "q4" => $request->q4,
                "q5" => $request->q5,
                "q6" => $request->q6,
                "q7" => $request->q7,
                "status" => '3']
            );
            // $report->order_id = $id;
            // $report->q0 = $request->q0;
            // $report->q1 = $request->q1;
            // $report->q2 = $request->q2;
            // $report->q3 = $request->q3;
            // $report->q4 = $request->q4;
            // $report->q5 = $request->q5;
            // $report->q6 = $request->q6;
            // $report->q7 = $request->q7;
            // $report->status = '3';
            // $report->save();

            

            return response()->json(["res"=>1]);
        }

        
    }


    public function getQuiz(Request $request){
        try{
            if($request->jwt){
                $id = base64_decode( $request->jwt);
                $status = Report::where('order_id',$id)->get();
                if($status[0]->status == 2 )
                    return view('report');
                else
                    return view('report_finished');
                // return response()->json(["jwt"=>$segment]);
            }
            else{
                return view('report_finished');
            }
        }
        catch(Exception $e){
            return view('report_finished');
        }
        catch(\Illuminate\Database\QueryException $e){
            return view('report_finished');
        }
    }

    private function QuizIsFinished(){

    }
}
