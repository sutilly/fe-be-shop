<?php

class ProductsModel implements ListInterface
{
    private $dbGateway;
    public $formattedQueryResult;

    public function __construct()
    {
        $this->dbGateway = new ProductDBGateway();
    }

    public function getProductsByTypeId($productTypeId)
    {
        $sqlQuery = "SELECT t.name AS productTypeName, p.id AS productId, p.name AS productName FROM product_types t JOIN products p ON t.id = p.id_product_types WHERE t.id = {$productTypeId}";
        $rawResult = $this ->dbGateway->processQuery($sqlQuery);
        $this->formattedQueryResult = $this->formatList($rawResult);
        return $this->formattedQueryResult;
    }

    public function formatList($dbResponse)
    {
        $formattedList = array();

        foreach ($dbResponse as $key) {
            $formattedList['productType'] = $key['productTypeName'];
            $formattedList['products'][] = ['name'=> $key['productName'], 'productId' => $key['productId']];
        }

        $formattedList['url'] = $this->generateURL();
        return $formattedList;
    }


    public function generateURL()
    {
        $protocol = ((!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off') || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
        $url = $protocol . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'] . "?action=listTypes";
        return $url;
    }


}
