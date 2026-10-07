<?php

header("Content-Type: application/json");

$redisHost = getenv("REDIS_HOST") ?: "redis";
$redisPort = getenv("REDIS_PORT") ?: 6379;

try {

    $redis = new Redis();

    $redis->connect(
        $redisHost,
        (int)$redisPort
    );

} catch (Exception $e) {

    http_response_code(500);

    echo json_encode([
        "error" => "Impossibile collegarsi a Redis"
    ]);

    exit;
}


/*
|--------------------------------------------------------------------------
| Chiave giornaliera
|--------------------------------------------------------------------------
*/

$date = new DateTime(
    "now",
    new DateTimeZone("Europe/Zurich")
);

$dailyKey = "chat:" . $date->format("Y-m-d");


/*
|--------------------------------------------------------------------------
| GET -> leggi messaggi
|--------------------------------------------------------------------------
*/

if ($_SERVER["REQUEST_METHOD"] === "GET") {

    $messages = $redis->lRange(
        $dailyKey,
        0,
        -1
    );

    $result = [];

    foreach ($messages as $message) {

        $result[] = json_decode(
            $message,
            true
        );

    }

    echo json_encode($result);

    exit;
}


/*
|--------------------------------------------------------------------------
| POST -> salva messaggio
|--------------------------------------------------------------------------
*/

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $input = json_decode(
        file_get_contents("php://input"),
        true
    );

    $username = trim(
        $input["username"] ?? ""
    );

    $message = trim(
        $input["message"] ?? ""
    );


    if ($username === "" || $message === "") {

        http_response_code(400);

        echo json_encode([
            "error" => "Nome e messaggio obbligatori"
        ]);

        exit;
    }


    $data = [
        "username" => $username,
        "message" => $message,
        "time" => $date->format("H:i:s")
    ];


    $redis->rPush(
        $dailyKey,
        json_encode($data)
    );


    /*
    |--------------------------------------------------------------------------
    | Scadenza alla prossima mezzanotte
    |--------------------------------------------------------------------------
    */

    $tomorrow = clone $date;

    $tomorrow
        ->modify("+1 day")
        ->setTime(0, 0, 0);


    $secondsUntilMidnight =
        $tomorrow->getTimestamp()
        -
        $date->getTimestamp();


    $redis->expire(
        $dailyKey,
        $secondsUntilMidnight
    );


    echo json_encode([
        "success" => true
    ]);

    exit;
}


http_response_code(405);

echo json_encode([
    "error" => "Metodo non consentito"
]);