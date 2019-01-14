<?php
	//on pageload
    $idletime=60;//after 60 seconds the user gets logged out
    if (time()-$_SESSION['timestamp']>$idletime){
        session_destroy();
        session_unset();

        $_SESSION['msg'];
        $_SESSION['alertcolor'];
        header("Location: index.php");
    }else{
        $_SESSION['timestamp']=time();
    }
    //on session creation
    $_SESSION['timestamp']=time();
?>