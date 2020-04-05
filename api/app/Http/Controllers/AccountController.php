<?php

namespace App\Http\Controllers;

use App\Enums\ErrorCodes;
use App\Http\Requests\Accounts\StoreRequest;
use App\Http\Requests\Accounts\UpdateRequest;
use App\Repositories\Accounts;
use App\Traits\ResponseUtils;
use Illuminate\Http\JsonResponse;

class AccountController extends Controller
{
    use ResponseUtils;

    protected $accountsRepository;

    public function __construct()
    {
        $this->accountsRepository = new Accounts();
    }

    /**
     * Returns a list of all accounts
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $accountsFetch =
            $this
                ->accountsRepository
                ->all();

        if ($accountsFetch->hasError()) {
            return $this->errorResponse(
                $accountsFetch->getErrorMessage()
            );
        }

        return $this->successListResponse($accountsFetch->getItems()->all());
    }

    /**
     * Stores an account and returns the stored item
     *
     * @param StoreRequest $request
     *
     * @return JsonResponse
     */
    public function store(StoreRequest $request): JsonResponse
    {
        $accountsStore =
            $this
                ->accountsRepository
                ->store($request->all());

        if ($accountsStore->hasError()) {
            return $this->errorResponse(
                $accountsStore->getErrorMessage()
            );
        }

        return $this->successResponse($accountsStore->getItems());
    }

    /**
     * Returns the account with the Id as a param
     *
     * @param int $id
     *
     * @return JsonResponse
     */
    public function show($id): JsonResponse
    {
        $accountFetch =
            $this
                ->accountsRepository
                ->show($id);

        if ($accountFetch->hasError()) {
            return $this->errorResponse(
                $accountFetch->getErrorMessage(),
                ErrorCodes::STD400
            );
        }

        return $this->successResponse($accountFetch->getItems());
    }

    /**
     * Updates an account and returns the updated item
     *
     * @param int $id
     * @param UpdateRequest $request
     *
     * @return JsonResponse
     */
    public function update($id, UpdateRequest $request): JsonResponse
    {
        $accountUpdate =
            $this
                ->accountsRepository
                ->update($id, $request->all());

        if ($accountUpdate->hasError()) {
            return $this->errorResponse(
                $accountUpdate->getErrorMessage()
            );
        }

        return $this->successResponse($accountUpdate->getItems());
    }

    /**
     * Removes the account with the Id as a param
     *
     * @param int $id
     *
     * @return JsonResponse
     */
    public function destroy($id): JsonResponse
    {
        $accountDestroy =
            $this
                ->accountsRepository
                ->destroy($id);

        if ($accountDestroy->hasError()) {
            return $this->errorResponse(
                $accountDestroy->getErrorMessage()
            );
        }

        return $this->successResponse($accountDestroy->getItems());
    }
}
