<?php
require_once '../configuration.php';
require_once SDK_ROOTPATH . '/../vendor/autoload.php';


//Payment capture
try {
    //Generating pcidss order to capture see more https://docs.fondy.eu/docs/page/4/
    $TestOrderData = [
        'order_id' => time(),
        'card_number' => '4444555511116666',
        'cvv2' => '333',
        'expiry_date' => '1232',
        'currency' => 'USD',
        'preauth' => 'Y',
        'amount' => 1000,
        'client_ip' => '127.2.2.1'
    ];
    //Call method to generate order for capture
    $captured_order_data = Cloudipsp\Pcidss::start($TestOrderData); //next order will be captured
    //Capture request always generated by merchant using host-to-host
    // Required param to capture it prev order_id see more https://docs.fondy.eu/docs/page/12/
    if ($captured_order_data->isApproved()) {// Checking if prev payment valid(signature)
        $dataToCapture = [
            'currency' => 'USD',
            'amount' => 1000,
            'order_id' => $TestOrderData['order_id']
        ];
        $capture_order = Cloudipsp\Order::capture($dataToCapture);
    }
    //getting returned data
    ?>
    <!doctype html>
    <html lang="en-US">
    <head>
        <meta charset="UTF-8">
        <title>Capture pre-purchase</title>
        <style>
            table tr td, table tr th {
                padding: 10px;
            }
        </style>
    </head>
    <body>
    <table style="margin: auto" border="1">
        <thead>
        <tr>
            <th style="text-align: center" colspan="2">Request to capture order</th>
        </tr>
        <tr>
            <th style="text-align: left"
                colspan="2"><?php printf("<pre>%s</pre>", json_encode(['request' => $dataToCapture], JSON_PRETTY_PRINT)) ?></th>
        </tr>
        </thead>
        <tbody>
        <tr>
            <td>Response status:</td>
            <td><?= $capture_order->getData()['response_status'] ?></td>
        </tr>
        <tr>
            <td>Normal response:</td>
            <td>
                <pre><?php print_r($capture_order->getData()); ?></pre>
            </td>
        </tr>
        <tr>
            <td>Check order is captured:</td>
            <td><?php var_dump($capture_order->isCaptured()); ?></td>
        </tr>
        </tbody>
    </table>
    </body>
    </html>
    <?php
} catch (\Exception $e) {
    echo "Fail: " . $e->getMessage();
}
