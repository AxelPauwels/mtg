<script>
    var var_cardname = '';
    var var_userId = '0';
    var var_setCodeId = '0';
    var var_colorId = '0';
    var var_rarityId = '0';
    var var_legalityId = '0';
    var var_formatId = '0';
    var var_supertypeId = '0';
    var var_typeId = '0';
    var var_subtypeId = '0';

    //    var var_orderby = 'name';
    //    var var_orderbyAscDesc = 'asc';
    //    var var_orderby = 'price';
    //    var var_orderbyAscDesc = 'desc';
    //    var var_orderby = 'rarityId';
    //    var var_orderbyAscDesc = 'asc';
    //    var var_orderby = 'setCodeId';
    //    var var_orderbyAscDesc = 'asc';
    //    var var_orderby = 'quantity';
    //    var var_orderbyAscDesc = 'desc';

    var var_orderby = 'quantityFoil';
    var var_orderbyAscDesc = 'desc';

    var pagination_startRow = 0;
    var pagination_aantalPerRow = 10;
    var pagination_totalRows = 0;

    function getCardsBySearchFunction() {
        $.ajax({
            type: "GET",
            url: site_url + "/cards/ajaxGetCardsBySearchFunction",
            data: {
                cardname: var_cardname,
                userId: var_userId,
                setCodeId: var_setCodeId,
                colorId: var_colorId,
                rarityId: var_rarityId,
                legalityId: var_legalityId,
                formatId: var_formatId,
                supertypeId: var_supertypeId,
                typeId: var_typeId,
                subtypeId: var_subtypeId,
                pagination_startRow: pagination_startRow,
                orderby: var_orderby,
                orderbyAscDesc: var_orderbyAscDesc
            },
            success: function (result) {
                $("#resultaat").html(result);
            },
            error: function (xhr, status, error) {
                alert("-- ERROR IN AJAX --\n\n" + xhr.responseText);
            }
        });
    }

    $(document).ajaxComplete(function () {
        pagination_totalRows = $("#paginationMaxRows").val();

        $('#cardDetailModal').on('hidden.bs.modal', function (e) {
            e.stopImmediatePropagation();
            getCardsBySearchFunction();
        });
    });

    $(document).ready(function () {
        // eerste ajax request direct uitvoeren
        getCardsBySearchFunction();

        // html controls ophalen
        var controlInput_name = $("#cardname");
        var controlDropdown_user = $("#selected_user");
        var controlDropdown_set = $("#selected_set");
        var controlDropdown_color = $("#selected_color");
        var controlDropdown_rarity = $("#selected_rarity");
        var controlDropdown_legality = $("#selected_legality");
        var controlDropdown_format = $("#selected_format");
        var controlDropdown_supertype = $("#selected_supertype");
        var controlDropdown_type = $("#selected_type");
        var controlDropdown_subtype = $("#selected_subtype");
        var controlPaginationBack = $("#paginationBack");
        var controlPaginationNext = $("#paginationNext");
//        pagination_totalRows = $("#paginationMaxRows").val();

        // trigger functies
        controlInput_name.keyup(function () {
            var_cardname = $(this).val();
            getCardsBySearchFunction();
        });
        controlDropdown_user.change(function () {
            var_userId = controlDropdown_user.val();
            getCardsBySearchFunction();
        });
        controlDropdown_set.change(function () {
            var_setCodeId = controlDropdown_set.val();
            getCardsBySearchFunction();
        });
        controlDropdown_color.change(function () {
            var_colorId = controlDropdown_color.val();
            getCardsBySearchFunction();
        });
        controlDropdown_rarity.change(function () {
            var_rarityId = controlDropdown_rarity.val();
            getCardsBySearchFunction();
        });
        controlDropdown_legality.change(function () {
            var_legalityId = controlDropdown_legality.val();
            getCardsBySearchFunction();
        });
        controlDropdown_format.change(function () {
            var_formatId = controlDropdown_format.val();
            getCardsBySearchFunction();
        });
        controlDropdown_supertype.change(function () {
            var_supertypeId = controlDropdown_supertype.val();
            getCardsBySearchFunction();
        });
        controlDropdown_type.change(function () {
            var_typeId = controlDropdown_type.val();
            getCardsBySearchFunction();
        });
        controlDropdown_subtype.change(function () {
            var_subtypeId = controlDropdown_subtype.val();
            getCardsBySearchFunction();
        });

        controlPaginationBack.click(function (e) {
            if (pagination_startRow >= pagination_aantalPerRow) {
                pagination_startRow = pagination_startRow - pagination_aantalPerRow;
            }
            getCardsBySearchFunction();
        });
        controlPaginationNext.click(function (e) {
            if (pagination_startRow <= (pagination_totalRows - pagination_aantalPerRow)) {
                pagination_startRow = pagination_startRow + pagination_aantalPerRow;
            }
            getCardsBySearchFunction();
        });

    });
</script>

<?php

$dropdownOptions_users = array('0' => 'All Cards');
foreach ($users as $user) {
    $dropdownOptions_users[$user->id] = ucwords($user->name) . " Cards";
}
$dropdownOptions_colors = array('0' => '- Color -');
foreach ($colors as $color) {
    $dropdownOptions_colors[$color->id] = ucwords($color->name);
}
$dropdownOptions_rarities = array('0' => '- Rarity -');
foreach ($rarities as $rarity) {
    $dropdownOptions_rarities[$rarity->id] = ucwords($rarity->name);
}
$dropdownOptions_sets = array('0' => '- Set -');
foreach ($sets as $set) {
    $dropdownOptions_sets[$set->code] = ucwords($set->name);
}
$dropdownOptions_formats = array('0' => '- Format -');
foreach ($formats as $format) {
    $dropdownOptions_formats[$format->id] = ucwords($format->name);
}
$dropdownOptions_supertypes = array('0' => '- Supertype -');
foreach ($supertypes as $supertype) {
    $dropdownOptions_supertypes[$supertype->id] = ucwords($supertype->name);
}
$dropdownOptions_types = array('0' => '- Type -');
foreach ($types as $type) {
    $dropdownOptions_types[$type->id] = ucwords($type->name);
}
$dropdownOptions_subtypes = array('0' => '- Subtype -');
foreach ($subtypes as $subtype) {
    $dropdownOptions_subtypes[$subtype->id] = ucwords($subtype->name);
}
$dropdownOptions_legalities = array('0' => '- Legality -');
foreach ($legalities as $legality) {
    $dropdownOptions_legalities[$legality->id] = ucwords($legality->name);
}
?>

<div id="content" class="row ">
    <div class="container container_searchCards">
        <form id="form_searchCard">
            <div class="row container_nameAndUser">
                <div class="form-group col-sm-6">
                    <?php echo form_input(
                        array(
                            'name' => 'cardname',
                            'id' => 'cardname',
                            'class' => 'form-control customInput center',
                            'placeholder' => '- Search -',
                            'onfocus' => "this.placeholder = ''",
                            'onblur' => "this.placeholder = '- Search -'"
                        )
                    ); ?>
                </div>
                <div class="form-group col-sm-5">
                    <?php echo form_dropdown(
                        'selected_user',
                        $dropdownOptions_users,
                        '0',
                        'id="selected_user" class="form-control btn btn-default customDropdown" '
                    ); ?>
                </div>
                <div class="form-group col-sm-1 toggleAdvancedSearch">
                    <i class="far fa-plus-square"></i>
                </div>
            </div>

            <div class="row container_advancedSearch showAdvancedSearch">
                <div class="form-group col-6">
                    <?php echo form_dropdown(
                        'selected_set',
                        $dropdownOptions_sets,
                        '0',
                        'id="selected_set" class="form-control btn btn-default customDropdown" '
                    ); ?>
                </div>
                <div class="form-group col-6">
                    <?php echo form_dropdown(
                        'selected_color',
                        $dropdownOptions_colors,
                        '0',
                        'id="selected_color" class="form-control btn btn-default customDropdown" '
                    ); ?>
                </div>
                <div class="form-group col-6">
                    <?php echo form_dropdown(
                        'selected_supertype',
                        $dropdownOptions_supertypes,
                        '0',
                        'id="selected_supertype" class="form-control btn btn-default customDropdown" '
                    ); ?>
                </div>
                <div class="form-group col-6">
                    <?php echo form_dropdown(
                        'selected_rarity',
                        $dropdownOptions_rarities,
                        '0',
                        'id="selected_rarity" class="form-control btn btn-default customDropdown" '
                    ); ?>
                </div>
                <div class="form-group col-6">
                    <?php echo form_dropdown(
                        'selected_type',
                        $dropdownOptions_types,
                        '0',
                        'id="selected_type" class="form-control btn btn-default customDropdown" '
                    ); ?>
                </div>

                <div class="form-group col-6">
                    <?php echo form_dropdown(
                        'selected_legality',
                        $dropdownOptions_legalities,
                        '0',
                        'id="selected_legality" class="form-control btn btn-default customDropdown" '
                    ); ?>
                </div>
                <div class="form-group col-6">
                    <?php echo form_dropdown(
                        'selected_subtype',
                        $dropdownOptions_subtypes,
                        '0',
                        'id="selected_subtype" class="form-control btn btn-default customDropdown" '
                    ); ?>
                </div>
                <div class="form-group col-6">
                    <?php echo form_dropdown(
                        'selected_format',
                        $dropdownOptions_formats,
                        '0',
                        'id="selected_format" class="form-control btn btn-default customDropdown" '
                    ); ?>
                </div>
            </div>
        </form>
    </div>


    <div class="container">
        <div id="resultaat"></div>
    </div>
    <div class=container>
        <div>
            <?php echo form_button(array(
                'name' => 'paginationBack',
                'id' => 'paginationBack',
                'type' => "button",
                "class" => "btn btn-primary",
                "content" => "Back",
                "style" => "display:inline-block"
            )); ?>

            <?php echo form_button(array(
                'name' => 'paginationNext',
                'id' => 'paginationNext',
                'type' => "button",
                "class" => "btn btn-primary",
                "content" => "Next",
                "style" => "display:inline-block"
            )); ?>
        </div>
    </div>
</div>

<script>
    $(document).ready(function () {
        $('.toggleAdvancedSearch').click(function (e) {
            var icon_plus = 'fa-plus-square';
            var icon_minus = 'fa-minus-square';
            var clickedIcon = $(this).children(":first");

            //change <i> logo (by changing the class)
            if (clickedIcon.hasClass(icon_plus)) {
                clickedIcon.removeClass(icon_plus);
                clickedIcon.addClass(icon_minus);
            } else {
                clickedIcon.removeClass(icon_minus);
                clickedIcon.addClass(icon_plus);
            }

            //hide container advancedSearch
            $('.container_advancedSearch').toggleClass('showAdvancedSearch');
        });

        $('div.checkboxSpecialLayout').hover(function (e) {
            $(this).children(':nth-child(3)').toggleClass('advancedSearch_hoverColor');
        });

    });
</script>
