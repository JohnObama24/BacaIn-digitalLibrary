<?php

return [

    'max_borrowing_days' => env('MAX_BORROWING_DAYS', 14),

    'fine_per_day' => env('FINE_PER_DAY', 1000),

    'auto_return_ebooks' => env('AUTO_RETURN_EBOOKS', true),
];
