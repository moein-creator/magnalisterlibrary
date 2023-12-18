<?php
$user = 'root';
$pass = 'root';
$db = 'shopify_prod_redgecko_magnalister_loc';
$charset = 'utf8mb4';




$shopName = $argv[1];

$dsn = "mysql:host=127.0.0.1;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_EMULATE_PREPARES   => false,
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
];
$pdo = new PDO($dsn, $user, $pass, $options);
try {
    $stmt = $pdo->prepare('DELETE FROM Customer WHERE CustomerId = ?');
    $stmt->execute([$shopName]);
    $stmt = null;
} catch (\Exception $e) {
    echo $e;
}

$shopName = preg_replace(getAllSpecialCharsPattern(), '_', $shopName);

try {
    $stmt = $pdo->query("DROP DATABASE " . $shopName);
    $stmt->execute();
    $stmt = null;
} catch (\Exception $e) {
    echo $e;
}

$shopName = preg_replace(getAllSpecialCharsPattern(), '-', $shopName);

try {
    exec("sudo rm -rf web/customers/" . $shopName);

} catch (\Exception $e) {
    echo $e;
}

function getAllSpecialCharsPattern() {
    return '![^a-z0-9]+!i';
}

echo "******************************";
echo "* Finished!                   *";
echo "******************************";


