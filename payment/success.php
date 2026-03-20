<?php
    if(!empty($_GET['session_id'])){
        session_start();
        require_once "config.php";
        require "../database/db_config.php";

        try{
            $checkout_session = \Stripe\Checkout\Session::retrieve($_GET['session_id']);
            $customer_email = $checkout_session->customer_details->email;
            
            $paymentIntent = \Stripe\PaymentIntent::retrieve($checkout_session->payment_intent);

            if($paymentIntent->status === 'succeeded'){
                $transaction_id = $paymentIntent->id;


                $laiks = date("Y-m-d H:i:s");
                $termins = date("Y-m-d H:i:s", strtotime("+30 days"));

                // ieliek pro lietotāju db
                $sql = $savienojums->prepare("INSERT INTO IT_pro_lietotaji (epasts, maksajuma_reference, laiks, termins)
                VALUES (?, ?, ?, ?)"); //drosibas del jaieliek ?,un pēctam jāzimanto bind_param()
                $sql->bind_param("ssss", $customer_email, $transaction_id, $laiks, $termins); /*katram ? ir burts un mainīgais
                (s - string,
                d - double,
                i - int,
                b - binary)*/
                $sql->execute();
                $sql->close();


                $_SESSION['pazinojums'] = "
                    <h2 data-lang-key = 'success_payment1'></h2>
                    <p data-lang-key = 'success_payment2'><b>$customer_email</b></p>
                    <p data-lang-key = 'success_payment3'></p>";
            }else{
               'Problēmas ar maksājuma asptrādi!';
            }
        }catch(Exception $e){
            $_SESSION['pazinojums'] = "Nav iespējams iegūt maksājuma informāciju: ".$e->getMessage();
        }
    }

    header("Location: ../")
?>

<!-- $_SESSION["pazinojums"] = '<span data-lang-key="pazinojums"></span>'; -->