<?php
declare(strict_types=1);
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\App;

return function (App $app) {

    // 1. Nomi dei pezzi per cui esiste almeno un fornitore
    $app->get('/1', function (Request $request, Response $response) {
        $db = $this->get(\PDO::class);
        $res = $db->query("SELECT DISTINCT P.pnome FROM Pezzi P JOIN Catalogo C ON P.pid = C.pid")->fetchAll();
        $response->getBody()->write(json_encode($res));
        return $response->withHeader('Content-Type', 'application/json');
    });

    // 2. Nomi dei fornitori che forniscono OGNI pezzo
    $app->get('/2', function (Request $request, Response $response) {
        $db = $this->get(\PDO::class);
        $res = $db->query("SELECT F.fnome FROM Fornitori F WHERE NOT EXISTS (SELECT P.pid FROM Pezzi P EXCEPT SELECT C.pid FROM Catalogo C WHERE C.fid = F.fid)")->fetchAll();
        $response->getBody()->write(json_encode($res));
        return $response->withHeader('Content-Type', 'application/json');
    });

    // 3. Nomi dei fornitori che forniscono ogni pezzo ROSSO
    $app->get('/3', function (Request $request, Response $response) {
        $db = $this->get(\PDO::class);
        $res = $db->query("SELECT F.fnome FROM Fornitori F WHERE NOT EXISTS (SELECT P.pid FROM Pezzi P WHERE P.colore = 'Rosso' EXCEPT SELECT C.pid FROM Catalogo C WHERE C.fid = F.fid)")->fetchAll();
        $response->getBody()->write(json_encode($res));
        return $response->withHeader('Content-Type', 'application/json');
    });

    // 4. Nomi dei pezzi forniti da Acme Corp
    $app->get('/4', function (Request $request, Response $response) {
        $db = $this->get(\PDO::class);
        $res = $db->query("SELECT P.pnome FROM Pezzi P JOIN Catalogo C ON P.pid = C.pid JOIN Fornitori F ON C.fid = F.fid WHERE F.fnome = 'Acme Corp'")->fetchAll();
        $response->getBody()->write(json_encode($res));
        return $response->withHeader('Content-Type', 'application/json');
    });

    // 5. FID dei fornitori che forniscono almeno un pezzo rosso o verde
    $app->get('/5', function (Request $request, Response $response) {
        $db = $this->get(\PDO::class);
        $res = $db->query("SELECT DISTINCT C.fid FROM Catalogo C JOIN Pezzi P ON C.pid = P.pid WHERE P.colore IN ('Rosso', 'Verde')")->fetchAll();
        $response->getBody()->write(json_encode($res));
        return $response->withHeader('Content-Type', 'application/json');
    });

    // 6. PID dei pezzi forniti da almeno due fornitori diversi
    $app->get('/6', function (Request $request, Response $response) {
        $db = $this->get(\PDO::class);
        $res = $db->query("SELECT pid FROM Catalogo GROUP BY pid HAVING COUNT(fid) >= 2")->fetchAll();
        $response->getBody()->write(json_encode($res));
        return $response->withHeader('Content-Type', 'application/json');
    });

    // 7. PID del pezzo più costoso nel catalogo
    $app->get('/7', function (Request $request, Response $response) {
        $db = $this->get(\PDO::class);
        $res = $db->query("SELECT pid FROM Catalogo WHERE costo = (SELECT MAX(costo) FROM Catalogo)")->fetchAll();
        $response->getBody()->write(json_encode($res));
        return $response->withHeader('Content-Type', 'application/json');
    });

    // 8. Media dei costi dei pezzi forniti da Acme Corp
    $app->get('/8', function (Request $request, Response $response) {
        $db = $this->get(\PDO::class);
        $res = $db->query("SELECT AVG(costo) as media FROM Catalogo C JOIN Fornitori F ON C.fid = F.fid WHERE F.fnome = 'Acme Corp'")->fetchAll();
        $response->getBody()->write(json_encode($res));
        return $response->withHeader('Content-Type', 'application/json');
    });

    // 9. Nomi dei fornitori che forniscono solo pezzi Rossi
    $app->get('/9', function (Request $request, Response $response) {
        $db = $this->get(\PDO::class);
        $res = $db->query("SELECT fnome FROM Fornitori WHERE fid IN (SELECT fid FROM Catalogo JOIN Pezzi ON Catalogo.pid = Pezzi.pid GROUP BY fid HAVING SUM(CASE WHEN colore != 'Rosso' THEN 1 ELSE 0 END) = 0)")->fetchAll();
        $response->getBody()->write(json_encode($res));
        return $response->withHeader('Content-Type', 'application/json');
    });

    // 10. Lista pezzi con il nome del fornitore più economico per quel pezzo
    $app->get('/10', function (Request $request, Response $response) {
        $db = $this->get(\PDO::class);
        $res = $db->query("SELECT C.pid, F.fnome, MIN(C.costo) as prezzo_min FROM Catalogo C JOIN Fornitori F ON C.fid = F.fid GROUP BY C.pid")->fetchAll();
        $response->getBody()->write(json_encode($res));
        return $response->withHeader('Content-Type', 'application/json');
    });
};