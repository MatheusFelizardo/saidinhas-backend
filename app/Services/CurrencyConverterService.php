<?php

namespace App\Services;

class CurrencyConverterService
{
    public function convert($expenses, $userCurrency)
    {
        $supportedCurrencies = ['BRL', 'USD', 'EUR', 'GBP'];
        $userCurrency = strtoupper($userCurrency);

        if (!in_array($userCurrency, $supportedCurrencies)) {
          throw new \Exception('Currency not supported');
        }

        // Retorna o valor convertido
        return $expenses;
    }

}
