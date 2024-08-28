<?php

namespace App\Dto;

use Spatie\DataTransferObject\DataTransferObject;

class PaymentMethodDto extends DataTransferObject
{
    public int $user_id;
    public string $provider;
    public ?string $card_brand;
    public ?string $card_last_four;
    public ?int $exp_month;
    public ?int $exp_year;
    public ?string $provider_payment_method_id;
    public ?bool $is_default;
}
