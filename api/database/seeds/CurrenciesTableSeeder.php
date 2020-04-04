<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use PragmaRX\Countries\Package\Countries;

class CurrenciesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $countriesRepo = new Countries();
        $currencyList = $countriesRepo->all()->pluck('currencies');

        foreach ($currencyList as $currencyListItem) {
            $currentCurrencyListItem = $currencyListItem ? $currencyListItem->getItems() : [];

            if (!empty($currentCurrencyListItem)) {
                foreach ($currencyListItem->getItems() as $currency) {
                    DB::table('currencies')->insert([
                        'name' => $currency['name'] ?? $currency
                    ]);
                }
            }
        }
    }
}
