<?php
// Supabase REST API
$SUPABASE_URL = "https://siojdadwlkvmgounvwtr.supabase.co/";
$SUPABASE_API_KEY = "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJzdXBhYmFzZSIsInJlZiI6InNpb2pkYWR3bGt2bWdvdW52d3RyIiwicm9sZSI6ImFub24iLCJpYXQiOjE3NjQ0MzU4MDUsImV4cCI6MjA4MDAxMTgwNX0.qMS5tagetgWHz8LhJLdLtnSeICOqVSAYgDJ--h4h6DA";

// Fungsi request Supabase
function supabase($method, $table, $data = null, $filter = "")
{
    global $SUPABASE_URL, $SUPABASE_API_KEY;

    $url = "$SUPABASE_URL/rest/v1/$table$filter";
    $curl = curl_init($url);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

    $headers = [
        "apikey: $SUPABASE_API_KEY",
        "Authorization: Bearer $SUPABASE_API_KEY",
        "Content-Type: application/json",
        "Accept: application/json"
    ];

    if ($method === "POST") {
        $headers[] = "Prefer: return=representation";
    }

    curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($curl, CURLOPT_CUSTOMREQUEST, $method);

    if ($data !== null) {
        curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($data));
    }

    $response = curl_exec($curl);
    $http = curl_getinfo($curl, CURLINFO_HTTP_CODE);
    curl_close($curl);

    return [
        "data" => json_decode($response, true),
        "status" => $http
    ];
}
?>
