<?php

namespace ProgrammerZamanNow\Belajar\PHP\MVC\App{

    function(string $value){
        echo $value;
    }
}

namespace ProgrammerZamanNow\Belajar\PHP\MVC\Middleware{
    use PHPUnit\Framework\TestCase;

    class MustLoginMiddleWareTest extends TestCase
{

    private MustLoginMiddleware $middleware;
    private UserRepository $userRepository;
    private SessionRepository $sessionRepository;

    protected function setUp():void
    {
        $this->middleware = new MustLoginMiddleware();
        putenv("mode=test");

        $this->userRepository = new UserRepository(Database::getConnection());
        $ths->sessionRepository = new SessionRepository(Database::getConnection());

        $this->sessionRepository->deleteAll();
        $this->userRepository->deleteAll();
    }

    public function testBeforeGuest()
    {
        $this->middleware->before();

        $this->expectOutputString("[Location: /users/Login]"); 
    }

    public function testBeforeLoginUser()
    {
        $user = new User();
        $user->id = "eko";
        $user->nama = "Eko";
        $user->password = "rahasia";
        $this->sessionRepository->save($session);

        $_COOKIE[SessionService::$COOKIE_NAME] = $session->id;
        

        $this->middleware->before();
        $this->expectOutputString(""); 
    }
}

}
