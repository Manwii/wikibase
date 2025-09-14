<?php
// Script to create OAuth consumer for QuickStatements

// Database connection details
$host = 'mariadb';
$dbname = 'my_wiki';
$username = 'my_wiki';
$password = 'my_wiki_user_password';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Generate consumer key and secret
    $consumerKey = bin2hex(random_bytes(16));
    $consumerSecret = bin2hex(random_bytes(16));
    
    // OAuth consumer data
    $consumerData = [
        'oarc_consumer_key' => $consumerKey,
        'oarc_name' => 'QuickStatements',
        'oarc_user_id' => 1, // admin user
        'oarc_version' => '1.0',
        'oarc_callback_url' => 'http://localhost:9090/oauth/callback',
        'oarc_description' => 'QuickStatements tool for editing Wikibase data',
        'oarc_email' => 'admin@example.com',
        'oarc_developer_agreement' => 1,
        'oarc_owner_only' => 0,
        'oarc_wiki' => 'my_wiki',
        'oarc_grants' => '["editpage","editmyuserinfo","highvolume"]',
        'oarc_registration' => date('YmdHis'),
        'oarc_secret_key' => $consumerSecret,
        'oarc_restrictions' => '{"IPAddresses":[]}',
        'oarc_stage' => 1, // approved
        'oarc_stage_timestamp' => date('YmdHis'),
        'oarc_oauth_version' => 1.0,
        'oarc_oauth2_is_confidential' => 1,
        'oarc_oauth2_allowed_grants' => '["authorization_code","refresh_token"]'
    ];
    
    // Insert OAuth consumer
    $sql = "INSERT INTO oauth_registered_consumer (
        oarc_consumer_key, oarc_name, oarc_user_id,
        oarc_version, oarc_callback_url, oarc_description,
        oarc_email, oarc_developer_agreement, oarc_owner_only, oarc_wiki,
        oarc_grants, oarc_registration, oarc_secret_key, oarc_restrictions,
        oarc_stage, oarc_stage_timestamp, oarc_oauth_version, oarc_oauth2_is_confidential,
        oarc_oauth2_allowed_grants
    ) VALUES (
        :oarc_consumer_key, :oarc_name, :oarc_user_id,
        :oarc_version, :oarc_callback_url, :oarc_description,
        :oarc_email, :oarc_developer_agreement, :oarc_owner_only, :oarc_wiki,
        :oarc_grants, :oarc_registration, :oarc_secret_key, :oarc_restrictions,
        :oarc_stage, :oarc_stage_timestamp, :oarc_oauth_version, :oarc_oauth2_is_confidential,
        :oarc_oauth2_allowed_grants
    )";
    
    $stmt = $pdo->prepare($sql);
    $result = $stmt->execute($consumerData);
    
    if ($result) {
        echo "✅ OAuth Consumer created successfully!\n";
        echo "Consumer Key: $consumerKey\n";
        echo "Consumer Secret: $consumerSecret\n";
        echo "\n";
        echo "Now you need to update the QuickStatements configuration.\n";
        
    } else {
        echo "❌ Failed to create OAuth consumer.\n";
    }
    
} catch (PDOException $e) {
    echo "Database error: " . $e->getMessage() . "\n";
}
?>
