<?php

return [
    'driver' => 'pgsql',
    'host' => getenv('PGHOST'),
    'port' => getenv('PGPORT'),
    'database' => getenv('PGDATABASE'),
    'username' => getenv('PGUSER'),
    'password' => getenv('PGPASSWORD'),
];
