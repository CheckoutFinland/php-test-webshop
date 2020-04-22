<?php
require_once 'src/helpers.php';

use function Helpers\formGroup;

require 'templates/tpl-header.php';
?>

<div class="row">
    <div class="col-lg-6 col-md-12">
        <?php echo formGroup('addCardForm', 'checkout-account', 'checkout-account', $signedAddCardParameters['checkout-account'], null, true);?>
        <?php echo formGroup('addCardForm', 'checkout-method', 'checkout-method', $signedAddCardParameters['checkout-method'], null, true);?>
        <?php echo formGroup('addCardForm', 'checkout-algorithm', 'checkout-algorithm', $signedAddCardParameters['checkout-algorithm'], null, true);?>
        <?php echo formGroup('addCardForm', 'checkout-timestamp', 'checkout-timestamp', $signedAddCardParameters['checkout-timestamp'], null, true);?>
        <?php echo formGroup('addCardForm', 'checkout-nonce', 'checkout-nonce', $signedAddCardParameters['checkout-nonce'], null, true);?>
    </div>
    <div class="col-lg-6 col-md-12">
        <?php echo formGroup('addCardForm', 'checkout-redirect-success-url', 'checkout-redirect-success-url', $signedAddCardParameters['checkout-redirect-success-url'], null, true);?>
        <?php echo formGroup('addCardForm', 'checkout-redirect-cancel-url', 'checkout-redirect-cancel-url', $signedAddCardParameters['checkout-redirect-cancel-url'], null, true);?>
        <?php echo formGroup('addCardForm', 'language', 'language', $signedAddCardParameters['language'], "language is not taken into signature calculation due to not prefixed with 'checkout-'");?>
        <?php echo formGroup('addCardForm', 'signature', 'signature', $signedAddCardParameters['signature'], 'Signature calculated from form values. Changing signature naturally causes request to fail.');?>
    </div>
    <div class="col-12">
        <form id="addCardForm" action="<?php echo CO_API_LOCALHOST_URL . '/tokenization/addcard-form' ?>" method="post">
            <p><button type="submit" class="btn btn-primary">Send</button> Or refresh page to generate new values.</p>
        </form>
    </div>
</div>

<?php require 'templates/tpl-footer.php' ?>
