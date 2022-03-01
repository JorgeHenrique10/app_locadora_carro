<?php

namespace App\Models\Locadora;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Carro extends Model
{
    use HasFactory;
    protected $table = 'carros';
    protected $fillable = ['modelo_id', 'placa', 'disponivel', 'km'];

    public function modelo()
    {
        return $this->hasOne(Modelo::class, 'modelo_id', 'id');
    }

    public function locacao()
    {
        return $this->belongsToMany(Cliente::class, 'locacoes', 'carro_id', 'cliente_id')
            ->withPivotValue([
                'data_inicio_periodo',
                'data_fim_periodo_previsto',
                'data_fim_periodo_entregue',
                'valor_diaria',
                'km_inicio',
                'km_fim',
                'created_at',
                'updated_at'
            ]);;
    }

    public function rules()
    {
        return [
            'modelo_id' => 'exists:modelos,id',
            'placa' => 'required|min:7|max:7',
            'disponivel' => 'required|boolean',
            'km' => 'required|integer'
        ];
    }

    public function feedback()
    {
        return [
            'required' => 'O campo :attribute é obrigatorio.',
            'integer' => 'O campo :attribute tem que ser do tipo integer',
            'boolean' => 'O campo :attribute tem que ser do tipo boolean',
            'modelo_id.exists' => 'O campo modelo_id deve está cadastrado na tabela de modelos',
            'placa.min' => 'O placa deve possuir no mínimo 7 caracteres',
            'placa.max' => 'O placa deve possuir no máximo 7 caracteres',
        ];
    }
}
