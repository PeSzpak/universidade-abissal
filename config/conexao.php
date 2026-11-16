<?php
// Conexão PDO única. Os valores padrão funcionam no XAMPP (root sem senha);
// para outro ambiente, defina DB_HOST, DB_PORT, DB_NAME, DB_USER e DB_PASS.
function conectar(): PDO
{
    static $pdo = null;

    if ($pdo === null) {
        $host   = getenv("DB_HOST") ?: "127.0.0.1";
        $porta  = getenv("DB_PORT") ?: "3306";
        $banco  = getenv("DB_NAME") ?: "universidade_abissal";
        $usuario = getenv("DB_USER") ?: "root";
        $senha  = getenv("DB_PASS") ?: "";

        $pdo = new PDO("mysql:host=$host;port=$porta;dbname=$banco;charset=utf8mb4", $usuario, $senha, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ]);
    }

    return $pdo;
}
