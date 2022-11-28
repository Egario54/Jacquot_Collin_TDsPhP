<?php
namespace iutnc\deefy\action;
class DisplayPlaylistAction extends Action {

    public function execute(): string
    {
        $html = "";
        session_start();
        if(!isset($_SESSION['user'])) {
            $html = "<a href=?=authentificate>Veuillez vous connecter</a>";
        } else {
            $user = unserialize($_SESSION['user']);
            $query = <<<end
                    SELECT id FROM user WHERE email = ?; 
                end;
            $result = ConnectionFactory::makeConnection()->prepare($query);
            $email = $user->getEmail();
            $result->bindParam(1, $email);
            $result->execute();
            $data = $result->fetch();
            $id = $data['id'];
            if(Auth::isOwnPlaylist($id, $_GET['id'])) {
                $pl = Playlist::find($_GET['id']);
                $render = new AudioListRenderer($pl);
                $html = $render->render(Renderer::COMPACT);
            } else {
                $html = "You're not the owner of this playlist";
            }
        }
        return $html;
    }
}