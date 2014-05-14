<?php

$destinataire = 'your mail';
$copie = 'no';
$message_envoye = "Your message has been sent!";
$message_non_envoye = "Delivery failed. Please try again.";
$message_formulaire_invalide = "Make sure all fields are filled out correctly.";
 
function Rec($text)
{
	$text = htmlspecialchars(trim($text), ENT_QUOTES);
	if (1 === get_magic_quotes_gpc())
	{
		$text = stripslashes($text);
	}
 
	$text = nl2br($text);
	return $text;
};
 
function IsEmail($email)
{
	$value = preg_match('/^(?:[\w\!\#\$\%\&\'\*\+\-\/\=\?\^\`\{\|\}\~]+\.)*[\w\!\#\$\%\&\'\*\+\-\/\=\?\^\`\{\|\}\~]+@(?:(?:(?:[a-zA-Z0-9_](?:[a-zA-Z0-9_\-](?!\.)){0,61}[a-zA-Z0-9_-]?\.)+[a-zA-Z0-9_](?:[a-zA-Z0-9_\-](?!$)){0,61}[a-zA-Z0-9_]?)|(?:\[(?:(?:[01]?\d{1,2}|2[0-4]\d|25[0-5])\.){3}(?:[01]?\d{1,2}|2[0-4]\d|25[0-5])\]))$/', $email);
	return (($value === 0) || ($value === false)) ? false : true;
}
 
$nom     = (isset($_POST['nom']))     ? Rec($_POST['nom'])     : '';
$email   = (isset($_POST['email']))   ? Rec($_POST['email'])   : '';
$objet   = (isset($_POST['objet']))   ? Rec($_POST['objet'])   : '';
$message = (isset($_POST['message'])) ? Rec($_POST['message']) : '';
 
$email = (IsEmail($email)) ? $email : '';
$err_formulaire = false;
 
if (isset($_POST['envoi']))
{
	if (($nom != '') && ($email != '') && ($objet != '') && ($message != ''))
	{
		$headers  = 'From:'.$nom.' <'.$email.'>' . "\r\n";
		if ($copie == 'oui')
		{
			$cible = $destinataire.','.$email;
		}
		else
		{
			$cible = $destinataire;
		};
 
		$message = str_replace("&#039;","'",$message);
		$message = str_replace("&#8217;","'",$message);
		$message = str_replace("&quot;",'"',$message);
		$message = str_replace('&lt;br&gt;','',$message);
		$message = str_replace('&lt;br /&gt;','',$message);
		$message = str_replace("&lt;","&lt;",$message);
		$message = str_replace("&gt;","&gt;",$message);
		$message = str_replace("&amp;","&",$message);
 
		if (mail($cible, $objet, $message, $headers))
		{
			echo '<p>'.$message_envoye.'</p>';
		}
		else
		{
			echo '<p>'.$message_non_envoye.'</p>';
		};
	}
	else
	{
		// une des 3 variables (ou plus) est vide ...
		echo '<p>'.$message_formulaire_invalide.'</p>';
		$err_formulaire = true;
	};
}; 
 
if (($err_formulaire) || (!isset($_POST['envoi'])))
{
	echo '
<div class="content">
	<form method="post" class="fo" action="'.$form_action.'">
	<fieldset class="fo"x>
	<p>
		<label for="nom" class="txt">Name :</label>
		<input type="text" id="nom" name="nom" value="'.stripslashes($nom).'" tabindex="1" />
	</p>
	<p>
		<label for="email" class="txt" >Email :</label>
		<input type="text" id="email" name="email" value="'.stripslashes($email).'" tabindex="2" />
	</p>
 
	<p>
		<label for="objet" class="txt" >Subject :</label>
		<input type="text" id="objet" name="objet" value="'.stripslashes($objet).'" tabindex="3" />
	</p>
	<p>
			<label for="message" class="txt" >Message :</label>
		<textarea id="message" name="message" tabindex="4" cols="30" rows="8">
		'.stripslashes($message).'
		</textarea>
	</p>
 
	<div style="text-align:center;"><input value="submit" type="image" src="http://hana.pm/mail.png" name="envoi" width="70" height="70" /></div>
	</fieldset>
	</form>
 </div>';
};
?>
