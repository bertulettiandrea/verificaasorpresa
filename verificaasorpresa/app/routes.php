<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\App;

return function (App $app) {

    // QUERY 1: Trovare i pnome dei pezzi per cui esiste un qualche fornitore
    $app->get('/1', function (Request $request, Response $response) {
        $db = $this->get(PDO::class);

        // SQL basato esattamente sulle tabelle del tuo dump (Pezzi e Catalogo)
        $sql = "SELECT DISTINCT P.pnome 
                FROM Pezzi P 
                INNER JOIN Catalogo C ON P.pid = C.pid";

        try {
            $stmt = $db->query($sql);
            $result = $stmt->fetchAll();

            // Risposta in formato application/json come richiesto
            $response->getBody()->write(json_encode($result));
            return $response
                ->withHeader('Content-Type', 'application/json')
                ->withStatus(200);

        } catch (PDOException $e) {
            $response->getBody()->write(json_encode(["error" => $e->getMessage()]));
            return $response->withStatus(500);
        }
    });
};