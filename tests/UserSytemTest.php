<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\User;
use App\Role;

class UserSytemTest extends TestCase
{
    public function test()
    {
        $users = User::all();
        $notAdmin = [];
        foreach ($users as $i => $user) {
            $addNotAdmin = false;
            if (!$user->hasRole('Admin')) {
                $addNotAdmin = true;
            }
            $user = $this->assignAdmin($user);
            if ($addNotAdmin) {
                $notAdmin[] = $user;
            }
            $this->assertTrue($user->hasRole('Admin'));
        }
        $notAdmin = collect($notAdmin);
        foreach ($notAdmin as $i => $user) {
            $this->assertTrue($user->hasRole('Admin'));
            $user = $this->revokeAdmin($user);
            $this->assertFalse($user->hasRole('Admin'));
        }
    }

    private function revokeAdmin(User $user) {
        Artisan::call('admin:manage', [
            'action' => 'revoke',
            'email' => $user->email,
        ]);
        return User::find($user->id);
    }

    private function assignAdmin(User $user) {
        Artisan::call('admin:manage', [
            'action' => 'assign',
            'email' => $user->email,
        ]);
        return User::find($user->id);
    }
}
