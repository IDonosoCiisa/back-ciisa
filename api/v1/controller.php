<?php

class Controlador{
    public function getAllServices(){
        return file_get_contents('../mockInfo/services.json');
    }
    public function getAboutUs(){
        return file_get_contents('../mockInfo/aboutUs.json');
    }
}