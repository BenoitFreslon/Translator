<?php
error_reporting(0);
$filename = "language.xml";
if (isset($newfilename)) {
	$filename = $newfilename;
}

include_once("connect_db.php");
header("Content-Type: text/xml; charset=UTF-8");
header('Content-Disposition: attachment; filename="'.$filename.'"');


//$id_game = 3;



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
    /*
    $sql = "SELECT * FROM tr_language WHERE code=".$_POST['code'];
    $res = mysqli_query($mysql_resource, $sql) or die ($sql);
    $row = mysqli_fetch_array($res, MYSQL_ASSOC);
    $id_language = $row["id_language"];
    */
	// Check if all keyword exist
    /*
	$sql = "SELECT *
    FROM tr_translate
    WHERE id_game=$id_game
    AND id_language=1
    ORDER BY keyword";
    
	$res = mysqli_query($mysql_resource, $sql) or die ($sql);
	/*
	while ($row = mysqli_fetch_array($res, MYSQL_ASSOC)) {
    
		$sql2 = "SELECT *
        FROM tr_translate
        JOIN tr_language ON tr_language.id_language=tr_translate.id_language
        AND keyword='".$row['keyword']."'
        ORDER BY keyword AND code";
        
		$res2 = mysqli_query($mysql_resource, $sql2) or die ($sql2);
		if (mysqli_num_rows($res2) == 0) {
			$keyword = $row['keyword'];
			$translate = $row['translate'];
			$sql3 = "INSERT INTO tr_translate VALUES ('','$id_language','$id_game','$keyword', '$translate', '', '')";
			mysqli_query($mysql_resource, $sql3) or die ($sql3);
		}
	}
	*/
	
	$my_xml = "";
	
	$old_code = "";
	$my_xml .= '<?xml version="1.0" encoding="UTF-8"?>'.chr(13);
	
	if (!$_GET["ok"]) {
		//print "Test mode";
		$my_xml .= "<!-- Test mode -->".chr(13);
		//exit;
	} else {
		
	}
	"<HelloTranslator><![CDATA[";
	
	
	$msg = "Hello players,\rIf you see errors here, typos or if you want to help me to translate my games on you own language please go to my website:\rhttp://www.benoitfreslon.com/contact-benoit-freslon or contact me by email: contact[@]benoitfreslon[.]com.\rPlease don't translate this xml file, there is a special module for that. :)\rThank you.\rBenoit.\rPS: Special thanks to all translators.";
	$msg = "http://www.benoitfreslon.com/modules/translator/";
	// Generate XML
	$sql = "SELECT *
	FROM tr_translate
	JOIN tr_language ON tr_language.id_language=tr_translate.id_language
    JOIN tr_game ON tr_game.id_game=tr_translate.id_game
	WHERE tr_translate.id_game=$id_game
    ORDER BY code, keyword";

	//echo $sql;
	
	$res = mysqli_query($mysql_resource, $sql) or die (sql);
	//$row = mysqli_fetch_array($res, MYSQL_ASSOC);
	//$code = $row['code'];

	// get all languages
	
	//$game = mysqli_result($res,0,"game");
	
	$my_xml .= "<!--".chr(13).$msg.chr(13)."-->".chr(13);
	
	
	
	/**
	if (!$_GET["ok"]) {
		//$msg = "";
		$my_xml .= $msg;
	} else {
		$my_xml .= "<!--".$msg."-->";
	}
	/**/
	
	
	$first = true;
	//$row = false;
	//$i = 0;
	while ($row = mysqli_fetch_array($res, MYSQL_ASSOC)) {
		//echo "<!-- ".$row["keyword"]." -- ".$row['translate']." -->";
		//echo "taille tab: ".count($row);
		if ($first) {
			//$my_xml .= "<HelloTranslator><![CDATA[".$msg."]]></HelloTranslator>".chr(13);
			$my_xml .= "<game name='".$row['game']."'>".chr(13);
			$first = false;
		}
        if ($old_code == "") {
			$my_xml .= chr(9)."<".$row['code']." code='".$row['code']."' language='".htmlspecialchars_decode($row['language'])."' english='".htmlspecialchars_decode($row['english'])."' representation='".$row['representation']."'>".chr(13);
			$old_code = $row['code'];
		}
		

		
		//$my_xml .= $row['code'];
        if ($old_code != "" && $old_code != $row['code']) {
			//$my_xml .= $old_code . " " .$old_code. " ".$row['code'];
			//$my_xml .= chr(9)."</lang>".chr(13);
			$my_xml .= chr(9)."</".$old_code.">".chr(13);
            $my_xml .= chr(9)."<".$row['code']." code='".$row['code']."' language='".stripslashes($row['language'])."' english='".htmlspecialchars_decode($row['english'])."' representation='".$row['representation']."'>".chr(13);
        }
        $old_code = $row['code'];
		if ($row['keyword'] != "") {
			$my_xml .= chr(9).chr(9)."<".$row['keyword']." k='".$row['keyword']."'><![CDATA[".stripslashes(html_entity_decode($row['translate']))."]]></".$row['keyword'].">".chr(13);
		} else {
			//print "error keyword empty";
		}
		//$i++;
	}
	//$my_xml .= chr(9)."</lang>".chr(13);
	$my_xml .= chr(9)."</".$old_code.">".chr(13);
	$my_xml .= "</game>".chr(13);
	
	print $my_xml;
	
	if (!$_GET["ok"]) {
		//print "Test mode";
		
		exit;
	}
	
	$date = date("Y-m-d H:i:s");
	/**/
	if (!file_exists("backup_language")) {
		mkdir("backup_language", 0700);
	}
	if (file_exists($filename)) {
		rename($filename, 'backup_language/language_'.$date.".xml");
	}
	
	$handle = fopen($filename, "w+");
	if (fwrite($handle, $my_xml) === false) {
		echo "Cannot write to xml file. <br />";          
	}
	fclose($handle);
	/**/
	
}	
?>