<?php
$passwords = [
    'OmYadavSuper2026',
    'manas2025',
    'Omyadav@5983',
    'OmYadav@5983'
];

foreach ($passwords as $p) {
    echo "Testing password: $p ...\n";
    try {
        $dsn = "pgsql:host=aws-1-ap-northeast-2.pooler.supabase.com;port=6543;dbname=postgres";
        $db = new PDO($dsn, 'postgres.pxnvbgrfowcpoysysmtd', $p, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_TIMEOUT => 5
        ]);
        echo "SUCCESS! The correct password is: $p\n\n";
        exit;
    } catch (Exception $e) {
        echo "FAILED: " . $e->getMessage() . "\n\n";
    }
}
