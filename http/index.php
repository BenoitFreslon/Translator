<?php

include_once("functions.php");

ini_set('display_errors',1);

session_start();

include_once("connect_db.php");

$javascript = "";
$feedback = "";

if ($_POST["disc"]) {
    $_SESSION['info'] = 0;
    $_SESSION['logged'] = 0;
	$_SESSION['admin'] = 0;
	$_SESSION['order'] = "id";
	$_SESSION['default_language'] = 1;
	session_unset();
}

//debug();

if ($_SESSION['info'] != 1 && $_SESSION['logged'] != 1) {
    login();
    $_SESSION['info'] = 1;
	exit;
} else if ($_SESSION['logged'] != 1) {
    $nickname = $_POST["nickname"];
    $password = $_POST["password"];
	// Check the input details against database
	$sql = "
	SELECT
	*
	FROM
	tr_translator
	JOIN tr_language ON tr_language.id_language = tr_translator.id_language
	WHERE
	nickname='".$nickname."' AND password='".$password."'";
	$res = mysqli_query($mysql_resource,  $sql ) or die ("<script>alert('Erreur MYSQL $sql')</script>");
	$aso = mysqli_fetch_array( $res );
    //print $sql;
	// Stop invalid logins
    //print mysqli_num_rows($res);
	if( mysqli_num_rows($res) == 0 ) {
        $_SESSION['info'] = 0;
        
		print '<script>alert("Wrong password or nickname please contact the administrator.")</script>';
		login();
        exit;
	} else if ($_SESSION['logged'] != 1){
		$_SESSION['logged'] = 1;
		$_SESSION['id_translator'] = $aso['id_translator'];
		$_SESSION['id_game'] = $aso['id_game'];
		$_SESSION['id_language'] = $aso['id_language'];
		$_SESSION['code'] = $aso['code'];
		$_SESSION['order'] = "id";
		$_SESSION['default_language'] = 1;
        
		if ($nickname == "admin") {
			$_SESSION['admin'] = 1;
			$_SESSION['order'] = "id";
			$_SESSION['id_language'] = 1;
			$_SESSION['id_game'] = 10;
            echo '<script>alert("Hello admin")</script>';
		}
		$_SESSION['id_translator'] = $aso['id_translator'];
        //print "<script>alert('Logged')</script>";
	}
	
}

$javascript = parseAction($mysql_resource, $_POST['action']);

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

		<?php
		if ($_SESSION['id_language'] && $_SESSION['id_game']) {
			$gameName = getGameName ($mysql_resource, $_SESSION['id_game'] );
			$languageName = getLanguageName ($mysql_resource, $_SESSION['id_language'] );
		}
		?>
		<title>Translator | <?php echo $gameName. " - ".$languageName; ?></title>
		<link rel="stylesheet" type="text/css" href="style.css">
		<script type="text/javascript" src="functions.js"></script>
	</head>



<body>

<?php

?>

<div align="right">
    <form method="POST">
        <input type="hidden" name="disc" value="1" />
        <input type="submit" value="X" />
    </form>
</div>

<div  align="center">
<b>Select a game</b>
<form id="form_game" name="form_game" method="post" action="#">
  
    <select name="id_game" id="select" onchange="this.form.submit()" >
    <?php
        //gecho '<script>alert("id game '.$_SESSION['id_game'].' updated!")</script>';
		$sql = "SELECT * FROM tr_game";
		$res = mysqli_query($mysql_resource, $sql);
		while ($row = mysqli_fetch_array($res, MYSQL_ASSOC)) {
			echo "<option value='".$row['id_game']."'";
			if ($row['id_game'] == $_SESSION['id_game']) {
				echo " selected='selected'";
				$url_language = $row['url_language'];
			}
			echo ">".$row['id_game']." - ".$row['game']."</option>".chr(13);
		}
	?>
    </select>
  
  <input type="hidden" name="action" value="change_game"   />
</form>
<?php

if ($_SESSION["admin"] == 1) {

?>
<div>
	<a href="<?php echo $url_language; ?>?id_game=<?php echo $_SESSION['id_game']; ?>" target='_blank'>Read</a> | 
	<a href="<?php echo $url_language; ?>?ok=1&id_game=<?php echo $_SESSION['id_game']; ?>" target='_blank'>Generate</a> | 
	<a href="<?php echo $url_language; ?>?ok=1&get=1&id_game=<?php echo $_SESSION['id_game']; ?>" target='_blank'>Download</a>
</div>
<br />


<div style='vertical-align: top; text-align: top;'>
	<b>Add Language</b><br />
	<form id="form_add_language" name="form_add_language" method="post" action="#">

		<input type="text" name="code_language" value='code' size="15" />
		<input type="text" name="language_name" value="name" size="15" />
        <input type="text" name="language_english" value="english" size="15" />
        <input type="text" name="language_representation" value="representation" size="15" />
		<input type="submit" value="Add" <?php if ($_SESSION['admin'] == 0) {echo " disabled='disabled'";}?> />
		<input type="hidden" name="action" value="add_language"  />
	</form>
</div>

<br />

<div style='vertical-align: top; text-align: top; text-align:center;'>
    <b>Add keyword</b><br />
    <form id="form_add_keyword" name="form_add_keyword" method="post" action="#">
        
        <input type="text" id="new_keyword" name="new_keyword" value='' />
        <input type="text" name="new_translate" size="80" />
        <input type="submit" value="Add" />
        <input type="hidden" name="action" value="add_keyword"  />
       
    </form>
    <font color='#FF0000'><b><?php echo $feedback; ?></b></font>
</div>


<br />

<div>
	<b>Translators</b>
	<table border="1">
	<?php
	$sql = "SELECT DISTINCT(email),code,english,nickname
	FROM tr_translate
	JOIN tr_translator ON tr_translate.id_translator = tr_translator.id_translator
	JOIN tr_language ON tr_translate.id_language=tr_language.id_language
	WHERE tr_translate.id_game=".$_SESSION['id_game']." ORDER BY tr_language.code";
		$res = mysqli_query($mysql_resource, $sql) or die ("Error MySQL: ".$sql);
		while ($row = mysqli_fetch_array($res, MYSQL_ASSOC)) {
			if ($row["nickname"] != "admin")
				echo "<tr><td>".$row["code"]."</td><td>".$row["english"]."</td><td>".$row["email"]."</td><td>".$row["nickname"]."</td></tr>";
		}
	?>

</table>
</div>
<br />

<b>Add translator</b><br />
<form id="form_add_translator" name="form_add_translator" method="post" action="#">
    <select name="new_id_language" id="new_id_language" onchange="this.form.submit()">
    <?php
		
		$sql = "SELECT * FROM tr_language WHERE id_language != 0 ORDER BY language";
		$res = mysqli_query($mysql_resource, $sql) or die ("Error MySQL: ".$sql);
		while ($row = mysqli_fetch_array($res, MYSQL_ASSOC)) { 
			echo "<option value='".$row["id_language"]."'>".$row["code"]." - ".$row['language']."</option>".chr(13);
		}
		
	?>
    </select>
	<input type="text" name="new_nickname" size='20' value='nickname' />
    <input type="text" name="new_password" size="20" value="password" />
    <input type="text" name="new_email" size="25" value="email" />
    <input type="hidden" name="action" value="add_translator" />
</form>
<br />

<?php
}
?>

<?php 
if ($_POST["order"]) {
	$_SESSION["order"] = $_POST["order"];
}
?>
<form id="form_order" name="form_order" method="post" action="#">
	<input type="radio" onchange="this.form.submit()" name="order" id="order" value="id" <?php if($_SESSION["order"] == "id") { echo "checked='checked'";} ?> >Order by ID
	
	<input type="radio" onchange="this.form.submit()" name="order" id="order" value="date" <?php if($_SESSION["order"] == "date") { echo "checked='checked'";} ?> >Order by update
	
	<input type="radio" onchange="this.form.submit()" name="order" id="order" value="keyword" <?php if($_SESSION["order"] == "keyword") { echo "checked='checked'";} ?> >Order by keyword
</form>

<?php
?>

<table border="1" cellpadding="0" cellspacing="0" style="width:100%;" >
<tr>

		<td>#id</td>
		<td>X</td>
		<td>Keywords</td>
		<td style="max-width:200px;">
		<!-- LANGUAGE DEFAULT -->
		Select the reference language.<br />The English language is the most accurate.
		<form id="form_default_language" name="form_default_language" method="post" action="#">
          
            <select name="default_language" id="default_language" onchange="this.form.submit()">
            <?php
                
                $sql = "SELECT * FROM tr_language";
                $res = mysqli_query($mysql_resource, $sql) or die ("Error MySQL: ".$sql);
                while ($row = mysqli_fetch_array($res, MYSQL_ASSOC)) { 
                    echo "<option value='".$row["id_language"]."'";
                    if ($row['id_language']== $_SESSION['default_language']) {
                        echo "selected='selected'";
                    }	
                    echo ">".htmlspecialchars_decode($row["code"])." - ".$row['language']." - ".$row['english']."</option>".chr(13);
                }
        
            ?>
            </select>
          
          <br />
          <input type="hidden" name="action" value="change_default_language" />
        </form>
        
		</td>
        
		<td>
		<form id="form_language" name="form_language" method="post" action="#">
    
    <select name="new_id_language" id="new_id_language" onchange="this.form.submit()">
    <?php
		if ($_SESSION['admin']) {
			$sql = "SELECT * FROM tr_language WHERE id_language != 0 ORDER BY code";
		} else {
			$sql = "SELECT * FROM tr_language WHERE id_language=".$_SESSION['id_language'];
		}
		$res = mysqli_query($mysql_resource, $sql) or die ("Error MySQL: ".$sql);
		while ($row = mysqli_fetch_array($res, MYSQL_ASSOC)) { 
			echo "<option value='".$row["id_language"]."'";
			if ($row['id_language']== $_SESSION['id_language']) {
				echo "selected='selected'";
			}	
			echo ">".htmlspecialchars_decode($row["code"])." - ".$row['language']." - ".$row['english']."</option>".chr(13);
		}
		
	?>
    </select>
  
  <br />
  <input type="hidden" name="action" value="change_language" />
</form>
		<div style="text-align:left">
		<font color="#FF0000">Translate all colored texts here. Click UPDATE or press ENTER after each modifications.<br/><b>Leave special chars: \n \r &lt;br /&gt;, respect the case sensitive, the meaning and the punctuation.</font>
		<br/>
		<br />
    		
    		<font color="#FF0000">Red text: To be translated</font><br />
    		<font color="#3399CC">Blue text: To validate. To modify if needed.</font><br />
    		<font color="#AAAAAA"><strike>Grey text</strike>: Disabled text.</font><br />
		</b>
		</div>
		</td>
		
		<td style="width:120px;" >And click Update or press the Enter key</td>
	</tr>
<?php

	if ($_SESSION["id_game"] == "") {
		exit;
	}
    $startTime = getCurTime();
    $sql =  "SELECT t1.*,
            t2.id_translate as id_translate2,
            t2.translate as translate2,
            t2.date as date2       
            FROM tr_translate as t1
            LEFT JOIN tr_translate as t2 ON t2.keyword=t1.keyword AND t2.id_language='".$_SESSION['id_language']."' AND t2.id_game='".$_SESSION['id_game']."'
            LEFT JOIN tr_translator ON tr_translator.id_translator=t2.id_translator
            WHERE t1.id_game='".$_SESSION['id_game']."'";
            
            if (!$_SESSION["admin"]) {
                $sql .= "
                AND t1.deactivated='0'";
            }
            
            
            $sql .= "
            AND t1.id_language='".$_SESSION['default_language']."'";   


	if ($_SESSION["order"] == "keyword") {
		$sql .= " ORDER BY t1.keyword";
	} else if ($_SESSION["order"] == "date") {
		$sql .= " ORDER BY t1.date DESC";
	} else {
	    $sql .= " ORDER BY t1.id_translate";
	}

    //echo $sql;

	$res = mysqli_query($mysql_resource, $sql) or die ("Error MySQL: ".$sql." ".mysqli_error($mysql_resource));
	$i = 0;
	while ($row = mysqli_fetch_array($res, MYSQL_ASSOC)) { 	
		
		$color = "";
		$style2 = "style='background-color:#FFFFFF'";
		if ($i%2 == 0){
			$color = "style='background-color:#EEEEEE;'";
			$style2 = "style='background-color:#EEEEEE;'";
		}
		$i++;
		$keyword = $row['keyword'];
		$value = stripslashes($row['translate']);
		//$value = $row['translate'];
		$id_translate = $row['id_translate'];
		$date1 = $row["date"];
		$deactivated = $row["deactivated"] == 1 ? true : false;
		
		echo '<form id="form_'.$keyword.'" name="form_'.$keyword.'" method="post" action="#'.$keyword.'">'.chr(13);
		echo "<tr $color>".chr(13);
			echo "<td>".$id_translate."</td>";
			
			// Add checkbox
			
    			echo "<td><input type='checkbox' name='deactivated'";
    			if ($deactivated) {
        			 echo "checked='checked'";
    			}
    			if (!$_SESSION['admin']) {
    			     echo "disabled='disabled'";
    			}
    			echo "/></td>";
			echo "<td><input $style2 type='text' name='new_keyword' value='$keyword' size='20'";
				if (!$_SESSION['admin']) {
					echo ' disabled="disabled" ';
				}
			echo "/>".chr(13);
			echo "<a name='".$keyword."'></a>".chr(13);
			echo "</td>".chr(13);
			if ($deactivated) {
    			echo "<td style='text-align: left;height:auto;' $color><strike>$value</strike></td>".chr(13);
			} else {
    			echo "<td style='text-align: left;height:auto;' $color>$value</td>".chr(13);
			}
			
			echo "<td style='height:auto'>".chr(13);
			// get translated text
			$style = "";

			$date2 = $row["date2"];
			$id_translate2 = $row["id_translate2"];
			
			$already = false;
			if ($id_translate2 != "") {
				$value = stripslashes($row['translate2']);
				//$value = $row['translate2'];

				$already = true;
			} else {
				$value = $value;
				$style = "style='color:#FF0000;'";
				if ($row["deactivated"] == 0)
				    $style2 = "style='background-color:#FF0000; color:#FFFFFF;'";
			}

			if($already && $date1 && $date2 && $date1 > $date2) {
    			$style = "style='color:#3399CC;'";
    			if ($row["deactivated"] == 0)
    			    $style2 = "style='background-color:#3399CC; color:#FFFFFF;'";
			} 			
			
			//echo "date1: ".$date1." date2: ".$date2;
			

			if ($deactivated) {
    			$style = "style='color:#888888;'";
			}
			
			$len = strlen($value);
			
			if (strpos($value, "\n")) {
				$size = 2;
			} else {
				$size = 1;
				
				//$style .=" height:16px;";
			}
			$size = 4;
			$len = strlen($value);
			
			// Comment this textarea
            if ($len > 100 && false) {
				//echo "<textarea name='new_translate' cols='120' rows='".(round($len/100)-1)."' $style $style2>".chr(13);
				echo "<textarea name='new_translate' cols='80' rows='".$size."' style='$style' scrollbar='yes'";
				
				if (!$_SESSION["admin"] && $row["deactivated"] == 1) {
    				echo "disabled='disabled'";
				}
				
				echo ">".chr(13);
				echo $value.chr(13);
				echo "</textarea>";
            } else {
            	echo "<input type='text' id='input_".$i."' name='new_translate' size='80' value=\"$value\" $style $style2 tabindex='".($i+1)."' title='edited by ".$row["nickname"]." ".$row["email"]." ".$row["date2"]."'";
            	
            	if (!$_SESSION["admin"] && $row["deactivated"] == 1) {
    				echo "disabled='disabled'";
				}
            	
            	echo "/>".chr(13);

            }
			echo "</td>".chr(13);
			echo "<td style=''>".chr(13);
			echo "<input type='submit' name='submit' onClick='update(form_".$keyword.")' value='Update' $style2 ";
			
			if (!$_SESSION["admin"] && $deactivated) {
    			echo "disabled='disabled'";
			}
			
			echo "/>".chr(13);
			echo "<input type='hidden' name='id_translate2' value='$id_translate2'/>".chr(13);
			echo "<input type='reset' value='Undo' onClick='window.confirm(\"Are you sure to undo your modfications?\")' ";
			if (!$_SESSION["admin"] && $deactivated) {
    			echo "disabled='disabled'";
			}
			echo "/>".chr(13);
			echo "<input type='hidden' name='old_keyword' value='$keyword' />".chr(13);
			echo "<input type='hidden' name='old_deactivated' value='".$row["deactivated"]."' />".chr(13);
			echo "<input type='hidden' name='input_id' value='$i' />".chr(13);
			echo "<input type='hidden' name='action' value='update' />".chr(13);
			echo "</td>";
		echo "</tr>".chr(13);
		echo "</form>".chr(13);
	}
	
	
?>

</table>
<br />
Translator module Copyrights 2009-2017© Benoît Freslon
</div>

<script language="JavaScript" type="text/javascript">

    document.addEventListener("DOMContentLoaded", function() { 
        window.setTimeout(function ()
        {
            //window.alert("Loaded");
            <?php
            echo $javascript;
            ?>
        }, 100);
     });
</script>

<?php
$finish = getCurTime();
$total_time = round(($finish - $startTime), 4);
echo 'Page generated in '.$total_time.' seconds.';
?>

</body>
</html>
