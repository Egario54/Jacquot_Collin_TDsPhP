<?php
namespace iutnc\deefy\action;
class AddPodcastTrackAction extends Action {
    public function execute(): string
    {
        $html = "";
        if($_SERVER['REQUEST_METHOD']==='GET'){
            $html.= <<<END
            <form method = "post" action = "?action=add-podcasttrack" enctype"multipart/form-data">
            <input type ="file" name=""><br>
            <input type ="text" name="titre" placeholder = "<titre>"><br>
            <input type ="text" name="genre" placeholder = "<genre>"><br>
            <input type ="number" name="duree" placeholder = "<duree>"><br>
            <input type ="text" name="auteur" placeholder = "<auteur>"><br>
            <input type ="date" name="date" placeholder = "<date>"><br>
            <button type ="submit"></button>
            </form>
            END;
        } else {
            $storage = new FileSystem('./audio');
            $file = new File ('inputfile',$storage);
            $new_filename = uniqid();
            $file->setName($new_filename);
            $file->addValidations(array(new Mimetype('application/octet-stream')),new Extension('mp3'),new Size('20M'));
            try{
                $file->upload();

                $titre = filter_var($_POST['titre'],FILTER_SANITIZE_STRING);
                $genre = filter_var($_POST['genre'],FILTER_SANITIZE_STRING);
                $duree = (int) filter_var($_POST['duree'],FILTER_SANITIZE_NUMBER_INT);
                $auteur = filter_var($_POST['auteur'],FILTER_SANITIZE_STRING);
                $date = filter_var($_POST['date'],FILTER_SANITIZE_STRING);
                $nom_fichier = "audio/$new_filename.mp3";

                $track = new PodcastTrack($titre, $nom_fichier);
                $track->genre=$genre;
                $track->duree=$duree;
                $track->auteur=$auteur;
                $track->date=$date;

                $html .=  'Upload réussi <br>';

                $playlist = unserialize($_SESSION['playlist']);
                $playlist->ajouterPiste($track);
                $_SESSION['playlist'] = serialize($playlist);
                $html .= (new AudioListRenderer($playlist))->render(0);
                $html .='<a href="?action=add-podcasttrack">Ajouter une autre piste</a>';
            } catch (\Exception | \ValueError $e){
                $errors = $file->getErrors();
                $html.= 'Erreur upload : <ul>';
                foreach ($errors as $error) $html.= "<li>$error</li>";
                $html .= '</ul>';
            }
        }
        return $html;
    }
}