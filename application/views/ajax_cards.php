<?php
if ($cards != null) {
    echo '<div class="resultSummary_CountAndPrice">';
    echo '<p>Found ' . $cardsCount . ' cards with a value of  € ' . $cardsTotalPrice . '</p>';
    echo '</div>';

    $template = array(
        'table_open' => '<table class="table table-responsive-sm table-striped table-hover table-sm" 
            id="cardsTable" border="0" cellpadding="4" cellspacing="0">'
    );
    $this->table->set_template($template);
    $this->table->set_heading(
        '<i class="fas fa-sort"></i>',
        'NAME',
        'RARITY',
        'SET',
        '#',
        'PRICE',
        'FOIL',
        'DECK',
        ''
    );

    $nr = 0;
    foreach ($cards as $card) {
        if ($card->price == 0.00) {
            $card->price = "";
        }
        $detailKnop = "<a href=''" . ' class="showCardDetail" data-id="' . $card->id . '">' . 'i' . '</a>' . "\n";
        $nr++;

        $displayRarity = '<span hidden>' . $card->rarity->id . '</span>
            <i class="ss ss-' . strtolower($card->set->code) . ' ss-fw ' . $card->iconColor . '" ></i>';
        $displayFoil = "<span hidden>0</span>";
        $displayInDeck = "<span hidden>0</span>";
        if ($card->quantityFoil > 0) {
            $displayFoil = '<span hidden>' . $card->quantityFoil . '</span>' . image(
                "/foil.png",
                'class="foilImage" '
            ) .
                '<p class="quantityFoil_displayNumber">' . $card->quantityFoil . '</p>' .
                '<p class="quantityFoil_displayPrice">€ ' . $card->priceFoil . '</p>';
        }
        if ($card->quantityDeck > 0) {
            $displayInDeck = '<span hidden>' . $card->quantityDeck . '</span>' . image(
                "/indeckWhite.png",
                'class="inDeckImage"'
            ) .
                '<p class="quantityDeck_displayNumber">' . $card->quantityDeck . '</p>';
        }
        if ($card->imageUrl == null) {
            $card->imageUrl = base_url('assets/images/cardBack.jpg');
        }
        $this->table->add_row(
            '<span data-cardid="' . $card->id . '">
        <img src="' . $card->imageUrl . '" class="imgCard">' . '</span>',
            $card->name,
            $displayRarity,
            $card->set->name,
            $card->quantity,
            "€ " . $card->price,
            $displayFoil,
            $displayInDeck,
            image($card->owner . '_square.jpg', 'class="imgOwner"')
        );
    }
    echo $this->table->generate();

    echo "<div hidden>" . form_input(array(
            'name' => 'paginationMaxRows',
            'id' => 'paginationMaxRows',
            'value' => $cardsCount
        )) . "</div>";
}
?>


<script>
    function updateCard(
        cardId,
        selected_price,
        selected_priceFoil,
        selected_quantity,
        selected_quantityFoil,
        selected_quantityDeck,
        selected_newOwner,
        selected_newOwnerQuantity,
        selected_newOwnerQuantityFoil
    ) {
        $.ajax({
            type: "POST",
            url: site_url + "/cards/ajaxUpdateCard",
            data: {
                cardId: cardId,
                selected_price: selected_price,
                selected_priceFoil: selected_priceFoil,
                selected_quantity: selected_quantity,
                selected_quantityFoil: selected_quantityFoil,
                selected_quantityDeck: selected_quantityDeck,
                selected_newOwner: selected_newOwner,
                selected_newOwnerQuantity: selected_newOwnerQuantity,
                selected_newOwnerQuantityFoil: selected_newOwnerQuantityFoil
            },
            success: function (resultArray) {
                // card is updated
            },
            error: function (xhr, status, error) {
                alert("-- ERROR IN AJAX --\n\n" + xhr.responseText);
            }
        });
    }

    function deleteCard(cardId) {
        $.ajax({
            type: "POST",
            url: site_url + "/cards/ajaxDeleteCard",
            data: {
                cardId: cardId
            },
            success: function (resultArray) {
                // card is deleted
            },
            error: function (xhr, status, error) {
                alert("-- ERROR IN AJAX --\n\n" + xhr.responseText);
            }
        });
    }

    var hideModal_byDeleteCardFunction;

    $(function () {
        $("#cardsTable").tablesorter();
        var td = $("td");
        td.hover(function () {
            $(this).closest('tr').css({"cursor": "pointer"});
            $(this).closest('tr').toggleClass('cardsTable_hoverColor');
        });
    });

    function getCardInfo_byId(id) {
        $.ajax({
            type: "GET",
            url: site_url + "/cards/ajaxGetCardInfoById",
            data: {
                cardid: id
            },
            success: function (result) {
                $("#cardDetailModal_resultaat").html(result);
                $('#cardDetailModal').modal('show');
            },
            error: function (xhr, status, error) {
                alert("-- ERROR IN AJAX --\n\n" + xhr.responseText);
            }
        });
    }

    //    voor modal-open function
    $(document).ajaxComplete(function () {
        hideModal_byDeleteCardFunction = false;

        $("tr").click(function (e) {
            e.preventDefault();
            var id = $(this).find('td:first-of-type span').data('cardid');
            if (id) {
                getCardInfo_byId(id);
            }
        });

        //indien modal gesloten word, door op de deleteknop te klikken
        $("#selected_deleteCard").click(function (e) {
            e.preventDefault();
            hideModal_byDeleteCardFunction = true;
            var cardId = $('#cardId').val();
            deleteCard(cardId);
            $('#cardDetailModal').modal('hide');
        });


    });

    $(document).ready(function () {
        $('#cardDetailModal').on('shown.bs.modal', function (event) {
            $(this).keypress(function (e) {
                e.stopImmediatePropagation();
                if (e.which == 13) {
                    $('#cardDetailModal').modal('hide');
                }
            });
        });

        // voor modal-close function
        $('#cardDetailModal').on('hide.bs.modal', function (e) {
            // indien modal gesloten wordt, zonder op de deleteknop te klikken
            // (indien op de delete-knop werd klikt, werdt hierboven de var "hideModal_byDeleteCardFunction"
            // op true gezet en gesloten zonder deze update functie uit te voeren)
            if (!hideModal_byDeleteCardFunction) {
                var cardId = $('#cardId').val();
                var selected_price = $('#selected_price').val();
                var selected_priceFoil = $('#selected_priceFoil').val();
                var selected_quantity = $('#selected_quantity').val();
                var selected_quantityFoil = $('#selected_quantityFoil').val();
                var selected_quantityDeck = $('#selected_quantityDeck').val();

                var selected_newOwner = $('#selected_newOwner').val();
                var selected_newOwnerQuantity = $('#selected_newOwnerQuantity').val();
                var selected_newOwnerQuantityFoil = $('#selected_newOwnerQuantityFoil').val();

                updateCard(
                    cardId,
                    selected_price,
                    selected_priceFoil,
                    selected_quantity,
                    selected_quantityFoil,
                    selected_quantityDeck,
                    selected_newOwner,
                    selected_newOwnerQuantity,
                    selected_newOwnerQuantityFoil
                );
            }
        });

    });
</script>

<!-- Modal -->
<div class="modal fade" id="cardDetailModal" tabindex="-1" role="dialog"
     aria-labelledby="cardDetailModal" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <!--            <div class="modal-header">-->
            <!--                <h5 class="modal-title" id="exampleModalLabel">Password</h5>-->
            <!--            </div>-->
            <!--            <h5 class="modal-title" id="exampleModalLabel">Password</h5>-->
            <div class="modal-body">
                <div>
                    <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                </div>
                <div class="container-fluid" id="cardDetailModal_resultaat"></div>
            </div>
            <!--<div class="modal-footer"></div>-->
        </div>
    </div>
</div>