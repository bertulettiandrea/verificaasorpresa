<?php
declare(strict_types=1);
use DI\ContainerBuilder;
use Psr\Container\ContainerInterface;

return function (ContainerBuilder $containerBuilder) {
    $containerBuilder->addDefinitions([
        \PDO::class => function (ContainerInterface $c) {
            // Percorso automatico
            $dbPath = __DIR__ . '/../db/database.db';
            
            // Se il file non esiste, lo crea al volo (automatico!)
            if (!file_exists($dbPath)) { touch($dbPath); }

            $pdo = new \PDO("sqlite:" . $dbPath);
            
            // Usiamo i numeri (19 = ATTR_ERRMODE, 3 = ERRMODE_EXCEPTION)
            $pdo->setAttribute(19, 3);
            $pdo->setAttribute(1000, 2); // 1000 = ATTR_DEFAULT_FETCH_MODE, 2 = FETCH_ASSOC

            // Creazione tabelle automatica se il DB è vuoto
            $pdo->exec("CREATE TABLE IF NOT EXISTS Pezzi (pid TEXT PRIMARY KEY, pnome TEXT, colore TEXT)");
            $pdo->exec("CREATE TABLE IF NOT EXISTS Catalogo (fid TEXT, pid TEXT, costo REAL, PRIMARY KEY(fid, pid))");
            
            // Inserimento dati di test se non ci sono (così l'endpoint non è vuoto)
            $count = $pdo->query("SELECT COUNT(*) FROM Pezzi")->fetchColumn();
            if ($count == 0) {
                $pdo->exec("INSERT INTO Pezzi VALUES ('P1', 'Bullone', 'Rosso'), ('P2', 'Vite', 'Verde')");
                $pdo->exec("INSERT INTO Catalogo VALUES ('F1', 'P1', 10.50), ('F2', 'P2', 8.00)");
            }

            return $pdo;
        },
    ]);
};