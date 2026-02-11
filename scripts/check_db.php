<?php
// Test de connexion à la base en lisant DATABASE_URL depuis .env.local ou .env
$envLocal = __DIR__ . '/../.env.local';
$envDefault = __DIR__ . '/../.env';
$databaseUrl = null;
foreach ([$envLocal, $envDefault] as $p) {
    if (file_exists($p)) {
        $c = file_get_contents($p);
        if (preg_match('/^DATABASE_URL\s*=\s*(.*)$/m', $c, $m)) {
            $val = trim($m[1]);
            if ((substr($val,0,1) === '"' && substr($val,-1) === '"') || (substr($val,0,1) === "'" && substr($val,-1) === "'")) {
                $val = substr($val,1,-1);
            }
            $databaseUrl = $val;
            break;
        }
    }
}
if (!$databaseUrl) {
    $databaseUrl = getenv('DATABASE_URL') ?: null;
}
if (!$databaseUrl) {
    fwrite(STDERR, "Erreur: DATABASE_URL introuvable dans .env.local/.env ou env vars\n");
    exit(2);
}
$parts = parse_url($databaseUrl);
if ($parts === false) {
    fwrite(STDERR, "Erreur: impossible d'analyser DATABASE_URL: {$databaseUrl}\n");
    exit(3);
}
$scheme = $parts['scheme'] ?? '';
try {
    if ($scheme === 'mysql' || $scheme === 'mysqli') {
        $user = $parts['user'] ?? null;
        $pass = $parts['pass'] ?? null;
        $host = $parts['host'] ?? '127.0.0.1';
        $port = $parts['port'] ?? null;
        $dbname = isset($parts['path']) ? ltrim($parts['path'],'/') : null;
        $charset = 'utf8mb4';
        if (!empty($parts['query'])) { parse_str($parts['query'],$q); if (!empty($q['charset'])) $charset = $q['charset']; }
        $dsn = "mysql:host={$host}";
        if (!empty($port)) $dsn .= ";port={$port}";
        if (!empty($dbname)) $dsn .= ";dbname={$dbname}";
        if (!empty($charset)) $dsn .= ";charset={$charset}";
        $pdo = new PDO($dsn, $user, $pass, [PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION]);
        $stmt = $pdo->query('SELECT 1');
        $ok = $stmt->fetchColumn();
        if ($ok) {
            echo "OK: connexion réussie à '{$dbname}' ({$host}" . (!empty($port)?":{$port}":"") . ")\n";
            exit(0);
        }
        echo "Avertissement: connexion établie mais test a échoué\n";
        exit(4);
    }
    if ($scheme === 'sqlite') {
        $path = $parts['path'] ?? null;
        $dsn = 'sqlite:' . $path;
        $pdo = new PDO($dsn, null, null, [PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION]);
        $stmt = $pdo->query('SELECT 1');
        if ($stmt->fetchColumn()) { echo "OK: connexion sqlite réussie\n"; exit(0); }
        exit(5);
    }
    fwrite(STDERR, "Erreur: scheme non supporté: {$scheme}\n");
    exit(6);
} catch (PDOException $e) {
    fwrite(STDERR, "Échec de connexion: " . $e->getMessage() . "\n");
    exit(1);
}
