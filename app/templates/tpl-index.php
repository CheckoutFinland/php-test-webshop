<?php require 'tpl-header.php'; ?>

<div class="row">
    <div class="col-12">
        <div class="row">
            <div class="col-12">
                <h5>Welcome</h5>
                <p>Here are some basic examples of API usage in PHP. See the API <a href="https://checkoutfinland.github.io/psp-api/#/" target="_blank">documentation</a>.</p>
            </div>
            <div class="col-lg-6 col-md-12">

                <div class="card" style="width: 100%;">
                    <div class="card-body">
                        <h5 class="card-title">Create payment</h5>
                        <p class="card-text">Create payments. Payment can be "normal" or Shop in shop.</p>
                        <p class="btn-group btn-block" role="group" aria-label="Basic example">
                            <a href="/payment.php" role="button" class="btn btn-light">Normal</a>
                            <a href="/payment.php?type=sis" role="button" class="btn btn-light">Shop in shop</a>
                        </p>
                    </div>
                </div>

                <div class="card" style="width: 100%;">
                    <div class="card-body">
                        <h5 class="card-title">Get transaction status</h5>
                        <p class="card-text">Get status for transactions made with this test shop</p>
                        <?php require 'tpl-transaction-dropdown.php'; ?>
                    </div>
                </div>

            </div>
            <div class="col-lg-6 col-md-12">

                <div class="card" style="width: 100%;">
                    <div class="card-body">
                        <h5 class="card-title">Tokenize credit cards</h5>
                        <p class="card-text">Add and tokenize test credit card.</p>
                        <p class="btn-group btn-block" role="group" aria-label="Basic example">
                            <a href="/credit-cards.php" role="button" class="btn btn-light">List test credit cards</a>
                            <a href="/token-add-card.php" role="button" class="btn btn-light">Add new card</a>
                        </p>
                    </div>
                </div>

                <div class="card" style="width: 100%;">
                    <div class="card-body">
                        <h5 class="card-title">Create token payment</h5>
                        <p class="card-text">Create payment with tokenized credit cards.</p>
                        <p class="btn-group btn-block" role="group" aria-label="Basic example">
                            <a href="/token-payment.php" role="button" class="btn btn-light">CIT Payment</a>
                            <a href="/token-payment.php?type=mit" role="button" class="btn btn-light">MIT payment</a>
                        </p>
                    </div>
                </div>

                <div class="card" style="width: 100%;">
                    <div class="card-body">
                        <h5 class="card-title">Create token authorization hold</h5>
                        <p class="card-text">Create authorization hold with tokenized credit card.</p>
                        <p class="btn-group btn-block" role="group" aria-label="Basic example">
                            <a href="/token-payment.php?authorization-hold=true" role="button" class="btn btn-light">CIT authorization hold</a>
                            <a href="/token-payment.php?authorization-hold=true&type=mit" role="button" class="btn btn-light">MIT authorization hold</a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>

<?php require 'tpl-footer.php'; ?>
