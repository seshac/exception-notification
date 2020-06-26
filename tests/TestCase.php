<?php

namespace Javelin\ExceptionNotification\Tests;

use Javelin\ExceptionNotification\ExceptionNotificationServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;

class TestCase extends Orchestra
{
    public function setUp(): void
    {
        parent::setUp();

        $this->withFactories(__DIR__.'/database/factories');
    }

    protected function getPackageProviders($app)
    {
        return [
            ExceptionNotificationServiceProvider::class,
        ];
    }
}
