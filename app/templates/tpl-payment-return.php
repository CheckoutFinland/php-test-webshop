<?php
require 'templates/tpl-header.php';
require_once 'src/helpers.php';
?>

<div class="row">
    <div class="col-6">
        <?php echo Helpers\printGetParameter('checkout-account') ?>
        <?php echo Helpers\printGetParameter('checkout-algorithm') ?>
        <?php echo Helpers\printGetParameter('checkout-amount') ?>
        <?php echo Helpers\printGetParameter('checkout-stamp') ?>
    </div>
    <div class="col-6">
        <?php echo Helpers\printGetParameter('checkout-reference') ?>
        <?php echo Helpers\printGetParameter('checkout-transaction-id') ?>
        <?php echo Helpers\printGetParameter('checkout-status') ?>
        <?php echo Helpers\printGetParameter('checkout-provider') ?>
    </div>
</div>

<?php if (isset($_GET['checkout-transaction-id'])) : ?>
    <a class="btn btn-primary" role="button" href="/payment-status.php?checkout-transaction-id=<?php echo $_GET['checkout-transaction-id']?>">Check transaction status</a>
<?php endif; ?>

<?php

require 'templates/tpl-footer.php';
