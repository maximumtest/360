<?php

namespace Tests\Helper;

use Codeception\Module;

class Api extends Module
{
    public static $configurationApp = null;

    /**
     * @param $realClass
     * @param $mock
     * @throws \Codeception\Exception\ModuleException
     */
    public function mockClass($realClass, $mock)
    {
        $this->getModule('\Helper\Mongo')->haveInstance($realClass, $mock);
    }
}
