#!/bin/bash

# OAuth Consumer Registration for QuickStatements
# This script will help you set up OAuth authentication between QuickStatements and Wikibase

echo "Setting up OAuth authentication for QuickStatements..."

# Get CSRF token
CSRF_TOKEN=$(curl -s "http://localhost:8080/w/api.php?action=query&meta=tokens&type=csrf&format=json" | php -r '$json = file_get_contents("php://stdin"); $data = json_decode($json, true); echo $data["query"]["tokens"]["csrftoken"];')

echo "CSRF Token: $CSRF_TOKEN"

# OAuth Consumer details
CONSUMER_NAME="QuickStatements"
CONSUMER_DESCRIPTION="QuickStatements tool for editing Wikibase data"
CONSUMER_VERSION="1.0"
CONSUMER_OWNER="admin"
CONSUMER_CALLBACK_URL="http://localhost:9090/oauth/callback"
CONSUMER_EMAIL="admin@example.com"

echo "Creating OAuth consumer registration..."

# Create the OAuth consumer
RESPONSE=$(curl -s -X POST "http://localhost:8080/w/api.php" \
  -d "action=oauthconsumer" \
  -d "format=json" \
  -d "token=$CSRF_TOKEN" \
  -d "name=$CONSUMER_NAME" \
  -d "description=$CONSUMER_DESCRIPTION" \
  -d "version=$CONSUMER_VERSION" \
  -d "owner=$CONSUMER_OWNER" \
  -d "callbackUrl=$CONSUMER_CALLBACK_URL" \
  -d "email=$CONSUMER_EMAIL" \
  -d "restrictions={}" \
  -d "grants=[]")

echo "OAuth Consumer Registration Response:"
echo "$RESPONSE"

# Extract consumer key and secret if successful
CONSUMER_KEY=$(echo "$RESPONSE" | grep -o '"consumerKey":"[^"]*"' | cut -d'"' -f4)
CONSUMER_SECRET=$(echo "$RESPONSE" | grep -o '"consumerSecret":"[^"]*"' | cut -d'"' -f4)

if [ ! -z "$CONSUMER_KEY" ] && [ ! -z "$CONSUMER_SECRET" ]; then
    echo ""
    echo "✅ OAuth Consumer created successfully!"
    echo "Consumer Key: $CONSUMER_KEY"
    echo "Consumer Secret: $CONSUMER_SECRET"
    echo ""
    echo "Now updating QuickStatements configuration..."
    
    # Update QuickStatements OAuth configuration
    docker exec wikibase-quickstatements bash -c "cat > /quickstatements/data/oauth.ini << EOF
; HTTP User-Agent header
agent = 'Wikibase Docker QuickStatements'
; OAuth Consumer credentials
consumerKey = '$CONSUMER_KEY'
consumerSecret = '$CONSUMER_SECRET'
EOF"
    
    echo "✅ QuickStatements OAuth configuration updated!"
    echo ""
    echo "🎉 OAuth setup complete! You can now:"
    echo "1. Go to http://localhost:9090"
    echo "2. Click 'Login' or 'Authorize'"
    echo "3. You'll be redirected to Wikibase to authorize QuickStatements"
    echo "4. After authorization, you can edit data in QuickStatements"
    
else
    echo "❌ Failed to create OAuth consumer. Please check the response above."
    echo ""
    echo "You may need to:"
    echo "1. Log into Wikibase as admin at http://localhost:8080"
    echo "2. Go to Special:OAuthConsumerRegistration"
    echo "3. Manually create an OAuth consumer for QuickStatements"
    echo "4. Update the oauth.ini file in QuickStatements with the credentials"
fi
