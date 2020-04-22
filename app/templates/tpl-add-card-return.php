<?php require 'tpl-header.php' ?>

<div class="row">
    <div class="col-12">
        <?php echo Helpers\printGetParameter('checkout-account') ?>
        <?php echo Helpers\printGetParameter('checkout-algorithm') ?>
        <?php echo Helpers\printGetParameter('checkout-method') ?>
        <?php echo Helpers\printGetParameter('checkout-status') ?>
        <?php
        if ($_GET['checkout-status'] === 'ok') {
            echo Helpers\printGetParameter('checkout-tokenization-id');
        }
        ?>
        <?php echo Helpers\printGetParameter('signature') ?>
        <?php if ($_GET['checkout-tokenization-id']) {
            echo '<p><a class="btn btn-primary" role="button" href="credit-card-tokenization.php?checkout-tokenization-id=' . $_GET['checkout-tokenization-id'] . '">Continue to tokenization</a></p>';
        }
        ?>
    </div>
</div>

<?php require 'tpl-footer.php' ?>
