<?php
declare(strict_types=1);

namespace Salle\LSCoins\Model\Repository;

use PDO;
use Salle\LSCoins\Model\User;
use Salle\LSCoins\Model\UserRepository;

final class MySQLUserRepository implements UserRepository
{
    private const DATE_FORMAT = 'Y-m-d H:i:s';

    private PDOSingleton $database;

    public function __construct(PDOSingleton $database)
    {
        $this->database = $database;
    }

    public function save(User $user): void
    {
        $query = <<<'QUERY'
        INSERT INTO users(email, password, createdAt, updatedAt, coins)
        VALUES(:email, :password, :createdAt, :updatedAt, :coins)
QUERY;
        $statement = $this->database->connection()->prepare($query);

        $email = $user->email();
        $password = $user->password();
        $createdAt = $user->createdAt()->format(self::DATE_FORMAT);
        $updatedAt = $user->updatedAt()->format(self::DATE_FORMAT);
        $coins = $user->coins();
        $statement->bindParam('email', $email, PDO::PARAM_STR);
        $statement->bindParam('password', $password, PDO::PARAM_STR);
        $statement->bindParam('createdAt', $createdAt, PDO::PARAM_STR);
        $statement->bindParam('updatedAt', $updatedAt, PDO::PARAM_STR);
        $statement->bindParam('coins',$coins,PDO::PARAM_INT);
        $statement->execute();
    }

    public function isPasswordOkay($email,  $pwd): bool{
        $query = <<<'QUERY'
        SELECT count((users.email)) FROM users WHERE email=:email AND password=:password
        QUERY;
        $statement = $this->database->connection()->prepare($query);
        $statement->bindParam('email', $email, PDO::PARAM_STR);
        $statement->bindParam('password', $pwd, PDO::PARAM_STR);
        $statement->execute();
        $results = $statement->fetchAll(PDO::FETCH_ASSOC);
        $query_result = intval($results[0]["count((users.email))"]);

        return ($query_result == 1);
    }

    private function getNumUsers($email): int{
        $query = <<<'QUERY'
        SELECT count((users.email)) FROM users WHERE email=:email 
        QUERY;
        $statement = $this->database->connection()->prepare($query);
        $statement->bindParam('email', $email, PDO::PARAM_STR);
        $statement->execute();
        $results = $statement->fetchAll(PDO::FETCH_ASSOC);
        return intval($results[0]["count((users.email))"]);
    }

    public function userExists(string $email): bool
    {
       $result = $this->getNumUsers($email);
       return $result > 0;
    }


}