<?php


class ProductCategoriesModel implements ListInterface
{

    private $dbGateway;
    private $formattedQueryResult;


    public function __construct()
    {
       $this->dbGateway = new ProductDBGateway();
    }

    public function getProductTypes() {
        $sqlQuery = "SELECT id, name FROM product_types ORDER BY id ";
        $rawResult = $this ->dbGateway->processQuery($sqlQuery);
        $this->formattedQueryResult = $this->formatList($rawResult);
        return $this->formattedQueryResult;
    }

    public function formatList($dbResponse)
    {
        $formattedList = array();

        foreach ($dbResponse as $key) {
           $formattedList[] = array('productType' => $key['name'], 'id'=> $key['id'], 'url' => $this->generateURL() . $key['id']);
        }

        return ($formattedList);
    }

    public function generateURL()
    {
        $protocol = ((!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off') || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
        $url = $protocol . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'] . "?action=listProductsByTypeId&typeId=";
        return $url;
    }

}
