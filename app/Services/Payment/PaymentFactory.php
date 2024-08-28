<?php

namespace App\Services\Payment;

use App\Enums\PaymentProviderEnum;
use App\Services\Payment\PaymentProcessors\StripeProcessorService;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Testing\Exceptions\InvalidArgumentException;

class PaymentFactory
{
    /**
     * @throws BindingResolutionException
     */
    public static function make(string $provider): PaymentService
    {
        //todo use this factory if we want to accept payment from different payment providers in parallel
        return match($provider) {
            PaymentProviderEnum::STRIPE->value => app()->make(StripeProcessorService::class),
            //todo add payment processors according to requirments
            default => throw new InvalidArgumentException('Unsupported payment provider'),
        };
    }
}
