<?php

declare(strict_types=1);

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\App;

return function (App $app) {

    // Endpoint richiesto: /1
$app->get('/1', function (Request $request, Response $response) {
        // Usa \PDO::class con il backslash iniziale
        $db = $this->get(\PDO::class);

        $sql = "SELECT DISTINCT P.pnome 
                FROM Pezzi P 
                INNER JOIN Catalogo C ON P.pid = C.pid";

        try {
            $stmt = $db->query($sql);
            $data = $stmt->fetchAll();

            $response->getBody()->write(json_encode($data));
            return $response->withHeader('Content-Type', 'application/json');

        } catch (\Exception $e) {
            $response->getBody()->write(json_encode(["error" => $e->getMessage()]));
            return $response->withStatus(500)->withHeader('Content-Type', 'application/json');
        }
    });
};