<h2 class="setupSectHeading">Install Plugins</h2>

<table style="font-size: 15px;" class="form">
<p style="color: #626262; text-align: center;">We thought you'd also like to throw in some plugins while you're at it:</p>

<form method="post">
    <table class="plugin_table" style="padding-left: 119px;">
        <?php foreach ($_assign_vars['plugins'] as $p):
            $plugin =  $p['plugin'];
            $auto = $p['auto'] ?>

            <tr <?php echo $auto ? 'style="display: none"' : '' ?>>
                <td width="32">
                    <input type="checkbox" name="plugins[]" <?php echo $auto ? 'checked="checked"' : '' ?> value="<?php echo $plugin['key'] ?>" id="<?php echo $plugin['key'] ?>">
                </td>
                <td>
                    <div class="plugin_title">
                        <label for="<?php echo $plugin['key'] ?>"><?php echo $plugin['title'] ?></label>
                    </div>

                    <div class="plugin_desc">
                        <label for="<?php echo $plugin['key'] ?>"><?php echo $plugin['description'] ?></label>
                    </div>
                </td>
            </tr>
        <?php endforeach ?>
    </table>

    <p style="text-align:center;margin: 10px 0 20px 0">
        <input type="submit" value="Finish" style=" text-transform: uppercase; font-size: 13px; font-weight: bold; color: #777;" />
    </p>
</form>