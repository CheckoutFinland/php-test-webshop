<h4>Parsed response</h4>
<p>Payment buttons parsed from response. Test credentials and test credit cards can be found from <a href="https://checkoutfinland.github.io/psp-api/#/payment-method-providers" target="_blank">documentation</a>.</p>

<?php if (isset($response->terms)) : ?>
    <p>Payment terms :<br /><?php echo $response->terms;?></p>
<?php endif; ?>

<?php if (isset($response->reference)) : ?>
    <p>Reference : <?php echo $response->reference?></p>
<?php endif; ?>


<?php if (isset($response->href)) : ?>
    <p>Jump to  <a href="<?php echo $response->href?>">hosted payment wall</a></p>
<?php endif; ?>

<?php if (isset($response->providers)) : ?>
    <div class="paymentProviders">
        <?php foreach ($response->providers as $provider) : ?>
            <form method="POST" action="<?=$provider->url?>">
                <button class="paymentProviderbutton">
                    <?php foreach ($provider->parameters as $parameter) : ?>
                        <input type="hidden" name="<?=$parameter->name?>" value="<?=$parameter->value?>">
                    <?php endforeach; ?>
                    <img alt="<?=$provider->name?>" src="<?=(isset($provider->svg) ? $provider->svg : $provider->icon);?>" />
                </button>
            </form>
        <?php endforeach; ?>
    </div>
<?php endif; ?>


<?php if (isset($response->transactionId)) : ?>
<p>Transaction id : <?php echo $response->transactionId?> <a class="btn btn-primary btn-block" role="button" href="payment-status.php?checkout-transaction-id=<?=$response->transactionId?>">Check payment status</a></p>
<?php endif; ?>
