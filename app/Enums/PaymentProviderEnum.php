<?php

namespace App\Enums;

enum PaymentProviderEnum: string
{
    case STRIPE = 'stripe';
    case PAYPAL = 'paypal';
}
