<?php

include "api/Config/config.php";

$productList = new ProductListController();
$productList->route();


