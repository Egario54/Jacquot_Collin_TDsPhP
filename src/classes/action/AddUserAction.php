<?php
namespace iutnc\deefy\action;
class AddUserAction extends Action {

    public function execute(): string
    {
        if ($_SERVER['REQUEST_METHOD'] === "GET") {
            $html = <<<END
            <form id='add-user' method="post" action="index.php?action=add-user">
            <label>Email : <input name="email" type="email"></label>
            <label>Mot de passe : <input name="password" type="password"></label>
            <label>Confirmation du mot de passe : <input name="passwordverif" type="password"></label>
            <button type="submit">Inscription</button>
            </form>
            END;
        } else {
            if (Auth::register($_POST["email"], $_POST["password"], $_POST["passwordverif"])) {
                $html = "Vous avez été enregistré avec succès dans notre base de données";
            } else {
                $html = "Votre inscription n'a pas pu aboutir";
            }
        }
        return $html;
    }
}