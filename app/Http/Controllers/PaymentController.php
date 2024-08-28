<?php

namespace App\Http\Controllers;

use App\Dto\PaymentIntentDto;
use App\Http\Controllers\v0\API\ApiController;
use App\Services\Payment\PaymentFactory;
use App\Services\Payment\PaymentService;
use Illuminate\Support\Facades\Request;

class PaymentController extends ApiController
{
    public function __construct(
        protected PaymentService $paymentService,
        // as a payment service we got StripeProcessorService as we bind them in PaymentServiceProvider
    ) {}

    public function pay(Request $request)
    {
        $paymentIntentDto = new PaymentIntentDto();
        $this->paymentService->pay($paymentIntentDto);
        //todo implement payment according to paymentService abstraction

        //factory implementation using
//        $paymentService = PaymentFactory::make($request->payment_provider);
//        $paymentIntent = $paymentService->pay($paymentIntentDto);
    }
}
