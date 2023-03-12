<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of DB
 *
 * @author Jesus
 */
class DB {

    //put your code here
    private $conect;

    public function __construct($value) {
        switch ($value) {
            case 1111:
                $host = 'localhost';
                $usuario = 'root';
                $contrasena = '';
                $bd = 'db_psicopedagogia';
                break;
        }
        $connectionString = 'mysql:host=' . $host . ';dbname=' . $bd . ';charset=utf8';
        try {
            $this->conect = new PDO($connectionString, $usuario, $contrasena);
            $this->conect->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->conect->exec("SET session sql_mode = '';");
        } catch (PDOException $e) {
            $this->conect = 'No hemos podido conectar la base de datos.';
            echo "ERROR:" . $e->getMessage();
            exit;
        }
    }

    public function connect() {
        return $this->conect;
    }

}
