#!/bin/bash
CSRF_TOKEN='+_\'
curl -s -X POST "http://localhost:8080/w/api.php" \
  -d "action=oauthconsumer" \
  -d "format=json" \
  --data-urlencode "token=$CSRF_TOKEN" \
  -d "name=QuickStatements" \
  -d "description=QuickStatements tool for editing Wikibase data" \
  -d "version=1.0" \
  -d "owner=admin" \
  --data-urlencode "callbackUrl=http://localhost:9090/oauth/callback" \
  -d "email=admin@example.com" \
  -d "restrictions={}" \
  -d "grants=[]"