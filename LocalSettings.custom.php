<?php
/**
 * ----------------------------------------------------------------------------------------
 * This file is provided by the wikibase/wikibase docker image.
 * This file will be passed through envsubst which will replace "$"
 * with "$".
 * If you want to change MediaWiki or Wikibase settings then either mount a file over this
 * template and or run a different entrypoint.
 * ----------------------------------------------------------------------------------------
 */

## Database settings
## Environment variables will be substituted in here.
$wgDBserver = "mariadb:3306";
$wgDBname = "my_wiki";
$wgDBuser = "my_wiki";
$wgDBpassword = "my_wiki_user_password";

## Logs
## Save these logs inside the container
$wgDebugLogGroups = array(
	'resourceloader' => '/var/log/mediawiki/resourceloader.log',
	'exception' => '/var/log/mediawiki/exception.log',
	'error' => '/var/log/mediawiki/error.log',
);

## Site Settings
# TODO pass in the rest of this with env vars?
$wgServer = WebRequest::detectServer();
$wgShellLocale = "en_US.utf8";
$wgLanguageCode = "en";
$wgSitename = "wikibase-docker";
$wgMetaNamespace = "Project";
# Configured web paths & short URLs
# This allows use of the /wiki/* path
## https://www.mediawiki.org/wiki/Manual:Short_URL
$wgScriptPath = "/w";        // this should already have been configured this way
$wgArticlePath = "/wiki/$1";

#Set Secret
$wgSecretKey = "QNCU0syLzEHdUWXP4K5wHEeQ+pqBUX73pPLzKpbud5M=";

## RC Age
# https://www.mediawiki.org/wiki/Manual:
# Items in the recentchanges table are periodically purged; entries older than this many seconds will go.
# The query service (by default) loads data from recent changes
# Set this to 1 year to avoid any changes being removed from the RC table over a shorter period of time.
$wgRCMaxAge = 365 * 24 * 3600;

wfLoadSkin( 'Vector' );

## Wikibase
# Load Wikibase repo & client with the example / default settings.
require_once "$IP/extensions/Wikibase/repo/Wikibase.php";
require_once "$IP/extensions/Wikibase/repo/ExampleSettings.php";
require_once "$IP/extensions/Wikibase/client/WikibaseClient.php";
require_once "$IP/extensions/Wikibase/client/ExampleSettings.php";

# Enable OAuth for QuickStatements
wfLoadExtension( 'OAuth' );
$wgGroupPermissions['sysop']['mwoauthproposeconsumer'] = true;
$wgGroupPermissions['sysop']['mwoauthmanageconsumer'] = true;
$wgGroupPermissions['sysop']['mwoauthviewprivate'] = true;
$wgGroupPermissions['sysop']['mwoauthupdateownconsumer'] = true;
$wgShowExceptionDetails = true;

