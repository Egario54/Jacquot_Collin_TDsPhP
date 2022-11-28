<?php
namespace iutnc\deefy\audio\tracks;
require_once 'AudioTrack.php';
class Podcast extends AudioTrack
{
    private ?string $date;


    public function __construct(?string $titre = null, ?string $cheminFichier = null){
        parent::__construct($titre, $cheminFichier);
        $this->date = null;
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
