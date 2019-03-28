<div class="row">
    <div class="col-md-4" id="cardImage">
        <img src="<?php echo $card->imageUrl; ?>" class="imgCard_inModal center img-fluid"/>
    </div>
    <div class="col-md-8" id="tabsContainer">
        <?php
        $active_becauseNotLoggedIn = "";
        $active_becauseLoggedIn = "";
        $activeTab_becauseNotLoggedIn = "";
        $activeTab_becauseLoggedIn = "";
        if ($user) {
            $active_becauseLoggedIn = " active";
            $activeTab_becauseLoggedIn = " show active";
        }
        else {
            $active_becauseNotLoggedIn = " active";
            $activeTab_becauseNotLoggedIn = " show active";
        }

        ?>
        <ul class="nav nav-tabs" id="myTab" role="tablist">
            <li class="nav-item">
                <a class="nav-link <?php echo $active_becauseNotLoggedIn; ?>" id="tabContent_basicInfo-tab"
                   data-toggle="tab" href="#tabContent_basicInfo"
                   role="tab" aria-controls="tabContent_basicInfo"
                   aria-selected="true">CARD INFO</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="tabContent_moreDetails-tab" data-toggle="tab" href="#tabContent_moreDetails"
                   role="tab"
                   aria-controls="tabContent_moreDetails" aria-selected="false">MORE DETAILS</a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?php echo $active_becauseLoggedIn; ?>" id="tabContent_owner-tab" data-toggle="tab"
                   href="#tabContent_owner"
                   role="tab"
                   aria-controls="tabContent_owner" aria-selected="false">OWNER</a>
            </li>
        </ul>
        <div class="tab-content" id="myTabContent">
            <div id="tabContent_basicInfo" class="tab-pane fade <?php echo $activeTab_becauseNotLoggedIn; ?>"
                 role="tabpanel"
                 aria-labelledby="tabContent_basicInfo-tab">
                <?php
                $manaCostIcons = "";
                $colorImages = array("W", "B", "G", "R", "U");
                foreach ($card->manacostColors as $color) {
                    if (in_array($color, $colorImages)) {
                        $manaCostIcons .= image('manacost/' . $color . '.png', 'class="manacolorIcon"');
                    }
                    else {
                        if ($color == null) {
                            $color = 0;
                        }
                        $manaCostIcons .= '<span class="customManacolorIcon" >' . $color . '</span>';
                    }
                }
                $template = array('table_open' => '<table class="table table-responsive-sm table-sm noHoverAllowed" border="0" cellpadding="4" cellspacing="0">');
                $this->table->set_template($template);
                $this->table->add_row("CARDNAME", $card->name);
                $this->table->add_row("MANACOST", $manaCostIcons);
                $this->table->add_row("TYPE", $card->type);
                $this->table->add_row("SET", $card->set->name);
                $this->table->add_row("RARITY", '<i class="ss ss-' . strtolower($card->set->code) . ' ss-fw ' . $card->iconColor . ' "></i>');

                $this->table->add_row("TEXT", $card->originalText);
                if ($card->flavor) {
                    $this->table->add_row("FLAVOR", $card->flavor);
                }
                if ($card->power) {
                    $this->table->add_row("POWER/TOUGHNESS", $card->power . " / " . $card->toughness);
                }

                $quantity_priceDisplay = '<div class="cardDetail_quantity_priceDisplayContainer">'.$card->quantity . '<p class="cardDetail_quantity_priceDisplay">€ ' . $card->price . '</p>'.'</div>';

                $this->table->add_row("QUANTITY / PRICE", $quantity_priceDisplay);
                $quantityDisplay = $card->quantity;
                $priceDisplay = $card->price;

                if ($card->quantityFoil > 0) {
                    $quantity_priceDisplayFoils = '<div class="cardDetail_quantity_priceDisplayContainer">'.$card->quantityFoil . '<p class="cardDetail_quantity_priceDisplay">€ ' . $card->priceFoil . '</p>'.'</div>';
                    $this->table->add_row(image("/foil.png", 'style="width:25px;height:auto;margin-left:-4px"'),$quantity_priceDisplayFoils);
                }

                if ($card->quantityDeck > 0) {
                    $this->table->add_row(image("/indeckWhite.png", 'style="width:18px;height:auto"'),$card->quantityDeck );
                }
                $this->table->add_row("ADDED", $card->added);

                echo $this->table->generate();
                ?>
            </div>
            <div id="tabContent_moreDetails" class="tab-pane fade" role="tabpanel"
                 aria-labelledby="tabContent_moreDetails-tab">
                <?php
                $template = array('table_open' => '<table class="table table-responsive-sm table-sm noHoverAllowed" border="0" cellpadding="4" cellspacing="0">');
                $this->table->set_template($template);
                $this->table->add_row("API ID", $card->apiCardId);
                $this->table->add_row("MULTIVERSE ID", $card->multiverseid);
                $this->table->add_row("SET NUMBER", $card->number);
                $this->table->add_row("ARTIST", $card->artist);
                $this->table->add_row("LAYOUT", ucfirst($card->layout));
                $this->table->add_row("RELEASED", $card->set->releaseDate);
                if ($card->allTypes->supertypes) {
                    $this->table->add_row("SUPERTYPES", $card->allTypes->supertypes);
                }
                if ($card->allTypes->types) {
                    $this->table->add_row("TYPES", $card->allTypes->types);
                }
                if ($card->allTypes->subtypes) {
                    $this->table->add_row("SUBTYPES", $card->allTypes->subtypes);
                }
                if ($card->allLegalities) {
                    $td = "";
                    foreach ($card->allLegalities as $legality) {
                        $td .= $legality;
                    }
                    $this->table->add_row("LEGALITIES", $td);
                }
                echo $this->table->generate();
                ?>
            </div>
            <div id="tabContent_owner" class="tab-pane fade <?php echo $activeTab_becauseLoggedIn; ?>" role="tabpanel"
                 aria-labelledby="tabContent_owner-tab">
                <?php
                $dataCardId = array(
                    'type'  => 'hidden',
                    'name'  => 'cardId',
                    'id'    => 'cardId',
                    'value' => $card->id
                );
                echo form_input($dataCardId);

                $template = array('table_open' => '<table class="table table-responsive-sm table-sm noHoverAllowed" border="0" cellpadding="4" cellspacing="0">');
                $this->table->set_template($template);
                if ($user) {
                    if ($user->id != $card->userId) {
                        $this->table->add_row("", "YOU'RE NOT THE OWNER OF THIS CARD");
                    }
                    else {
                        $this->table->add_row('PRICE', form_input('selected_price', $card->price, 'type="number" step="0.01" value="0.00" placeholder="Price" id="selected_price"  class="form-control btn btn-default customInput" '));
                        $this->table->add_row('PRICE FOIL', form_input('selected_priceFoil', $card->priceFoil, 'type="number" step="0.01" value="0.00" placeholder="PriceFoil" id="selected_priceFoil" class="form-control btn btn-default customInput" '));
                        $this->table->add_row('QUANTITY', form_dropdown('selected_quantity', array("0","1", "2", "3", "4", "5", "6", "7", "8", "9", "10", "11", "12", "13", "14", "15", "16", "17", "18", "19", "20"), $card->quantity, 'id="selected_quantity" '));
                        $this->table->add_row('QUANTITY'.'<div class="owner_quantityFoilImageContainer">'.image("/foil.png", 'class="owner_quantityFoilImage"').'</div>', form_dropdown('selected_quantityFoil', array("0","1", "2", "3", "4", "5", "6", "7", "8", "9", "10", "11", "12", "13", "14", "15", "16", "17", "18", "19", "20"), $card->quantityFoil, 'id="selected_quantityFoil" '));
                        $this->table->add_row('QUANTITY'.'<div class="owner_quantityDeckImageContainer">'.image("/indeckWhite.png", 'class="owner_quantityDeckImage"').'</div>', form_dropdown('selected_quantityDeck', array("0","1", "2", "3", "4", "5", "6", "7", "8", "9", "10", "11", "12", "13", "14", "15", "16", "17", "18", "19", "20"), $card->quantityDeck, 'id="selected_quantityDeck" '));
                        $this->table->add_row('SWITCH OWNER', form_dropdown('selected_newOwner', $dropdownOptions_users, $card->userId, 'id="selected_newOwner"'));
                        $this->table->add_row('QUANTITY', form_dropdown('selected_newOwnerQuantity', array("0", "1", "2", "3", "4", "5", "6", "7", "8", "9", "10"), 0, 'id="selected_newOwnerQuantity" '));
                        $this->table->add_row('QUANTITY'.'<div class="owner_quantityFoilImageContainer">'.image("/foil.png", 'class="owner_quantityFoilImage"').'</div>', form_dropdown('selected_newOwnerQuantityFoil', array("0", "1", "2", "3", "4", "5", "6", "7", "8", "9", "10"), 0, 'id="selected_newOwnerQuantityFoil" '));

                        $deleteButtonCell = array('data' => form_button('selected_deleteCard', 'DELETE CARD', 'id="selected_deleteCard"'), 'colspan' => 2);
                        $this->table->add_row($deleteButtonCell);
                    }
                }
                else {
                    $notLoggedInCell = array('data' =>  'NOT LOGGED IN ', 'colspan' => 2);

                    $this->table->add_row($notLoggedInCell);
                }
                echo $this->table->generate();

                ?>
            </div>
        </div>

    </div>
</div>