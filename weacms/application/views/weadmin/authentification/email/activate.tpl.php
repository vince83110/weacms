<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<body style="font-family:Arial;">
	<h3>Activation du compte - <?php echo $identity;?></h3>
    <hr />
	<p>Merci de cliquer sur ce lien pour pouvoir accéder à votre outil en ligne : <?php echo anchor('authentification/activer/'. $id .'/'. $activation, 'Activer mon compte');?>.</p>
    <br />
    <strong>Votre login : </strong><?php echo $email; ?>
    <br />
    <strong>Votre mot de passe : </strong><?php echo $password; ?>
    
    <br />
    <p><small>WeAdmin - Application de gestion de contenus</small></p>
</body>
</html>