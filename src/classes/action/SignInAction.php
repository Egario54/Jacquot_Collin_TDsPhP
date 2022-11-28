<?php
namespace iutnc\deefy\action;

class SignInAction extends Action {

    public function execute(): string
    {
        $html = "";
        if ($_SERVER['REQUEST_METHOD'] === "GET") {
            $html = <<<END
            <form id='signin' method="post" action="index.php?action=signin">
            <label>Email : <input name="email" type="email"></label>
            <label>Password : <input name="password" type="password"></label>
            <button type="submit">connection</button>
            </form>
            END;
        }else {
            try {
                if (Auth::authenticate($_POST["email"], $_POST["password"])) {
                    $user = unserialize($_SESSION["userAuthentified"]);
                    $playlist = $user->getPlaylists();
                    foreach ($playlist as $audiolist) {
                        $render = new AudioListRenderer($audiolist);
                        $html += $render->render();
                        $html +=" <br>";
                    }
                } else {
                    $html = <<<END
                        <form id='signin' method="post" action="index.php?action=signin">
                        <label>Connection denied</label>
                        </form>
                    END;
                }
            } catch (AuthException $e) {
                $html = $e->getMessage();
            }
        }
        return $html;
    }
}