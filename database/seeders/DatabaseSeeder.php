<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Category;
use App\Models\Permission;
use App\Models\Purchase;
use App\Models\Role;
use App\Models\Supplier;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();

      Category::factory(3)->create();
      Supplier::factory(5)->create();
 // Create admin role
 $adminRole = Role::create([
  'name' => 'Admin',
  'description' => 'Administrator',
]);
$pharmacyRole = Role::create([
  'name' => 'pharmacy',
  'description' => 'pharmacyy',
]);

  // Create permissions
  $createUserPermission1 = Permission::create([
    'name' => 'get-employee',
    'description' => 'get-employee',
]);
$createUserPermission2 = Permission::create([
  'name' => 'create-employee',
  'description' => 'create-employee',
]);
$createUserPermission3 = Permission::create([
  'name' => 'delete-employee',
  'description' => 'delete-employee',
]);
$createUserPermission = Permission::create([
  'name' => 'show-employee',
  'description' => 'show-employee',
]);

$editUserPermission = Permission::create([
    'name' => 'edit-user',
    'description' => 'Edit User',
]);
$deleteUserPermission = Permission::create([
    'name' => 'delete-supplier',
    'description' => 'Delete User',
]);

// Assign permissions to admin role
$adminRole->permissions()->attach([
    $createUserPermission->id,
    $createUserPermission1->id,
    $createUserPermission2->id,
    $createUserPermission3->id,
    $editUserPermission->id,
    $deleteUserPermission->id,
]);
// Create admin user
$adminUser = User::create([
  'name' => 'Admin',
  'email' => 'admin@gmail.com',
  'password' => bcrypt('password'),
  'role_id' => $adminRole->id,
  'type' => 'admin',
]);

}




    //   Purchase::factory(10)->create();
    }

