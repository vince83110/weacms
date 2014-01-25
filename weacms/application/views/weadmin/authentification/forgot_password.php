<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
 	<meta charset="UTF-8">
 	<title>Emoacube - Authentification</title>
    <link rel="stylesheet" href="<?=base_url()?>theme/css/login.css" type="text/css" media="screen, projection" charset="utf-8" />
</head>
<body>
	<div class="wrapper">
		<h1>Mot de passe oublié</h1>
		<?=form_open(base_url_admin('authentification/forgot_password'), array('id' => 'login'))?>	
			<? if (strlen($message)) { 
				echo $message;
			} 
			?>
			<p>Merci d'entrer votre mail pour recevoir votre nouveau mot de passe :<br />
				<?=form_input($identity)?>
			</p>
			
         	<input type="submit" value="M'envoyer mon code" class="button" />
         	<div class="clear"></div>
         	<hr />
         	<br />
            <img src="<?=base_url('theme/css/img/weacms.jpg')?>" />
         	<p>Weacms est votre outil de gestion de contenu en ligne</p>
            <div class="clear"></div>
       </form>
       <p><a href="<?=base_url_admin('authentification/forgot_password')?>">Vous avez perdu votre mot de passe ?</a></p>
	</div>
 </body>
</html>