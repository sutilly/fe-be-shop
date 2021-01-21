<?php


class ProductListController
{

    private $jsonView;
    private $cart;

    public function __construct()
    {
        $this->jsonView = new JsonView;
        $this->cart = new ShoppingCartModel();
    }

    public function route()
    {

        $action = filter_input(INPUT_GET, 'action', FILTER_SANITIZE_STRING);

        switch (strtolower($action)) {
            case 'listtypes':
                $this->listProductTypes();
                break;
            case 'listproductsbytypeid':
                $typeId = filter_input(INPUT_GET, 'typeId', FILTER_SANITIZE_NUMBER_INT);
                $this->listProductTypesByTypeId($typeId);
                break;
            case 'addarticle':
                $articleId = filter_input(INPUT_GET, 'articleId', FILTER_SANITIZE_NUMBER_INT);
                $this->addArticle($articleId);
                break;
            case 'removearticle':
                $articleId = filter_input(INPUT_GET, 'articleId', FILTER_SANITIZE_NUMBER_INT);
                $this->removeArticle($articleId);
                break;
            case 'listcart':
                $this->listCart();
                break;
            case 'endsession':
                $this->cart->endSession();
                break;
            default:
                $this->jsonView->streamOutput(
                    ["error" => "Please chose a valid action.",
                        "possible actions:" => "listTypes, listProductsByTypeId"
                    ]);
                return false;
        }
    }


    private function listProductTypes()
    {
        $productTypesModel = new ProductCategoriesModel();
        $list = $productTypesModel->getProductTypes();
        $this->jsonView->streamOutput($list);
    }

    private function listProductTypesByTypeId($typeId)
    {
        $productTypesModel = new ProductsModel();
        $list = $productTypesModel->getProductsByTypeId($typeId);
        $this->jsonView->streamOutput($list);
    }

    private function addArticle($articleId)
    {
        $status = $this->cart->addToCart($articleId);
        $this->statusUpdate($status);
        $this->cart->saveSession();
    }

    private function removeArticle($articleId)
    {
        $status = $this->cart->removeFromCart($articleId);
        $this->statusUpdate($status);
        $this->cart->saveSession();
    }

    private function statusUpdate($statusOK)
    {
        if ($statusOK) {
            $ok = ['state' => 'OK'];
            $this->jsonView->streamOutput($ok);
        } else {
            $error = ['state' => 'ERROR'];
            $this->jsonView->streamOutput($error);
        }
    }

    private function listCart()
    {
        $cartContent = $this->cart->getContent();
        $this->jsonView->streamOutput($cartContent);

    }

}








