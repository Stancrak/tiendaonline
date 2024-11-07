<?php

namespace App\Models;

use App\Dao\AdministradorDAOInterface;

require_once __DIR__ . '/../../Dao/Interfaces/AdministradorDaoInterfaces.php';

class AdministradorModel
{
    private $adminDAO;

    public function __construct(AdministradorDAOInterface $adminDAO)
    {
        $this->adminDAO = $adminDAO;
    }
}
