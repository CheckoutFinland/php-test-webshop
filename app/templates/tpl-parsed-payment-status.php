<?php if (isset($response->threeDSecureUrl)) : ?>
    <h5>3DS authentication required to continue payment</h5>
    <p>Complete 3DS authentication on :<br /><a href="<?= $response->threeDSecureUrl; ?>"><?= $response->threeDSecureUrl; ?></a></p>
<?php else : ?>
    <h5>Parsed response/status</h5>
    <table class="table">
        <?php if (isset($response->transactionId)) : ?>
            <tr>
                <th>Transaction&nbsp;Id</th>
                <td><?=str_replace('-', '&#8209;', $response->transactionId)?></td>
            </tr>
        <?php endif; ?>

        <?php if (isset($response->status)) : ?>
            <tr>
                <th>Status</th>
                <?php if ($response->status === 'new') : ?>
                    <td><span class="badge badge-primary"><?=$response->status?></span></td>
                <?php elseif ($response->status === 'ok') : ?>
                    <td><span class="badge badge-success"><?=$response->status?></span></td>
                <?php elseif ($response->status === 'fail' || $response->status === 'error') : ?>
                    <td><span class="badge badge-danger"><?=$response->status?></span></td>
                <?php elseif ($response->status === 'authorization-hold') : ?>
                    <td><span class="badge badge-secondary"><?=$response->status?></span></td>
                <?php else : ?>
                    <td><span class="badge badge-light"><?=$response->status?></span></td>
                <?php endif; ?>
            </tr>
        <?php endif; ?>

        <?php if (isset($response->message)) : ?>
            <tr>
                <th>Message</th>
                <td><?=$response->message?></td>
            </tr>
        <?php endif; ?>

        <?php if (isset($response->createdAt)) : ?>
            <tr>
                <th>Created&nbsp;at</th>
                <td><?=$response->createdAt?></td>
            </tr>
        <?php endif; ?>

        <?php if (isset($response->paidAt)) : ?>
            <tr>
                <th>Paid&nbsp;at</th>
                <td><?=$response->paidAt?></td>
            </tr>
        <?php endif; ?>

        <?php if (isset($response->amount)) : ?>
            <tr>
                <th>Amount</th>
                <td><?=$response->amount?></td>
            </tr>
        <?php endif; ?>

        <?php if (isset($response->currency)) : ?>
            <tr>
                <th>Currency</th>
                <td><?=$response->currency?></td>
            </tr>
        <?php endif; ?>

        <?php if (isset($response->reference)) : ?>
            <tr>
                <th>Reference</th>
                <td><?=$response->reference?></td>
            </tr>
        <?php endif; ?>

        <?php if (isset($response->stamp)) : ?>
            <tr>
                <th>Stamp</th>
                <td><?=$response->stamp?></td>
            </tr>
        <?php endif; ?>

        <?php if (isset($response->provider)) : ?>
            <tr>
                <th>Provider</th>
                <td><?=$response->provider?></td>
            </tr>
        <?php endif; ?>

        <?php if (isset($response->filingCode)) : ?>
            <tr>
                <th>Filing&nbsp;code</th>
                <td><?=$response->filingCode?></td>
            </tr>
        <?php endif; ?>

        <?php if (isset($response->href)) : ?>
            <tr>
                <th>Payment&nbsp;URL</th>
                <td><a href="<?=$response->href?>"><?=$response->href?></a></td>
            </tr>
        <?php endif; ?>
    </table>

    <?php if (isset($isAuthorizationHold) && $isAuthorizationHold === true && isset($response->transactionId)) : ?>
        <h5>Actions for authorization hold</h5>
        <p>
            <div class="btn-group btn-block" role="group" aria-label="Basic example">
                <a class="btn btn-warning" role="button" href="token-payment-cancel.php?checkout-transaction-id=<?=$response->transactionId?>">Cancel</a>
                <a class="btn btn-success" role="button" href="token-payment-commit.php?checkout-transaction-id=<?=$response->transactionId?>">Commit</a>
            </div>
        </p>
    <?php endif; ?>

    <?php if (isset($response->transactionId)) : ?>
        <h5>Check payment status</h5>
        <p><a class="btn btn-primary" role="button" href="payment-status.php?checkout-transaction-id=<?=$response->transactionId?>">Check payment status</a></p>
    <?php elseif (isset($_GET['checkout-transaction-id'])) : ?>
        <h5>Check payment status</h5>
        <p><a class="btn btn-primary" role="button" href="payment-status.php?checkout-transaction-id=<?=$_GET['checkout-transaction-id']?>">Check payment status</a></p>
    <?php endif; ?>

    <?php if (isset($response->transactionId)) {
     include 'tpl-refund-form.php';
    }
    ?>

<?php endif; ?>
