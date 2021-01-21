<?php

if (!session_id()) {
    session_start();
}

class ShoppingCartModel
{

    private $dbGateway;
    private $cartContent;

    public function __construct()
    {

        if (!isset($_SESSION['cart-content'])) {
            $_SESSION['cart-content'] = array();
        }

        $this->dbGateway = new ProductDBGateway();
        $this->cartContent = $_SESSION['cart-content'];

    }

    /*CHECK PRODUCT INFO and AVAILABILITY*/

    private function getProductInfo($articleId)
    {
        $sql = "SELECT id AS productId, name AS productName, price_of_sale AS price FROM products WHERE id ='{$articleId}'";
        $response = $this->dbGateway->processQuery($sql);

        if ($response) {
            return $response;
        } else {
            return false;
        }
    }

    /*ADD*/

    public function addToCart($articleId)
    {
        $isInCart = $this->isInCart($articleId);
        $isAvailable = $this->getProductInfo($articleId);

        if ($isAvailable && !$isInCart) {
            $this->addNewItem($articleId);
        } elseif ($isInCart) {
            $this->increaseItemAmount($articleId);
        } else {
            return false;
        }

        return true;
    }


    private function isInCart($articleId)
    {
        $cartContent = $this->cartContent;
        return in_array($articleId, array_column($cartContent, 'Id'));
    }


    private function addNewItem($articleId)
    {
        $name = $this->getNameFromId($articleId);
        $price = $this->getPriceFromId($articleId);
        $this->cartContent[] = array('Id' => $articleId, 'articleName' => $name, 'price' => $price, 'amount' => 1);
    }


    private function increaseItemAmount($articleId)
    {

        $index = array_search($articleId, array_column($this->cartContent, 'Id'));
        $this->cartContent[$index]['amount']++;

    }

    private function getNameFromId($articleId)
    {
        $product = $this->getProductInfo($articleId);
        return $product[0]['productName'];
    }

    private function getPriceFromId($articleId)
    {
        $product = $this->getProductInfo($articleId);
        return $product[0]['price'];
    }

    /*REMOVE*/

    public function removeFromCart($articleId)
    {
        $isInCart = $this->isInCart($articleId);

        if ($isInCart) {
            $this->ReduceOrDelete($articleId);
        } else {
            return false;
        }
        return true;
    }


    private function ReduceOrDelete($articleId)
    {

        $index = array_search($articleId, array_column($this->cartContent, 'Id'));
        $amount = $this->cartContent[$index]['amount'];

        if ($amount == 1) {
            unset($this->cartContent[$index]);
        } elseif ($amount > 1) {
            $this->reduceItemAmount($index);
        }
    }

    private function reduceItemAmount($index)
    {
        $this->cartContent[$index]['amount']--;
    }


    /*GET CONTENT*/

    public function getContent()
    {
        if ($this->cartContent) {
            return $this->cartContent;
        }
    }


    /*END + SAVE SESSION*/

    public function endSession()
    {
        unset($this->cartContent);
        session_destroy();
    }

    public function saveSession()
    {
        $_SESSION['cart-content'] = array_values($this->cartContent);
    }


}


