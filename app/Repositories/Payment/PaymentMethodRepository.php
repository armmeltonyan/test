<?php

namespace App\Repositories\Payment;

use App\Dto\PaymentMethodDto;
use App\Models\PaymentMethod;

class PaymentMethodRepository
{
    public function findByProviderPaymentId(string $providerPaymentMethodId): ?PaymentMethod
    {
        return PaymentMethod::where('provider_payment_method_id', $providerPaymentMethodId)->first();
    }

    public function create(PaymentMethodDto $paymentMethodDto): PaymentMethod
    {
        return PaymentMethod::create($paymentMethodDto->toArray());
    }
}
