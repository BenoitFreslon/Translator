<?php

// GET Params
// get = 1 > Download
// ok = 1 > Generate
// id_game = id of the game

$filename = "Localization.xml";
if (isset($newfilename)) {
	$filename = $newfilename;
}

error_reporting(0);


include_once("connect_db.php");

header("Content-Type: text/xml; charset=UTF-8");

if ($_GET["get"]) {
	header('Content-Disposition: attachment; filename="'.$filename.'"');
}

if ( !function_exists('htmlspecialchars_decode') )
{

    function htmlspecialchars_decode($value)	{
        $value = str_replace('>','>',$value);
        $value = str_replace('<','<',$value);
        $value = str_replace('"','"',$value);
        $value = str_replace('&','&',$value);
        return $value;
    }
}

// PHP 4
/*
function htmlspecialchars_decode($string,$style=ENT_COMPAT)
{
    $translation = array_flip(get_html_translation_table(HTML_SPECIALCHARS,$style));
    if($style === ENT_QUOTES){ $translation['&#039;'] = '\''; }
    return strtr($string,$translation);
}
*/
if (isset($id_game)) {

	$my_xml = "";
	$old_code = "";
	
	$my_xml .= '<?xml version="1.0" encoding="UTF-8"?>'.chr(13);
	
	if (!$_GET["ok"]) {
		$my_xml .= "<!-- Test mode -->".chr(13);
	} else {
		
	}
	"<HelloTranslator><![CDATA[";
	
	$msg = "Hello players,\rIf you see errors here, typos or if you want to help me to translate my games on you own language please go to my website:\rhttp://www.benoitfreslon.com/contact-benoit-freslon or contact me by email: contact[@]benoitfreslon[.]com.\rPlease don't translate this xml file, there is a special module for that. :)\rThank you.\rBenoit.\rPS: Special thanks to all translators.";
	$msg = "http://www.benoitfreslon.com/modules/translator/";
	
	$sql = "SELECT *
	FROM tr_translate
	JOIN tr_language ON tr_language.id_language=tr_translate.id_language
    JOIN tr_game ON tr_game.id_game=tr_translate.id_game
	WHERE tr_translate.id_game=$id_game
    ORDER BY code, keyword";

	//echo $sql;
	
	$res = mysqli_query($mysql_resource, $sql) or die (sql);
	
	$my_xml .= "<!--".chr(13).$msg.chr(13)."-->".chr(13);
	
	$first = true;

	while ($row = mysqli_fetch_array($res, MYSQL_ASSOC)) {

		if ($first) {
			$my_xml .= "<game id='".$row['id_game']."' name='".$row['game']."'>".chr(13);
			$first = false;
		}
        if ($old_code == "") {
			$my_xml .= chr(9)."<lang code='".$row['code']."' language='".htmlspecialchars_decode($row['language'])."' english='".htmlspecialchars_decode($row['english'])."' representation='".$row['representation']."'>".chr(13);
			$old_code = $row['code'];
		}

        if ($old_code != "" && $old_code != $row['code']) {
			$my_xml .= chr(9)."</lang>".chr(13);
            $my_xml .= chr(9)."<lang code='".$row['code']."' language='".htmlspecialchars_decode($row['language'])."' english='".htmlspecialchars_decode($row['english'])."' representation='".$row['representation']."'>".chr(13);
        }
        $old_code = $row['code'];
		if ($row['keyword'] != "") {
			$my_xml .= chr(9).chr(9)."<t k='".$row['keyword']."'><![CDATA[".stripslashes(html_entity_decode($row['translate']))."]]></t>".chr(13);
		} else {
		}
	}
	$my_xml .= chr(9)."</lang>".chr(13);
	$my_xml .= "</game>".chr(13);
	 
	// Show XML
	print $my_xml;
	
	// Generate XML
	
	if (!$_GET["ok"]) {
		exit;
	}
	
	$date = date("Y-m-d H:i:s");
	/**/
	if (!file_exists("backup_language")) {
		mkdir("backup_language", 0700);
	}
	if (file_exists("language.xml")) {
		rename($filename, 'backup_language/language_'.$date.".xml");
	}
	
	
	$handle = fopen($filename, "w+");
	if (fwrite($handle, $my_xml) === false) {
		echo "Cannot write to xml file. <br />";          
	}
	fclose($handle);
	
}	
?>