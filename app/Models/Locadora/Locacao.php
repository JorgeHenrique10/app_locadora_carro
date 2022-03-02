<?php

namespace App\Models\Locadora;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Locacao extends Model
{
    use HasFactory;

    protected $table = 'locacoes';
    protected $fillable = [
        'cliente_id',
        'carro_id',
        'data_inicio_periodo',
        'data_fim_periodo_previsto',
        'data_fim_periodo_entregue',
        'valor_diaria',
        'km_inicio',
        'km_fim'
    ];

    public function rules()
    {
        return [
            'cliente_id' => 'exists:clientes,id',
            'carro_id' => 'exists:carros,id',
            'data_inicio_periodo' => 'required|date',
            'data_fim_periodo_previsto' => 'required|date',
            'data_fim_periodo_entregue' => 'date|nullable',
            'valor_diaria' => 'required|numeric',
            'km_inicio' => 'required|integer',
            'km_final' => 'integer',
        ];
    }

    public function feedback()
    {
        return [
            'required' => 'O campo :attribute é obrigatorio.',
            'integer' => 'O campo :attribute tem que ser do tipo integer',
            'date' => 'O campo :attribute tem que ser do tipo date',
            'cliente_id.exists' => 'O campo cliente_id deve está cadastrado na tabela de clientes',
            'carro_id.exists' => 'O campo carro_id deve está cadastrado na tabela de carros',
        ];
    }
}
