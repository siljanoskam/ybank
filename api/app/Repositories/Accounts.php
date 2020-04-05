<?php

namespace App\Repositories;

use App\Http\Resources\Account as AccountResource;
use App\Models\Account;
use App\Traits\ModelOperations;
use Illuminate\Support\Facades\Log;

class Accounts extends Repository
{
    use ModelOperations;

    private $model;

    public function __construct()
    {
        $this->model = new Account();
    }

    public function all(): Repository
    {
        try {
            $accounts = $this->getAllModelItems($this->model);
            $accountsList = AccountResource::collection($accounts);
        } catch (\Exception $exception) {
            Log::error(
                'Something went wrong while getting the accounts from the database',
                [
                    'message' => $exception->getMessage()
                ]
            );
            $error = true;
            $errorMessage = $exception->getMessage();
        }

        return (new Repository())
            ->setItems($accountsList ?? [])
            ->setError($error ?? false)
            ->setErrorMessage($errorMessage ?? '');
    }

    public function store($data): Repository
    {
        try {
            $account = $this->storeModelItem($this->model, $data);
            $singleItem = new AccountResource($account);
        } catch (\Exception $exception) {
            Log::error(
                'Something went wrong while storing the account into the database',
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
            $account = $this->findModelItem($this->model, $id);
            $singleItem = new AccountResource($account);
        } catch (\Exception $exception) {
            Log::error(
                'Something went wrong while getting the account from the database',
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

    public function update($id, $data): Repository
    {
        try {
            $account = $this->updateModelItem($this->model, $id, $data);
            $singleItem = new AccountResource($account);
        } catch (\Exception $exception) {
            Log::error(
                'Something went wrong while updating the account into the database',
                [
                    'message' => $exception->getMessage(),
                    'id' => $id,
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

    public function destroy($id): Repository
    {
        try {
            $this->destroyModelItem($this->model, $id);
        } catch (\Exception $exception) {
            Log::error(
                'Something went wrong while removing the account from the database',
                [
                    'message' => $exception->getMessage(),
                    'id' => $id
                ]
            );
            $error = true;
        }

        return (new Repository())
            ->setError($error ?? false)
            ->setErrorMessage($errorMessage ?? '');
    }
}
