<?php
// scripts/seed_pincodes.php - Seed test pincode data to Supabase
require_once dirname(__DIR__) . '/api/config.php';

$testPincodes = [
    ['pincode' => '400001', 'city' => 'Mumbai', 'state' => 'Maharashtra', 'is_serviceable' => true, 'partner' => 'Direct'],
    ['pincode' => '110001', 'city' => 'New Delhi', 'state' => 'Delhi', 'is_serviceable' => true, 'partner' => 'Direct'],
    ['pincode' => '560001', 'city' => 'Bengaluru', 'state' => 'Karnataka', 'is_serviceable' => true, 'partner' => 'Direct'],
    ['pincode' => '600001', 'city' => 'Chennai', 'state' => 'Tamil Nadu', 'is_serviceable' => true, 'partner' => 'Direct'],
    ['pincode' => '700001', 'city' => 'Kolkata', 'state' => 'West Bengal', 'is_serviceable' => true, 'partner' => 'Direct'],
    ['pincode' => '800001', 'city' => 'Patna', 'state' => 'Bihar', 'is_serviceable' => false, 'partner' => 'None']
];

echo "Seeding test pincodes...\n";

try {
    $stmt = $pdo->prepare("INSERT INTO pincodes (pincode, city, state, is_serviceable, partner_courier) 
                           VALUES (:pincode, :city, :state, :is_serviceable, :partner)
                           ON CONFLICT (pincode) DO UPDATE SET 
                           city = EXCLUDED.city, 
                           state = EXCLUDED.state, 
                           is_serviceable = EXCLUDED.is_serviceable, 
                           partner_courier = EXCLUDED.partner_courier");

    foreach ($testPincodes as $p) {
        $stmt->execute([
            'pincode' => $p['pincode'],
            'city' => $p['city'],
            'state' => $p['state'],
            'is_serviceable' => $p['is_serviceable'] ? 1 : 0,
            'partner' => $p['partner']
        ]);
        echo "Inserted/Updated: " . $p['pincode'] . "\n";
    }

    echo "DONE: Seeded " . count($testPincodes) . " pincodes.\n";

} catch (PDOException $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
}
?>
