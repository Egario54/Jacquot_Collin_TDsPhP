<?php
namespace iutnc\deefy\action;
class AddPlaylistAction extends Action{

    public function execute(): string
    {
        $html ="";
        if ($_SERVER['REQUEST _METHOD'] == "GET") {
            $html = <<<END
            <form method="post" action="add-playlist">
                <Label>Nom de la playlist <input type = "text" name = "nom" placeholder="<nom>">
            <button type="submit">Créer</button>
            </Label>
            </form>
            END;
        } else { // POST
                $nom = Filter_var($_POST["nom"], FILTER_SANITIZE_STRING);
                $playlist = new Playlist ($nom);
                $_SESSION['playlist'] = serialize($playlist);
                $html = 'Création de la playlist'."<b>$nom</b>".'réussie <br>';
                $html .= (new AudioListRenderer($playlist))->render(0);
                $html .='<a href="?action=add-podcasttrack">Ajouter une piste</a>';
        }
        return $html;
    }
}