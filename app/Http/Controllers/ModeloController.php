<?php

namespace App\Http\Controllers;

use App\Models\Locadora\Modelo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ModeloController extends Controller
{
    public function __construct(Modelo $modelo)
    {
        $this->modelo = $modelo;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $modelos = [];
        if ($request->atributos) {
            $modelos = $this->modelo->selectRaw($request->atributos);
        } else {
            $modelos = $this->modelo;
        }

        if ($request->atributos_marca) {
            $modelos = $modelos->with('marca:id,' . $request->atributos_marca);
        } else {
            $modelos = $modelos->with('marca');
        }
        if ($request->filtros) {
            $filtros = explode(';', $request->filtros);
            foreach ($filtros as $condicao) {
                $c = explode(':', $condicao);
                $modelos->where($c[0], $c[1], $c[2]);
            }
        }
        $modelos = $modelos->get();

        if ($modelos == null) {
            return response()->json(['msg' => 'Não foi encontrado nenhum registro.'], 404);
        }

        return response()->json($modelos, 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate($this->modelo->rules(), $this->modelo->feedback());

        $this->modelo->fill($request->all());

        if ($request->file('imagem')) {

            $this->modelo->imagem = $request->file('imagem')->store('imagens/modelo', 'public');
        }

        $this->modelo->save();

        return response()->json($this->modelo, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Locadora\Modelo  $modelo
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id)
    {
        $req = [];
        if ($request->atributos) {
            $req = $this->modelo->selectRaw($request->atributos);
        } else {
            $req = $this->modelo;
        }

        if ($request->atributos_marca) {
            $req = $req->with('marca:id,' . $request->atributos_marca)->find($id);
        } else {
            $req = $req->with('marca')->find($id);
        }

        if ($req == null) {
            return response()->json(['msg' => 'Não foi encontrado nenhum registro.'], 404);
        }

        return response()->json($req, 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Locadora\Modelo  $modelo
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $modelo = $this->modelo->find($id);

        if ($modelo == null) {
            return response()->json(['msg' => 'Não foi encontrado nenhum registro com este id.'], 404);
        }

        if ($request->method() === 'PUT') {
            $request->validate($modelo->rules(), $modelo->feedback());

            $modelo->fill($request->all());

            if ($request->file('imagem')) {

                $modelo->imagem = $request->file('imagem')->store('imagens/modelo', 'public');
                Storage::disk('public')->delete($modelo->getOriginal()['imagem']);
            }

            $modelo->save();

            return response()->json(['msg' => 'Modelo atualizada com sucesso.', 'data' => $modelo], 200);
        } else {
            $regrasDinamicas = [];

            foreach ($this->modelo->rules() as $campo => $regra) {
                if (array_key_exists($campo, $request->all())) {
                    $regrasDinamicas[$campo] = $regra;
                }
            }

            $request->validate($regrasDinamicas, $modelo->feedback());

            $modelo->fill($request->all());

            if ($request->file('imagem')) {

                $modelo->imagem = $request->file('imagem')->store('imagens/modelo', 'public');

                Storage::disk('public')->delete($modelo->getOriginal()['imagem']);
            }

            $modelo->save();

            return response()->json(['msg' => 'Modelo atualizada com sucesso.', 'data' => $modelo], 200);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Locadora\Modelo  $modelo
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $modelo = $this->modelo->find($id);

        if ($modelo == null) {
            return response()->json(['msg' => 'Não foi encontrado nenhum registro com este id.'], 404);
        }

        Storage::disk('public')->delete($modelo->imagem);

        $modelo->delete();
        return response()->json(['msg' => 'Modelo deletada com sucesso.'], 200);
    }
}
