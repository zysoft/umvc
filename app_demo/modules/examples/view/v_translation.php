<h2>Translate</h2>
<?=t('ID_01');?>

<h2>Translate with arguments</h2>
<?=t('ID_02', array('@name' => '<b>filtered</b>', '!code' => '<b>not filtered</b>'));?>

<h2>Translate override namespace</h2>
<?=t('ID_01', NULL, 'override_hello');?>
