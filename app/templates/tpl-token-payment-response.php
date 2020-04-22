<h4>Token transaction response</h4>
<?php if (isset($response->threeDSecureUrl)) : ?>
<?php elseif (isset($response->status) && $response->status === 'error') : ?>
    <div class="row"><div class="col-12"><div class="alert alert-danger" role="alert">Error : <?=$response->message?></div></div></div>
<?php elseif (isset($response->transactionId)) : ?>
    <div class="row"><div class="col-12"><div class="alert alert-success" role="alert">OK</div></div></div>
    <p>Transaction ID : <?=$response->transactionId?></p>
    <p><a class="btn btn-primary" role="button" href="/payment-status.php?checkout-transaction-id=<?=$response->transactionId?>">Transaction status</a>

    <?php if (isset($isAuthorizationHold) &&  $isAuthorizationHold === true) : ?>
        <a class="btn btn-danger" role="button" href="token-payment-cancel.php?checkout-transaction-id=<?=$response->transactionId?>">Cancel</a>
        <a class="btn btn-success" role="button" href="token-payment-commit.php?checkout-transaction-id=<?=$response->transactionId?>">Commit</a>
    <?php endif; ?>

    <p>
<?php endif; ?>
