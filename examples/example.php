<?php

require '../vendor/autoload.php';

$rounder = new \Osmalek\Rounder\rounder\StandardRounder(
    1,
    [
        new \Osmalek\Rounder\digit\Digit(3),
        new \Osmalek\Rounder\digit\Digit(5),
        new \Osmalek\Rounder\digit\Digit(8),
    ]
);
$roundedValue = $rounder->round(12.40);
