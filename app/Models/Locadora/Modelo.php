<?php

namespace App\Models\Locadora;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Modelo extends Model
{
    use HasFactory;

    protected $table = 'Modelos';
    protected $fillable = ['marca_id', 'nome', 'imagem', 'numero_portas', 'lugares', 'airbag', 'abs'];

    public function marca()
    {
        return $this->belongsTo(Marca::class, 'marca_id', 'id');
    }

    public function rules()
    {
        return [
            'marca_id' => 'required|exists:marcas,id',
            'nome' => 'required|min:3|max:200',
            'imagem' => 'required|mimes:jpg,bmp,png',
            'numero_portas' => 'required|integer|digits_between:1,5',
            'lugares' => 'required|integer|digits_between:1,20',
            'airbag' => 'required|boolean',
            'abs' => 'required|boolean',
        ];
    }
    public function feedback()
    {
        return [
            'required' => 'O campo :attribute é obrigatorio.',
            'nome.min' => 'O nome deve possuir no mínimo 3 caracteres',
            'nome.max' => 'O nome deve possuir no máximo 200 caracteres',
            'marca_id.exists' => 'O campo marca_id deve está cadastrado na tabela de marcas',
            'integer' => 'O campo :attribute tem que ser do tipo integer',
            'boolean' => 'O campo :attribute tem que ser do tipo boolean',
        ];
    }
}
