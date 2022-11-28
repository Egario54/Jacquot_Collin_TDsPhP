<?php
namespace iutnc\deefy\audio\tracks;

use iutnc\deefy\db\ConnectionFactory;
use iutnc\deefy\exception\InvalidPropertyNameException;
use iutnc\deefy\exception\InvalidPropertyValueException;
use iutnc\deefy\exception\NonEditablePropertyException;

abstract class AudioTrack
{
    private ?string $auteur, $titre, $fichierAudio, $genre;
    private ?int $duree;

    public function __construct(?string $titre=null, ?string $cheminFichier =null){
        $this->titre = $titre;
        $this->fichierAudio = $cheminFichier;
        $this->auteur = null;
        $this->genre = null;
        $this->duree = null;
    }

    /**
     * @throws InvalidPropertyNameException
     */
    public function __get(string $name)
    {
        if (property_exists($this, $name)) return $this->$name;
        throw new InvalidPropertyNameException("Le nom est invalide");
    }

    /**
     * @throws InvalidPropertyNameException
     * @throws NonEditablePropertyException
     * @throws InvalidPropertyValueException
     */
    public function __set(string $name, $value): void
    {
        if(property_exists($this, $name)) {
            if($name === "fichierAudio" || $name === "titre") throw new NonEditablePropertyException("$name ne peut etre modifier");
            if($name === "duree" && $value < 0) throw new InvalidPropertyValueException("La duree ne peut etre negative");
            $this->$name = $value;
        } else throw new InvalidPropertyNameException("Le nom est invalide");
    }

    public function insertTrack() {
        ConnectionFactory::setConfig("dbData.ini");
        $db = ConnectionFactory::makeConnection();
        $query = $db->prepare("insert into track(titre, genre, duree,filename, auteur_podcast) values (?, ?, ?, ?, ?)");
        $query->bindParam(1,$this->titre);
        $query->bindParam(2, $this->genre);
        $query->bindParam(3,$this->duree);
        $query->bindParam(4,$this->fichierAudio);
        $query->bindParam(5,$this->auteur);
        $query->execute();
        $query->closeCursor();
        $db->commit();

    }
}