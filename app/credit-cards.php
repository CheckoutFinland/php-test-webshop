<?php

use Helpers\CreditCards;

require_once 'config/configuration.php';
require_once 'src/helpers.php';

$pageMainHeader = 'Test credit cards';
$creditCards = new CreditCards();

require 'templates/tpl-header.php';
?>

<div class="row">
  <div class="col-12">
    <?php
        echo $creditCards->getTable();
    ?>
  </div>
</div>

<?php
require 'templates/tpl-footer.php';
?>
