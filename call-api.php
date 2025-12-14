<?php
// predict_call.php
function call_ml_api($payload) {
    $url = "https://api-insightx-production.up.railway.app/predict";

    $ch = curl_init($url);
    $json = json_encode($payload);

    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $json);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [  
        "Content-Type: application/json",
        "Content-Length: " . strlen($json)
    ]);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10); // batas waktu

    $resp = curl_exec($ch);
    $err = curl_error($ch);
    $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($resp === false) {
        return ["error" => "curl error: $err"];
    }

    $decoded = json_decode($resp, true);
    if ($httpcode >= 400) {
        return ["error" => "API returned http $httpcode", "raw" => $decoded];
    }

    return $decoded;
}
?>