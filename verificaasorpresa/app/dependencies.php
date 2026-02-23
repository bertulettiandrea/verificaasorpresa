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
                // 10 FORNITORI
                $pdo->exec("INSERT INTO Fornitori VALUES 
                    ('F1', 'Acme Corp', 'Via Roma 1'), 
                    ('F2', 'Global Parts', 'Via Milano 10'), 
                    ('F3', 'Rossi Forniture', 'Via Napoli 5'),
                    ('F4', 'Ferramenta Bianchi', 'Via Torino 22'),
                    ('F5', 'Solo Rossi S.r.l.', 'Via Genova 8'),
                    ('F6', 'Tecno-Market', 'Viale Kennedy 100'),
                    ('F7', 'Utensileria Veneta', 'Via Padova 3'),
                    ('F8', 'Euro-Components', 'Boulevard Paris 15'),
                    ('F9', 'Alpha Supplies', 'Main Street 1'),
                    ('F10', 'Omega Tools', 'Second Street 2')");
                
                // 10 PEZZI (Vari colori)
                $pdo->exec("INSERT INTO Pezzi VALUES 
                    ('P1', 'Bullone', 'Rosso'), ('P2', 'Vite', 'Verde'), 
                    ('P3', 'Chiodo', 'Argento'), ('P4', 'Ingranaggio', 'Rosso'),
                    ('P5', 'Rondella', 'Blu'), ('P6', 'Dado', 'Verde'),
                    ('P7', 'Perno', 'Rosso'), ('P8', 'Molla', 'Giallo'),
                    ('P9', 'Piastra', 'Grigio'), ('P10', 'Gancio', 'Nero')");
                
                // CATALOGO (Circa 35 inserimenti per far funzionare tutto)
                $pdo->exec("INSERT INTO Catalogo VALUES 
                    -- Acme Corp (F1) fornisce TUTTO (per la Query 2)
                    ('F1', 'P1', 10.5), ('F1', 'P2', 5.0), ('F1', 'P3', 0.5), ('F1', 'P4', 45.0), ('F1', 'P5', 1.2),
                    ('F1', 'P6', 0.8), ('F1', 'P7', 3.5), ('F1', 'P8', 7.0), ('F1', 'P9', 12.0), ('F1', 'P10', 2.5),
                    
                    -- Global Parts (F2) fornisce quasi tutto
                    ('F2', 'P1', 9.5), ('F2', 'P2', 4.8), ('F2', 'P4', 42.0), ('F2', 'P5', 1.0), ('F2', 'P10', 2.0),
                    
                    -- Rossi Forniture (F3) fornisce i pezzi Rossi (per la Query 3)
                    ('F3', 'P1', 11.0), ('F3', 'P4', 46.0), ('F3', 'P7', 4.0),
                    
                    -- Solo Rossi S.r.l. (F5) fornisce SOLO pezzi Rossi (per la Query 9)
                    ('F5', 'P1', 10.0), ('F5', 'P4', 44.0), ('F5', 'P7', 3.8),
                    
                    -- Altri inserimenti sparsi per medie e conteggi
                    ('F4', 'P2', 5.5), ('F4', 'P6', 0.9), ('F6', 'P1', 12.0), ('F6', 'P9', 11.5),
                    ('F7', 'P3', 0.6), ('F7', 'P8', 6.5), ('F8', 'P5', 1.5), ('F9', 'P10', 3.0),
                    ('F10', 'P1', 10.0), ('F10', 'P2', 5.0)");
            }
            return $pdo;
        },
    ]);
};