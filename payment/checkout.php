<?php
    require_once "config.php";

    $checkout_session = \Stripe\Checkout\Session::create([
        "mode" => "payment", //payment - vienreizejs maksajums; setup - subscriptions
        "success_url" => "https://kristovskis.lv/2023/markovs/it-support-cirkel/payment/success.php?session_id={CHECKOUT_SESSION_ID}",
        "cancel_url" => "https://kristovskis.lv/2023/markovs/it-support-cirkel",
        "locale" => "lv", //lv, en, auto
        "line_items" => [
            [
                "quantity" => 1,
                "price_data" => [
                    "currency" => "eur",
                    "unit_amount" => 4999, // 49,99 EUR
                    "product_data" => [
                        "name" => "PRO plāns (uz 1 mēnesi)"
                    ]
                ] 
            ]
        ]

    ]);

    header("Location: ".$checkout_session->url);
?>