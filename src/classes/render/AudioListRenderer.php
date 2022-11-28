<?php
namespace iutnc\deefy\render;
require_once 'AlbumTrackRenderer.php';
require_once 'Renderer.php';
require_once 'RendererFactory.php';

use iutnc\deefy\render\Renderer as Renderer;
use iutnc\deefy\audio\lists\AudioList;
use Exception;

class AudioListRenderer implements Renderer
{
    private AudioList $audioList;

    /**
     * @param AudioList $audioList
     */
    public function __construct(AudioList $audioList)
    {
        $this->audioList = $audioList;
    }

    /**
     * @throws Exception
     */
    public function render(int $selector=1): string
    {
        // initialisation
        $codeHTML = "";
        // Parcours de la liste audio
        foreach ($this->audioList as $track) {
            $renderer = RendererFactory::getRenderer($track);
            $codeHTML .= $renderer->render($selector);
        }
        return $codeHTML;
    }


}