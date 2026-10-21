<?php
// Copy the original deployment key securely to config/env.php. Never commit that key.
return array('key' => getenv('APP_CRYPT_KEY') ?: 'REPLACE_WITH_ORIGINAL_DEPLOYMENT_KEY');
