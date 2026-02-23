<?php
declare(strict_types=1);
use DI\ContainerBuilder;
use Psr\Container\ContainerInterface;

return function (ContainerBuilder $containerBuilder) {
    $containerBuilder->addDefinitions([
\PDO::class => function (ContainerInterface $c) {
            $dbPath = __DIR__ . '/../db/database.db';
            $pdo = new \PDO("sqlite:" . $dbPath);
            $pdo->setAttribute(19, 3); 
            $pdo->setAttribute(1000, 2);

            $pdo->exec("CREATE TABLE IF NOT EXISTS Fornitori (fid TEXT PRIMARY KEY, fnome TEXT, indirizzo TEXT)");
            $pdo->exec("CREATE TABLE IF NOT EXISTS Pezzi (pid TEXT PRIMARY KEY, pnome TEXT, colore TEXT)");
            $pdo->exec("CREATE TABLE IF NOT EXISTS Catalogo (fid TEXT, pid TEXT, costo REAL, PRIMARY KEY (fid, pid))");
            
            $check = $pdo->query("SELECT COUNT(*) FROM Pezzi")->fetchColumn();
            if ($check == 0) {
                // Fornitori con nomi veri
                $pdo->exec("INSERT INTO Fornitori VALUES ('F1', 'Acme Corporation', 'Via Roma 1'), ('F2', 'Elettro Forniture S.r.l.', 'Via Milano 10'), ('F3', 'Brico Casa', 'Via Napoli 5')");
                
                // Pezzi disponibili
                $pdo->exec("INSERT INTO Pezzi VALUES ('P1', 'Bullone', 'Rosso'), ('P2', 'Vite', 'Verde'), ('P3', 'Chiodo', 'Argento')");
                
                // CATALOGO
                // Acme Corporation (F1) vende TUTTO (P1, P2, P3). In /2 uscirà solo lui.
                $pdo->exec("INSERT INTO Catalogo VALUES ('F1', 'P1', 1.0), ('F1', 'P2', 2.0), ('F1', 'P3', 0.5)");
                
                // Elettro Forniture (F2) vende solo P1 e P2 (NON vende il Chiodo)
                $pdo->exec("INSERT INTO Catalogo VALUES ('F2', 'P1', 1.2), ('F2', 'P2', 1.8)");
                
                // Brico Casa (F3) vende solo P3
                $pdo->exec("INSERT INTO Catalogo VALUES ('F3', 'P3', 0.4)");
            }
            return $pdo;
        },
    ]);
};