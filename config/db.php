<?php

return [
    'class' => \yii\db\Connection::class,
    'dsn' => 'mysql:host=localhost;dbname=taskflow_db',
    'username' => 'root',
    'password' => '',
    'charset' => 'utf8mb4',

    // Schema cache options (for production environment)
    //'enableSchemaCache' => true,
    //'schemaCacheDuration' => 60,
    //'schemaCache' => 'cache',
];
