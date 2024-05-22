<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");

if (isset($_GET['url'])) {
    $url = $_GET['url'];

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_TIMEOUT, 30);

    // Configuración adicional de DNS
    curl_setopt($ch, CURLOPT_DNS_USE_GLOBAL_CACHE, false);
    curl_setopt($ch, CURLOPT_DNS_CACHE_TIMEOUT, 2);
    curl_setopt($ch, CURLOPT_DNS_SERVERS, '8.8.8.8,8.8.4.4'); // Usar los DNS de Google

    // Opciones de autenticación (si es necesario)
    // curl_setopt($ch, CURLOPT_USERPWD, "username:password"); // Si el CRM requiere autenticación básica

    $response = curl_exec($ch);
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $content_type = curl_getinfo($ch, CURLINFO_CONTENT_TYPE);
    $curl_error = curl_error($ch);
    curl_close($ch);

    if ($curl_error) {
        echo "cURL Error: $curl_error";
    } elseif ($http_code == 200 && strpos($content_type, 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet') !== false) {
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="data.xlsx"');
        header('Content-Length: ' . strlen($response));
        echo $response;
    } else {
        echo "HTTP Error: $http_code. Content-Type: $content_type. Response: $response";
    }
} else {
    echo "No URL provided.";
}
?>
