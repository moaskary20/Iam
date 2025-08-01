<?php

return [
    // إذا كانت fee_type = percent سيتم احتساب fee كنسبة مئوية من المبلغ
    // إذا كانت fee_type = fixed سيتم احتساب fee كمبلغ ثابت
    'withdraw_fee_type' => 'percent', // percent أو fixed
    'withdraw_fee' => 2.5, // إذا كانت percent: نسبة %، إذا كانت fixed: مبلغ ثابت
    'deposit_fee_type' => 'fixed',
    'deposit_fee' => 2.00,
];
