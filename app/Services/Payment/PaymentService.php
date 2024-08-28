<?php

namespace App\Services\Payment;

use App\Dto\PaymentIntentDto;
use App\Models\Payment;
use App\Models\PaymentMethod;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Http;

abstract class PaymentService implements PaymentInterface
{
    protected string $secret;
    protected string $baseUrl;

    protected function validatePaymentAmount(float $amount): bool
    {
        return $amount > 0;
    }

    abstract public function pay(PaymentIntentDto $paymentIntentDto): ?Payment;
    abstract public function attachPaymentMethod(string $paymentMethodId): ?PaymentMethod;
    abstract public function createCustomer(string $paymentMethodId): ?string;

    protected function request(): PendingRequest
    {
        return Http::withToken($this->secret)
            ->withHeaders(['Content-Type' => 'application/x-www-form-urlencoded']);
    }
}
