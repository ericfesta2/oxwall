<?php echo install_tpl_feedback() ?>
<h3>Let's get started.</h3>

<form method="post">
    <div class="initSetupSetting">
        <input type="radio" name="init_setup_setting" id="new_site" value="new_site" required />
        <label for="new_site">I am <span class="em">setting up a new site</span></label>
    </div>

    <div class="initSetupSetting">
        <input type="radio" name="init_setup_setting" id="existing_db" value="existing_db" />
        <label for="existing_db">I want to <span class="em">connect this installation to an existing database</span></label>
    </div>

    <p style="text-align:center">
        <input type="submit" value="Continue" style=" text-transform: uppercase; font-size: 13px; font-weight: bold; color: #777;" />
    </p>
</form>
