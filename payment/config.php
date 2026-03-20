<?php
    require_once '../vendor/autoload.php';

    $stripe_secret_key = "sk_test_51T2oxoJRywxDTItB5zWaiAZyzhNvyqkTdo3aMpwIa9zbXpm7lgjuDCrSitnYKXWaEYAYIDUbRN0mJAHyQEIthQUH00zyZfpqfO";

    \Stripe\Stripe::setApiKey($stripe_secret_key);
?>