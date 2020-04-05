<?php

namespace App\Http\Controllers;

use App\Enums\ErrorCodes;
use App\Http\Requests\Transactions\StoreRequest;
use App\Repositories\Transactions;
use App\Traits\ResponseUtils;
use Illuminate\Http\JsonResponse;

class TransactionController extends Controller
{
    use ResponseUtils;

    protected $transactionsRepository;

    public function __construct()
    {
        $this->transactionsRepository = new Transactions();
    }

    /**
     * Returns a list of all transactions
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $transactionsFetch =
            $this
                ->transactionsRepository
                ->all();

        if ($transactionsFetch->hasError()) {
            return $this->errorResponse(
                $transactionsFetch->getErrorMessage()
            );
        }

        return $this->successListResponse($transactionsFetch->getItems()->all());
    }

    /**
     * Returns a list of all transactions of the account with Id as a parameter
     *
     * @param int $accountId
     *
     * @return JsonResponse
     */
    public function getAllTransactionsByAccount($accountId): JsonResponse
    {
        $transactionsFetch =
            $this
                ->transactionsRepository
                ->allByAccount($accountId);

        if ($transactionsFetch->hasError()) {
            return $this->errorResponse(
                $transactionsFetch->getErrorMessage()
            );
        }

        return $this->successListResponse($transactionsFetch->getItems()->all());
    }

    /**
     * Returns a list of all transactions that were made from the account with Id as parameter
     *
     * @param int $accountId
     *
     * @return JsonResponse
     */
    public function getMadeTransactionsByAccount($accountId): JsonResponse
    {
        $transactionsFetch =
            $this
                ->transactionsRepository
                ->allMadeTransactionsByAccount($accountId);

        if ($transactionsFetch->hasError()) {
            return $this->errorResponse(
                $transactionsFetch->getErrorMessage()
            );
        }

        return $this->successListResponse($transactionsFetch->getItems()->all());
    }

    /**
     * Returns a list of all transactions that the account with Id as parameter received
     *
     * @param int $accountId
     *
     * @return JsonResponse
     */
    public function getReceivedTransactionsByAccount($accountId): JsonResponse
    {
        $transactionsFetch =
            $this
                ->transactionsRepository
                ->allReceivedTransactionsByAccount($accountId);

        if ($transactionsFetch->hasError()) {
            return $this->errorResponse(
                $transactionsFetch->getErrorMessage()
            );
        }

        return $this->successListResponse($transactionsFetch->getItems()->all());
    }

    /**
     * Stores a transaction and returns the stored item
     *
     * @param StoreRequest $request
     *
     * @return JsonResponse
     */
    public function store(StoreRequest $request): JsonResponse
    {
        $transactionsStore =
            $this
                ->transactionsRepository
                ->store($request->all());

        if ($transactionsStore->hasError()) {
            return $this->errorResponse(
                $transactionsStore->getErrorMessage()
            );
        }

        return $this->successResponse($transactionsStore->getItems());
    }

    /**
     * Returns the transaction with the Id as a param
     *
     * @param int $id
     *
     * @return JsonResponse
     */
    public function show($id): JsonResponse
    {
        $transactionFetch =
            $this
                ->transactionsRepository
                ->show($id);

        if ($transactionFetch->hasError()) {
            return $this->errorResponse(
                $transactionFetch->getErrorMessage(),
                ErrorCodes::STD400
            );
        }

        return $this->successResponse($transactionFetch->getItems());
    }
}
