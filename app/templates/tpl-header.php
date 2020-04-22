<?php

$SelectedTransactionId = false;

// transaction id from url parameters
if (isset($_GET['checkout-transaction-id'])) {
    $SelectedTransactionId = $_GET['checkout-transaction-id'];
} elseif (isset($response->transactionId)) {
    $SelectedTransactionId = $response->transactionId;
}

?>
<html>
    <head>
    <title>Checkout Test Web Shop</title>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
        <link href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" integrity="sha384-wvfXpqpZZVQGK6TAh5PVlGOfQNHSoD2xbE+QkPxCAFlNEevoEH3Sl0sibVcOQVnN" crossorigin="anonymous">
        <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>

        <link rel="stylesheet" type="text/css" href="assets/css/main.css" />
    </head>
    <body>

    <nav class="navbar sticky-top navbar-expand-lg navbar-dark bg-dark justify-content-between">
        <a class="navbar-brand" href="<?=CO_SHOP_URL?>"><img src="/assets/images/cart.png" /> PHP test web shop</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse mx-auto order-0" id="navbarSupportedContent">
            <ul class="navbar-nav mr-auto">

                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        Payments
                    </a>
                    <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                        <a class="dropdown-item" href="/payment.php">Normal</a>
                        <a class="dropdown-item" href="/payment.php?type=sis">Shop in shop</a>
                    </div>
                </li>

                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        Token payments
                    </a>
                    <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                        <a class="dropdown-item" href="/credit-cards.php">Credit cards</a>
                        <a class="dropdown-item" href="/token-add-card.php">Tokenize a credit card</a>
                        <a class="dropdown-item" href="/token-payment.php">Create a CIT payment</a>
                        <a class="dropdown-item" href="/token-payment.php?type=mit">Create a MIT payment</a>
                        <a class="dropdown-item" href="/token-payment.php?authorization-hold=true">Create an CIT authorization hold</a>
                        <a class="dropdown-item" href="/token-payment.php?authorization-hold=true&type=mit">Create an MIT authorization hold</a>
                    </div>
                </li>

                <li class="nav-item dropdown">
                    <?php include 'tpl-transaction-dropdown.php'; ?>
                </li>
            </ul>
        </div>

        <div class="navbar-collapse collapse w-10 order-3 dual-collapse2">
          <ul class="navbar-nav ml-auto">
              <li class="nav-item">
                  <button type="button" class="btn btn-danger btn-sm" data-toggle="modal" data-target="#resetModal">
                      Reset shop
                  </button>
              </li>
          </ul>
        </div>

    </nav>

    <?php if (isset($pageMainHeader)) : ?>
    <div class="jumbotron text-center"><h1><?=$pageMainHeader?></h1></div>
    <?php endif; ?>

    <div class="modal fade" id="resetModal" tabindex="-1" role="dialog" aria-labelledby="resetModalTitle" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered" role="document">
          <div class="modal-content">
              <div class="modal-header">
                  <h5 class="modal-title" id="resetModalTitle">Reset session</h5>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                  </button>
              </div>
              <div class="modal-footer">
                  <button type="button" class="btn btn-success" data-dismiss="modal">Cancel</button>
                  <a href="/?resetWebShop=1" type="button" class="btn btn-danger">Yes</a>
              </div>
          </div>
      </div>
    </div>

    <div class="container">

    <?php if (isset($response->status) && $response->status === 'error') : ?>
        <h5>Error</h5>
        <div class="row"><div class="col-12"><div class="alert alert-danger" role="alert"><?=$response->message?></div></div></div>

        <?php if ($response->meta) : ?>
            <?php foreach ($response->meta as $meta) : ?>
                <div class="row"><div class="col-12"><div class="alert alert-secondary" role="alert">Meta : <?=$meta?></div></div></div>
            <?php endforeach; ?>
        <?php endif; ?>

    <?php endif; ?>


    <?php if (isset($alert)) : ?>
        <div class="row"><div class="col-12"><div class="alert alert-<?=$alert['type']?>" role="alert"><?=$alert['message']?></div></div></div>
    <?php endif; ?>
