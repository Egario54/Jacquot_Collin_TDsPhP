<?php

namespace iutnc\deefy\render;

require_once 'AudioTrackRenderer.php';
require_once 'Renderer.php';

use iutnc\deefy\audio\tracks\AlbumTrack;
use iutnc\deefy\exception\InvalidPropertyNameException;

class AlbumTrackRenderer extends AudioTrackRenderer implements Renderer
{

    /**
     * @param AlbumTrack $track
     */
    public function __construct(AlbumTrack $track)
    {
        parent::__construct($track);
    }

    /**
     * @throws InvalidPropertyNameException
     */
    public function render(int $selector=1):string {
        $codeHTML = parent::render($selector);
        if($selector === 2) {
            $codeHTML .= "<p> annee : {$this->track->__get("annee")} </p>\n";
            $codeHTML .= "<p> numPiste : {$this->track->__get("numPiste")} </p>\n";
            $codeHTML .= "<p> album : {$this->track->__get("album")} </p>\n";
        }
        return $codeHTML;
    }




}

