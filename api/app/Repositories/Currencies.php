<?php

namespace App\Repositories;

use App\Http\Resources\Currency as CurrencyResource;
use App\Models\Currency;
use App\Traits\ModelOperations;
use Illuminate\Support\Facades\Log;

class Currencies extends Repository
{
    use ModelOperations;

    private $model;

    public function __construct()
    {
        $this->model = new Currency();

    }

    public function all(): Repository
    {
        try {
            $currencies = $this->getAllModelItems($this->model);
            $currenciesList = CurrencyResource::collection($currencies);
        } catch (\Exception $exception) {
            Log::error(
                'Something went wrong while getting the currencies from the database',
                [
                    'message' => $exception->getMessage()
                ]
            );
            $error = true;
            $errorMessage = $exception->getMessage();
        }

        return (new Repository())
            ->setItems($currenciesList ?? [])
            ->setError($error ?? false)
            ->setErrorMessage($errorMessage ?? '');
    }
}
