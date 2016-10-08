<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Repositories\ActivationRepository;
use App\Services\ActivationService;
use App\User;

class PageTest extends TestCase
{
    use DatabaseTransactions;
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testExample() {
        $this
            ->visit('/')
            ->see(config('app.name'));
            ;

        $this
            ->visit('/')
            ->click('Login')
            ->seePageIs('/login')
        ;

        $this
            ->visit('/')
            ->click('Register')
            ->seePageIs('/register')
        ;

        $this
            ->visit('/register')
            ->type('Taylor', 'name')
            ->type('taylor@gmail.com', 'email')
            ->type('asdfasdf', 'password')
            ->type('asdfasdf', 'password_confirmation')
            ->press('Register')
            ->seePageIs('/login')
            ->see(trans('_.user_activation_sent'))
        ;

        $this
            ->visit('/login')
            ->type('taylor@gmail.com', 'email')
            ->type('asdfasdf', 'password')
            ->press('Login')
            ->seePageIs('/login')
            ->see(trans('_.user_unactivated'))
        ;

        $user = User::where('email', 'taylor@gmail.com')->first();

        $activationRepo = new ActivationRepository(DB::connection());
        $activation = $activationRepo->getActivation($user);

        $this
            ->visit('/user/activation/'.$activation->token)
            ->seePageIs('/login')
            ->see(trans('_.user_activated'))
        ;

        $form = $this->visit('/home')->getForm();
        $this->visit('/home')->makeRequestUsingForm($form)->see('/');

        $this
            ->visit('/login')
            ->type('taylor@gmail.com', 'email')
            ->type('asdfasdf', 'password')
            ->press('Login')
            ->seePageIs('/home')
        ;

        $this
            ->visit('/')
            ->seePageIs('/home')
        ;
    }
}
