<div class="steps">
    <?php $iteration = 1 ?>
    <?php foreach ($_assign_vars['steps'] as $step): ?>
            <?php $iteration++ ?>
	        <span <?php if ($step['active']) echo 'class="activepointer"'; ?> >
	            <span class="<?php echo match ($iteration) {
                    1 => 'borderright',
                    count($_assign_vars['steps']) => 'borderleft',
                    default => 'borderleft borderright'
                } ?>">
	                <span class="item<?php if ($step['active']) echo ' active' ?>">
	                    <?php echo $step['label']; ?>
	                </span>
	            </span>
	        </span>
    <?php endforeach ?>
</div>
