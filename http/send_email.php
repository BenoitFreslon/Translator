<?php
include_once($_SERVER['DOCUMENT_ROOT']."/modules/connect_db.php");

$mail = "contact@benoitfreslon.com";
	
$header = "From: $mail\r\n";
$header .= "Reply-To: $mail\r\n";
	
$object = "[RollingJump] Need awesome translators :)";
	


	//mail($new_email, $object, $message, $header) or die ("Error sending email");
	//mail("benoit.freslon@gmail.com", $object, $message, $header) or die ("Error sending email");
	
$ok = $_GET['ok'];
	
//$sql = "SELECT * FROM  tr_translator WHERE id_language='3' OR id_language='4' OR id_language='5' OR id_language='8'  OR id_language='10' OR id_language='12' OR id_language='13' OR id_language='14' OR id_language='15' OR id_language='23'";
$sql = "SELECT * FROM  tr_translator";
$res = mysqli_query($mysql_resource, $sql) or die($sql);
while ($row = mysqli_fetch_array($res)) {
	echo $row['email']."<br>";
	
	$nickname = $row['nickname'];
	$password = $row['password'];
	
	$message = "Hello $nickname,
	
I'm contacting you because you already translated one of my games (EnigmBox or Nano War) and I want to say thank you very much, you are awesome :).

I released my game Rolling Jump on iOS in 2011 but I need your help to translate the game.

You can play the game here: http://bit.ly/rolling-jump (locked version)

Or register on TestFlightApp to download the unlimited version (FREE)
https://www.testflightapp.com/join/4ee26af02efe08560eb615055e07e376-Nzk2ODU/

I'm an indie game developer and I'm alone to create and develop games for you, players.
That's why I need your help to test and translate my games.

Here the instructions to help me:

	Translate the AppDescription here:
	https://docs.google.com/spreadsheets/d/13zo5BXdsOn42iuZ21enn_xQOtwwONlbWZuqjTmUoTI8/edit#gid=1919615225

    The translator module is here:
    http://www.benoitfreslon.com/modules/translator/

	Nickname: $nickname
	Password: $password

	Select the game : 'Rolling Jump' and you can start to translate. :)

	Your task is simple: just translate or check the sentences on the right one by one.
	And don't forget to hit enter or click on the update button on every modifications.
	
	Send me an email if you have any questions or when all you work is done.
	If you have questions about the translation or if you see bugs please contact me.

Thank you very much.
	
Have fun ;) !

Benoit.
	
PS: If you don't want to receive emails anymore please send me an email I will remove your email address form my database.";
	
	if ($ok == 1) {
	
		$error = mail($row['email'], $object, $message, $header);
		//$error = mail("benoit.freslon@gmail.com", $object, $message, $header);
		if (!$error) {
			echo ("Error sending email to: ".$nickname." ".$row['email']."<br />");
		} else { 
			echo "email sent to ".$row['email']."<br />";
		}
	}
	
	//return;
}
if ($_GET["ok"] == "test") {
	echo "TEST MODE<br/>";
	$error = mail("contact@benoitfreslon.com", $object, $message, $header);
}
echo $message;	
/*
"Hello $nickname,
	
I'm contacting you because you already translated one of my games (Nano War or Take Something Literally or ) and I want to say: Thank you very much, you are awesome :).

Today I'm proud to present you my new games:

Nano War on iOS (iPhone and iPad) already released.
https://itunes.apple.com/fr/app/nano-war-strategy-cell-conquests/id589502011?l=en&mt=8

And

EnigmBox (Take Something Literally on iPhone) Not released yet.
Website: http://www.slidedb.com/games/think-outside-the-box
Trailer: http://www.youtube.com/watch?v=lQr5PiR65p4&

I'm an indie game developer and I'm also alone to create and develop games for you, players.
That's why I need your help to translate my games.
	
If you help me I WILL SEND YOU A FREE COPY OF <EnigmBox> BEFORE THE RELEASE DATE.
By the way you can download Nano War iOS for free :).

Here the instructions to help me:

    The translator module is here:
    http://www.benoitfreslon.com/modules/translator/

	Nickname: $nickname
	Password: $password

	Select the game : Nano War iOS and EnigmBox and you can start to translate. :)

	Your task is simple: just translate or check the sentences on the right one by one.
	And don't forget to hit enter or click on the update button on every modifications.
	
	Send me an email if you have any questions or when all you work is done.
	If you have questions about the translation or if you see bugs please contact me.

Thank you very much.
	
Have fun ;) !

Benoit.
	
PS: If you don't want to receive emails anymore please send me an email I will remove your email address form my database."
*/
?>