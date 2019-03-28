<script>
    var nameToSearchFor;
    var searchByExactName = false;
    var databaseAlreadyChecked = false; // ook false zetten wanneer de dropdown (opnieuw) wordt aangesproken

    function checkIfCardExistsInDatabase(cardname, apicardid) {
        if (!databaseAlreadyChecked) {
            databaseAlreadyChecked = true;
            $.ajax({
                type: "POST",
                url: site_url + "/cards/ajaxCheckifcardexists",
                data: {
                    name: cardname,
                    apicardid: apicardid
                },
                success: function (resultCard) {
                    if (resultCard) {
                        var foundCard = JSON.parse(resultCard);
                        var message = "YOU HAVE THIS CARD " + foundCard["quantity"];
                        if (foundCard["quantity"] == 1) {
                            message += " TIME. ";
                        } else {
                            message += " TIMES. ";
                        }
                        if (foundCard["quantityFoil"] > 0 || foundCard["quantityDeck"] > 0) {
                            message += " (";
                        }
                        if (foundCard["quantityFoil"] > 0) {
                            message += foundCard ["quantityFoil"] + " FOIL";
                            if (foundCard["quantityFoil"] > 1) {
                                message += "S";
                            }
                        }
                        if (foundCard["quantityFoil"] > 0 && foundCard["quantityDeck"] > 0) {
                            message += " AND ";
                        }
                        if (foundCard["quantityDeck"] > 0) {
                            message += foundCard ["quantityDeck"] + " TIME";
                            if (foundCard["quantityDeck"] > 1) {
                                message += "S";
                            }
                            message += " IN A DECK";
                        }
                        if (foundCard["quantityFoil"] > 0 || foundCard["quantityDeck"] > 0) {
                            message += ")";
                        }

                        $("p#showIfCardExistsMessage span").html(message);
                        $("p#showIfCardExistsMessage").removeAttr('hidden');
                    }
                },
                error: function (xhr, status, error) {
                    alert("-- ERROR IN AJAX --\n\n" + xhr.responseText);
                }
            });
        }
    }

    function getCards_byName_fromApi() {
        var cardname = nameToSearchFor;

        if (searchByExactName) {
            // indien zoeken op exacte naam, extra accolades zetten =>
            // 'https://api.magicthegathering.io/v1/cards?name="exactName"'
            cardname = '"' + nameToSearchFor + '"';
        }

        $.ajax({
            type: "GET",
            url: 'https://api.magicthegathering.io/v1/cards',
            data: {
                name: cardname
            },
            success: function (resultArray) {
                if (nameToSearchFor.length > 0) {
                    $('#dropdownName').empty();
                    $('#selected_name').attr("data-toggle", "dropdown");
                    $('#dropdownName').dropdown('toggle');
                } else if (nameToSearchFor.length == 0) {
                    $('#selected_name').attr("data-toggle", "");
                    $('#dropdownName').dropdown().addClass("testtt"); //TODO: CHECK THIS VALIDATION !!!!!!!!!!!!!
                }

                $.each(resultArray['cards'], function (index, value) {
                    if (nameToSearchFor.length > 0) {
                        $('#dropdownName').append('<li role="displayNames" >' +
                            '<a role="menuitem dropdownNameli" class="dropdownlivalue" ' +
                            'data-cardname="' + value['name'] + '" data-cardid="' + value['id'] +
                            '" data-cardimage="' + value['imageUrl'] + '" data-cardsetcode="' +
                            value['set'] + '">' + value['name'] + ' (' + value['setName'] + ')</a></li>');
                    }
                });

                $('ul.ulName').on('click', 'li a', function () {
                    var cardname = $(this).data('cardname');
                    var apicardid = $(this).data('cardid');
                    var setcodeid = $(this).data('cardsetcode');
                    $('#selected_name').val(cardname);
                    $('#selected_cardid').val(apicardid);
                    $('#selected_cardsetcodeid').val(setcodeid);
                    $('#dropdownName').empty();
                    $('#cardImage').attr('src', $(this).data('cardimage'));
                    checkIfCardExistsInDatabase(cardname, apicardid);
                });
            },
            error: function (xhr, status, error) {
                alert("-- ERROR IN AJAX --\n\n" + xhr.responseText);
            }
        });
    }

    $(document).ready(function () {
        var control_inputName = $("#selected_name");

        control_inputName.mouseenter(function () {
            databaseAlreadyChecked = false;
        });

        control_inputName.keyup(function () {
            nameToSearchFor = $(this).val();
            getCards_byName_fromApi();
        });

        $('.container_fontAwesomeIcon').click(function (e) {
            // toggle class (for icon-color)
            $(this).toggleClass('locked');

            // toggle checkbox when clicked (to store in session when form will be POST to controller)
            var $checkbox = $(this).parent().find('input:checkbox:first');

            // als de geklikte "dropdown" een checkbox is, de volgende checkbox ophalen om te togglen
            if ($checkbox.attr('id') === "checkboxIsFoil" || $checkbox.attr('id') === "checkboxIsInDeck") {
                $checkbox = $(this).parent().find('input:checkbox:nth-child(2)');
            }

            // het checked-attribuut togglen
            var $currentCheckedStatus = $checkbox.attr('checked');
            $checkbox.attr('checked', !$currentCheckedStatus);
        });

        // toggle class (for checkbox text color)
        $('label#labelToClick_isFoil').click(function (e) {
            $('span#text_IsFoil').toggleClass('toggleColor_MTGDark');
            $('select#selected_numberOfCardsFoil').toggleClass('hideElement');
            $('input#selected_priceFoil').toggleClass('hideElement');
        });

        $('label#labelToClick_isInDeck').click(function (e) {
            $('span#text_IsInDeck').toggleClass('toggleColor_MTGDark');
            $('select#selected_numberOfCardsIsInDeck').toggleClass('hideElement');
        });
    });

    function copyToClipboard() {
        var $temp = $("<input>");
        $("body").append($temp);
        $temp.val($("#selected_name").val()).select();
        document.execCommand("copy");
        $temp.remove();
    }

    function activateSearchByExactName() { //this function is used as string in the checkbox !!!
        searchByExactName = !searchByExactName;
    }
</script>

<?php
if ($user != null) {
    echo javascript("validator.js");

// form data maken/ophalen
    $dataOpen = array(
        'id' => 'form_addCard',
        'name' => 'form_addCard',
        'data-toggle' => 'validator',
        'role' => 'form'
    );

    $dataSubmit = array(
        'id' => 'submit_addCard',
        'name' => 'submit_addCard',
        'type' => 'submit',
        'value' => 'ADD CARD',
        'class' => 'btn btn-primary size customInputSubmit'
    );
    ?>

    <div id="content" class="row justify-content-center container_addCard">

        <div class="col-sm-8 container_formAddCard">
            <!--            absoluut position to parent div.container_formAddCard-->
            <p id="showIfCardExistsMessage" hidden><i class="fas fa-info-circle"></i><span
                        style="padding-left: 4px"></span></p>

            <?php echo form_open('cards/addCard', $dataOpen); ?>

            <div class="row" id="copyIconContainer">
                <div>
                   <span id="copyIcon" onclick="copyToClipboard()">
                        <i class="fas fa-copy"></i>
                    </span>
                </div>
            </div>

            <div class="row" id="inputCardnameContainer">
                <div class="form-group col-9">
                    <?php echo form_input(
                        'selected_name',
                        '',
                        'required="required" autofocus placeholder="Cardname" id="selected_name" 
                            type="text" class="form-control btn btn-default customInput" autocomplete="off" '
                    ); ?>
                    <ul class="dropdown-menu ulName" role="menu"
                        aria-labelledby="dropdownMenu" id="dropdownName"></ul>
                </div>
                <div class="form-group col-3" id="exactNameContainer">
                    <?php echo form_checkbox(
                        'selected_searchByExactName',
                        '1',
                        0,
                        ' id="checkboxSearchByExactName" name="checkboxSearchByExactName" 
                        onclick="activateSearchByExactName()" '
                    ); ?>
                    <label for="checkboxSearchByExactName" id="labelToClick_searchByExactName">BY EXACT NAME</label>
                </div>
            </div>

            <div class="row">
                <div class="form-group">
                    <?php echo form_input(
                        'selected_price',
                        '',
                        'type="number" step="0.01" value="0.00" placeholder="Price" 
                        id="selected_price" class="form-control btn btn-default customInput" '
                    ); ?>
                </div>
            </div>

            <div class="row" id="inputSelectedNumberOfCardsContainer">
                <div class="form-group">
                    <?php echo form_dropdown('selected_numberOfCards', array(
                        "Quantity",
                        "1",
                        "2",
                        "3",
                        "4",
                        "5",
                        "6",
                        "7",
                        "8",
                        "9",
                        "10",
                        "11",
                        "12",
                        "13",
                        "14",
                        "15",
                        "16",
                        "17",
                        "18",
                        "19",
                        "20"
                    ), '', 'id="selected_numberOfCards" required="required"'); ?>
                </div>
            </div>


            <div class="row">
                <div class="form-group checkboxSpecialLayout">
                    <?php echo form_checkbox(
                        'selected_isFoil',
                        '1',
                        $lockedFormSettings->isFoil_visibleCheckboxCheckedValue,
                        'hidden id="checkboxIsFoil" name="checkboxIsFoil"'
                    ); ?>
                    <label for="checkboxIsFoil" id="labelToClick_isFoil"></label><span id="text_IsFoil">Is Foil</span>
                </div>
                <div class="container_fontAwesomeIcon
            <?php echo $lockedFormSettings->isFoil_iconClassForColor; ?> ">
                    <i class="fas fa-lock"></i>
                    <?php echo form_checkbox(
                        'lock_isFoil',
                        '1',
                        $lockedFormSettings->isFoil_checkboxCheckedValue,
                        'hidden id="checkboxLockIsFoil" name="checkboxLockFoil" '
                    ); ?>
                </div>
            </div>
            <!--            absoluut position to parent div.container_formAddCard-->
            <div id="foilQuantityDropdown">
                <?php echo form_dropdown(
                    'selected_numberOfCardsFoil',
                    array(
                        "Quantity",
                        "1",
                        "2",
                        "3",
                        "4",
                        "5",
                        "6",
                        "7",
                        "8",
                        "9",
                        "10",
                        "11",
                        "12",
                        "13",
                        "14",
                        "15",
                        "16",
                        "17",
                        "18",
                        "19",
                        "20"
                    ),
                    '',
                    'id="selected_numberOfCardsFoil" class="hideElement" required="required"'
                ); ?>
            </div>
            <div id="foilPriceInput">
                <?php echo form_input(
                    'selected_priceFoil',
                    '',
                    'type="number" step="0.01" value="0.00" placeholder="Price" 
                        id="selected_priceFoil" class="hideElement form-control btn btn-default customInput" '
                ); ?>
            </div>


            <div class="row">
                <div class="form-group checkboxSpecialLayout">
                    <?php echo form_checkbox(
                        'selected_isInDeck',
                        '1',
                        $lockedFormSettings->isInDeck_visibleCheckboxCheckedValue,
                        'hidden id="checkboxIsInDeck" name="checkboxIsInDeck"'
                    ); ?>
                    <label for="checkboxIsInDeck" id="labelToClick_isInDeck"></label><span
                            id="text_IsInDeck">Is In Deck</span>
                </div>
                <div class="container_fontAwesomeIcon <?php echo $lockedFormSettings->isInDeck_iconClassForColor; ?> ">
                    <i class="fas fa-lock"></i>
                    <?php echo form_checkbox(
                        'lock_isInDeck',
                        '1',
                        $lockedFormSettings->isInDeck_checkboxCheckedValue,
                        'hidden id="checkboxLockIsInDeck" name="checkboxLockIsInDeck" '
                    ); ?>
                </div>
            </div>
            <!--            absoluut position to parent div.container_formAddCard-->
            <div id="isInDeckQuantityDropdown">
                <?php echo form_dropdown(
                    'selected_numberOfCardsIsInDeck',
                    array(
                        "Quantity",
                        "1",
                        "2",
                        "3",
                        "4",
                        "5",
                        "6",
                        "7",
                        "8",
                        "9",
                        "10",
                        "11",
                        "12",
                        "13",
                        "14",
                        "15",
                        "16",
                        "17",
                        "18",
                        "19",
                        "20"
                    ),
                    '',
                    'id="selected_numberOfCardsIsInDeck" class="hideElement" required="required"'
                ); ?>
            </div>
            <div hidden>
                <?php echo form_input(
                    'selected_cardid',
                    '',
                    'hidden id="selected_cardid" type="text" '
                ); ?>
            </div>
            <div hidden>
                <?php echo form_input(
                    'selected_setcodeid',
                    '',
                    'hidden id="selected_cardsetcodeid" type="text" '
                ); ?>
            </div>

            <div class="row align-center" id="submitAddCard">
                <div class="form-group">
                    <?php echo form_submit($dataSubmit) . "\n"; ?>
                </div>
            </div>

            <?php
            echo form_close();
            ?>
        </div>

        <div class="col-sm-4 container_cardImage">
            <img id="cardImage"/>
        </div>

    </div>
    <?php
} else {
    ?>
    <div id="content" class="row">
        <div class="container">
            <p>NIET ingelogd</p>
        </div>
    </div>
    <?php
}
?>
