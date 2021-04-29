<?php
    session_start();
    include_once('connexionDB.php');

    if(isset($_SESSION['username']) && !empty($_SESSION['username'])) {
        $id = $_GET['id'];
        $regexNb = '/^[0-9]+$/i';
        if(!preg_match($regexNb,$id)) {
            header('Location: films.php');
            exit();
        } else {
            $request = $bdd->prepare('SELECT count(*) FROM Comments WHERE commentId=:id');
            $request->bindValue(':id',$id);
            $request->execute;
            if($request == 1) {
                $deleteComment = $bdd->prepare('DELETE FROM Commentaires WHERE commentId=:id');
                $deleteComment->bindValue(':id',$id);
                $deleteComment->execute();
                header('Location: films.php');
            } else {
                header('Location: films.php');
                exit();
            }
        }
    }

?>