<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class PageTest extends TestCase
{
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
    }
}
