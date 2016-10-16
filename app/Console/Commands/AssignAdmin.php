<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\User;
use App\Role;

class AssignAdmin extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'assign:admin {email}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Assign person as a User Admin';

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
        $email = $this->argument('email');
        if ($user = User::where('email', $email)->first()) {
            $this->info('User found ... assign Admin role');
            $this->assign($user);
        } else {
            $this->warn('User not found!');
        }
    }

    private function assign(User $user) {
        $role = Role::where('name', 'admin')->first();
        if (!$user->assignRole($role)) {
            $this->warn('Cannot assign User as Admin!');
        }
    }
}
