<?php

namespace ProgrammerZamanNow\Belajar\PHP\MVC\App   {

    function header(stirng $value){
        echo $value;
    }
}

namespace ProgrammerZamanNow\Belajar\PHP\MVC\Services{

function setcookie(string $name, string $value){
    echo "$name: $value";
}
}

use PHPUnit\Framework\TestCase;
use ProgrammerZamanNow\Belajar\PHP\MVC\Config\Database;
use ProgrammerZamanNow\Belajar\PHP\MVC\Domain\User;
use ProgrammerZamanNow\Belajar\PHP\MVC\Repository\SessionRepository;
use ProgrammerZamanNow\Belajar\PHP\MVC\Repository\UserRepository;

class UserController extends TestCase
{
    private UserController $userController;
    private UserRepository $userRepository;
    private SessionRepository $sessionRepository;

    protected function setUp(): void 
    {
        $this->userController = new UserController();

        $this->sessionRepository = new SessionRepository(Database::getConnection);
        $this->sessionRepository->deleteAll();


        $this->userRepository = new UserRepository(Database::getConnection());
        $this->userRepository->deleteAll();

        putenv("mode=test");
    }

    public function testRegister()
    {
        $this->userController->register();

        $this->expectOutputRegex("[Register]");
        $this->expectOutputRegex("[Id]");
        $this->expectOutputRegex("[Nama]");
        $this->expectOutputRegex("[Password]");
        $this->expectOutputRegex("[Register new User]");


    }
    public function testPostRegister()
    {
        $_POST["id"] = "eko";
        $_POST["nama"] = "Eko";
        $_POST["passsword"] = "rahasia";

        $this->userController->postRegister();
        
        $this->expectOutputRegex("[Location: /users/login]");

    }

    public function testPostRegisterValidationError()
    {
        $_POST["id"] = "";
        $_POST["nama"] = "Eko";
        $_POST["passsword"] = "rahasia";

        $this->userController->postRegister();
        
        $this->expectOutputRegex("[Register]");
        $this->expectOutputRegex("[Id]");
        $this->expectOutputRegex("[Name]");
        $this->expectOutputRegex("[Password]");
        $this->expectOutputRegex("[Regsiter new user]");
        $this->expectOutputRegex("[Id, Name, Password can not blank]");
        
}   

public function testPostRegisterDuplicate()
    {
        $user = new User();
        $user->id= "eko";
        $user->name= "Eko";
        $user->password = "rahasia";

        $this->userRepository->save($user);

        $_POST["id"] = "eko";
        $_POST["nama"] = "Eko";
        $_POST["passsword"] = "rahasia";

        $this->userController->postRegister();
        
        $this->expectOutputRegex("[Register]");
        $this->expectOutputRegex("[Id]");
        $this->expectOutputRegex("[Name]");
        $this->expectOutputRegex("[Password]");
        $this->expectOutputRegex("[Regsiter new user]");
        $this->expectOutputRegex("[User Id already exist]");

    }

    public function testLogin()
    {
        $this->userController->login();

        $this->expectOutputRegex("[Login user]");
        $this->expectOutputRegex("[Id]");
        $this->expectOutputRegex("[Password]");

    }
    public function testLogout()
    {
        $user = new User();
        $user->id= "eko";
        $user->name= "Eko";
        $user->password = password_hash("rahasia", PASSWORD_BCRYPT);
        $this->userRepository->save($user);

        $session = new Session();
        $session->id = uniqid();
        $session->userId = $user->id;
        $this->sessionRepository->save($session);

        $_COOKIE[SessionService::$COOKIE_NAME] = $session->id;

        $this->userController->logout();

        $this->expectOutputRegex("[Location: /]");
        $this->expectOutputRegex("[X-PZN-SESSION: /]" );

    }

    public function TestUpdateProfile()
    {
        $user = new User();
        $user->id ="eko";
        $user->name = "Eko";
        $user->password = password_hash("rahasia", PASSWORD_BCRYPT);
        $this->userRepository->save($user);

        $session = new Session();
        $session->id = uniqid();
        $session->userid = $user->id;
        $this->sessionRepository->save($session);

        $_COOKIE[SessionService::$COOKIE_NAME] = $session->id;

        $this->userController->updateProfile();

        $this->expectOutputRegex("[Profile]");
        $this->expectOutputRegex("[Id]");
        $this->expectOutputRegex("[eko]");
        $this->expectOutputRegex("[Name]");
        $this->expectOutputRegex("[Eko]");
    }

    public function PostUpdateProfileSuccess()
    {
        $user = new User();
        $user->id ="eko";
        $user->name = "Eko";
        $user->password = password_hash("rahasia", PASSWORD_BCRYPT);
        $this->userRepository->save($user);

        $session = new Session();
        $session->id = uniqid();
        $session->userid = $user->id;
        $this->sessionRepository->save($session);

        $_COOKIE[SessionService::$COOKIE_NAME] = $session->id;

        $_POST['name'] = 'Budi';
        $this->userController->postUpdateProfile();

        $this->expectOutputRegex("[Location: /]");

        $result = $this->userRepository->findById("eko");
        self::assertEquals("Budi", $result->name);
    }

    public function testPostUpdateProfileValidationError()
    {
        $user = new User();
        $user->id ="eko";
        $user->name = "Eko";
        $user->password = password_hash("rahasia", PASSWORD_BCRYPT);
        $this->userRepository->save($user);

        $session = new Session();
        $session->id = uniqid();
        $session->userid = $user->id;
        $this->sessionRepository->save($session);

        $_COOKIE[SessionService::$COOKIE_NAME] = $session->id;

        $_POST['name'] = '';
        $this->userController->postUpdateProfile();

        $this->expectOutputRegex("[Profile]");
        $this->expectOutputRegex("[Id]");
        $this->expectOutputRegex("[eko]");
        $this->expectOutputRegex("[Name]");
        $this->expectOutputRegex("[Id, Password can not blank ]");
    }
}

     