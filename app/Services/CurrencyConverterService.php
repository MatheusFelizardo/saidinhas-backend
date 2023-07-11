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
          $formated_date = date('Y-m-d', strtotime($date));
          $exchange_rate = FakeExchangeRate::where('date', $formated_date)->first();
          array_push($exchange_rates_in_dates, $exchange_rate);
        };

        if (!in_array($userCurrency, $supportedCurrencies)) {
          throw new \Exception('Currency not supported');
        }

        // Retorna o valor convertido
        return $exchange_rates_in_dates;
    }
    

    function convert_amount_to_eur($amount, $currency, $date = null) {
      if ($date) {
        $formated_date = date('Y-m-d', strtotime($date));
      } else {
        $formated_date = date('Y-m-d');
      }

      $exchange_rate = FakeExchangeRate::where('date', $formated_date)->first();
      $currency = strtolower('rate_' . $currency);

      $amount = strval($amount / $exchange_rate[$currency]);
      return $amount;
    }

    function convert_amount_to_currency($amount, $currency, $date) {
      $formated_date = date('Y-m-d', strtotime($date));
      $exchange_rate = FakeExchangeRate::where('date', $formated_date)->first();
      $currency = strtolower('rate_' . $currency);

      $amount = strval($amount * $exchange_rate[$currency]);
      
      return $amount;
    }

    function format_currency_after_save($expense, $currency) {
      $converted_amount = $this->convert_amount_to_currency($expense['amount'], $currency, $expense['created_at']);
      $parsedExpense = [
        ...$expense,
        'amount' => $converted_amount,
        'currency' => $currency
      ];
      return $parsedExpense;
    }

    // function rounded_amout($amount) {
    //   $digits = substr($amount, strpos($amount, '.') + 3);
    //   if (intval($digits) > 5) {
    //     return ceil($amount);
    //   } 
    //   return $amount;
    // }
}
