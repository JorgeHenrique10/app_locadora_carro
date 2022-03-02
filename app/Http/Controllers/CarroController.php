<?php

namespace App\Http\Controllers;

use App\Models\Locadora\Carro;
use App\Repositories\Locadora\CarroRepository;
use Illuminate\Http\Request;

class CarroController extends Controller
{
    public function __construct(Carro $carro)
    {
        $this->carro = $carro;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $carroRepository = new CarroRepository($this->carro);

        if ($request->atributos) {
            $carroRepository->selectAtributosRegistro($request->atributos);
        }
        if ($request->filtros) {
            $carroRepository->filtro($request->filtros);
        }
        $carros = $carroRepository->getResultado();

        if ($carros == null) {
            return response()->json(['msg' => 'Não foi encontrado nenhum registro.'], 404);
        }
        return response()->json($carros, 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate($this->carro->rules(), $this->carro->feedback());

        $resp = $this->carro->create($request->all());

        return response()->json($resp, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Locadora\Carro  $carro
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id)
    {
        $req = [];
        if ($request->atributos) {
            $req = $this->carro->selectRaw($request->atributos);
        } else {
            $req = $this->carro;
        }

        $req = $req->find($id);

        if ($req == null) {
            return response()->json(['msg' => 'Não foi encontrado nenhum registro.'], 404);
        }

        return response()->json($req, 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Locadora\Carro  $carro
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $carro = $this->carro->find($id);

        if ($carro == null) {
            return response()->json(['msg' => 'Não foi encontrado nenhum registro com este id.'], 404);
        }

        if ($request->method() === 'PUT') {
            $request->validate($carro->rules(), $carro->feedback());

            $carro->update($request->all());

            return response()->json(['msg' => 'Carro atualizado com sucesso.', 'data' => $carro], 200);
        } else {
            $regrasDinamicas = [];

            foreach ($this->carro->rules() as $campo => $regra) {
                if (array_key_exists($campo, $request->all())) {
                    $regrasDinamicas[$campo] = $regra;
                }
            }

            $request->validate($regrasDinamicas, $carro->feedback());

            $carro->update($request->all());

            return response()->json(['msg' => 'Carro atualizado com sucesso.', 'data' => $carro], 200);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Locadora\Carro  $carro
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $carro = $this->carro->find($id);

        if ($carro == null) {
            return response()->json(['msg' => 'Não foi encontrado nenhum registro com este id.'], 404);
        }

        $carro->delete();
        return response()->json(['msg' => 'Carro deletada com sucesso.'], 200);
    }
}
