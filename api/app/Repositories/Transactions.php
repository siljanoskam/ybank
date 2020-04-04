<?php

namespace App\Repositories;

use App\Transaction;
use App\Http\Resources\Transaction as TransactionResource;
use App\Traits\ModelOperations;
use Illuminate\Support\Facades\Log;

class Transactions extends Repository
{
    use ModelOperations;

    private $model;

    public function __construct()
    {
        $this->model = new Transaction();

    }

    public function all(): Repository
    {
        try {
            $transactions = $this->getAllModelItems($this->model);
            $transactionsList = TransactionResource::collection($transactions);
        } catch (\Exception $exception) {
            Log::error(
                'Something went wrong while getting the transactions from the database',
                [
                    'message' => $exception->getMessage()
                ]
            );
            $error = true;
            $errorMessage = $exception->getMessage();
        }

        return (new Repository())
            ->setItems($transactionsList ?? [])
            ->setError($error ?? false)
            ->setErrorMessage($errorMessage ?? '');
    }

    public function allByAccount($accountId): Repository
    {
        try {
            $transactions =
                $this
                    ->getAllModelItems($this->model)
                    ->where('from', '=', $accountId)
                    ->orWhere('to', '=', $accountId);
            $transactionsList = TransactionResource::collection($transactions);
        } catch (\Exception $exception) {
            Log::error(
                'Something went wrong while getting the transactions from the database',
                [
                    'message' => $exception->getMessage()
                ]
            );
            $error = true;
            $errorMessage = $exception->getMessage();
        }

        return (new Repository())
            ->setItems($transactionsList ?? [])
            ->setError($error ?? false)
            ->setErrorMessage($errorMessage ?? '');
    }

    public function allMadeTransactionsByAccount($accountId): Repository
    {
        try {
            $transactions = $this->getAllWhere($this->model, 'from', '=', $accountId);
            $transactionsList = TransactionResource::collection($transactions);
        } catch (\Exception $exception) {
            Log::error(
                'Something went wrong while getting the made transactions from the database',
                [
                    'message' => $exception->getMessage(),
                    'accountId' => $accountId
                ]
            );
            $error = true;
            $errorMessage = $exception->getMessage();
        }

        return (new Repository())
            ->setItems($transactionsList ?? [])
            ->setError($error ?? false)
            ->setErrorMessage($errorMessage ?? '');
    }

    public function allReceivedTransactionsByAccount($accountId): Repository
    {
        try {
            $transactions = $this->getAllWhere($this->model, 'to', '=', $accountId);
            $transactionsList = TransactionResource::collection($transactions);
        } catch (\Exception $exception) {
            Log::error(
                'Something went wrong while getting the received transactions from the database',
                [
                    'message' => $exception->getMessage(),
                    'accountId' => $accountId
                ]
            );
            $error = true;
            $errorMessage = $exception->getMessage();
        }

        return (new Repository())
            ->setItems($transactionsList ?? [])
            ->setError($error ?? false)
            ->setErrorMessage($errorMessage ?? '');
    }

    public function store($data): Repository
    {
        try {
            $transaction = $this->storeModelItem($this->model, $data);
            $singleItem = new TransactionResource($transaction);
        } catch (\Exception $exception) {
            Log::error(
                'Something went wrong while storing the transaction into the database',
                [
                    'message' => $exception->getMessage(),
                    'data' => $data
                ]
            );
            $error = true;
        }

        return (new Repository())
            ->setItems($singleItem ?? [])
            ->setError($error ?? false)
            ->setErrorMessage($errorMessage ?? '');
    }

    public function show($id): Repository
    {
        try {
            $transaction = $this->findModelItem($this->model, $id);
            $singleItem = new TransactionResource($transaction);
        } catch (\Exception $exception) {
            Log::error(
                'Something went wrong while getting the transaction from the database',
                [
                    'message' => $exception->getMessage(),
                    'id' => $id
                ]
            );
            $error = true;
        }

        return (new Repository())
            ->setItems($singleItem ?? [])
            ->setError($error ?? false)
            ->setErrorMessage($errorMessage ?? '');
    }
}
