<?php

namespace App\Repositories;

use App\Enums\ErrorMessages;
use App\Http\Resources\Transaction as TransactionResource;
use App\Models\Transaction;
use App\Traits\ModelOperations;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class Transactions extends Repository
{
    use ModelOperations;

    private $model;

    protected $accountsRepository;

    public function __construct()
    {
        $this->model = new Transaction();
        $this->accountsRepository = new Accounts();
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
                    ->model
                    ->where('from', '=', $accountId)
                    ->orWhere('to', '=', $accountId)
                    ->get();
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
            $account =
                $this
                    ->accountsRepository
                    ->show($accountId);

            $transactions =
                $account
                    ->getItems()
                    ->transactionsMade;

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
            $account =
                $this
                    ->accountsRepository
                    ->show($accountId);

            $transactions =
                $account
                    ->getItems()
                    ->transactionsReceived;

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
        DB::beginTransaction();

        try {
            $senderAccount =
                $this
                    ->accountsRepository
                    ->show($data['from'])
                    ->getItems();

            $receiverAccount =
                $this
                    ->accountsRepository
                    ->show($data['to'])
                    ->getItems();

            // We check if the sender account has enough big balance to do the current transaction
            if ($senderAccount->balance - $data['amount'] >= 0) {
                $transaction = $this->storeModelItem($this->model, $data);

                // We update the sender account balance
                // By subtracting the transaction amount from their balance
                $this
                    ->accountsRepository
                    ->update($senderAccount->id, [
                        'balance' => $senderAccount->balance - $transaction->amount
                    ]);

                // We update the receiver account balance
                // By increasing their balance with the amount of the transaction
                $this
                    ->accountsRepository
                    ->update($receiverAccount->id, [
                        'balance' => $receiverAccount->balance + $transaction->amount
                    ]);

                $singleItem = new TransactionResource($transaction);

                DB::commit();
            } else {
                $error = true;
                $errorMessage = ErrorMessages::LOW_BALANCE;
                DB::rollBack();
            }
        } catch (\Exception $exception) {
            Log::error(
                'Something went wrong while storing the transaction into the database',
                [
                    'message' => $exception->getMessage(),
                    'data' => $data
                ]
            );
            $error = true;
            DB::rollBack();
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
