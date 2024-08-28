<?php

namespace App\Dto;

use Spatie\DataTransferObject\DataTransferObject;

class PaymentIntentDto extends DataTransferObject
{
    public string $payment_provider;
    public string $amount;
    public string $currency;
    public string $payment_method;
}
