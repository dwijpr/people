<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class PageTest extends TestCase
{
    use DatabaseTransactions;
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testExample()
    {
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
            ->seePageIs('/home')
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
