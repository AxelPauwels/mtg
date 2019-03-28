<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="Axel Pauwels">
    <link rel="icon" type="image/png" href="<?php echo base_url(); ?>/mtgLogo.ico">
    <script defer src="https://use.fontawesome.com/releases/v5.0.10/js/all.js" integrity="sha384-slN8GvtUJGnv6ca26v8EzVaR9DC58QEwsIk9q1QXdCU8Yu8ck/tL/5szYlBbqmS+" crossorigin="anonymous"></script>
    <title>Magic The Gathering</title>

    <?php echo stylesheet("bootstrap.css"); ?>
    <?php echo stylesheet("heroic-features.css"); ?>
    <?php echo stylesheet("buttons.css"); ?>
<!--    magic set icons (https://andrewgioia.github.io/Keyrune/index.html)-->
    <link href="//cdn.jsdelivr.net/npm/keyrune@latest/css/keyrune.css" rel="stylesheet" type="text/css" />
    <?php echo stylesheet("my.css"); ?>

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
        <?php echo javascript("jquery-3.1.0.min.js"); ?>

<!--    <script-->
<!--            src="https://code.jquery.com/jquery-3.3.1.js"-->
<!--            integrity="sha256-2Kok7MbOyxpgUVvAk/HJ2jigOSYS2auK4Pfzbm7uH60="-->
<!--            crossorigin="anonymous">-->
<!--    </script>-->

    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.11.0/umd/popper.min.js"
            integrity="sha384-b/U6ypiBEHpOf/4+1nzFpr53nxSS+GLCkfwBdFNTxtclqqenISfwAzpKaMNFNmj4"
            crossorigin="anonymous"></script>
    <?php echo javascript("bootstrap.js"); ?>

    <script type="text/javascript"
            src="https://cdnjs.cloudflare.com/ajax/libs/jquery.tablesorter/2.29.6/js/jquery.tablesorter.min.js">
    </script>

    <script type="text/javascript">
        var site_url = '<?php echo site_url(); ?>';
        var base_url = '<?php echo base_url(); ?>';
    </script>
</head>

<body>
<div class="container-fluid">
    <?php
    echo $myHeader;
    echo $myContent;
    echo $myFooter;
    ?>
</div>
</body>
</html>