<?php
// Class album track
namespace iutnc\deefy\audio\tracks;
require_once 'AudioTrack.php';

class AlbumTrack extends AudioTrack {

    // Attribut en public d'un album
    private ?int $annee, $numPiste;
    private ?string $album;

    // Constructeur qui met les valeurs a null par defaut sauf ses parametres
    public function __construct(?string $titre = null, ?string $cheminFichier = null){
        parent::__construct($titre, $cheminFichier);
        $this->album = null;
        $this->annee = null;
        $this->numPiste = null;
    }

    // Retourne un format json
    public function __toString():string {
        return json_encode($this);
    }


    public function __get(string $name)
    {
        if(property_exists($this, $name)) return $this->$name;
        else return parent::__get($name);
    }

    public function __set(string $name, $value): void
    {
        if(property_exists($this, $name)) $this->$name = $value;
        else parent::__set($name, $value);
    }



}