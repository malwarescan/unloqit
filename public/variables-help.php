<?php
/**
 * Shows EXACT variables you need to add to Railway
 */
header('Content-Type: text/html; charset=utf-8');
?>
<!DOCTYPE html>
<html>
<head>
    <title>Railway Variables - Exact List</title>
    <style>
        body { font-family: Arial, sans-serif; padding: 20px; max-width: 800px; margin: 0 auto; background: #f5f5f5; }
        .variable { background: white; padding: 15px; margin: 10px 0; border-left: 4px solid #007bff; }
        .name { font-weight: bold; color: #007bff; font-size: 18px; }
        .value { color: #28a745; font-family: monospace; margin-top: 5px; }
        .instructions { background: #fff3cd; padding: 15px; border-left: 4px solid #ffc107; margin: 20px 0; }
        h1 { color: #333; }
        code { background: #f4f4f4; padding: 2px 6px; border-radius: 3px; }
    </style>
</head>
<body>
    <h1>EXACT Variables to Add to Railway</h1>
    
    <div class="instructions">
        <h2>Step 1: Generate APP_KEY</h2>
        <p>Run this command locally:</p>
        <code>php -r "echo 'base64:' . base64_encode(random_bytes(32)) . PHP_EOL;"</code>
        <p>Copy the output (starts with <code>base64:</code>)</p>
    </div>
    
    <div class="instructions">
        <h2>Step 2: Get Database Variables</h2>
        <ol>
            <li>Go to Railway Dashboard</li>
            <li>Click on your <strong>MySQL database service</strong></li>
            <li>Click <strong>"Variables"</strong> tab</li>
            <li>Copy these values:
                <ul>
                    <li><code>MYSQLHOST</code> → Use for <code>DB_HOST</code></li>
                    <li><code>MYSQLPORT</code> → Use for <code>DB_PORT</code></li>
                    <li><code>MYSQLDATABASE</code> → Use for <code>DB_DATABASE</code></li>
                    <li><code>MYSQLUSER</code> → Use for <code>DB_USERNAME</code></li>
                    <li><code>MYSQLPASSWORD</code> → Use for <code>DB_PASSWORD</code></li>
                </ul>
            </li>
        </ol>
    </div>
    
    <h2>Step 3: Add These Variables to Your App Service</h2>
    <p>Go to: <strong>Railway Dashboard → Your App Service → Variables → New Variable</strong></p>
    
    <div class="variable">
        <div class="name">APP_KEY</div>
        <div class="value">base64:PASTE_YOUR_GENERATED_KEY_HERE</div>
    </div>
    
    <div class="variable">
        <div class="name">APP_ENV</div>
        <div class="value">production</div>
    </div>
    
    <div class="variable">
        <div class="name">APP_DEBUG</div>
        <div class="value">true</div>
    </div>
    
    <div class="variable">
        <div class="name">APP_URL</div>
        <div class="value">https://unloqit.com</div>
    </div>
    
    <div class="variable">
        <div class="name">DB_CONNECTION</div>
        <div class="value">mysql</div>
    </div>
    
    <div class="variable">
        <div class="name">DB_HOST</div>
        <div class="value">PASTE_MYSQLHOST_FROM_DATABASE_SERVICE</div>
    </div>
    
    <div class="variable">
        <div class="name">DB_PORT</div>
        <div class="value">3306</div>
    </div>
    
    <div class="variable">
        <div class="name">DB_DATABASE</div>
        <div class="value">PASTE_MYSQLDATABASE_FROM_DATABASE_SERVICE</div>
    </div>
    
    <div class="variable">
        <div class="name">DB_USERNAME</div>
        <div class="value">PASTE_MYSQLUSER_FROM_DATABASE_SERVICE</div>
    </div>
    
    <div class="variable">
        <div class="name">DB_PASSWORD</div>
        <div class="value">PASTE_MYSQLPASSWORD_FROM_DATABASE_SERVICE</div>
    </div>
    
    <div class="instructions">
        <h2>Step 4: After Adding Variables</h2>
        <p>Railway will auto-redeploy. Wait 1-2 minutes, then visit:</p>
        <p><a href="/check-env.php">https://unloqit.com/check-env.php</a></p>
        <p>All variables should show ✅ (green checkmarks)</p>
    </div>
</body>
</html>

