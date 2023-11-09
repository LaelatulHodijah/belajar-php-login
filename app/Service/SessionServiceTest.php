<?php

namespace ProgrammerZamanNow\Belajar\PHP\MVC\Services;

use PHPUnit\Framework\TestCase;
use ProgrammerZamanNow\Belajar\PHP\MVC\Config\Database;
use ProgrammerZamanNow\Belajar\PHP\MVC\Repository\SessionRepository;
use ProgrammerZamanNow\Belajar\PHP\MVC\Repository\UserRepository;

function setcookie(string $name, string $value){
    echo "$name: $value";
}
class SessionServiceTest extends TestCase
{
    private SessionService $sessionService;
    private SessionRepository $sessionRepository;
    private UserRepository $userRepository;

    protected function setUp():void
    {
       $this->sessionRepository = new SessionRepository(Database::getConnection());
       $this->userRepository = new UserRepository(Database::getConnection());
       $this->sessionService = new SessionService($this->sessionRepository, $this->userRepository);  
       $this->sessionRepository = new SessionRepository($connection);


       $this-> sessionRepository->deleteAll();
       $this-> UserRepository->deleteAll();  


       $user = new user();
        $user->id="eko";
        $user->name = "Eko";
        $user->password = "rahasia";
        $this->userRepository->save($user);
    }

    public function testCreate()
    {
        
        $session = $this->sessionService->create("eko");
        
        $this->expectOutputRegex("[X-PZN-SESSION: $session->id]");

        $result = $this->sessionRepository->findById($session->id);

        self::assertEquals("eko", $result->userId);
    }

    public function testDestroy()
    {
        $session = new Session();
        $session->id = uniqid();
        $session->userId ="eko";

        $this -> sessionRepository->save($session);

        $_COOKIE[SessionService:: $COOKIE_NAME] = $session->id;

        $this->sessionService->destroy();

        $this->expectOutputRegex("[X-PZN-SESSION: ]");

        $result = $this->sessionRepository->findById($session->id);
        self::assertNull($result);
    }

    public function testCurrent()
    {
        $session = new Session();
        $session->id = uniqid();
        $session->userId ="eko";

        $this -> sessionRepository->save($session);

        $_COOKIE[SessionService:: $COOKIE_NAME] = $session->id;

        $user = $this->sessionService->current();

        self::assertEquals($session->userId, $user->id);
    }

    public function testUpdateSuccess()
    {
        $user = new User();
        $user->id = "eko";
        $user->name = "Eko";
        $user->password = password_hash("eko". PASSWORD_BCRYPT);
        $this->userReposirory->save($user);

        $request = new UserProfileUpdateRequest();
        $request->id = "eko";
        $request->name = "Budi";

        $this->userService->UpdateProfile($request);

        $result = $this->userRepository->findById($user->id);

        self::assertEquals($request->name, $result->name);
    }

    
    public function UpdateSuccess()
    {
        $user = new User();
        $user->id = "eko";
        $user->name = "Eko";
        $user->password = password_hash("eko". PASSWORD_BCRYPT);
        $this->userReposirory->save($user);

        $request = new UserProfileUpdateRequest();
        $request->id = "eko";
        $request->name = "Budi";

        $this->userService->UpdateProfile($request);

        $result = $this->userReposirory->findById($user->id);

        self::assertEquals($request->name, $result->name);
    }

    public function testUpdateValidationError()
    {
        $this->expectException(ValidationException::class);

        $request = new UserProfileUpdateRequest();
        $request->id = "";
        $request->name = "";

        $this->userService->UpdateProfile($request);

    }
    
    public function testUpdateNotFound()
    {
        $this->expectException(ValidationException::class);

        $request = new UserProfileUpdateRequest();
        $request->id = "eko";
        $request->name = "Budi";

        $this->userService->UpdateProfile($request);

    }

    public function testUpdatePasswordSuccess()
    {
        $user = new User();
        $user->id = "eko";
        $user->name = "Eko";
        $user->password = password_hash("eko". PASSWORD_BCRYPT);
        $this->userReposirory->save($user);

        $request = new UserPasswordUpdateRequest();
        $request->oldPassword = "eko";
        $request->newPassword = "new";

        $this->userService->UpdatePassword($request);

        $result = $this->userReposirory->findById($user->id);
        self::assertTrue(password_verify($request->newPassword, $result->password));
    }

    public function testUpdatePasswordValidationError()
    {
        $this->expectException(ValidationException::class);

        $request = new UserPasswordUpdateRequest();
        $request->oldPassword = "";
        $request->newPassword = "";

        $this->userService->UpdatePassword($request);
    }

    public function testUpdatePasswordWrongOldPassword()
    {
        $this->expectException(ValidationException::class);

        $user = new User();
        $user->id = "eko";
        $user->name = "Eko";
        $user->password = password_hash("eko". PASSWORD_BCRYPT);
        $this->userReposirory->save($user);

        $request = new UserPasswordUpdateRequest();
        $request->oldPassword = "salah";
        $request->newPassword = "new";

        $this->userService->UpdatePassword($request);
    }

    public function testUpdatePasswordNotFound()
    {
        $this->expectException(ValidationException::class);

        $request = new UserPasswordUpdateRequest();
        $request->oldPassword = "eko";
        $request->newPassword = "new";

        $this->userService->UpdatePassword($request);
    }
}
