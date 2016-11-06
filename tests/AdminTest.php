<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\User;
use App\Repositories\ActivationRepository;

class AdminTest extends TestCase
{
    use DatabaseTransactions;

    /*
        User Register
    */
    private function userRegister() {
        $this
            ->visit('/register')
            ->type('Google', 'name')
            ->type('google@gmail.com', 'email')
            ->type('asdfasdf', 'password')
            ->type('asdfasdf', 'password_confirmation')
            ->press('Register')
            ->seePageIs('/login')
        ;
    }

    /*
        Activate User
    */

    private function userActivation() {
        $user = User::where('email', 'google@gmail.com')->first();

        $activationRepo = new ActivationRepository(DB::connection());
        $activation = $activationRepo->getActivation($user);

        $this
            ->visit('/user/activation/'.$activation->token)
            ->seePageIs('/login')
        ;
    }

    /*
        Assign Admin
    */
    private function assignAdmin() {
        $user = User::where('email', 'google@gmail.com')->first();
        Artisan::call('admin:manage', [
            'action' => 'assign',
            'email' => $user->email,
        ]);
    }

    /*
        Login
    */
    private function login() {
        $this
            ->visit('/login')
            ->type('google@gmail.com', 'email')
            ->type('asdfasdf', 'password')
            ->press('Login')
        ;
    }

    /*
        Don't see People Link
    */
    private function dontSeePeopleLink() {
        $this
            ->dontSee('People')
        ;
    }

    /*
        Go to People page
    */
    private function goToPeoplePage() {
        return $this
            ->visit('/people')
        ;
    }

    public function test()
    {
        $this->userRegister();
        $this->userActivation();
        $this->login();
        $this->dontSeePeopleLink();
        $this->goToPeoplePage()->assertResponseStatus(404);

        $this->assignAdmin();
        $this->goToPeoplePage();
        $this->seeListOfUsers();
        $this->storeUsersStatus();
        $this->activateDeactiveUser();
        $this->bringBackUserActivationState();
    }
}
