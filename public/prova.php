<?php

// Definisci l'URL dell'API
$url = 'http://localhost:5000/api/coil/calculate';

// Definisci i dati da inviare alla API
$data = [
    "TAirIn" => 20,
    "TRHIn" => 80,
    "AirVolume" => 450,
    "TWIn" => 7,
    "TWOut" => 12,
    "MediType" => "W",
    "QConc" => 0,
    "QMedium" => 0
];

// Inizializza cURL
$ch = curl_init($url);

// Configura cURL per inviare una richiesta POST con i dati in formato JSON
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Content-Type: application/json',
    'Content-Length: ' . strlen(json_encode($data))
]);

// Esegui la richiesta e salva la risposta
$response = curl_exec($ch);

// Controlla se ci sono errori
if ($response === false) {
    $error = curl_error($ch);
    curl_close($ch);
    die('Errore cURL: ' . $error);
}

// Chiudi cURL
curl_close($ch);

// Decodifica la risposta JSON
$responseData = json_decode($response, true);

// Stampa la risposta
echo 'Risposta dall\'API: ' . $response;
