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
    $consumerKey = 'qs_' . bin2hex(random_bytes(16));
    $consumerSecret = bin2hex(random_bytes(32));
    
    // OAuth consumer data
    $consumerData = [
        'oarc_id' => null,
        'oarc_consumer_key' => $consumerKey,
        'oarc_consumer_secret' => $consumerSecret,
        'oarc_name' => 'QuickStatements',
        'oarc_user_id' => 1, // admin user
        'oarc_version' => '1.0',
        'oarc_callback_url' => 'http://localhost:9090/oauth/callback',
        'oarc_callback_is_prefix' => 0,
        'oarc_description' => 'QuickStatements tool for editing Wikibase data',
        'oarc_email' => 'admin@example.com',
        'oarc_email_authenticated' => null,
        'oarc_developer_agreement' => 1,
        'oarc_owner_only' => 0,
        'oarc_wiki' => 'my_wiki',
        'oarc_grants' => '["editpage","editmyuserinfo","highvolume"]',
        'oarc_registration' => date('YmdHis'),
        'oarc_secret_key' => bin2hex(random_bytes(32)),
        'oarc_public_key' => null,
        'oarc_restrictions' => '{}',
        'oarc_stage' => 1, // approved
        'oarc_stage_timestamp' => date('YmdHis'),
        'oarc_oauth_version' => 1.0,
        'oarc_oauth2_is_confidential' => 1,
        'oarc_oauth2_allowed_grants' => '["authorization_code","refresh_token"]'
    ];
    
    // Insert OAuth consumer
    $sql = "INSERT INTO oauth_registered_consumer (
        oarc_consumer_key, oarc_consumer_secret, oarc_name, oarc_user_id,
        oarc_version, oarc_callback_url, oarc_callback_is_prefix, oarc_description,
        oarc_email, oarc_developer_agreement, oarc_owner_only, oarc_wiki,
        oarc_grants, oarc_registration, oarc_secret_key, oarc_restrictions,
        oarc_stage, oarc_stage_timestamp, oarc_oauth_version, oarc_oauth2_is_confidential,
        oarc_oauth2_allowed_grants
    ) VALUES (
        :oarc_consumer_key, :oarc_consumer_secret, :oarc_name, :oarc_user_id,
        :oarc_version, :oarc_callback_url, :oarc_callback_is_prefix, :oarc_description,
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
        echo "Now updating QuickStatements configuration...\n";
        
        // Update QuickStatements OAuth configuration
        $oauthConfig = "; HTTP User-Agent header\n";
        $oauthConfig .= "agent = 'Wikibase Docker QuickStatements'\n";
        $oauthConfig .= "; OAuth Consumer credentials\n";
        $oauthConfig .= "consumerKey = '$consumerKey'\n";
        $oauthConfig .= "consumerSecret = '$consumerSecret'\n";
        
        file_put_contents('/tmp/oauth.ini', $oauthConfig);
        
        echo "✅ OAuth configuration written to /tmp/oauth.ini\n";
        echo "\n";
        echo "🎉 OAuth setup complete! You can now:\n";
        echo "1. Go to http://localhost:9090\n";
        echo "2. Click 'Login' or 'Authorize'\n";
        echo "3. You'll be redirected to Wikibase to authorize QuickStatements\n";
        echo "4. After authorization, you can edit data in QuickStatements\n";
        
    } else {
        echo "❌ Failed to create OAuth consumer.\n";
    }
    
} catch (PDOException $e) {
    echo "Database error: " . $e->getMessage() . "\n";
}
?>
