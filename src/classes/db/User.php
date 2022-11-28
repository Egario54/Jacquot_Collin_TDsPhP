<?php

namespace iutnc\deefy\db;

use iutnc\deefy\audio\lists\Playlist;
use iutnc\deefy\audio\tracks\AlbumTrack;

class User
{
    private string $email;
    private string $passwd;
    private int $role;

    /**
     * @param string $email
     * @param string $passwd
     * @param int $role
     */
    public function __construct(string $email, string $passwd, int $role)
    {
        $this->email = $email;
        $this->passwd = $passwd;
        $this->role = $role;
    }

    public function getPlaylist() : array {
        ConnectionFactory::setConfig("dbData.ini");
        $base = ConnectionFactory::makeConnection();

        $query =  <<<end
            SELECT nom FROM playlist
            INNER JOIN user2playlist ON playlist.id = user2playlist.id_pl
            INNER JOIN user ON user2playlist.id_user = user.id
            WHERE email = ?  and passwd = ?;
        end;
        $result = $base->prepare($query);
        $result->bindParam(1, $this->email);
        $result->bindParam(2, $this->passwd);
        $result->execute();
        $playlists = [];
        while($datas = $result->fetch(\PDO::FETCH_ASSOC)) {
            $playlists[] = new Playlist($datas['nom'], []);
        }
        $result->closeCursor();
        return $playlists;
    }


}