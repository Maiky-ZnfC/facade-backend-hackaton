<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product {
    public $codsap;
    public $desproducto;
    public $desunidadnegocio;
    public $desmarca;
    public $descategoria;
    public $desgrupoarticulo;
    public $desclase;
    public $largo;
    public $ancho;
    public $volumen;
    public $pesobruto;

    public function __construct($data) {
        $this->codsap = $data['codsap'] ?? null;
        $this->desproducto = $data['desproducto'] ?? null;
        $this->desunidadnegocio = $data['desunidadnegocio'] ?? null;
        $this->desmarca = $data['desmarca'] ?? null;
        $this->descategoria = $data['descategoria'] ?? null;
        $this->desgrupoarticulo = $data['desgrupoarticulo'] ?? null;
        $this->desclase = $data['desclase'] ?? null;
        $this->largo = $data['largo'] ?? null;
        $this->ancho = $data['ancho'] ?? null;
        $this->volumen = $data['volumen'] ?? null;
        $this->pesobruto = $data['pesobruto'] ?? null;
    }
}
