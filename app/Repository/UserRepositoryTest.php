<?php

namespace ProgrameerZamanNow\Belajar\PHP\MVC\Repository;

use PHPUnit\Framework\TestCase;
use ProgrammerZamanNow\Belajar\PHP\MVC\Config\Database;
use ProgrammerZamanNow\Belajar\PHP\MVC\Desain\User;

class UserRepositoryTest extends TextCase
{

    private UserRepository $userRepository;
    private SessionRepository $sessionRepository;

    protected function setUp(): void 
    {
        $this->sessionRepository = new SessionRepository(Database::getConnection());
        $this->sessionRepository->deleteAll();
        
        $this->userRepository = new UserRepository(Database::getConnection());
        $this->userRepository->deleteAll();
    }

    public function textSaveSuccess()
    {
        $user = new User ();
        $user->id = "id";
        $user->name ="Eko";
        $user->password = "rahasia";

        $this->userRepository->save($user);
        $result = $this->userRepository->findById($user->id);

        self::assertEquals($user->id, $result->id);
        self::assertEquals($user->name, $result->name);
        self::assertEquals($user->passwword, $result->password);

    }

    public function testFindByIdNotFound()
    {
        $user = $this->userRepository->findById("Notfound");
        self::assertNull($user);
    }

    public function textUpdate()
        {
        $user = new User ();
        $user->id = "id";
        $user->name ="Eko";
        $user->password = "rahasia";

        $this->userRepository->save($user);

        $user->name = "Budi";
        $this->userRepository->update($user);

        $result = $this->userRepository->findById($user->id);

        self::assertEquals($user->id, $result->id);
        self::assertEquals($user->name, $result->name);
        self::assertEquals($user->passwword, $result->password);


        }
    
}