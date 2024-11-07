<?php
namespace App\Dao;

interface LoginDAOInterface
{
    public function obtenerUsuarioAc($login);
    public function inicioSesion($login);
}
