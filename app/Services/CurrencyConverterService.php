<?php

namespace App\Services;

use App\Models\FakeExchangeRate;
use Carbon\Carbon;
use DateTime;

class CurrencyConverterService
{

    public function convert($expenses, $userCurrency)
    {

      $expenseCopy = [...$expenses];
      if ($userCurrency === 'EUR') {
        return $expenses;
      }

      $convertedExpenses = [];
      $rates_in_date = $this->get_rates_in_date($expenses, $userCurrency);


      foreach ($expenseCopy as $key => $expense) {
        $expense_date = date('Y-m-d', strtotime($expense->created_at));
        $rightRate = [];
        $currency_key = strtolower('rate_' . $userCurrency);

        foreach ($rates_in_date as $rate) {
          if ($rate->date === $expense_date) {
            $rightRate = $rate;
          }
        }

        $expense->amount = strval($expense->amount * $rightRate[$currency_key]);
        $expense->currency = $userCurrency;

        array_push($convertedExpenses, $expense);
      }
      

      return $convertedExpenses;
    }

    function get_rates_in_date($expenses, $userCurrency) {
      $exchange_rates_in_dates = [];
        $supportedCurrencies = ['BRL', 'USD', 'EUR', 'GBP'];
        $userCurrency = strtoupper($userCurrency);
        $dates = $expenses->pluck('created_at')->toArray();

        foreach ($dates as $date) {
          $dataFormatada = date('Y-m-d', strtotime($date));
          $exchange_rate = FakeExchangeRate::where('date', $dataFormatada)->first();
          array_push($exchange_rates_in_dates, $exchange_rate);
        };

        if (!in_array($userCurrency, $supportedCurrencies)) {
          throw new \Exception('Currency not supported');
        }

        // Retorna o valor convertido
        return $exchange_rates_in_dates;
    }

}
