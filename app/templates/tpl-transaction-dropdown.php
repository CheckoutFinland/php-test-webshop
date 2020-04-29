<?php
require_once 'src/helpers.php';

use Helpers\createDropDownList;

?>

<a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown"
   aria-haspopup="true" aria-expanded="false">Transactions</a>

<div class="dropdown-menu">

    <?php

    $SelectedTransactionId = false;

    // transaction id from url parameters
    if (isset($_GET['checkout-transaction-id'])) {
        $SelectedTransactionId = $_GET['checkout-transaction-id'];
    } elseif (isset($response->transactionId)) {
        $SelectedTransactionId = $response->transactionId;
    }

    $dropDownHTML = '';

    $dropDownHTML .= Helpers\createDropDownList(
        $_SESSION['transactions']['normal'],
        'Normal',
        $SelectedTransactionId
    );
    $dropDownHTML .= Helpers\createDropDownList(
        $_SESSION['transactions']['sisPayment'],
        'Shop in shop',
        $SelectedTransactionId
    );
    $dropDownHTML .= Helpers\createDropDownList(
        $_SESSION['transactions']['charge'],
        'Token charges',
        $SelectedTransactionId
    );
    $dropDownHTML .= Helpers\createDropDownList(
        $_SESSION['transactions']['authorizationHold'],
        'Token authorization holds',
        $SelectedTransactionId
    );
    $dropDownHTML .= Helpers\createDropDownList(
        $_SESSION['transactions']['refund'],
        'Refunds',
        $SelectedTransactionId
    );

    echo $dropDownHTML;

    ?>

    <?php if ($dropDownHTML === '') : ?>
        <h6 class="dropdown-header">There is no transactions yet...</h6>
    <?php else : ?>

    <?php endif; ?>
</div>
