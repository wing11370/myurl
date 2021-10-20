<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CalculatorController extends Controller
{
    public function getCSRFToken(){return csrf_token();}

    public function getAllData()
    {
        $resultArray = DB::table('calculate_record')->get();
        $output = ([
                    "result"=>"success",
                    "message"=> "",
                    "data"=>$resultArray
                    ]);
        return response($output,200);
    }

    public function startCalc(Request $request)
    {
        $response = file_get_contents('https://tw.rter.info/capi.php');
        $response = json_decode($response,true);
        foreach ($response as $key=>$value){
            if (strlen($key)==6){echo substr($string = $key, $offset = 3)."\n";}
            
        };
        $currency = $request["currency"];

        // $USD2Other_rate = $response["USD".$currency]["Exrate"];
        // $USD2TWD_rate = $response["USDTWD"]["Exrate"];
        // $TWD2Other_rate = $USD2TWD_rate/$USD2Other_rate;
        $Other2TWD_rate = 1/(($response["USD".$currency]["Exrate"])/($response["USDTWD"]["Exrate"]));

        $price = $request["price"];
        
        if ($currency == 'JPY' or $currency == 'USD'){
            $discount = 0;
        }else{
            $discount = $request["discount"];
        }
        
        $result = ($price-$discount)*$Other2TWD_rate;
        $record_time = $response["USD".$currency]["UTC"];
        
        $resultArray = [ 
            "currency"=>$currency,
            "rate"=>$Other2TWD_rate,
            "price"=>$price,
            "discount"=>$discount,
            "result"=>$result
        ];
        // dd(type($resultArray));
        DB::table('calculate_record')->insert([
            "currency"=>$resultArray["currency"],
            "rate"=>$resultArray["rate"],
            "price"=>$resultArray["price"],
            "discount"=>$resultArray["discount"],
            "result"=>$resultArray["result"],
            "record_time"=>$record_time
        ]);

        echo '<!DOCTYPE html>
                <html lang="en">
                    <head>
                        <title>台幣匯率計算機</title>
                        <link rel="stylesheet" type="text/css" href="/style.css">
                    </head>
                    <body>
                        <div style="position:fixed;">
                            <form method="get" action="">
                                <label for="output" style="font-size: 2rem;">台幣結果:</label>
                                <br/>
                                <textarea name="output" id="output" cols="30" rows="10">';
                                    foreach($resultArray as $key => $value){
                                        echo $key.":".$value."\n";
                                    }
                                echo'</textarea>
                                <a href="..">回上一頁</a>
                            </form>
                        </div>
                    </body>
                </html>
                ';

        // return response($resultArray,200);
    }
    public function getDataFromDB(){
        return DB::table('calculate_record')->get();
    }
    // /**
    //  * Display a listing of the resource.
    //  *
    //  * @return \Illuminate\Http\Response
    //  */
    // public function index()
    // {
    //     //
    // }

    // /**
    //  * Show the form for creating a new resource.
    //  *
    //  * @return \Illuminate\Http\Response
    //  */
    // public function create()
    // {
    //     //
    // }

    // /**
    //  * Store a newly created resource in storage.
    //  *
    //  * @param  \Illuminate\Http\Request  $request
    //  * @return \Illuminate\Http\Response
    //  */
    // public function store(Request $request)
    // {
    //     // 
    // }

    // /**
    //  * Display the specified resource.
    //  *
    //  * @param  int  $id
    //  * @return \Illuminate\Http\Response
    //  */
    // public function show($id)
    // {
    //     //
    // }

    // /**
    //  * Show the form for editing the specified resource.
    //  *
    //  * @param  int  $id
    //  * @return \Illuminate\Http\Response
    //  */
    // public function edit($id)
    // {
    //     //
    // }

    // /**
    //  * Update the specified resource in storage.
    //  *
    //  * @param  \Illuminate\Http\Request  $request
    //  * @param  int  $id
    //  * @return \Illuminate\Http\Response
    //  */
    // public function update(Request $request, $id)
    // {
    //     //
    // }

    // /**
    //  * Remove the specified resource from storage.
    //  *
    //  * @param  int  $id
    //  * @return \Illuminate\Http\Response
    //  */
    // public function destroy($id)
    // {
    //     //
    // }
}
