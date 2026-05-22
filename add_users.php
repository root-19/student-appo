<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);

$kernel->bootstrap();

$users = [
    [
        'name' => 'Admin PTC',
        'email' => 'adminptc31@gmail.com',
        'password' => 'adminptc123',
        'role' => 'Admin',
        'department' => 'Student Affairs',
        'status' => 'Active',
    ],
    [
        'name' => 'Super Admin PTC',
        'email' => 'superadminptc2k26@gmail.com',
        'password' => 'superadminptc123',
        'role' => 'SuperAdmin',
        'status' => 'Active',
    ],
    [
        'name' => 'Registrar Staff PTC',
        'email' => 'registrarstaff20k26@gmail.com',
        'password' => 'registrarptc123',
        'role' => 'Registrar',
        'department' => 'Registrar Office',
        'status' => 'Active',
    ],
    [
        'name' => 'Academic Staff PTC',
        'email' => 'acadstaff2k26@gmail.com',
        'password' => 'academicptc123',
        'role' => 'Academic',
        'department' => 'Academic Affairs',
        'status' => 'Active',
    ],
];

foreach ($users as $userData) {
    $user = new \App\Models\User();
    $user->name = $userData['name'];
    $user->email = $userData['email'];
    $user->password = \Illuminate\Support\Facades\Hash::make($userData['password']);
    $user->role = $userData['role'];
    $user->department = $userData['department'] ?? null;
    $user->status = $userData['status'];
    $user->save();
    echo "Added user: {$userData['email']}\n";
}

echo "All users added successfully!\n";
