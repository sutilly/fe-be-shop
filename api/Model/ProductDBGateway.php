<?php

/* Gateway wird zwischengeschaltet, um über das Database Service je nach Bedarf mehrere DB einzubinden */

class ProductDBGateway extends DatabaseService
{

    public function __construct()
    {
        parent::__construct("localhost", "productDB", "root", "" );
    }

}

