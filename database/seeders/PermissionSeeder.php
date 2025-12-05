<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Define all permissions
        $all_permissions = [
            'posts.view',
            'posts.create',
            'posts.update.own',
            'posts.delete.own',
            'posts.delete.any',
            'posts.view.pending',
            'posts.approve',
            'posts.reject',
            
            'comments.create',
            'comments.update.own',
            'comments.delete.own',
            'comments.delete.any',
            
            'replies.create',
            'replies.update.own',
            'replies.delete.own',
            'replies.delete.any',
            
            'users.view',
            'users.block',
            'users.unblock',
            
            'reports.view',
            'reports.resolve',
            
            'view roles',
            'assign roles',
            'view permissions',
            'assign permissions',
        ];

        // Create all permissions
        foreach ($all_permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission, 'guard_name' => 'api']);
        }

        // Define Role Permissions
        $user_permissions = [
            'posts.view',
            'posts.create',
            'posts.update.own',
            'posts.delete.own',
            'comments.create',
            'comments.update.own',
            'comments.delete.own',
            'replies.create',
            'replies.update.own',
            'replies.delete.own',
        ];

        $moderator_permissions = array_merge($user_permissions, [
            'posts.view.pending',
            'posts.approve',
            'posts.reject',
            'users.view',
            'users.block',
            'users.unblock',
        ]);

        $admin_permissions = array_merge($moderator_permissions, [
            'posts.delete.any',
            'comments.delete.any',
            'replies.delete.any',
            'reports.view',
            'reports.resolve',
        ]);

        $superadmin_permissions = array_merge($admin_permissions, [
            'view roles',
            'assign roles',
            'view permissions',
            'assign permissions',
        ]);

        // Create Roles and Assign Permissions
        $user = Role::firstOrCreate(['name' => 'user', 'guard_name' => 'api']);
        $user->syncPermissions($user_permissions);

        $moderator = Role::firstOrCreate(['name' => 'moderator', 'guard_name' => 'api']);
        $moderator->syncPermissions($moderator_permissions);

        $admin = Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'api']);
        $admin->syncPermissions($admin_permissions);

        $superadmin = Role::firstOrCreate(['name' => 'superadmin', 'guard_name' => 'api']);
        $superadmin->syncPermissions($superadmin_permissions);
    }
}