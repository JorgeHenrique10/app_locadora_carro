<?php

namespace App\Models\Locadora;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{
    use HasFactory;

    protected $table = 'clientes';
    protected $fillable = ['nome'];

    public function locacao()
    {
        return $this->belongsToMany(Carro::class, 'locacoes', 'cliente_id', 'carro_id')
            ->withPivotValue([
                'data_inicio_periodo',
                'data_fim_periodo_previsto',
                'data_fim_periodo_entregue',
                'valor_diaria',
                'km_inicio',
                'km_fim',
                'created_at',
                'updated_at'
            ]);
    }

    public function rules()
    {
        return [
            'nome' => 'required|min:3|max:200',
        ];
    }
    public function feedback()
    {
        return [
            'required' => 'O campo :attribute é obrigatorio.',
            'nome.min' => 'O nome deve possuir no mínimo 3 caracteres',
            'nome.max' => 'O nome deve possuir no máximo 200 caracteres',
        ];
    }
}
