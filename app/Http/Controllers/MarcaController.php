<?php

namespace App\Http\Controllers;

use App\Models\Locadora\Marca;
use Illuminate\Http\Request;

class MarcaController extends Controller
{
    private $marca;

    public function __construct()
    {
        $this->marca = new Marca();
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $marcas = $this->marca->all();

        if ($marcas == null) {
            return response()->json(['msg' => 'Não foi encontrado nenhum registro.'], 404);
        }
        return response()->json($marcas, 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate($this->marca->rules(), $this->marca->feedback());

        $req = $this->marca->create($request->all());

        return response()->json($req, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Locadora\Marca  $marca
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $req = $this->marca->find($id);

        if ($req == null) {
            return response()->json(['msg' => 'Não foi encontrado nenhum registro.'], 404);
        }

        return response()->json($req, 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Locadora\Marca  $marca
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $marca = $this->marca->find($id);

        if ($marca == null) {
            return response()->json(['msg' => 'Não foi encontrado nenhum registro com este id.'], 404);
        }

        if ($request->method() === 'PUT') {
            $request->validate($marca->rules(), $marca->feedback());
            $marca->update($request->all());
            return response()->json(['msg' => 'Marca atualizada com sucesso.', 'data' => $marca], 200);
        } else {
            $regrasDinamicas = [];

            foreach ($this->marca->rules() as $campo => $regra) {
                if (array_key_exists($campo, $request->all())) {
                    $regrasDinamicas[$campo] = $regra;
                }
            }

            $request->validate($regrasDinamicas, $marca->feedback());

            $marca->update($request->all());
            return response()->json(['msg' => 'Marca atualizada com sucesso.', 'data' => $marca], 200);
        }

        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Locadora\Marca  $marca
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $marca = $this->marca->find($id);

        if ($marca == null) {
            return response()->json(['msg' => 'Não foi encontrado nenhum registro com este id.'], 404);
        }

        $marca->destroy();
        return response()->json(['msg' => 'Marca deletada com sucesso.'], 200);
    }
}
