<?php

use Sndpbag\AdminPanel\Models\Role;

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$roles = Role::all();

foreach ($roles as $role) {
    echo "ID: " . $role->id . " | Name: " . $role->name . " | Slug: '" . $role->slug . "'\n";
}
