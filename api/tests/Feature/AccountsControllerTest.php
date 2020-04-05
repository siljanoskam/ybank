<?php

namespace Tests\Feature;

use App\Enums\ErrorCodes;
use App\Models\Account;
use App\Models\User;
use Tests\MigrateFreshSeedOnce;
use Tests\TestCase;

class AccountsControllerTest extends TestCase
{
    use MigrateFreshSeedOnce;

    public function testAccountsList()
    {
        $this
            ->call('GET', route('accounts.list'))
            ->assertSuccessful()
            ->assertJsonMissing(['errors'])
            ->assertJsonStructure(['items', 'warnings']);
    }

    public function testAccountCreateWithValidData()
    {
        $mockData = [
            'name' => 'Test Account',
            'balance' => 20000,
            'user_id' => 2
        ];

        $this
            ->call('POST', route('accounts.store'), $mockData)
            ->assertSuccessful()
            ->assertJsonMissing(['errors'])
            ->assertJsonStructure(['item']);
    }

    public function testAccountCreateWithInvalidData()
    {
        $mockData = [
            'name' => 'Test Account',
            'balance' => 'invalid_balance',
            'user_id' => User::all()->random()->id
        ];

        $this
            ->call('POST', route('accounts.store'), $mockData)
            ->assertStatus(ErrorCodes::STD422)
            ->assertJsonStructure(['errors', 'warnings']);
    }

    public function testAccountShowWithValidId()
    {
        // Get the last created account Id i.e. the one we created with the CREATE test above
        $mockId = Account::latest()->first()->id;

        $this
            ->call('GET', route('accounts.show', ['id' => $mockId]))
            ->assertSuccessful()
            ->assertJsonMissing(['errors'])
            ->assertJsonStructure(['item']);
    }

    public function testAccountShowWithInvalidId()
    {
        $mockId = 'invalid_id';

        $this
            ->call('GET', route('accounts.show', ['id' => $mockId]))
            ->assertStatus(ErrorCodes::STD400)
            ->assertJsonStructure(['errors', 'warnings']);
    }

    public function testAccountUpdateWithValidData()
    {
        // Get the last created account Id i.e. the one we created with the CREATE test above
        $mockId = Account::latest()->first()->id;

        $mockData = [
            'name' => 'Test Account Updated',
            'balance' => 5000
        ];

        $this
            ->call('PUT', route('accounts.update', ['id' => $mockId]), $mockData)
            ->assertSuccessful()
            ->assertJsonMissing(['errors'])
            ->assertJsonStructure(['item']);
    }

    public function testAccountUpdateWithInvalidData()
    {
        // Get the last created account Id i.e. the one we created with the CREATE test above
        $mockId = Account::latest()->first()->id;

        $mockData = [
            'name' => 500,
            'balance' => 'invalid_balance'
        ];

        $this
            ->call('PUT', route('accounts.update', ['id' => $mockId]), $mockData)
            ->assertStatus(ErrorCodes::STD422)
            ->assertJsonStructure(['errors', 'warnings']);
    }

    public function testAccountDeleteWithValidId()
    {
        // Get the last created account Id i.e. the one we created with the CREATE test above
        $mockId = Account::latest()->first()->id;

        $this
            ->call('DELETE', route('accounts.destroy', ['id' => $mockId]))
            ->assertSuccessful();
    }

    public function testAccountDeleteInvalidId()
    {
        $mockId = 'invalid_id';

        $this
            ->call('DELETE', route('accounts.destroy', ['id' => $mockId]))
            ->assertStatus(ErrorCodes::STD500)
            ->assertJsonStructure(['errors', 'warnings']);
    }
}
