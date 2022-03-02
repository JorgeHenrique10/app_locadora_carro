<?php

namespace App\Repositories\Locadora;

use Illuminate\Database\Eloquent\Model;

abstract class AbstractRepository
{
    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    public function selectAtributosRegistro($atributos)
    {
        $this->model = $this->model->selectRaw($atributos);
    }
    public function selectAtributosRegistroRelacionamento($atributos)
    {
        $this->model = $this->model->with($atributos);
    }
    public function filtro($filtros)
    {
        $filtros = explode(';', $filtros);
        foreach ($filtros as $condicao) {
            $c = explode(':', $condicao);
            $this->model = $this->model->where($c[0], $c[1], $c[2]);
        }
    }
    public function getResultado()
    {
        return $this->model->get();
    }
}
