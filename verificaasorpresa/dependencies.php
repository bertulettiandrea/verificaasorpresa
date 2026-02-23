use PDO;
use Psr\Container\ContainerInterface;
// ...

return function (ContainerBuilder $containerBuilder) {
    $containerBuilder->addDefinitions([
        PDO::class => function (ContainerInterface $c) {
            // Questi dati devono corrispondere al tuo server locale (es. XAMPP)
            $host = 'localhost';
            $db   = 'nome_tuo_db'; // Il nome del database creato dal dump
            $user = 'root';
            $pass = ''; 
            
            $dsn = "mysql:host=$host;dbname=$db;charset=utf8mb4";
            return new PDO($dsn, $user, $pass, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            ]);
        },
    ]);
};