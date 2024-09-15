<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="description" content="{$page_desc|default:$_var.page.description}" />
    <title><?php echo $_assign_vars['pageTitle'] ?></title>
    <link rel="stylesheet" type="text/css" href="<?php echo $_assign_vars['pageStylesheetUrl'] ?>" />
</head>

<body>
    <div class="body_wrapper">
        <div role="img" class="logo_container" alt="Install Oxwall"></div>
        <div class="body">
            <div class="content">
                <h1 class="setupSectHeading"><?php echo $_assign_vars['pageHeading'] ?></h1>
                <?php echo $_assign_vars['pageBody'] ?>
            </div>
            <div class="body_bottom">
                <?php echo $_assign_vars['pageSteps'] ?>
            </div>
        </div>
    </div>
</body>
</html>
