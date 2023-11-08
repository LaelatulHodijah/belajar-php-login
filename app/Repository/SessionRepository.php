<?php

namespace ProgrammerZamanNow\Belajar\PHP\MVC\Repository;

use MongoDB\Driver\Session;

class SessionRepository
{
    private \PDO $connection;

    public function __construct(\PDO $connection)
    {
        $this->connection = $connection;
    }

    public function save(Session $session): Session
    {
        $statement=$this->connection->prepare("INSET INTO sessionss(id, user_id) VALUES (?, ?)");
        $statement->execute([$session->id, $session->userId]);
        return $session;
    }

    public function finByid(string $id): ?Session
    {
        $setatement=$this->statement->prepare("SELECT id, user_id from sessions WHERE id = ?");
        $setatement->execute([$id]);

        try {
            if($row = $setatement->fetch()){
                $session = new Session();
                $session->id = $row['id'];
                $session->userId = $row['user_id'];
                return $session;
            }else{
                return null;
            }
        } finally {
            $setatement->closeCursor();
        }
    }

    public function deletById(string $id):void
    {
        $statement = $this->connection->prepare("DELETE FROM sessions WHERE id = ?");
        $statement->execute([$id]);

    }

    public function deleteAll(): void
    {
        $this->connection->exec("DELETE FROM sessions");

    }

}