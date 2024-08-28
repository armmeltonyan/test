<?php

namespace App\Repositories\Payment;

use App\Models\Payment;

class PaymentRepository
{
    public function create(array $paymentData): Payment
    {
        return Payment::create($paymentData);
    }
}
