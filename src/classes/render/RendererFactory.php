<?php

namespace iutnc\deefy\render;
require_once 'Renderer.php';

use Exception;
use iutnc\deefy\audio\tracks\AlbumTrack;
use iutnc\deefy\audio\tracks\Podcast;
use iutnc\deefy\audio\tracks\AudioTrack;

class RendererFactory
{
    /**
     * @throws Exception
     */
    public static function getRenderer(AudioTrack|AlbumTrack|Podcast $track) : Renderer {
        if(get_class($track) === "iutnc\deefy\audio\\tracks\AlbumTrack") {
            return new AlbumTrackRenderer($track);
        } elseif(get_class($track) === "iutnc\deefy\audio\\tracks\Podcast") {
            return new PodcastRenderer($track);
        } else throw new Exception("Don't know this track" );
    }
}