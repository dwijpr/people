<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\User;
use App\Role;

class ManageAdmin extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'admin:manage {action} {email}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Assign / Revoke person with Admin role';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $action = $this->argument('action');
        $email = $this->argument('email');
        $role = Role::where('name', 'admin')->first();
        if ($user = User::where('email', $email)->first()) {
            $this->info('User found ... '.$action.' Admin role');
            $this->{$action}($user, $role);
        } else {
            $this->warn('User not found!');
        }
    }

    private function revoke(User $user, Role $role) {
        if (!$user->removeRole($role)) {
            $this->warn('Error removing admin role for the user!');
        }
    }

    private function assign(User $user, Role $role) {
        if (!$user->assignRole($role)) {
            $this->warn('Failed assign User as Admin!');
        }
    }
}
