<?php

namespace ProgrammerZamanNow\Belajar\PHP\MVC\Services;

use PHPUnit\Framework\TestCase;
use ProgrammerZamanNow\Belajar\PHP\MVC\Config\Database;
use ProgrammerZamanNow\Belajar\PHP\MVC\Domain\Session;

class SessionRepositoryTest extends TestCase
{
    private SessionRepository $sessionRepository;
    private UserRepository $userRepository;

    protected function setUp(): void
    {
        $this->userRepository = new UserRepository(Database::getConnection());
        $this->sessionRepository = new SessionRepository(Database::getConnection());

        $this->sessionRepository->deleteAll();
        $this->UserRepository->deleteAll();

        $user = new user();
        $user->id="eko";
        $user->name = "Eko";
        $user->password = "rahasia";
        $this->userRepository->save($user);



    }
    public function testSaveSuccess()
    {
        $session = new Session();
        $session->od = uniqid();
        $session->userId ="eko";

        $this->sessionRepository->save($session);

        $result = $this->sessionRepository->findById($session->id);
        self::assertEquals($session->id, $result->id);
        self::assertEquals($session->userId, $result->userId);

        $this->sessionRepository->deleteById($session->id);

        $result = $this -> sessionRepository->findById($session->id);
        self::assertNull($result);
    
    }
    public function testFindByIdNotFound()
    {
        $result = $this->sessionRepository->findById('notfound');
        self::assertNull($result);
    }
}

