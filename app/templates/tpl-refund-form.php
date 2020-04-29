<?php

$refundAmount = 100;

if (isset($response->amount) && $response->amount > 0) {
    $refundAmount = $response->amount;
} else if (isset($payload->amount) && $payload->amount > 0) {
    $refundAmount = $payload->amount;
}

?>

<?php ?>
<h5>Refund</h5>
<form class="form" action="./payment-refund.php" method="GET">
    <!-- Another variation with a button -->
    <div class="input-group">
        <input type="hidden" value="<?=$response->transactionId?>" name="checkout-transaction-id">
        <input type="text" class="form-control" placeholder="Refund amount" value="<?=$refundAmount?>" name="refund-amount">
        <div class="input-group-append">
            <button class="btn btn-outline-warning" type="submit">
                Refund
            </button>
        </div>
    </div>
</form>
