<?php

namespace iutnc\deefy\dispatcher;

use iutnc\deefy\action\AddPlaylistAction;
use iutnc\deefy\action\AddPodcastTrackAction;
use iutnc\deefy\action\AddUserAction;
use iutnc\deefy\action\DisplayPlaylistAction;
use iutnc\deefy\action\SignInAction;

class Dispatcher
{
    /**
     * Méthode qui effectue l'action adaptée
     * @return void
     */
    public function run():void{
        $action = $_GET['action'];
        $res = "";
        switch ($action){
            case "signin":
                $act = new SignInAction();
                $res = $act->execute();
                break;
            case "add-user":
                $act = new AddUserAction();
                $res = $act->execute();
                break;
            case "display-playlist":
                $act = new DisplayPlaylistAction();
                $res = $act->execute();
                break;
            case "add-playlist":
                $act = new AddPlaylistAction();
                $res = $act->execute();
                break;
            case "add-podcasttrack":
                $act = new AddPodcastTrackAction();
                $res = $act->execute();
                break;
            default :
                print "Bienvenue ! <br>";
        }
        $this->renderPage($res);
    }

    /**
     * Méthode qui effectue un rendu de la page
     * @param String $html
     * @return void
     */
    private function renderPage(String $html) : void {
        $res = <<<END
        <!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <title>Deefy</title>
        </head>
        <body>
        END;
        $res .= $html;
        $res .= "</body>
        </html>";
        echo $res;
    }
}