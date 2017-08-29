
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>Export strings></title>

  </head>
  <body>
<?php
error_reporting(E_ALL);

//header("Content-Type: text/xml; charset=UTF-8");

include_once("connect_db.php");

$id_game = $_GET["id_game"];

function writeFile($code, $my_xml, $name) {
    //echo $code." ".file_exists($code.".lproj")."\n";
    echo "writeFile ".$name."\n";
	$date = date("Y-m-d H:i:s");
	
	if (!file_exists($code.".lproj")) {
		mkdir($code.".lproj", 0755);
		//echo "mkdir".$code.".lproj";
	}
	/*
	if (file_exists("language.xml")) {
		rename('Localizable.strings.xml', 'backup_language/language_'.$date.".xml");
	}
	*/
	$filename = $code.".lproj/".$name;
	
	$handle = fopen($filename, "w+");
	if (fwrite($handle, $my_xml) === false) {
		echo "Cannot write to xml file. <br />";          
	} else {
    	echo $name." created successfully!";
	}
	fclose($handle);
}

function recurse_copy($src,$dst) { 
    $dir = opendir($src); 
    @mkdir($dst); 
    while(false !== ( $file = readdir($dir)) ) { 
        if (( $file != '.' ) && ( $file != '..' )) { 
            if ( is_dir($src . '/' . $file) ) { 
                recurse_copy($src . '/' . $file,$dst . '/' . $file); 
            } 
            else { 
                copy($src . '/' . $file,$dst . '/' . $file); 
            } 
        } 
    } 
    closedir($dir); 
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


function buildInfoPlist($key, $name, $value ) {
   
    if ($key == $name) {
        //echo $key." ".$name." ".$value.chr(13);
        $line = "\"".$name."\"\t = \"".html_entity_decode($value)."\";\n";
        return $line;
    }
    return "";
}

if (isset($id_game)) {
	//print "init";
	// Save english words
	$arrEnglish = array();
	$sql = "SELECT *
	FROM tr_translate
	JOIN tr_language ON tr_language.id_language=tr_translate.id_language
    JOIN tr_game ON tr_game.id_game=tr_translate.id_game
	WHERE tr_translate.id_game=$id_game
	AND tr_translate.id_language = 1
    ORDER BY code, keyword";
    $res = mysqli_query($mysql_resource,$sql) or die ($sql);
	while ($row = mysqli_fetch_array($res, MYSQL_ASSOC)) {
	   //echo $row["keyword"]."\n";
	   $arrEnglish[$row["keyword"]] = $row['translate'];
	}
	
	// Get all languages
	$arrLanguage = array();
	$sql = "SELECT *
	FROM tr_translate
	JOIN tr_language ON tr_language.id_language=tr_translate.id_language
    JOIN tr_game ON tr_game.id_game=tr_translate.id_game
	WHERE tr_translate.id_game=$id_game
    ORDER BY keyword";
    $res = mysqli_query($mysql_resource,$sql) or die ($sql);
    while ($row = mysqli_fetch_array($res, MYSQL_ASSOC)) {
        //echo $row["code"]." ".$row["id_language"]."\n";
        if ($row["code"] == "zh-TW") {
    		$row["code"] = "zh-Hant";
		}
		if ($row["code"] == "zh-CN") {
    		$row["code"] = "zh-Hans";
		}
        $arrLanguage[$row["code"]] = $row["id_language"];
	}
	/**/
	//echo $arrLanguage;
	//echo $code;
	//echo $id_language;
	foreach ($arrLanguage as $code => $id_language) {
    	$my_xml = "//////////".$code."\n";
    	
    	
    	echo "*************************************************** ".$code." ".$id_language."<br />".chr(13);
    	$infoPlist = "// InfoPlist.strings\n";
    	foreach($arrEnglish as $key => $value) {
            $sql = "SELECT *
        	FROM tr_translate
        	JOIN tr_language ON tr_language.id_language=tr_translate.id_language
            JOIN tr_game ON tr_game.id_game=tr_translate.id_game
        	WHERE tr_translate.id_game=$id_game
        	AND tr_translate.id_language=$id_language
        	AND tr_translate.keyword = '$key'
            ORDER BY keyword";
            
            $newValue = $value;
            
            $res = mysqli_query($mysql_resource, $sql) or die ("Error MySQL: ".$sql);
        	if (mysqli_num_rows($res) > 0) {
        	    $row = mysqli_fetch_array($res, MYSQL_ASSOC);
        	    $my_xml .= "\"".$key."\"\t = \"".stripcslashes(html_entity_decode($row["translate"]))."\";\n";
        	    $newValue = $row["translate"];
        	} else {
                $my_xml .= "\"".$key."\"\t = \"".stripcslashes((html_entity_decode($value)))."\";\n";
                $newValue = $value;
        	}
            $infoPlist .= buildInfoPlist($key, "CFBundleDisplayName", $newValue);
            $infoPlist .= buildInfoPlist($key, "CFBundleName", $newValue);
            $infoPlist .= buildInfoPlist($key, "NSAppleMusicUsageDescription", $newValue);
            $infoPlist .= buildInfoPlist($key, "NSBluetoothPeripheralUsageDescription", $newValue);
            $infoPlist .= buildInfoPlist($key, "NSCalendarsUsageDescription", $newValue);
            $infoPlist .= buildInfoPlist($key, "NSCameraUsageDescription", $newValue);
            $infoPlist .= buildInfoPlist($key, "NSContactsUsageDescription", $newValue);
            $infoPlist .= buildInfoPlist($key, "NSHealthShareUsageDescription", $newValue);
            $infoPlist .= buildInfoPlist($key, "NSHealthUpdateUsageDescription", $newValue);
            $infoPlist .= buildInfoPlist($key, "NSHomeKitUsageDescription", $newValue);
            $infoPlist .= buildInfoPlist($key, "NSLocationAlwaysUsageDescription", $newValue);
            $infoPlist .= buildInfoPlist($key, "NSLocationUsageDescription", $newValue);
            $infoPlist .= buildInfoPlist($key, "NSLocationWhenInUseUsageDescription", $newValue);
            $infoPlist .= buildInfoPlist($key, "NSMicrophoneUsageDescription", $newValue);
            $infoPlist .= buildInfoPlist($key, "NSMotionUsageDescription", $newValue);
            $infoPlist .= buildInfoPlist($key, "NSPhotoLibraryUsageDescription", $newValue);
            $infoPlist .= buildInfoPlist($key, "NSRemindersUsageDescription", $newValue);
            $infoPlist .= buildInfoPlist($key, "NSSiriUsageDescription", $newValue);
            $infoPlist .= buildInfoPlist($key, "NSSpeechRecognitionUsageDescription", $newValue);
            $infoPlist .= buildInfoPlist($key, "NSVideoSubscriberAccountUsageDescription", $newValue);

    	}
    	if ($_GET["ok"] == 1) {
    	    writeFile($code, $my_xml, "Localizable.strings");
    	    writeFile($code, $infoPlist, "InfoPlist.strings");
    	    recurse_copy("en.lproj", "Base.lproj");
    	    print "<br/ >Strings generated !";
    	} else {
            //print $my_xml;
            //print "\nApp name:\n";
            print "<br />infoPlist: ".$infoPlist;
    	}
	}

	

}
?>
    </body>
</html>

<?php
	exit;
?>