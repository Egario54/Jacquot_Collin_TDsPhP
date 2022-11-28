<?php

namespace iut\deefy\auth;



use iut\deefy\connection\ConnectionFactory;
use iut\deefy\exception\AuthException;
use iut\deefy\user\User;

class Auth
{

    /**
     * @throws AuthException
     */
    public static function authenticate($email, $mdp): bool
    {
        if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
            ConnectionFactory::setConfig("file.ini");
            $db = ConnectionFactory::makeConnection();
            $sql = <<<END
            select email,passwd,role from user
            END;
            $stmt1 = $db->prepare($sql);
            $stmt1->execute();
            $isConnected = false;
            $role = 1;
            while ($donnes = $stmt1->fetch()) {
                if ($donnes["email"] === $email && password_verify($mdp,$donnes["passwd"])) {
                    $isConnected = true;
                    $role = $donnes["role"];
                }
            }
        }else throw new AuthException("Authentification error");
        if ($isConnected) {
            $mdp = password_hash($mdp,PASSWORD_BCRYPT,['cost' => 12]);
            $user = new User($email, $mdp, $role);
            $_SESSION["userAuthentified"] = serialize($user);
        }
        return $isConnected;
    }

    public static function register(string $email, string $password, string $passwordverif):bool
    {
        if (!($password === $passwordverif && filter_var($email, FILTER_VALIDATE_EMAIL))) {
            return false;
        }
        if (!strlen($password) >= 10) {
            return false;
        }
        ConnectionFactory::setConfig("file.ini");
        $db = ConnectionFactory::makeConnection();
        $sql = <<<END
            select email from user
            END;
        $stmt1 = $db->prepare($sql);
        $stmt1->execute();
        while ($donnes = $stmt1->fetch()) {
            if ($donnes["email"] === $email) {
                return false;
            }
        }
        $password = password_hash($password,PASSWORD_BCRYPT,['cost'=>12]);
        $sql = <<<END
            insert into user (email,passwd) values(?,?)
            END;
        $stmt1 = $db->prepare($sql);
        $stmt1->bindParam(1, $email);
        $stmt1->bindParam(2, $password);
        $stmt1->execute();
        return true;
    }

    public static function isOwnPlaylist(int $idPl): bool
    {
        ConnectionFactory::setConfig("file.ini");
        $db = ConnectionFactory::makeConnection();
        $user = unserialize($_SESSION["userAuthentified"]);
        $email = $user->email;
        $role = $user->role;
        if ($role === 100) {
            return true;
        }
        $sql = <<<END
            select email from user u
            inner join user2playlist up on up.id_user = u.id
            where up.id_pl = ?
            END;
        $stmt1 = $db->prepare($sql);
        $stmt1->bindParam(1, $idPl);
        $stmt1->execute();
        while ($donnes = $stmt1->fetch()) {
            if ($donnes["email"] === $email) {
                return true;
            }
        }
        return false;
    }


}