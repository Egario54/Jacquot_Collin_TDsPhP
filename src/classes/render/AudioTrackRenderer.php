<?php

namespace iutnc\deefy\render;

use iutnc\deefy\audio\tracks\AudioTrack;
use iutnc\deefy\exception\InvalidPropertyNameException;

abstract class AudioTrackRenderer
{

    protected AudioTrack $track;

    /**
     * @param AudioTrack $track
     */
    public function __construct(AudioTrack $track)
    {
        $this->track = $track;
    }


    /**
     * @throws InvalidPropertyNameException
     */
    public function render(int $selector=1): string
    {
        $codeHTML = "<audio controls src='{$this->track->__get("fichierAudio")}'></audio>";
        $codeHTML .= "<h3>{$this->track->__get("titre")}</h3>";
        if($selector === 2) {
            $codeHTML .= "<p> auteur : {$this->track->__get("auteur")} </p>\n";
            $codeHTML .= "<p> genre : {$this->track->__get("genre")} </p>\n";
            $codeHTML .= "<p> duree : {$this->track->__get("duree")} </p>\n";
        }
        return $codeHTML;
    }

    /**
     * @return AudioTrack
     */
    public function getTrack(): AudioTrack
    {
        return $this->track;
    }

    /**
     * @param AudioTrack $track
     */
    public function setTrack(AudioTrack $track): void
    {
        $this->track = $track;
    }

}