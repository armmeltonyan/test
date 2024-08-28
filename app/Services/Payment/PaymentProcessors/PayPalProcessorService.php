<?php

namespace App\Services\Payment\PaymentProcessors;

use App\Dto\PaymentIntentDto;
use App\Models\Payment;
use App\Models\PaymentMethod;
use App\Repositories\Payment\PaymentMethodRepository;
use App\Repositories\Payment\PaymentRepository;
use App\Repositories\User\UserRepository;
use App\Services\Payment\PaymentService;

class PayPalProcessorService extends PaymentService
{
    public function __construct(
        protected PaymentMethodRepository $paymentMethodRepository,
        protected PaymentRepository $paymentRepository,
        protected UserRepository $userRepository
    ) {
        $this->secret = env('STRIPE_SECRET');
        $this->baseUrl = env('STRIPE_BASE_URL');
    }

    public function pay(PaymentIntentDto $paymentIntentDto): ?Payment
    {
        // TODO: Implement pay() method.
    }

    public function attachPaymentMethod(string $paymentMethodId): ?PaymentMethod
    {
        // TODO: Implement attachPaymentMethod() method.
    }

    public function createCustomer(string $paymentMethodId): ?string
    {
        // TODO: Implement createCustomer() method.
    }
}
