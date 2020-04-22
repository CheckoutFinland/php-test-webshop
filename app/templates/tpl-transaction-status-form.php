<form class="form-inline my-2 my-lg-0" action="tpl-payment-status.php" method="GET">
    <!-- Another variation with a button -->
    <div class="input-group">
        <input type="text" class="form-control" placeholder="Transaction id" name="checkout-transaction-id" value="<?=isset($transactionIdSearch)?$transactionIdSearch:''?>">
        <div class="input-group-append">
            <button class="btn btn-outline-success my-2 my-sm-0" type="submit">
                <i class="fa fa-search"></i>
            </button>
        </div>
    </div>
</form>
