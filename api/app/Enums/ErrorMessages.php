<?php

namespace App\Enums;

abstract class ErrorMessages extends StdEnum
{
    const LOW_BALANCE = 'The balance of the sender account is low i.e. it does not have enough money to complete the transaction.';
}
