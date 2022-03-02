<?php

namespace App\Http\Controllers;

use App\Models\Locadora\Locacao;
use App\Repositories\Locadora\LocacaoRepository;
use Illuminate\Http\Request;

class LocacaoController extends Controller
{
    public function __construct(Locacao $locacao)
    {
        $this->locacao = $locacao;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $locacaoRepository = new LocacaoRepository($this->locacao);

        if ($request->atributos) {
            $locacaoRepository->selectAtributosRegistro($request->atributos);
        }
        if ($request->filtros) {
            $locacaoRepository->filtro($request->filtros);
        }
        $locacao = $locacaoRepository->getResultado();

        if ($locacao == null) {
            return response()->json(['msg' => 'Não foi encontrado nenhum registro.'], 404);
        }
        return response()->json($locacao, 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate($this->locacao->rules(), $this->locacao->feedback());

        $resp = $this->locacao->create($request->all());

        return response()->json($resp, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Locadora\Locacao  $locacao
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id)
    {
        $req = [];
        if ($request->atributos) {
            $req = $this->locacao->selectRaw($request->atributos);
        } else {
            $req = $this->locacao;
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
     * @param  \App\Models\Locadora\Locacao  $locacao
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $locacao = $this->locacao->find($id);

        if ($locacao == null) {
            return response()->json(['msg' => 'Não foi encontrado nenhum registro com este id.'], 404);
        }

        if ($request->method() === 'PUT') {
            $request->validate($locacao->rules(), $locacao->feedback());

            $locacao->update($request->all());

            return response()->json(['msg' => 'Locacao atualizado com sucesso.', 'data' => $locacao], 200);
        } else {
            $regrasDinamicas = [];

            foreach ($this->locacao->rules() as $campo => $regra) {
                if (array_key_exists($campo, $request->all())) {
                    $regrasDinamicas[$campo] = $regra;
                }
            }

            $request->validate($regrasDinamicas, $locacao->feedback());

            $locacao->update($request->all());

            return response()->json(['msg' => 'Locacao atualizado com sucesso.', 'data' => $locacao], 200);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Locadora\Locacao  $locacao
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $locacao = $this->locacao->find($id);

        if ($locacao == null) {
            return response()->json(['msg' => 'Não foi encontrado nenhum registro com este id.'], 404);
        }

        $locacao->delete();
        return response()->json(['msg' => 'Locacao deletada com sucesso.'], 200);
    }
}
