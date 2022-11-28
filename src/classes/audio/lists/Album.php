<?php
namespace iutnc\deefy\audio\lists;
use iutnc\deefy\exception\NonEditablePropertyException;
require_once 'AudioList.php';

class Album extends AudioList
{
    private ?string $artiste, $dateSortie;

    public function __construct(string $nom ,array $pistes, ?string $nomArt = null, ?string $dateS = null)
    {
        $this->artiste = $nomArt;
        $this->dateSortie = $dateS;
        parent::__construct($nom, $pistes);
    }

    public function __get(string $name)
    {
        if(property_exists($this, $name)) return $this->$name;
        else return parent::__get($name);
    }

    /**
     * @throws NonEditablePropertyException
     */
    public function __set(string $name, $value): void
    {
        if(property_exists($this, $name)) {
            if ($name === "pistes") throw new NonEditablePropertyException("$name ne peut etre modifier");
            $this->$name = $value;
        }
        else parent::__set($name, $value);
    }


}