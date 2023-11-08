<?php

namespace ProgrammerZamanNow\Belajar\PHP\MVC\Controller;

use PHPUnit\Framework\TestCase;

class HomeControllerTest extends TestCase

{

   private HomeController $homeController;
   private UserRepository $userRepository;
   private SessionRepository $sessionRepository;

   protected function setUp(): void 
   {
    $this->homeController = new HomeController();
    $this ->sessionRepository = new SessionRepository(Database::getConnection());
    $this->userRepository = new UserRepository(Database::getConnection());
   
    $this->sessionRepository->deleteAll();
    $this->userRepository->deleteAll();
}

    public function tesGuest()
    {
       $this->homeController->index();
       
       $this->expectOutputRegex("[Login Management]");
    }

    public function testUserLogin()
        {
            $user = new User();
            $user->id ="eko";
            $user->name = "Eko";
            $user->password ="rahasia";
            $this->userRepository->save($user);

            $session = new Session ();
            $session->id = uniqId();
            $session->userId = $user->id;
            $this->sessionRepository->save($session);

            $_COOKIE[SessionService::$COOKIE_NAME] = $session->id;

            $this->homeController->index();
       
            $this->expectOutputRegex("[Hello Eko]");
        }
    
}