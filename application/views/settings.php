<?php
if ($user != null) {
    if($user->level == 5){
    ?>

    <div id="content" class="container justify-content-center ">
        <p style="color:whitesmoke">INSERT SECTION</p>
        <div class="row"><?php echo anchor(site_url('/admin/insertDataFromApi/supertypes'), "SuperTypes"); ?></div>

        <div class="row"><?php echo anchor(site_url('/admin/insertDataFromApi/types'), "Types"); ?></div>

        <div class="row"><?php echo anchor(site_url('/admin/insertDataFromApi/subtypes'), "SubTypes"); ?></div>

        <div class="row"><?php echo anchor(site_url('/admin/insertDataFromApi/sets'), "Sets"); ?></div>

        <div class="row"><?php echo anchor(site_url('/admin/insertDataFromApi/formats'), "Formats"); ?></div>

        <div class="row"><?php echo anchor(site_url('/admin/insertDataFromApi/rarities'), "Rarities"); ?></div>

        <div class="row"><?php echo anchor(site_url('/admin/insertDataFromApi/colors'), "Colors"); ?></div>

        <div class="row"><?php echo anchor(site_url('/admin/insertDataFromApi/legalities'), "Legalities"); ?></div>
    </div>

    <?php
    }
}
else {
    ?>
    <div id="content" class="row">
        <div class="container">
            <p>NIET ingelogd</p>
        </div>
    </div>
    <?php
}
?>
