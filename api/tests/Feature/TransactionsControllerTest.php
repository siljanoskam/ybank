<?php

namespace Tests\Feature;

use App\Enums\ErrorCodes;
use App\Models\Account;
use App\Models\Transaction;
use App\Models\User;
use Tests\MigrateFreshSeedOnce;
use Tests\TestCase;

class TransactionsControllerTest extends TestCase
{
    use MigrateFreshSeedOnce;

    public function testTransactionsList()
    {
        $this
            ->call('GET', route('transactions.list'))
            ->assertSuccessful()
            ->assertJsonMissing(['errors'])
            ->assertJsonStructure(['items', 'warnings']);
    }

    public function testAllTransactionsByAccount()
    {
        // Get the last created account Id i.e. the one we created with the CREATE Account test
        $mockAccountId = Account::latest()->first()->id;

        $this
            ->call('GET', route('account-transactions.list', ['id' => $mockAccountId]))
            ->assertSuccessful()
            ->assertJsonMissing(['errors'])
            ->assertJsonStructure(['items', 'warnings']);
    }

    public function testAllTransactionsThatWereMadeFromAnAccount()
    {
        // Get the last created account Id i.e. the one we created with the CREATE Account test
        $mockAccountId = Account::latest()->first()->id;

        $this
            ->call('GET', route('account-transactions-made.list', ['id' => $mockAccountId]))
            ->assertSuccessful()
            ->assertJsonMissing(['errors'])
            ->assertJsonStructure(['items', 'warnings']);
    }

    public function testAllTransactionsThatWereReceivedByAccount()
    {
        // Get the last created account Id i.e. the one we created with the CREATE Account test
        $mockAccountId = Account::latest()->first()->id;

        $this
            ->call('GET', route('account-transactions-received.list', ['id' => $mockAccountId]))
            ->assertSuccessful()
            ->assertJsonMissing(['errors'])
            ->assertJsonStructure(['items', 'warnings']);
    }

    public function testTransactionCreateWithValidData()
    {
        $mockData = [
            'from' => 1,
            'to' => 2,
            'details' => 'Test transaction store',
            'amount' => 50
        ];

        $this
            ->call('POST', route('transactions.store'), $mockData)
            ->assertSuccessful()
            ->assertJsonMissing(['errors'])
            ->assertJsonStructure(['item']);
    }

    public function testTransactionCreateWithInvalidData()
    {
        $mockData = [
            'from' => 'invalid_id',
            'to' => 2,
            'details' => 'Test transaction store',
            'amount' => 'no_amount' // this is a valid amount because the seeder is defined to generate numbers >= 20.000
        ];

        $this
            ->call('POST', route('transactions.store'), $mockData)
            ->assertStatus(ErrorCodes::STD422)
            ->assertJsonStructure(['errors', 'warnings']);
    }

    public function testTransactionCreateWithEnoughBalanceAccount()
    {
        $mockData = [
            'from' => 1,
            'to' => 2,
            'details' => 'Test transaction store',
            'amount' => 6000 // this is a valid amount because the seeder is defined to generate numbers >= 20.000
        ];

        $this
            ->call('POST', route('transactions.store'), $mockData)
            ->assertSuccessful()
            ->assertJsonMissing(['errors'])
            ->assertJsonStructure(['item']);
    }

    public function testTransactionCreateWithLowBalanceAccount()
    {
        $mockData = [
            'from' => 2,
            'to' => 1,
            'details' => 'Test transaction store',
            'amount' => 100000 // this is not a valid amount because the seeder is defined to generate numbers <= 50.000
        ];

        $this
            ->call('POST', route('transactions.store'), $mockData)
            ->assertStatus(ErrorCodes::STD500)
            ->assertJsonStructure(['errors', 'warnings']);
    }

    public function testTransactionShowWithValidId()
    {
        // Get the last created account Id i.e. the one we created with the CREATE test above
        $mockId = Transaction::latest()->first()->id;

        $this
            ->call('GET', route('transactions.show', ['id' => $mockId]))
            ->assertSuccessful()
            ->assertJsonMissing(['errors'])
            ->assertJsonStructure(['item']);
    }

    public function testTransactionShowWithInvalidId()
    {
        $mockId = 'invalid_id';

        $this
            ->call('GET', route('transactions.show', ['id' => $mockId]))
            ->assertStatus(ErrorCodes::STD400)
            ->assertJsonStructure(['errors', 'warnings']);
    }
}
