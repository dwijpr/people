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
        $user = User::find($user->id);
        return $user;
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

    /**
     * See People link
     */
    private function seePeopleLink() {
        $this
            ->visit('/home')
            ->see('People')
        ;
    }

    /**
     * Get People page
     */
    private function getPeoplePage() {
        return $this
            ->get('/people')
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

    /**
     * Logout
     */
    public function logout() {
        $form = $this->visit('/home')->getForm();
        $this->visit('/home')->makeRequestUsingForm($form)->see('/');
    }

    /**
     * Main Test Flow
     */
    public function test()
    {
        $this->userRegister();
        $this->userActivation();
        $this->login();
        $this->dontSeePeopleLink();
        $this->getPeoplePage()->assertResponseStatus(401);

        $this->assignAdmin();
        $this->logout();
        $this->login();
        $this->seePeopleLink();
        $this->goToPeoplePage();
        $this->seeListOfUsers();
        $this->storeUsersStatus();
        $this->activateDeactiveUser();
        $this->bringBackUserActivationState();
    }
}
