<?php

use Laravel\Lumen\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    /**
     * Creates the application.
     *
     * @return \Laravel\Lumen\Application
     */
    public function createApplication()
    {
        return require __DIR__.'/../bootstrap/app.php';
    }

        
    /**
    * Setup the test environment.
    *
    * @return void
    */
    protected function setUp(): void
    {
        if (! $this->app) {
            $this->refreshApplication();
        }

        $this->setUpTraits();
        $this->artisan('db:seed --class=CurrencyTableSeeder');
    }

        
    /**
    * check json count
    *
    * @param int $count
    */
    public function assertJsonCount(int $count)
    {
        $data =  (int) count(json_decode($this->response->content(), true));
        $this->assertEquals($count, $data);

        return $this;
    }

    /**
    * create mocked object
    *
    * @param string $class
    * @param Closure $expectations
    *
    * @return $mock
    */
    public function mock(string $class, Closure $expectations)
    {
        $mock = Mockery::mock($class)->makePartial();
        $expectations($mock);
        app()->instance($class, $mock);
        
        return $mock;
    }
}
