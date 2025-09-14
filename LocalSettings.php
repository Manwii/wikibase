<?php

# Database
$wgDBserver = "mariadb:3306";
$wgDBname = "my_wiki";
$wgDBuser = "my_wiki";
$wgDBpassword = "my_wiki_user_password";

# Site
$wgServer = "http://localhost:8080";
$wgSitename = "My Wikibase";
$wgScriptPath = "/w";
$wgArticlePath = "/wiki/$1";

# Secret key
$wgSecretKey = "QNCU0syLzEHdUWXP4K5wHEeQ+pqBUX73pPLzKpbud5M=";

# Debug/logs
$wgDebugLogGroups = [
    'resourceloader' => '/var/log/mediawiki/resourceloader.log',
    'exception' => '/var/log/mediawiki/exception.log',
    'error' => '/var/log/mediawiki/error.log',
];

# OAuth extension
wfLoadExtension( 'OAuth' );
$wgMWOAuthSecureTokenTransfer = true;

# Wikibase
require_once "$IP/extensions/Wikibase/repo/Wikibase.php";
require_once "$IP/extensions/Wikibase/repo/ExampleSettings.php";
require_once "$IP/extensions/Wikibase/client/WikibaseClient.php";
require_once "$IP/extensions/Wikibase/client/ExampleSettings.php";

# Skin
wfLoadSkin( 'Vector' );
