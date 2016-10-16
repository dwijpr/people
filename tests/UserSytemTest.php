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
        foreach ($users as $i => $user) {
            $this->assignAdmin($user);
        }
    }

    private function assignAdmin(User $user) {
        $role = Role::find(1);
        if (!$user->roles->contains($role)) {
            Artisan::call('assign:admin', [
                'email' => $user->email
            ]);
            dump(Artisan::output());
        }
    }
}
