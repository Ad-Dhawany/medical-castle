<!DOCTYPE html>
<html lang="<?php echo $langArray['lang-html'] ?>" dir="<?php echo $langArray['dir'] ?>">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Cache-control" content="no-cache"> <!-- just while implementing and testing -->
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo fnc::getTitle(); ?></title>
    <link rel="icon" href="<?php echo $dir. "media/site_logo/logo_thump.png" ?>" type="image/png">
    <link rel="shortcut icon" href="<?php echo $dir. "media/site_logo/logo_thump.png" ?>" type="image/png">
    <!-- <link rel="stylesheet" href="<?php echo $cssP; ?>fontawesome-free-6.2.1-web.min.css"> -->
    <link rel="stylesheet" href="<?php echo $cssP; ?>fontawesome-free-6.4.0-web.min.css">
    <!-- <link rel="stylesheet" href="<?php echo $cssP; ?>fontawesome-free-6.2.0-web-regular.min.css"> -->
    <!-- <link rel="stylesheet" href="<?php echo $cssP; ?>bootstrap.min.css"> -->
    <link rel="stylesheet" href="<?php echo $cssP. $langArray['bootstrap'] ?>">
    <link rel="stylesheet" href="<?php echo $cssP; ?>front-style.css">
    <?php if($LANGUAGE == "arabic"){?>
        <link rel="stylesheet" href="<?php echo $cssP; ?>front-style.rtl.css">
    <?php } ?>
</head>
<body>
