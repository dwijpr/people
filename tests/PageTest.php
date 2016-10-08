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

    /*
        Landing Page
        -   User visit landing page see config.app.name
        -   [_.Login, _.Register]
    */

    public function testLandingPage() {
        $texts = [
            config('app.name'),
            trans('_.Login'),
            trans('_.Register'),
        ];
        $landingPage = $this->visit('/');
        foreach ($texts as $i => $text) {
            $landingPage->see($text);
        }
    }
        
    /*
        Pages Links
        -   Landing Page [/]
            -   Login -> /login
            -   Register -> /register
        -   Login [/login]
            -   app.name -> /
            -   _.Register -> /register
        -   Register [/register]
            -   app.name -> /
            -   _.Login -> /login
    */

    public function testLinks() {
        $data = [
            '/' => [
                ['Login', '/login'],
                ['Register', '/register'],
            ],
            '/login' => [
                [config('app.name'), '/'],
                [trans('_.Register'), '/register'],
            ],
            '/register' => [
                [config('app.name'), '/'],
                [trans('_.Login'), '/login'],
            ],
        ];
        foreach ($data as $route => $links) {
            foreach ($links as $i => $link) {
                $this
                    ->visit($route)
                    ->click($link[0])
                    ->seePageIs($link[1])
                ;
            }
        }
    }

    /*
        User Registration
        -   /register [form] -> inputs
            success -> /login message _.user_activation_sent

        Login Unactivated User
        -   /register [login] -> inputs
            success -> /login message _.user_unactivated

        User Activation
        -   /user/activation/{token}
            success -> /login message _.user_activated

        Logout

        Login
    */

    public function testExample() {

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
