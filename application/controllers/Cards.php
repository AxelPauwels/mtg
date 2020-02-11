<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Cards extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->library('form_validation');
        $this->load->library('pagination');
        $this->load->model('cards_model');
        $this->load->model('user_model');
    }

    public function addCard()
    {
        $functionIsInsert = true; //bepaal of het insert of update is
        $apiCard = null; // wordt enkel gebruikt wanneer deze wordt opgevuld indien het insert is

        $card = new stdClass();
        //eerst ophalen wat er nodig is om te kijken of de card al bestaat
        $card->userId = $this->session->userdata('user_id');
        $card->name = $this->input->post('selected_name');
        $card->apiCardId = $this->input->post('selected_cardid');
        $card->setCodeId = $this->input->post('selected_setcodeid');
        $card->quantity = ($this->input->post('selected_numberOfCards') == 0)
            ? 1 : $this->input->post('selected_numberOfCards');

        $priceWithoutEuro = str_replace(" €", "", $this->input->post('selected_price'));
        $card->price = str_replace(",", ".", $priceWithoutEuro);

        $foilBestaat = ($this->input->post('selected_isFoil') == true) ? true : false;
        $IsInDeckBestaat = ($this->input->post('selected_isInDeck') == true) ? true : false;
        if ($foilBestaat) {
            $card->quantityFoil = ($this->input->post('selected_numberOfCardsFoil') == 0)
                ? 1 : $this->input->post('selected_numberOfCardsFoil');
            $priceWithoutEuro = str_replace(" €", "", $this->input->post('selected_priceFoil'));
            $card->priceFoil = str_replace(",", ".", $priceWithoutEuro);
        }
        if ($IsInDeckBestaat) {
            $card->quantityDeck = ($this->input->post('selected_numberOfCardsIsInDeck') == 0)
                ? 1 : $this->input->post('selected_numberOfCardsIsInDeck');
        }

        //check if card exist in database
        $existingCard = $this->checkIfCardExistsByUserIdByApiCardId($card->userId, $card->apiCardId);

        if ($existingCard != null) {
            //update quantity
            $functionIsInsert = false;
        } else {
            //data ophalen van api
            $apiCard = $this->getCardFromApiById($card->apiCardId);
            if ($apiCard != null) {
                //eigen gegevens toevoegen

                $card->added = date('Y-m-d');

                //api gegevens toevoegen
                if (isset($apiCard["manaCost"])) {
                    $card->manaCost = $apiCard["manaCost"];
                }
                if (isset($apiCard["cmc"])) {
                    $card->cmc = $apiCard["cmc"];
                }
                if (isset($apiCard["type"])) {
                    $card->type = $apiCard["type"];
                }
                if (isset($apiCard["rarity"])) {
                    $card->rarityId = $this->cards_model->getRarityByName($apiCard["rarity"]);
                }
                if (isset($apiCard["text"])) {
                    $card->text = $apiCard["text"];
                }
                if (isset($apiCard["flavor"])) {
                    $card->flavor = $apiCard["flavor"];
                }
                if (isset($apiCard["artist"])) {
                    $card->artist = $apiCard["artist"];
                }
                if (isset($apiCard["number"])) {
                    $card->number = $apiCard["number"];
                }
                if (isset($apiCard["layout"])) {
                    $card->layout = $apiCard["layout"];
                }
                if (isset($apiCard["multiverseid"])) {
                    $card->multiverseid = $apiCard["multiverseid"];
                }
                if (isset($apiCard["imageUrl"])) {
                    $card->imageUrl = $apiCard["imageUrl"];
                }
                if (isset($apiCard["power"])) {
                    $card->power = $apiCard["power"];
                }
                if (isset($apiCard["toughness"])) {
                    $card->toughness = $apiCard["toughness"];
                }
                if (isset($apiCard["originalText"])) {
                    $card->originalText = $apiCard["originalText"];
                }
                if (isset($apiCard["originalType"])) {
                    $card->originalType = $apiCard["originalType"];
                }
            }
        }

        // de locked settings opslaan in "session"
        $lockIsFoil = $this->input->post('lock_isFoil');
        $lockIsInDeck = $this->input->post('lock_isInDeck');
        $this->setLockedSelectionsToSession($card, $lockIsFoil, $lockIsInDeck);

        //kaart updaten of inserten // indien het insert is, ook de arrays opslaan
        $this->insertOrUpdateCard($functionIsInsert, $card, $existingCard, $apiCard);

        redirect('home/addCards');
    }

    private function checkIfCardExistsByUserIdByApiCardId($userId, $apiCardId)
    {
        return $this->cards_model->checkIfCardExistsByUserIdByApiCardId($userId, $apiCardId);
    }

    public function ajaxCheckifcardexists()
    {
        $userId = $this->session->userdata('user_id');
        $apiCardId = $this->input->post('apicardid');

        //check if card exist in database
        $existingCard = $this->checkIfCardExistsByUserIdByApiCardId($userId, $apiCardId);
        if ($existingCard) {
            echo json_encode($existingCard);
        }
    }

    private function insertApiArrays($apiCard, $cardId)
    {
        if (isset($apiCard["colors"])) {
            foreach ($apiCard["colors"] as $color) {
                $cardColor = new stdClass();
                $cardColor->cardId = $cardId;
                $cardColor->colorId = $this->cards_model->getColorByName($color);
                if ($cardColor->colorId != null) {
                    $this->cards_model->insertCardColor($cardColor);
                }
            }
        }
        if (isset($apiCard["supertypes"])) {
            foreach ($apiCard["supertypes"] as $supertype) {
                $cardSupertype = new stdClass();
                $cardSupertype->cardId = $cardId;
                $cardSupertype->supertypeId = $this->cards_model->getSupertypeByName($supertype);
                if ($cardSupertype->supertypeId != null) {
                    $this->cards_model->insertCardSupertype($cardSupertype);
                }
            }
        }
        if (isset($apiCard["types"])) {
            foreach ($apiCard["types"] as $type) {
                $cardtype = new stdClass();
                $cardtype->cardId = $cardId;
                $cardtype->typeId = $this->cards_model->getTypeByName($type);
                if ($cardtype->typeId != null) {
                    $this->cards_model->insertCardType($cardtype);
                }
            }
        }
        if (isset($apiCard["subtypes"])) {
            foreach ($apiCard["subtypes"] as $subtype) {
                $cardSubtype = new stdClass();
                $cardSubtype->cardId = $cardId;
                $cardSubtype->subtypeId = $this->cards_model->getSubtypeByName($subtype);
                if ($cardSubtype->subtypeId != null) {
                    $this->cards_model->insertCardSubtype($cardSubtype);
                }
            }
        }

        if (isset($apiCard["legalities"])) {
            foreach ($apiCard["legalities"] as $legality) {
                $cardLegality = new stdClass();
                $cardLegality->cardId = $cardId;
                $cardLegality->legalityId = $this->cards_model->getLegalityByName($legality["legality"]);
                $cardLegality->formatId = $this->cards_model->getFormatByName($legality["format"]);
                if ($cardLegality->legalityId != null && $cardLegality->formatId != null) {
                    $this->cards_model->insertCardLegality($cardLegality);
                }
            }
        }
    }

    private function getCardFromApiById($cardId)
    {
        $apiRequestUrl = "https://api.magicthegathering.io/v1/cards?id=" . $cardId;

        // api request
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => $apiRequestUrl,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => array(
                "cache-control: no-cache"
            ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);
        curl_close($curl);
        // end api request

        if (json_decode($response, true)) {
            $response = json_decode($response, true); //because of true, it's in an array
            // return only the id
            return $response['cards'][0];
        } else {
            return null;
        }
    }

    private function setLockedSelectionsToSession($card, $lockIsFoil, $lockIsInDeck)
    {
        if ($lockIsFoil) {
            $this->session->set_flashdata(
                'addCards_lockedFormSettings_isFoil_visibleCheckboxCheckedValue',
                $card->isFoil
            );
            $this->session->set_flashdata('addCards_lockedFormSettings_isFoil_checkboxCheckedValue', true);
            $this->session->set_flashdata('addCards_lockedFormSettings_isFoil_iconClassForColor', "locked");
        }
        if ($lockIsInDeck) {
            $this->session->set_flashdata(
                'addCards_lockedFormSettings_isInDeck_visibleCheckboxCheckedValue',
                $card->isInDeck
            );
            $this->session->set_flashdata('addCards_lockedFormSettings_isInDeck_checkboxCheckedValue', true);
            $this->session->set_flashdata('addCards_lockedFormSettings_isInDeck_iconClassForColor', "locked");
        }
    }

    private function insertOrUpdateCard($functionIsInsert, $newCard, $existingCard, $apiCard)
    {
        if (!$functionIsInsert) {
            $newQuantity = $existingCard->quantity + $newCard->quantity;
            $newQuantityFoil = $existingCard->quantityFoil;
            $newQuantityDeck = $existingCard->quantityDeck;
            $newPrice = $existingCard->price;
            $newPriceFoil = $existingCard->priceFoil;
            if ($newCard->quantityFoil > 0) {
                $newQuantityFoil += $newCard->quantityFoil;
            }
            if ($newCard->quantityDeck > 0) {
                $newQuantityDeck += $newCard->quantityDeck;
            }
            if ($newCard->price > 0.00 && ($newCard->price != null || $newCard->price != 0)) {
                $newPrice = $newCard->price;
            }
            if ($newCard->priceFoil > 0.00 && ($newCard->priceFoil != null || $newCard->priceFoil != 0)) {
                $newPriceFoil = $newCard->priceFoil;
            }
            $this->cards_model->updateQuantitiesAndPricesByCardId(
                $existingCard->id,
                $newQuantity,
                $newQuantityFoil,
                $newQuantityDeck,
                $newPrice,
                $newPriceFoil
            );
        } else {
            $cardId = $this->cards_model->insertCard($newCard);
            $this->insertApiArrays($apiCard, $cardId);
        }
    }

//    AJAX FUNCTIONS
    public function ajaxGetCardsBySearchFunction()
    {
        $cardToSearchFor = new stdClass();

        $cardToSearchFor->cardname = $this->input->get('cardname');
        $cardToSearchFor->userId = intval($this->input->get('userId'));
        $cardToSearchFor->setCodeId = $this->input->get('setCodeId');
        $cardToSearchFor->rarityId = intval($this->input->get('rarityId'));
        $cardToSearchFor->typeId = intval($this->input->get('typeId'));
        $cardToSearchFor->colorId = intval($this->input->get('colorId'));
        $cardToSearchFor->legalityId = intval($this->input->get('legalityId'));
        $cardToSearchFor->formatId = intval($this->input->get('formatId'));
        $cardToSearchFor->supertypeId = intval($this->input->get('supertypeId'));
        $cardToSearchFor->subtypeId = intval($this->input->get('subtypeId'));

        $cards1 = $this->cards_model->getCardsBySearchFunction($cardToSearchFor);

        $orderby = $this->input->get('orderby');
        $orderbyAscDesc = $this->input->get('orderbyAscDesc');
        $pagination_startRow = intval($this->input->get('pagination_startRow'));
        $pagination_rowAantal = 10;//ook aanpassen in main_menu.php

        $cards = $this->cards_model->getCardsBySearchFunction2(
            $cardToSearchFor,
            $pagination_startRow,
            $pagination_rowAantal,
            $orderby,
            $orderbyAscDesc
        );

        foreach ($cards as $card) {
            $card->set = $this->cards_model->getSetByCodeId($card->setCodeId);
            $card->rarity = $this->cards_model->getRarityById($card->rarityId);
            $card->iconColor = $this->getIconColorClassname($card->rarity->name);
            $card->owner = $this->user_model->getUserName_byId($card->userId);
            //$timestamp = strtotime($card->added);
            //$card->added_formatted = date('d-m-Y', $timestamp);
        }
        $data['cards'] = $cards;
        $data['cardsCount'] = sizeof($cards1);
        $data['cardsTotalPrice'] = $this->getTotalPriceOfCards($cards1);
        $data['user'] = $this->authex->getUserInfo();

        $this->load->view('ajax_cards', $data);
    }

    private function getTotalPriceOfCards($cards)
    {
        $totalPrice = 0.0;
        foreach ($cards as $card) {
            $totalPrice += ($card->price + $card->priceFoil);
        }
        return $totalPrice;
    }

    private function getIconColorClassname($rarityName)
    {
        // common is zwart op een zwarte achtergrond, extra klasse geven voor text-shadow blur
        $iconColor = "";
        switch ($rarityName) {
            case "Common":
                $iconColor = "ss-common iconShadow";
                break;
            case "Uncommon":
                $iconColor = "ss-uncommon";
                break;
            case "Mythic Rare":
                $iconColor = "ss-mythic";
                break;
            case "Rare":
                $iconColor = "ss-rare";
                break;
        }
        return $iconColor;
    }

    public function ajaxGetCardInfoById()
    {
        $cardId = $this->input->get('cardid');
        $card = $this->cards_model->getCardById($cardId);
        $card->originalText = $this->getTextWithImages($card->originalText);
        $card->set = $this->cards_model->getSetByCodeId($card->setCodeId);
        $card->rarity = $this->cards_model->getRarityById($card->rarityId);
        $card->iconColor = $this->getIconColorClassname($card->rarity->name);
        $card->manacostColors = $this->getManaColors($card->manaCost);
        $card->allTypes = $this->getAllTypes($card);
        $card->allLegalities = $this->getAllLegalities($card);

        $data['card'] = $card;
        $data['user'] = $this->authex->getUserInfo();
        $users = $this->user_model->getUsers();
        $dropdownOptions_users = array();
        foreach ($users as $user) {
            $dropdownOptions_users[$user->id] = ucfirst($user->name);
        }
        $data['dropdownOptions_users'] = $dropdownOptions_users;
        $this->load->view('ajax_cardDetails', $data);
    }

    private function getManaColors($manacost)
    {
        $manacostColors = [];
//        trim voor laatste whiteSpace, dan rtrim voor laatste "}"
        $colors = explode("}", rtrim(trim($manacost), "}"));

        foreach ($colors as $color) {
            array_push($manacostColors, str_replace("{", "", $color));
        }

        return $manacostColors;
    }

    private function getAllTypes($card)
    {
        $allTypes = new stdClass();

        // supertypes
        $supertypes = $this->cards_model->getSupertypesByCardId($card->id);
        $supertypes_string = "";
        foreach ($supertypes as $supertype) {
            $supertypes_string .= rtrim($supertype->name) . "<br>";
        }
        $allTypes->supertypes = $supertypes_string;

        // types
        $types = $this->cards_model->getTypesByCardId($card->id);
        $types_string = "";
        foreach ($types as $type) {
            $types_string .= rtrim($type->name) . "<br>";
        }
        $allTypes->types = $types_string;

        // subtypes
        $subtypes = $this->cards_model->getSubtypesByCardId($card->id);
        $subtypes_string = "";
        foreach ($subtypes as $subtype) {
            $subtypes_string .= rtrim($subtype->name) . "<br>";
        }
        $allTypes->subtypes = $subtypes_string;

        return $allTypes;
    }

    private function getAllLegalities($card)
    {
        $allLegalities = array();
        $cardLegalities = $this->cards_model->getLegalitiesByCardId($card->id);
        foreach ($cardLegalities as $cardLegality) {
            $string = "";
            $formatName = $this->cards_model->getFormatNameById($cardLegality->formatId);
            $legalityName = $this->cards_model->getLegalityNameById($cardLegality->legalityId);
            array_push(
                $allLegalities,
                '<span style="display:inline-block;width: 75px">' . $formatName . ":</span>" . $legalityName . "<br/>"
            );
        }
        return $allLegalities;
    }

    private function getTextWithImages($text)
    {
//        $textWithColorImages = new stdClass();
//        $textWithImages = new stdClass();

        $textWithColorImages = str_replace(
            array('{T}', '{C}', '{W}', '{B}', '{G}', '{R}', '{U}'),
            array(
                image('/manacost/T.png', 'class="manacolorIcon_inOriginalText"'),
                image('/manacost/C.png', 'class="manacolorIcon_inOriginalText"'),
                image('/manacost/W.png', 'class="manacolorIcon_inOriginalText"'),
                image('/manacost/B.png', 'class="manacolorIcon_inOriginalText"'),
                image('/manacost/G.png', 'class="manacolorIcon_inOriginalText"'),
                image('/manacost/R.png', 'class="manacolorIcon_inOriginalText"'),
                image('/manacost/U.png', 'class="manacolorIcon_inOriginalText"'),
            ),
            $text
        );

        $textWithImages = str_replace(
            array(
                '{0}',
                '{1}',
                '{2}',
                '{3}',
                '{4}',
                '{5}',
                '{6}',
                '{7}',
                '{8}',
                '{9}',
                '{10}',
                '{11}',
                '{12}',
                '{13}',
                '{14}',
                '{15}',
                '{16}'
            ),
            array(
                '<span class="customManacolorIcon_inOriginalText" >0</span>',
                '<span class="customManacolorIcon_inOriginalText" >1</span>',
                '<span class="customManacolorIcon_inOriginalText" >2</span>',
                '<span class="customManacolorIcon_inOriginalText" >3</span>',
                '<span class="customManacolorIcon_inOriginalText" >4</span>',
                '<span class="customManacolorIcon_inOriginalText" >5</span>',
                '<span class="customManacolorIcon_inOriginalText" >6</span>',
                '<span class="customManacolorIcon_inOriginalText" >7</span>',
                '<span class="customManacolorIcon_inOriginalText" >8</span>',
                '<span class="customManacolorIcon_inOriginalText" >9</span>',
                '<span class="customManacolorIcon_inOriginalText" >10</span>',
                '<span class="customManacolorIcon_inOriginalText" >11</span>',
                '<span class="customManacolorIcon_inOriginalText" >12</span>',
                '<span class="customManacolorIcon_inOriginalText" >13</span>',
                '<span class="customManacolorIcon_inOriginalText" >14</span>',
                '<span class="customManacolorIcon_inOriginalText" >15</span>',
                '<span class="customManacolorIcon_inOriginalText" >16</span>',
            ),
            $textWithColorImages
        );
        return $textWithImages;
    }

    public function ajaxUpdateCard()
    {
    	// TODO
    	return;

        $updateCard = new stdClass();
        $updateCard->id = $this->input->post('cardId');
        $updateCard->price = $this->input->post('selected_price');
        $updateCard->priceFoil = $this->input->post('selected_priceFoil');
        $updateCard->quantity = intval($this->input->post('selected_quantity'));
        $updateCard->quantityFoil = intval($this->input->post('selected_quantityFoil'));
        $updateCard->quantityDeck = intval($this->input->post('selected_quantityDeck'));

        $newOwnerId = intval($this->input->post('selected_newOwner'));
        $newOwnerQuantity = intval($this->input->post('selected_newOwnerQuantity'));
        $newOwnerQuantityFoil = intval($this->input->post('selected_newOwnerQuantityFoil'));

//        $updateCard->userId = $this->input->post('selected_owner');
        ///TODO jcepjfokeofjeosfbujzdsjcehosiodkazçpeosidghvndorsdfhiuezrlfghbivuzileuehbfiykufdkudjo zaukqkj
        $ownerQuantityToSwitch = $this->input->post('selected_ownerQuantity');

        if ($updateCard->id && $updateCard->price &&
            $updateCard->priceFoil && $updateCard->quantity &&
            $updateCard->quantityFoil && $updateCard->quantityDeck) {
            $this->cards_model->updateCard($updateCard);
        } else {
//            TODO DELETE
            redirect("some data is missing (in cards/ajaxUpdateCard)");
        }
    }

    public function ajaxDeleteCard()
    {
        $cardId = $this->input->post('cardId');
        $this->cards_model->deleteCardColorsByCardId($cardId);
        $this->cards_model->deleteCardTypesByCardId($cardId);
        $this->cards_model->deleteCardSubtypesByCardId($cardId);
        $this->cards_model->deleteCardSupertypesByCardId($cardId);
        $this->cards_model->deleteCardLegalitiesByCardId($cardId);
        $this->cards_model->deleteCardByCardId($cardId);
    }
}
