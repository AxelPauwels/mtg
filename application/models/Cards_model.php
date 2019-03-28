<?php

class Cards_model extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
    }

    // GET ----------------------------------------------------

    public function getAll($soort)
    {
        $this->db->order_by("name", "ASC");
        $query = $this->db->get($soort);
        return $query->result();
    }

    // get_byId
    public function getCardById($id)
    {
        $this->db->where('id', $id);
        $query = $this->db->get('card');
        return $query->row();
    }

    public function getSetByCodeId($codeId)
    {
        $this->db->where('code', $codeId);
        $query = $this->db->get('sets');
        if ($query->num_rows() == 1) {
            return $query->row();
        } else {
            return 0;
        }
    }

    public function getRarityById($id)
    {
        $this->db->where('id', $id);
        $query = $this->db->get('rarities');
        if ($query->num_rows() == 1) {
            return $query->row();
        } else {
            return 0;
        }
    }

    // (only Name)
    public function getSetNameByCodeId($codeId)
    {
        $this->db->select('name');
        $this->db->where('code', $codeId);
        $query = $this->db->get('sets');
        if ($query->num_rows() == 1) {
            return $query->row()->name;
        } else {
            return 0;
        }
    }

    public function getRarityNameById($id)
    {
        $this->db->select('name');
        $this->db->where('id', $id);
        $query = $this->db->get('rarities');
        if ($query->num_rows() == 1) {
            return $query->row()->name;
        } else {
            return 0;
        }
    }

    public function getFormatNameById($id)
    {
        $this->db->select('name');
        $this->db->from('formats');
        $this->db->where('id', $id);
        $query = $this->db->get()->row()->name;
        return $query;
    }

    public function getLegalityNameById($id)
    {
        $this->db->select('name');
        $this->db->from('legalities');
        $this->db->where('id', $id);
        $query = $this->db->get()->row()->name;
        return $query;
    }

    // get_byCardId
    public function getTypesByCardId($id)
    {
        $this->db->select('types.name');
        $this->db->from('card_types');
        $this->db->join('types', 'types.id = card_types.typeId', 'left');
        $this->db->where('card_types.cardId', $id);
        $query = $this->db->get()->result();
        return $query;
    }

    public function getSubtypesByCardId($id)
    {
        $this->db->select('subtypes.name');
        $this->db->from('card_subtypes');
        $this->db->join('subtypes', 'subtypes.id = card_subtypes.subtypeId', 'left');
        $this->db->where('card_subtypes.cardId', $id);
        $query = $this->db->get()->result();
        return $query;
    }

    public function getSupertypesByCardId($id)
    {
        $this->db->select('supertypes.name');
        $this->db->from('card_supertypes');
        $this->db->join('supertypes', 'supertypes.id = card_supertypes.supertypeId', 'left');
        $this->db->where('card_supertypes.cardId', $id);
        $query = $this->db->get()->result();
        return $query;
    }

    public function getLegalitiesByCardId($id)
    {
        $this->db->select('*');
        $this->db->where('cardId', $id);
        $this->db->from('card_legalities');
        $query = $this->db->get()->result();
        return $query;
    }


    // get_byName
    public function getRarityByName($name)
    {
        $this->db->where('name', $name);
        $query = $this->db->get('rarities');
        if ($query->num_rows() > 0) {
            return $query->row()->id;
        } else {
            return null;
        }
    }

    public function getColorByName($name)
    {
        $this->db->where('name', $name);
        $query = $this->db->get('colors');
        if ($query->num_rows() > 0) {
            return $query->row()->id;
        } else {
            return null;
        }
    }

    public function getSupertypeByName($name)
    {
        $this->db->where('name', $name);
        $query = $this->db->get('supertypes');
        if ($query->num_rows() > 0) {
            return $query->row()->id;
        } else {
            return null;
        }
    }

    public function getTypeByName($name)
    {
        $this->db->where('name', $name);
        $query = $this->db->get('types');
        if ($query->num_rows() > 0) {
            return $query->row()->id;
        } else {
            return null;
        }
    }

    public function getSubtypeByName($name)
    {
        $this->db->where('name', $name);
        $query = $this->db->get('subtypes');
        if ($query->num_rows() > 0) {
            return $query->row()->id;
        } else {
            return null;
        }
    }

    public function getLegalityByName($name)
    {
        $this->db->where('name', $name);
        $query = $this->db->get('legalities');
        if ($query->num_rows() > 0) {
            return $query->row()->id;
        } else {
            return null;
        }
    }

    public function getFormatByName($name)
    {
        $this->db->where('name', $name);
        $query = $this->db->get('formats');
        if ($query->num_rows() > 0) {
            return $query->row()->id;
        } else {
            return null;
        }
    }

    public function getSetByName($name)
    {
        $this->db->where('name', $name);
        $query = $this->db->get('sets');
        if ($query->num_rows() > 0) {
            return $query->row()->id;
        } else {
            return null;
        }
    }

    public function checkIfCardExistsByUserIdByApiCardId($userId, $apiCardId)
    {
        $this->db->where('userId', $userId);
        $this->db->where('apiCardId', $apiCardId);
        $query = $this->db->get('card');
        if ($query->num_rows() == 1) {
            return $query->row();
        } else {
            return null;
        }
    }

    // INSERT ----------------------------------------------------

    public function insertCardColor($cardColor)
    {
        $this->db->insert('card_colors', $cardColor);
    }

    public function insertCardSupertype($cardSupertype)
    {
        $this->db->insert('card_supertypes', $cardSupertype);
    }

    public function insertCardType($cardType)
    {
        $this->db->insert('card_types', $cardType);
    }

    public function insertCardSubtype($cardSubtype)
    {
        $this->db->insert('card_subtypes', $cardSubtype);
    }

    public function insertCardLegality($cardLegality)
    {
        $this->db->insert('card_legalities', $cardLegality);
    }

    public function insertCard($card)
    {
        $this->db->insert('card', $card);
        return $this->db->insert_id();
    }

    // UPDATE ----------------------------------------------------

    public function updateQuantitiesAndPricesByCardId(
        $cardId,
        $newQuantity,
        $newQuantityFoil,
        $newQuantityDeck,
        $newPrice,
        $newPriceFoil
    ) {
        $card = new stdClass();
        $card->quantity = $newQuantity;
        $card->quantityFoil = $newQuantityFoil;
        $card->quantityDeck = $newQuantityDeck;
        $card->price = $newPrice;
        $card->priceFoil = $newPriceFoil;
        $this->db->where('id', $cardId);
        $this->db->update('card', $card);
    }

    public function updateCard($card)
    {
        $this->db->where('id', $card->id);
        $this->db->update('card', $card);
    }

    // DELETE ----------------------------------------------------
    public function deleteCardColorsByCardId($cardId)
    {
        $this->db->where('cardId', $cardId);
        $this->db->delete('card_colors');
    }

    public function deleteCardTypesByCardId($cardId)
    {
        $this->db->where('cardId', $cardId);
        $this->db->delete('card_types');
    }

    public function deleteCardSubtypesByCardId($cardId)
    {
        $this->db->where('cardId', $cardId);
        $this->db->delete('card_subtypes');
    }

    public function deleteCardSupertypesByCardId($cardId)
    {
        $this->db->where('cardId', $cardId);
        $this->db->delete('card_supertypes');
    }

    public function deleteCardLegalitiesByCardId($cardId)
    {
        $this->db->where('cardId', $cardId);
        $this->db->delete('card_legalities');
    }

    public function deleteCardByCardId($cardId)
    {
        $this->db->where('id', $cardId);
        $this->db->delete('card');
    }


    // AJAX ----------------------------------------------------
    public function getCardsBySearchFunction($cardToSearchFor)
    {
        $this->db->select('card.id,card.userId,card.name,card.setCodeId,
        card.rarityId,card.quantity,card.quantityFoil,card.quantityDeck,
        card.price,card.priceFoil,card.imageUrl');
        $this->db->from('card');
        // anders...
        if ($cardToSearchFor->cardname != "" || $cardToSearchFor->cardname != null) {
            $this->db->like('name', $cardToSearchFor->cardname, 'after');
        }
        if ($cardToSearchFor->userId != 0) {
            $this->db->where('userId', $cardToSearchFor->userId);
        }
        if ($cardToSearchFor->setCodeId != "0") {
            $this->db->where('setCodeId', $cardToSearchFor->setCodeId);
        }
        if ($cardToSearchFor->rarityId != 0) {
            $this->db->where('rarityId', $cardToSearchFor->rarityId);
        }
        if ($cardToSearchFor->colorId != 0) {
            $this->db->join('card_colors', 'card_colors.cardId = card.id', 'left');
            $this->db->where('card_colors.colorId', $cardToSearchFor->colorId);
        }
        if ($cardToSearchFor->supertypeId != 0) {
            $this->db->join('card_supertypes', 'card_supertypes.cardId = card.id', 'left');
            $this->db->where('card_supertypes.supertypeId', $cardToSearchFor->supertypeId);
        }
        if ($cardToSearchFor->typeId != 0) {
            $this->db->join('card_types', 'card_types.cardId = card.id', 'left');
            $this->db->where('card_types.typeId', $cardToSearchFor->typeId);
        }
        if ($cardToSearchFor->subtypeId != 0) {
            $this->db->join('card_subtypes', 'card_subtypes.cardId = card.id', 'left');
            $this->db->where('card_subtypes.subtypeId', $cardToSearchFor->subtypeId);
        }
        if ($cardToSearchFor->legalityId != 0 || $cardToSearchFor->formatId != 0) {
            $this->db->join('card_legalities', 'card_legalities.cardId = card.id', 'inner');
            $this->db->group_by('card_legalities.cardId');
            if ($cardToSearchFor->legalityId != 0) {
                $this->db->where('card_legalities.legalityId', $cardToSearchFor->legalityId);
            }
            if ($cardToSearchFor->formatId != 0) {
                $this->db->where('card_legalities.formatId', $cardToSearchFor->formatId);
            }
        }
        $this->db->order_by('name', 'asc');
        $query = $this->db->get()->result();
        return $query;
    }

    public function getCardsBySearchFunction2(
        $cardToSearchFor,
        $pagination_startRow,
        $pagination_rowAantal,
        $orderby,
        $orderbyAscDesc
    ) {
        $this->db->select('card.id,card.userId,card.name,
        card.setCodeId,card.rarityId,card.quantity,card.quantityFoil,
        card.quantityDeck,card.price,card.priceFoil,card.imageUrl');
        $this->db->from('card');
        // anders...
        if ($cardToSearchFor->cardname != "" || $cardToSearchFor->cardname != null) {
            $this->db->like('name', $cardToSearchFor->cardname, 'after');
        }
        if ($cardToSearchFor->userId != 0) {
            $this->db->where('userId', $cardToSearchFor->userId);
        }
        if ($cardToSearchFor->setCodeId != "0") {
            $this->db->where('setCodeId', $cardToSearchFor->setCodeId);
        }
        if ($cardToSearchFor->rarityId != 0) {
            $this->db->where('rarityId', $cardToSearchFor->rarityId);
        }
        if ($cardToSearchFor->colorId != 0) {
            $this->db->join('card_colors', 'card_colors.cardId = card.id', 'left');
            $this->db->where('card_colors.colorId', $cardToSearchFor->colorId);
        }
        if ($cardToSearchFor->supertypeId != 0) {
            $this->db->join('card_supertypes', 'card_supertypes.cardId = card.id', 'left');
            $this->db->where('card_supertypes.supertypeId', $cardToSearchFor->supertypeId);
        }
        if ($cardToSearchFor->typeId != 0) {
            $this->db->join('card_types', 'card_types.cardId = card.id', 'left');
            $this->db->where('card_types.typeId', $cardToSearchFor->typeId);
        }
        if ($cardToSearchFor->subtypeId != 0) {
            $this->db->join('card_subtypes', 'card_subtypes.cardId = card.id', 'left');
            $this->db->where('card_subtypes.subtypeId', $cardToSearchFor->subtypeId);
        }
        if ($cardToSearchFor->legalityId != 0 || $cardToSearchFor->formatId != 0) {
            $this->db->join('card_legalities', 'card_legalities.cardId = card.id', 'inner');
            $this->db->group_by('card_legalities.cardId');
            if ($cardToSearchFor->legalityId != 0) {
                $this->db->where('card_legalities.legalityId', $cardToSearchFor->legalityId);
            }
            if ($cardToSearchFor->formatId != 0) {
                $this->db->where('card_legalities.formatId', $cardToSearchFor->formatId);
            }
        }
        $this->db->order_by($orderby, $orderbyAscDesc);
        $this->db->limit($pagination_rowAantal, $pagination_startRow);
        $query = $this->db->get()->result();
        return $query;
    }
}
