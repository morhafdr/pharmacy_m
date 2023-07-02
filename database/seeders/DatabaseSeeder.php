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
  $createUserPermission = Permission::create([
    'name' => 'create-user',
    'description' => 'Create User',
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
    $editUserPermission->id,
    $deleteUserPermission->id,
]);
// Create admin user
$adminUser = User::create([
  'name' => 'Admin',
  'email' => 'admin@gmail.com',
  'password' => bcrypt('password'),
  'role_id' => $adminRole->id
]);

}




    //   Purchase::factory(10)->create();
    }

