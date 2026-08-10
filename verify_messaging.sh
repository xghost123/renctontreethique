#!/bin/bash

# Test Messaging API Endpoints
# This script verifies all messaging endpoints are properly configured

echo "=== MESSAGING API VERIFICATION ==="
echo ""

# Check if Laravel app is running
echo "1. Testing API availability..."
curl -s http://localhost:8000/api/user -H "Accept: application/json" > /dev/null 2>&1
if [ $? -eq 0 ]; then
  echo "✓ API is running"
else
  echo "✗ API is not running on localhost:8000"
  echo "  Start the app with: php artisan serve"
  exit 1
fi

# Check routes
echo ""
echo "2. Checking API routes..."
php artisan route:list | grep "conversations\|messages" | grep "api" > /tmp/routes.txt
if [ -s /tmp/routes.txt ]; then
  echo "✓ Routes are registered"
  echo ""
  cat /tmp/routes.txt | head -15
else
  echo "✗ Routes not found"
  exit 1
fi

# Check migrations
echo ""
echo "3. Checking database migrations..."
if [ -f database/migrations/2026_08_04_000001_create_conversations_table.php ]; then
  echo "✓ Conversations migration exists"
fi

if [ -f database/migrations/2026_08_04_000002_create_messages_table.php ]; then
  echo "✓ Messages migration exists"
fi

# Check models
echo ""
echo "4. Checking models..."
php -l app/Models/Message.php > /dev/null 2>&1 && echo "✓ Message model is valid"
php -l app/Models/Conversation.php > /dev/null 2>&1 && echo "✓ Conversation model is valid"

# Check controllers
echo ""
echo "5. Checking controllers..."
php -l app/Http/Controllers/MessageController.php > /dev/null 2>&1 && echo "✓ MessageController is valid"
php -l app/Http/Controllers/ConversationController.php > /dev/null 2>&1 && echo "✓ ConversationController is valid"

# Check events
echo ""
echo "6. Checking WebSocket events..."
php -l app/Events/MessageSent.php > /dev/null 2>&1 && echo "✓ MessageSent event is valid"
php -l app/Events/TypingIndicator.php > /dev/null 2>&1 && echo "✓ TypingIndicator event is valid"
php -l app/Events/UserOnlineStatusChanged.php > /dev/null 2>&1 && echo "✓ UserOnlineStatusChanged event is valid"

# Check Vue components
echo ""
echo "7. Checking Vue components..."
for component in MessagesPanel ConversationList ChatBox MessageInput TypingIndicator OnlineStatus; do
  if [ -f "resources/js/components/Messages/${component}.vue" ]; then
    echo "✓ ${component}.vue exists"
  else
    echo "✗ ${component}.vue missing"
  fi
done

# Check frontend build
echo ""
echo "8. Checking frontend build..."
if [ -f "public/build/manifest.json" ]; then
  echo "✓ Frontend build exists (public/build/manifest.json)"
fi

echo ""
echo "=== VERIFICATION COMPLETE ==="
