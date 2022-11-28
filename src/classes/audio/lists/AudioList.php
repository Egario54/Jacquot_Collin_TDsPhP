<?php
namespace iutnc\deefy\audio\lists;
use Iterator;
use iutnc\deefy\exception\InvalidPropertyNameException;

abstract class AudioList implements Iterator
{
    private string $nom;
    private int $nombrePistes, $dureeTotal;
    private array $pistes;
    private int $position;

    /**
     * @param string $nom
     * @param array $pistes
     */
    public function __construct(string $nom, array $pistes=[])
    {
        $this->nom = $nom;
        $this->pistes = $pistes;
        $i = 0; // nombre de piste (compte)
        $tempTotal = 0; // temp accumuler
        foreach ($pistes as $value) {
            $i++; // nombre de boucle = nombre de piste
            $tempTotal += $value->duree; // somme des durees des pistes
        }
        $this->position = 0;
        $this->nombrePistes = $i;
        $this->dureeTotal = $tempTotal;
    }

    /**
     * @throws InvalidPropertyNameException
     */
    public function __get(string $name)
    {
        if(property_exists($this, $name)) return $this->$name;
        else throw new InvalidPropertyNameException("$name n'est pas une propriete");
    }

    public function current()
    {
//        var_dump(__METHOD__);
        return $this->pistes[$this->position];

    }

    public function next()
    {
//        var_dump(__METHOD__);
        $this->position++;
    }

    public function key()
    {
//        var_dump(__METHOD__);
        return $this->position;
    }

    public function valid(): bool
    {
//        var_dump(__METHOD__);
        return isset($this->pistes[$this->position]);
    }

    public function rewind()
    {
//        var_dump(__METHOD__);
        $this->position = 0;
    }

    public function __set(string $name, mixed $value)
    {
        if(property_exists($this, $name)) $this->$name = $value;
        else throw new InvalidPropertyNameException("$name n'est pas un argument d'AudioList ");
    }
}