<?php

function validate($key, $fingerprint) {    
    $url = 'https://api.keygen.sh/v1/accounts/5c8527e9-edad-4ee6-8240-2c2b7ec16415/licenses/' . $key . '/actions/validate';
    
    $body = json_encode([
        'meta' => [
            'scope' => ['fingerprint' => $fingerprint]
        ]
    ]);
    
    $ch = curl_init();
    
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $body);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    
    $headers = [
        'Content-Type: application/vnd.api+json',
        'Accept: application/vnd.api+json',
        'Authorization: Bearer prod-c7d6796f33d445a4fb707cc6cdaf6c168981bd46d49518fb96709c29c1709e0bd6bd7d91c05b86852d655a10f77fc45c16357176cffd70b33d7922a0dd65f2v2'
    ];
    
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    
    $response = curl_exec ($ch);
    $statusCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close ($ch);

    $validation = json_decode($response);
    
    if($statusCode == 200) {
        return (object) array(
            "key" => $key,
            "fingerprint" => $fingerprint,
            "id" => $validation->data->id,
            "expiry" => $validation->data->attributes->expiry
        );
    } else {
        return (object)array(
            "statusCode" => $statusCode,
            "body" => json_decode($response)
        );
    }
}

?>
<html>
    <head>
        <style>
            body {
                background-color: #f3f3f7;
                font-family: 'Open Sans', 'Helvetica Neue', Arial, Helvetica, sans-serif, Meiryo;
                font-size: 12px;
            }
        </style>
        <script>
            sketchup.setLicense(<?print(json_encode(validate($_GET["key"] ?: '', $_GET["fingerprint"] ?: '')))?>);
        </script>
    </head>
    <body>
        <div id="message" align="center">
            Validating License... 
        </div>
    </body>
</html>