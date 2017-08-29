<?php

session_start();

include_once("connect_db.php");
//include_once("functions.php");

$action = $_POST["action"];

//echo $action;

$today = date("Y-m-d H:i:s");

if ($action == "change_game") {
//////////////////////////////////////////// Change game
    //print '<script>alert("Change game to'.$_POST['id_game'].'")</script>';
    $_SESSION['id_game'] = $_POST['id_game'];
    //echo '<script type="text/javascript">window.alert("session idgame '.$.'")</scritp>';
} else if ($action == "change_language") {
//////////////////////////////////////////// Change default language
    //echo '<script>alert("Language changed")</script>';
    $_SESSION['id_language'] = $_POST['new_id_language'];
} else if ($action == "change_default_language") {
//////////////////////////////////////////// Change language
    //echo '<script>alert("Language changed")</script>';
    $_SESSION['default_language'] = $_POST['default_language'];
} else if ($action == "add_language") {
    $code_language = $_POST["code_language"];
    $language_name = $_POST["language_name"];
    $language_english = $_POST["language_english"];
    if ($code_language != "" && $language_name != "") {
        //echo $code_language." ".$language_name;
        $sql = "INSERT INTO tr_language VALUES ('', '$code_language', '$language_name', '$language_english', '$language_representation', NULL)";
        mysqli_query($mysql_resource, $sql) or die ( "Error MySQL: ".$sql." ".mysqli_error($mysql_resource) );
        echo '<script>alert("Language '.$code_language.' > '.$language_name.' added")</script>';
    }
} else if ($action  == "add_keyword"){
//////////////////////////////////////////// ADD Keyword
    $new_keyword = $_POST['new_keyword'];
    $new_translate = mysqli_real_escape_string( $mysql_resource, htmlspecialchars($_POST['new_translate'] ));
    $new_translate = addslashes($new_translate);
    //$new_translate = $_POST['new_translate'];

    $id_lang = $_SESSION['id_language'];
    $sql = "SELECT id_translate FROM tr_translate WHERE keyword='$new_keyword' AND id_game=".$_SESSION['id_game'];
    $res = mysqli_query($mysql_resource, $sql) or die ("Error MySQL: ".$sql." ".mysqli_error($mysql_resource));
    $javascript = 'document.getElementById("new_keyword").focus().select();'.chr(13);
    //echo "<script>".$javascript."</script>";
    if (mysqli_num_rows($res) <1) {
        $sql = "INSERT INTO tr_translate VALUES('','1','".$_SESSION['id_game']."', '$new_keyword', '$new_translate' ,'".$_SESSION['id_translator']."', '$today', '0')";
        mysqli_query($mysql_resource, $sql) or die ("Error MySQL: ".$sql." ".mysqli_error($mysql_resource));
        //echo '<script>alert("Translate '.$new_keyword.' added")</script>';
        $feedback = "Translate ".$new_keyword." added";
    } else {
        echo '<script>alert("Keyword '.$new_keyword.' already exist in this game!")</script>';
    }
    return $javascript;
} else if ($action  == "update") {

    //echo ", Updating";

    //////////////////////////////////////////// Update
    $new_id_translate = $_POST['id_translate2'];
    $new_translate = mysqli_real_escape_string( $mysql_resource, htmlspecialchars($_POST['new_translate'] ));
    $new_translate = addslashes($new_translate);

    $new_keyword = $_POST['new_keyword'];
    $old_keyword = $_POST['old_keyword'];

    $deactivated = $_POST['deactivated'];

    $deactivated = $deactivated == "on" ? 1 : 0;
    $old_deactivated = $_POST['old_deactivated'];

    echo "deactivated: ".$deactivated." old_deactivated: ".$old_deactivated;


    if ($new_translate == "" && $new_keyword == "" && $_SESSION['admin']) {
        // Delete translate
        $sql = "DELETE FROM tr_translate WHERE keyword='$old_keyword' AND id_game='".$_SESSION['id_game']."'";
        $res = mysqli_query($mysql_resource, $sql) or die ("Error MySQL: ".$sql." ".mysqli_error($mysql_resource));
        //echo '<script>alert("'.$sql.'")</script>';        
        //echo '<script>alert("Keyword '.$new_keyword.' deleted!")</script>';
    } else if ($old_keyword == "") {
        //echo '<script>alert("Error update! Please contact the administrator\nkeyword: '.$old_keyword.'\n\new_translate: '.$new_translate.'")</script>';
    } else if ($new_translate == "") {
        $sql = "DELETE FROM tr_translate
        WHERE keyword='$old_keyword'
        AND
        id_game='".$_SESSION['id_game']."'
        AND id_language=".$_SESSION["id_language"];
        mysqli_query($mysql_resource, $sql) or die ("Error MySQL: ".$sql." ".mysqli_error($mysql_resource));
        //echo '<script>alert("Translate '.$old_keyword.' removed form the language"'.$_SESSION["id_language"].')</script>';
    } else {
        $id = $_POST['id'];
        $id_language = $_SESSION['id_language'];
        $id_translator = $_SESSION['id_translator'];
        $id_game = $_SESSION['id_game'];
    
        // echo "id"+$id;
        // echo "id_language"+$id_language;
        // echo "id_translator"+$id_translator;
        // echo "id_game"+ $id_game;

        // if translate doesn't exist
        $sql = "SELECT id_translate FROM tr_translate
        WHERE   id_game='$id_game'
        AND     id_language='$id_language'
        AND 	keyword='$old_keyword'";
    
        $res = mysqli_query($mysql_resource, $sql) or die ("Error MySQL: ".$sql." ".mysqli_error($mysql_resource));
    
        if ($_SESSION['admin'] && $old_keyword != $new_keyword) {
            $keyword = $new_keyword;
        } else {
            $keyword = $old_keyword;
        }

        if (mysqli_num_rows($res)) {
            $sql = "
            UPDATE  tr_translate
            SET     translate='$new_translate', keyword='$keyword', id_translator='$id_translator', date='$today'
            WHERE   id_translate='$new_id_translate'
            AND     id_game='".$_SESSION['id_game']."'";
        
            //echo '<script>alert("'.$_SESSION['admin'].' Translate '.$old_deactivated.' : '.$deactivated.' updated!")</script>';
            echo ", Updating line";
            // Mettre à jour tous les keywords des autres translate
            if ($_SESSION['admin'] && ($old_keyword != $new_keyword || $old_deactivated != $deactivated) ) {
                $sql2 = "UPDATE tr_translate
                SET keyword='$keyword', deactivated='$deactivated'
                WHERE keyword='$keyword'
                AND id_game='".$_SESSION['id_game']."'";
                $res = mysqli_query($mysql_resource, $sql2) or die ("Error MySQL: ".$sql2." ".mysqli_error($mysql_resource));
                echo "Change deactivation";
                //echo '<script>alert("Translate activation '.$old_keyword.' updated!")</script>';
            }
            mysqli_query( $mysql_resource, $sql ) or die ( "Error MySQL: ".$sql." ".mysqli_error($mysql_resource) );
            echo '<script>alert("Translate updated '.$new_translate.' updated!")</script>';
        } else if ( $old_keyword != "" && $new_translate != "" ){

            // Ajouter une nouvelle ligne de traduction
            $sql = "INSERT INTO tr_translate
            VALUES ('', '$id_language', '$id_game', '$old_keyword', '$new_translate', '$id_translator', '$today', '0')";
            mysqli_query($mysql_resource, $sql) or die ("Error MySQL: ".$sql." ".mysqli_error($mysql_resource));
            //echo '<script>alert("Translate '.$old_keyword.' created!")</script>';
        }
        return $javascript;
    }
} else if ($action == "add_translator") {

    $new_id_language = $_POST["new_id_language"];
    $new_nickname = $_POST["new_nickname"];
    $new_password = $_POST["new_password"];
    $new_email = $_POST["new_email"];

    $mail = "contact@benoitfreslon.com";

    $header = "From: $mail\r\n";
    $header .= "Reply-To: $mail\r\n";

    $object = "[BenoitFreslon.com] Game translation";

    $message = "Hello,

Sounds good, I'm happy to work with you.
I'm Benoit a french indie game designer.
I'm creating my own games alone but I need people like you to help me.

I just created an account on the translator module :

http://www.benoitfreslon.com/modules/translator/

Nickname: $new_nickname
Password: $new_password

Your task is simple: just translate or check the sentences on the right one by one,
and don't forget to click on the update button every modification.

Send me an email if you have any questions or when all you work is done.

If you see bugs on the game please report them.

Thank you again.

Benoit.
";

    mail($new_email, $object, $message, $header) or die ("Error sending email");
    mail("contact@benoitfreslon.com", $object, $message, $header) or die ("Error sending email");

    $sql = "INSERT INTO tr_translator VALUES ('', '$new_id_language', '$new_nickname', '$new_password', '$new_email')";
    $res = mysqli_query($mysql_resource, $sql) or die ("Error MySQL: ".$sql." ".mysqli_error($mysql_resource));

    print '<script>alert("Translator added!")</script>';
}
//////////////////

?>