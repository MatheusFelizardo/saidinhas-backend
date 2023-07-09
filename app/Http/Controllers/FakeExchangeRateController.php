<?php

namespace App\Http\Controllers;

use App\Models\FakeExchangeRate;
use Illuminate\Http\Request;

class FakeExchangeRateController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //get all exchange rates
        $exchangeRates = FakeExchangeRate::all();

        return response()->json($exchangeRates);

    }

    /**
     * Display the specified resource.
     */
    public function show(String $date)
    {   

        // get a single exchange rate by date
        $rate = FakeExchangeRate::where('date', $date)->first();

        if ($rate) {
            return response()->json($rate);
        }

        $rate = FakeExchangeRate::where('date', '2023-01-01')->first();

        return response()->json($rate);
    }


  

}
