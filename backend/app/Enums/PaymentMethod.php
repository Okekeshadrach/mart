<?php

namespace App\Enums;

enum PaymentMethod: string
{
    case Card = 'card';
    case Paypal = 'paypal';
    case CashOnDelivery = 'cod';
}
