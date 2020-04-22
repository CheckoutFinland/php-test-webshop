<h5>Request payload and response</h5>

<?php if (isset($payload->token)) : ?>
<p>Payment token : <?=$payload->token;?></p>
<?php endif; ?>

<div id="accordion">

    <?php if (isset($payload)) : ?>
    <div class="card">
        <div class="card-header" id="request">
            <h4 class="mb-0">
                <button class="btn btn-link collapsed" data-toggle="collapse" data-target="#collapse1" aria-expanded="false" aria-controls="collapse1">
                    Request payload
                </button>
            </h4>
        </div>
        <div id="collapse1" class="collapse" aria-labelledby="request" data-parent="#accordion">
            <div class="card-body">
                <pre style="font-size: .6em;"><code><?=json_encode($payload, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES)?></code></pre>
            </div>
        </div>
    </div>
    <?php else : ?>
    <p>(Payload may not exist for GET request or requests that will need no payload)</p>
    <?php endif; ?>

    <?php if (isset($response)) : ?>
    <div class="card">
        <div class="card-header" id="response">
            <h4 class="mb-0">
                <button class="btn btn-link collapsed" data-toggle="collapse" data-target="#collapse2" aria-expanded="false" aria-controls="collapse2">
                    Response
                </button>
            </h4>
        </div>
        <div id="collapse2" class="collapse" aria-labelledby="response" data-parent="#accordion">
            <div class="card-body">
                <pre style="font-size: .6em;"><code><?=json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES)?></code></pre>
            </div>
        </div>
    </div>
    <?php endif; ?>

</div>
