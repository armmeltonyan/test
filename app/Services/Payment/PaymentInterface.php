<?php

namespace App\Services\Payment;

use App\Dto\PaymentIntentDto;
use App\Models\Payment;
use App\Models\PaymentMethod;

interface PaymentInterface
{
    public function pay(PaymentIntentDto $paymentIntentDto): ?Payment;
    public function attachPaymentMethod(string $paymentMethodId): ?PaymentMethod;
    public function createCustomer(string $paymentMethodId): ?string;
}
