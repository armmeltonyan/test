<?php

namespace App\Services\Payment\PaymentProcessors;

use App\Dto\PaymentIntentDto;
use App\Dto\PaymentMethodDto;
use App\Enums\PaymentProviderEnum;
use App\Models\Payment;
use App\Models\PaymentMethod;
use App\Repositories\Payment\PaymentMethodRepository;
use App\Repositories\Payment\PaymentRepository;
use App\Repositories\User\UserRepository;
use App\Services\Payment\PaymentService;
use Illuminate\Support\Facades\Log;
use Throwable;

class StripeProcessorService extends PaymentService
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
        if ($this->validatePaymentAmount($paymentIntentDto->amount)) {
            $response = $this->request()->post($this->baseUrl . '/payment_intents?' . http_build_query([
                    'amount' => $paymentIntentDto->amount,
                    'currency' => $paymentIntentDto->currency,
                    'payment_method' => $paymentIntentDto->payment_method,
                    'metadata' => [
                        'user_id' => auth()->id(),
                    ],
                ]));
            if ($response->successful()) {
                $paymentIntent = $response->json();

                return $this->paymentRepository->create($paymentIntent);
            }
        }

        Log::error(__FUNCTION__, [$response->json()]);

        return null;
    }

    public function attachPaymentMethod(string $paymentMethodId): ?PaymentMethod
    {
        $paymentMethod = $this->paymentMethodRepository->findByProviderPaymentId($paymentMethodId);
        if (null === $paymentMethod) {
            try {
                $paymentMethod = $this->request()
                    ->post($this->baseUrl . "/payment_methods/{$paymentMethodId}/attach?" . http_build_query([
                            'customer' => auth()->user()->stripe_id ?? $this->createCustomer($paymentMethodId),
                        ]))->json();

                $paymentMethodDto = new PaymentMethodDto(
                    user_id: auth()->user()->id,
                    provider: PaymentProviderEnum::STRIPE->value,
                    card_brand: $paymentMethod['card']['brand'],
                    card_last_four: $paymentMethod['card']['last4'],
                    exp_month: $paymentMethod['card']['exp_month'],
                    exp_year: $paymentMethod['card']['exp_year'],
                    provider_payment_method_id: $paymentMethodId,
                    is_default: true,
                );
                $paymentMethod = $this->paymentMethodRepository->create($paymentMethodDto);
            } catch (Throwable $throwable) {
                Log::error(__FUNCTION__, [$throwable->getMessage()]);
            }
        }

        return $paymentMethod;
    }

    public function createCustomer(string $paymentMethodId): ?string
    {
        try {
            $response = $this->request()->post($this->baseUrl . '/customers', [
                'email' => auth()->user()->email,
                'name' => auth()->user()->name,
                'payment_method' => $paymentMethodId,
                'invoice_settings' => [
                    'default_payment_method' => $paymentMethodId,
                ],
            ]);
            $this->userRepository->update(auth()->id(), ['stripe_id' => $response->json()['id']]);

            return $response->json()['id'];

        } catch (Throwable $throwable) {
            Log::error(__FUNCTION__, [$throwable->getMessage()]);

            return null;
        }
    }
}
