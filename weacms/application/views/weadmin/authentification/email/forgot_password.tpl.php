<html>
<body>
	<h3>Nouveau mot de passe pour : <?php echo $identity;?></h3>
	<p>Merci de cliquer ici : <?php echo anchor(base_url_admin('authentification/reset_password/'. $forgotten_password_code), 'pour avoir un nouveau mot de passe.');?>.</p>
</body>
</html>