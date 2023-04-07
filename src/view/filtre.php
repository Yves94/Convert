<?php $nature = ''; ?>
<?php if (isset($filtres) && !empty($filtres)): ?>
	<div id="filtreContent">
	<?php foreach ($filtres as $filtre): ?>
		<?php if ($nature != $filtre['nature']): ?>
			<div class="title"><?php echo $filtre['nature']; ?></div>
			<?php $nature = $filtre['nature']; ?>
		<?php endif; ?>
		<div class="filtreLibelle"><?php echo $filtre['libelle']; ?><span class="ion-checkmark"></span></div>
	<?php endforeach; ?>
	</div>
<?php else: ?>
	<div class="msg-error">Aucun filtre applicable</div>
<?php endif; ?>