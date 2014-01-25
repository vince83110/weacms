<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8" />
		<link rel="stylesheet" type="text/css" href="<?= web_url('css/bootstrap.css') ?>" />
		<link rel="stylesheet" type="text/css" href="<?= web_url('css/page-builder.css') ?>" />
		<script type="text/javascript" src="<?= web_url('js/jquery.min.js') ?>"></script>
		<script type="text/javascript" src="<?= web_url('js/page-builder.js') ?>"></script>
    	<script type="text/javascript" src="<?= web_url('js/ckeditor/ckeditor.js') ?>"></script>
		<script type="text/javascript" src="<?= web_url('js/ckeditor/adapters/jquery.js') ?>"></script>
	</head>
	<body class="edit">
		<?= $sidebar ?>
		<a class="btn btn-link btn-page-builder" id="sourcepreview">Voir un aperçu</a>
		<a class="btn btn-link btn-page-builder" id="edit">Mode éditeur</a>
		<div id="page-builder" class="ui-sortable" style="min-height: 400px;">
			<?= $content ?>
		</div>	
	</body>
</html>
