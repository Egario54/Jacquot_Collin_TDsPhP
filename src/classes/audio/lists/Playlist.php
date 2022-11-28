<?php
namespace iutnc\deefy\audio\lists;
use iutnc\deefy\audio\tracks\AlbumTrack;
use iutnc\deefy\audio\tracks\AudioTrack;
use iutnc\deefy\db\ConnectionFactory;
use iutnc\deefy\exception\InvalidPropertyNameException;
require_once 'AudioList.php';

class Playlist extends AudioList
{

    public function __construct(string $nom, array $pistes)
    {
        parent::__construct($nom, $pistes);
    }

    /**
     * @throws InvalidPropertyNameException
     */
    public function addPiste(AudioTrack $track) {

        $pistes = parent::__get("pistes");
        if(in_array($track, $pistes)) return; // ne l'ajoute pas s'il existe deja



        // increment nbpistes
        $actual = parent::__get("nombrePistes");
        $actual++;
        parent::__set("nombrePistes" , $actual);

        // sinon l'ajoute
        $pistes[] = $track;
        parent::__set("pistes", $pistes);

        // augmente la duree total de la playlist
        $duree = parent::__get("dureeTotal");
        $duree += $track->__get("duree");
        parent::__set("dureeTotal", $duree);
    }

    /**
     * @throws InvalidPropertyNameException
     */
    public function remPiste(AudioTrack $track){
        $actual = parent::__get("nombrePistes");
        $actual--;
        parent::__set("nombrePistes" , $actual);

        $pistes = parent::__get("pistes");
        foreach($pistes as $index => $value) {
            if($value === $track) {
                unset($pistes[$index]);
                break;
            }
        }

        $duree = parent::__get("dureeTotal");
        $duree -= $track->__get("duree");
        parent::__set("dureeTotal", $duree);
    }

    /**
     * @throws InvalidPropertyNameException
     */
    public function addListPiste(array $pistes) {
        foreach ($pistes as $piste) {
            $this->addPiste($piste);
        }
    }

    /***
     * This method is different from find : it returns every playlist
     * @return array
     * @throws InvalidPropertyNameException
     * @throws \iutnc\deefy\exception\InvalidPropertyValueException
     * @throws \iutnc\deefy\exception\NonEditablePropertyException
     */
    public function getTrackList(): array {
        ConnectionFactory::setConfig("dbData.ini");
        $base = ConnectionFactory::makeConnection();

        $query =  <<<end
            SELECT titre, genre, duree, filename,titre_album,artiste_album FROM track
            INNER JOIN playlist2track p2t on track.id = p2t.id_track
            INNER JOIN playlist p on p2t.id_pl = p.id
            WHERE nom = ?;
        end;
        $result = $base->prepare($query);
        $var = parent::__get('nom');
        $result->bindParam(1,$var);
        $result->execute();
        $tracks = [];
        while($datas = $result->fetch(\PDO::FETCH_ASSOC)) {
            $track = new AlbumTrack($datas['titre'], $datas['filename']);
            $track->__set('duree',$datas['duree']);
            $track->__set('auteur',$datas['artiste_album']);
            $track->__set('genre',$datas['genre']);
            $track->__set('album',$datas['titre_album']);
            $tracks[] = $track;
        }
        $result->closeCursor();
        return $tracks;
    }

    public static function find(int $identifiant) : Playlist {
        $base = ConnectionFactory::makeConnection();
        $query = <<<end
            SELECT nom, titre, genre, duree, filename,titre_album,artiste_album FROM track
            INNER JOIN playlist2track p2t on track.id = p2t.id_track
            INNER JOIN playlist on p2t.id_pl = playlist.id
            WHERE id_pl = ?;
        end;
        $result = $base->prepare($query);
        $result->bindParam(1, $identifiant);
        $result->execute();
        $tracks = [];
        $nom = "";
        while($datas = $result->fetch(\PDO::FETCH_ASSOC)) {
            if ($nom === "") $nom = $datas['nom'];
            $track = new AlbumTrack($datas['titre'], $datas['filename']);
            $track->__set('duree',$datas['duree']);
            $track->__set('auteur',$datas['artiste_album']);
            $track->__set('genre',$datas['genre']);
            $track->__set('album',$datas['titre_album']);
            $tracks[] = $track;
        }
        $playlist = new Playlist($nom,$tracks);
        $result->closeCursor();
        return $playlist;
    }

}