<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Repositories\Accounts;
use App\Repositories\Currencies;
use App\Traits\ResponseUtils;
use Illuminate\Http\JsonResponse;

class CurrencyController extends Controller
{
    use ResponseUtils;

    protected $currenciesRepository;

    public function __construct()
    {
        $this->currenciesRepository = new Currencies();
    }

    /**
     * Returns a list of all currencies
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $currenciesFetch =
            $this
                ->currenciesRepository
                ->all();

        if ($currenciesFetch->hasError()) {
            return $this->errorResponse(
                $currenciesFetch->getErrorMessage()
            );
        }

        return $this->successResponse($currenciesFetch->getItems());
    }
}
