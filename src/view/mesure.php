<?php if (isset($mesures)): ?>
	<?php $i = 0; ?>
	<?php foreach ($mesures as $mesure): ?>
		<?php $i++; ?>
		<div id="mesure_<?php echo $mesure->id ?>" <?php if ($i % 2): ?>class="striped"<?php endif; ?> data-uri="<?php echo $mesure->uri; ?>">
			<div class="calcul">
				<div class="firstArea">
					<span class="unit"><?php echo $mesure->unite; ?></span>
					<span class="btnDescription" data-tooltip="Description">?</span>
					<?php if ($mesure->pays): ?>
						<span class="btnFlag">
							<?php if ($mesure->pays == 'ww'): ?>
								<span data-tooltip="Internationnal" class="ion-android-globe"></span>
							<?php elseif ($mesure->pays == 'spqr'): ?>
								<img class="romainFlag" data-tooltip="Romain" src="/public/img/spqr.png" height="24">
							<?php elseif ($mesure->pays): ?>
								<span data-tooltip="ISO : <?php echo $mesure->pays; ?>" class="flag-icon flag-icon-squared flag-icon-<?php echo $mesure->pays; ?>"></span>
							<?php endif; ?>
						</span>
					<?php endif; ?>
					<?php if (strpos($mesure->formule, '?')): ?>
						<span class="btnVariable">
							<span class="ion-flag" data-tooltip="Variables"></span>
						</span>
					<?php endif; ?>
				</div>
				<div class="SecondArea">
					<input class="value" value="<?php echo $mesure->value; ?>">
					<span class="abrege"><?php echo $mesure->abrege; ?></span>
				</div>
			</div>
			<div class="detail">
				<span class="formule"><span>Formule</span>&nbsp;<?php echo $mesure->formule; ?>&nbsp;</span>
				<?php if (strpos($mesure->formule, '?')): ?>
					<?php $variables = explode('?', $mesure->formule); ?>
					<span class="variable">
						<span>Variables</span>
						<span class="inputObj">
						<?php foreach ($variables as $key => $value): ?>
							<?php if ($key % 2): ?>
								<input type="text" class="input" data-uri="<?php echo $value; ?>" value="<?php echo $mesure->variable[$key] ?>" placeholder="<?php echo ucfirst($value); ?>">&nbsp;
							<?php endif; ?>
						<?php endforeach; ?>
						<button class="btnCalcul">Calculer</button>&nbsp;
						</span>
					</span>
				<?php endif; ?>
				<?php if ($mesure->filtre): ?>
					<span class="filtre">
						<span>Filtre</span>
						<?php foreach ($mesure->filtre as $filtre): ?>
							<span class="filtreObj" data-tooltip="<?php echo $filtre[0]; ?>" data-id="<?php echo $filtre[2]; ?>"><?php echo $filtre[1]; ?></span>
						<?php endforeach; ?>
					</span>
				<?php endif; ?>
				<?php if ($mesure->description): ?>
					<span class="description"><span>Description</span>&nbsp;<?php echo $mesure->description; ?>&nbsp;</span>
				<?php endif; ?>
			</div>
		</div>
	<?php endforeach; ?>
<?php else: ?>
	<div class="msg-error"><?php echo $error; ?></div>
<?php endif; ?>