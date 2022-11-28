<?php

namespace iutnc\deefy\render;

require_once 'Renderer.php';
require_once 'AudioTrackRenderer.php';

use iutnc\deefy\audio\tracks\AudioTrack;
use iutnc\deefy\audio\tracks\Podcast;
use iutnc\deefy\exception\InvalidPropertyNameException;

class PodcastRenderer extends AudioTrackRenderer implements Renderer
{

    /**
     * @param Podcast $podcast
     */
    public function __construct(Podcast $podcast)
    {
        parent::__construct($podcast);
    }

    /**
     * @throws InvalidPropertyNameException
     */
    public function render(int $selector =1): string
    {
        $codeHTML = parent::render($selector);
        if($selector === 2) {
            $codeHTML .= "<p> date : {$this->track->__get("date")} </p>\n";
        }
        return $codeHTML;
    }

    /**
     * @return AudioTrack
     */
    public function getPodcast(): AudioTrack
    {
        return $this->track;
    }

    /**
     * @param Podcast $podcast
     */
    public function setPodcast(Podcast $podcast): void
    {
        $this->track = $podcast;
    }


}

