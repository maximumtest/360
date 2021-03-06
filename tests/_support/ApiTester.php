<?php

namespace Tests;

use Codeception\Scenario;
use _generated\ApiTesterActions;
use JWTAuth;

/**
 * Inherited Methods
 * @method void wantToTest($text)
 * @method void wantTo($text)
 * @method void execute($callable)
 * @method void expectTo($prediction)
 * @method void expect($prediction)
 * @method void amGoingTo($argumentation)
 * @method void am($role)
 * @method void lookForwardTo($achieveValue)
 * @method void comment($description)
 * @method \Codeception\Lib\Friend haveFriend($name, $actorClass = NULL)
 *
 * @SuppressWarnings(PHPMD)
*/
class ApiTester extends \Codeception\Actor
{
    use ApiTesterActions;

    public function __construct(Scenario $scenario)
    {
        parent::__construct($scenario);

        $this->haveHttpHeader('Accept', 'application/json');
        $this->haveHttpHeader('Content-Type', 'application/json');
        $this->haveHttpHeader('X-Requested-With', 'XMLHttpRequest');
    }
    
    public function getToken($email, $password)
    {
        return JWTAuth::attempt(['email' => $email, 'password' => $password]);
    }
}
