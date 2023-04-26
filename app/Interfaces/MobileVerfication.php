<?php

namespace App\Interfaces;

interface MobileVerification
{
    public function sendMessage($ToMobileNumber, $code);
}
