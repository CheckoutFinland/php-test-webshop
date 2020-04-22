<?php require 'templates/tpl-header.php'; ?>

<?php if (isset($_GET['checkout-tokenization-id'])) : ?>
    <div class="row">
        <div class="col-sm-6">
            <?php include 'tpl-request-payload-and-response.php'; ?>
        </div>
        <div class="col-sm-6">
            <?php if (isset($response->token)) : ?>
                <h5>Got token</h5>
                <p><?=$response->token?></p>
                <h5>Create a payment with token</h5>
                <p>
                    <a class="btn btn-primary" role="button" href="token-payment.php?type=cit&token=<?=$response->token?>">CIT payment</a>
                    <a class="btn btn-primary" role="button" href="token-payment.php?type=mit&token=<?=$response->token?>">MIT payment</a>
                </p>
                <h5>Create an authorization hold</h5>
                <p>
                    <a class="btn btn-primary" role="button" href="token-payment.php?authorization-hold=true&type=cit&token=<?=$response->token?>">CIT authorization hold</a>
                    <a class="btn btn-primary" role="button" href="token-payment.php?authorization-hold=true&type=mit&token=<?=$response->token?>">MIT authorization hold</a>
                </p>
            <?php elseif (isset($response->status)) : ?>
                <h5><?=$response->status?></h5>
                <p><?=$response->message?></p>
            <?php endif; ?>
        </div>
    </div>
<?php else : ?>
    <div class="row">
        <div class="col-12 alert alert-info" role="alert">Nothing to tokenize...</div>
        <div class="col-12"><p>Maybe <a href="/token-add-card.php">add a card<a/> first or try  <a href="/credit-cards.php">these<a/></p></div>

        <?php include 'tpl-tokenization-form.php'; ?>

    </div>
<?php endif; ?>

<?php require 'templates/tpl-footer.php'; ?>
