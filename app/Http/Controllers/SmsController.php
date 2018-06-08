<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use GuzzleHttp\Client;

use Illuminate\Support\Facades\Cache;
use \Carbon\Carbon;

use App\Models\Report;
use App\Models\Order;
use Log;


// use File;
// session_start();
class SmsController extends Controller
{
    private $SMS_SENDER = "Watpo";
    private $RESPONSE_TYPE = 'json';
    private $SMS_USERNAME;
    private $SMS_PASSWORD;
    private $DOMAIN_NAME;
    private $TIMEOUT = 1800;
    private $URL = "http://api.every8d.com/API21/HTTP/sendSMS.ashx";

    public function __construct()
    {
        $this->SMS_USERNAME = config('sms.user');
        $this->SMS_PASSWORD = config('sms.password');
        $this->DOMAIN_NAME = config('sms.domain_name');
    }

    public function schedulingSendReportSMS(){
        $sendQuiz = Report::where('status',0)->orWhere('status','-1')->get();
        foreach($sendQuiz as $mdata){
            $person_data = Order::where('id',$mdata->order_id)->get();
           
            if($person_data[0]->phone !== "現場客"){
                $report_url = $this->DOMAIN_NAME."report?jwt=".base64_encode($person_data[0]->id);
                $message = "親愛的".$person_data[0]->name."，感謝您本次於泰和殿的消費，希望您能撥冗為我們填寫意見函，您的寶貴意見，是我們最大的動力。 ".$report_url." 線上預約app ios:https://ez2o.co/8N8Lt android:https://ez2o.co/0N87b";
                                                                // $person_data[0]->phone
                $resp = $this->initiateSmsActivation($person_data[0]->name,$person_data[0]->phone, $message,null);
                if($resp['error']==0)
                    Report::where('order_id',$mdata->order_id)->update(['status'=>2]);
                else{
                    Log::info('Showing error sending log : '.$resp['message']);
                    Report::where('order_id',$mdata->order_id)->update(['status'=>-1]);
                }
      

            }
            else{
                Report::where('order_id',$mdata->order_id)->update(['status'=>1]);
            }

        }
    }


    public function send_SMS(Request $request)
    { 
        try{
			$name = $request->name;
            $phone = $request->phone;
            $CODE = substr(md5(rand()),0,4);
            $message = "歡迎蒞臨泰和殿，您的驗證碼為 ". $CODE . " 請於30分鐘內驗證完畢！";
            
            if(!Cache::has($phone))
                $resp = $this->initiateSmsActivation($name,$phone, $message,$CODE);
            else
                return response()->json(["status"=>1,"msg"=>"reEnter"]);    

            if($resp['error']==0)
                return response()->json(["status"=>0,"msg"=>"Success"]);
            else
                return response()->json(["status"=>2,"msg"=>$resp["message"]]);
		}
		catch(Exception $e){
			return response()->json([]);
		}
		catch(\Illuminate\Database\QueryException $e){
			return response()->json([]);
        }
    }

    public function check_Code(Request $request){
        
        try{
			$name = $request->name;
            $phone = $request->phone;
            $code = $request->code;
            
            // if($code == $request->session()->get($phone))
            if($code ==  Cache::get($phone))
                return response()->json(["status"=>0,"msg"=>"Success"]);
            else
                return response()->json(["status"=>1,"msg"=>"Error"]);
		}
		catch(Exception $e){
			return response()->json([]);
		}
		catch(\Illuminate\Database\QueryException $e){
			return response()->json([]);
        }
    }



    private function initiateSmsActivation($name,$phone_number, $message,$CODE){

        $GET_Data = ['UID'=> $this->SMS_USERNAME,
        'PWD'=> $this->SMS_PASSWORD,
        'MSG'=>$message,
        'sender'=>$this->SMS_SENDER,
        'DEST'=>$phone_number,
        'RESP'=>$this->RESPONSE_TYPE];
    
        
        $client = new Client();
        $res = $client->get($this->URL,["query"=>$GET_Data]);

        $isError = 0;
        $errorMessage = true;

        $expiresAt = Carbon::now()->addMinutes(30);
        if($CODE)
            Cache::put($phone_number, $CODE, $expiresAt);
        // Cache::put($phone_number."Time", time(), $expiresAt)

        
        // session([$phone_number=>$CODE]);
        // session([$phone_number."Time"=>time()]);
        //Preparing post parameters
       
        // $url = str_replace(" ", '%20', $url);
        // curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        // curl_setopt($ch, CURLOPT_URL, $url.$GET_Data);

        //get response
        // $output = curl_exec($ch);
        

        // //Print error if any
        // if (curl_errno($ch)) {
        //     $isError = true;
        //     $errorMessage = curl_error($ch);
        // }
        // curl_close($ch);

        $res = $res->getBody();
        // echo $res;
        if(substr($res.'',0,1) == "-"){
            return array('error' => 1 , 'message' => $res);
        }else{
            return array('error' => 0 );
        }

    }

   
}
