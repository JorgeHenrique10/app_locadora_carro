<?php

namespace App\Models\Locadora;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Marca extends Model
{
    use HasFactory;

    protected $table = 'marcas';
    protected $fillable = ['nome', 'imagem'];

    public function modelos()
    {
        return $this->hasMany(Modelo::class, 'marca_id', 'id');
    }

    public function rules()
    {
        return [
            'nome' => "required|unique:marcas,id," . $this->id . "|min:3",
            'imagem' => 'required|mimes:jpg,bmp,png'
        ];
    }
    public function feedback()
    {
        return [
            'required' => 'O campo :attribute é obrigatório.',
            'nome.unique' => "Já foi cadastrado uma marca com este nome.",
            'nome.min' => 'O campo nome deve possuir no mínimo 3 caracteres'
        ];
    }
}
