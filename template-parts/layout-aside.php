<?php
/**
 * The aside layout
 *
 * @package csomaster
 */

$dropbox =  get_acf_value('client_dropbox','main');
$zipAssets =  get_acf_value('client_brand_assets','main');
$downloadPreference = (int) get_acf_value('client_use_zip','main');
$downloadLink = $downloadPreference === 1 ? $zipAssets : $dropbox;
?>
	<aside id="masthead" class="">
		<div class='inner'>
			<h5><?= get_acf_value('client_name', 'main') ?> <span class="pipe">|</span> <?= get_acf_value('page_title', 'main') ?></h5>
			<p><a href="<?= $downloadLink ?>" class="button" target="_blank"><span><?= $downloadPreference === 1 ? 'Download' : 'View'; ?>  all</span></a></p>
		</div>
	</aside><!-- #masthead -->