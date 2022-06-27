<?php

require __DIR__ . '/../vendor/autoload.php';

// try {
//     $dotenv = Dotenv\Dotenv::create(__DIR__ . '/../');
//     $dotenv->load();
// } catch (\Throwable $th) {
//     throw $th;
// }

// defined('YII_DEBUG') || define('YII_DEBUG', (getenv('YII_DEBUG') === 'true'));
// defined('YII_ENV') ?: define('YII_ENV', getenv('YII_ENV'));

require __DIR__ . '/../vendor/yiisoft/yii2/Yii.php';

$config = require __DIR__ . '/../config/web.php';

(new yii\web\Application($config))->run();
