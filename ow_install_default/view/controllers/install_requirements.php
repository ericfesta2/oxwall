<h2 class="setupSectHeading">Hosting Requirements</h2>

<p class="red">
	Your hosting account doesn't meet the following requirements:
</p>

<ul class="ow_regular">
<!-- PHP version -->
<?php if ( !empty($_assign_vars['fails']['php']['version']) ):
    $requiredVersion = $_assign_vars['fails']['php']['version'] ?>
    <li>
        Required PHP version: <b class="high"><?php echo $requiredVersion ?></b> or higher <span class="small">(currently <b><?php echo $_assign_vars['current']['php']['version'] ?></b>)</span>
    </li>
<?php endif ?>

<!-- PHP extensions -->
<?php if ( !empty($_assign_vars['fails']['php']['extensions']) ): ?>
    <?php foreach ($_assign_vars['fails']['php']['extensions'] as $requiredExt): ?>    
        <li>
            <b class="high"><?php echo $requiredExt; ?></b> PHP extension not installed
        </li>
    <?php endforeach ?>
<?php endif ?>

<!-- INI Configs -->
<?php if ( !empty($_assign_vars['fails']['ini']) ): ?>
    <?php foreach ($_assign_vars['fails']['ini'] as $iniName => $iniValue): ?>
        <li>
            <span class="high"><?php echo $iniName ?></span> must be <b class="high"><?php echo $iniValue ? 'on' : 'off' ?></b>
            <span class="small">(currently <b><?php echo $_assign_vars['current']['ini'][$iniName] ? 'on' : 'off' ?></b>)</span>
        </li>
    <?php endforeach ?>

<?php endif ?>

<!-- GD version -->
<?php if ( !empty($_assign_vars['fails']['gd']['version']) ):
    $requiredVersion = $_assign_vars['fails']['gd']['version'] ?>

    <li>
        Required <span class="high">GD library</span> version: <b class="high"><?php echo $requiredVersion ?></b> or higher 
        <span class="small">(currently <b><?php echo $_assign_vars['current']['gd']['version'] ?></b>)</span>
    </li>
<?php endif ?>

<!-- GD support -->
<?php if ( !empty($_assign_vars['fails']['gd']['support']) ):
    $requiredSupportType = $_assign_vars['fails']['gd']['support'] ?>

    <li>
        <b class="high"><?php echo $requiredSupportType ?></b> required for <span class="high">GD library</span>
    </li>
<?php endif ?>

</ul>

<p>
	Please correct these before you can proceed with Oxwall installation. Complete server requirements list and compatible hosting can be found at <a href="https://www.oxwall.org/hosting">Oxwall.org/hosting</a>
</p>