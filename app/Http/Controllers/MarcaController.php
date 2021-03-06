<?php

namespace App\Http\Controllers;

use App\Models\Locadora\Marca;
use App\Repositories\Locadora\MarcaRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MarcaController extends Controller
{
    public function __construct(Marca $marca)
    {
        $this->marca = $marca;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        $marcaRepository = new MarcaRepository($this->marca);

        if ($request->atributos) {
            $marcaRepository->selectAtributosRegistro($request->atributos);
        }
        if ($request->atributos_modelo) {
            $marcaRepository->selectAtributosRegistroRelacionamento('modelos:marca_id,' . $request->atributos_modelo);
        } else {
            $marcaRepository->selectAtributosRegistroRelacionamento('modelos');
        }
        if ($request->filtros) {
            $marcaRepository->filtro($request->filtros);
        }
        // $marcas = $marcaRepository->getResultado();
        $marcas = $marcaRepository->getResultadoPaginado(2);

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

        $this->marca->fill($request->all());

        if ($request->file('imagem')) {

            $this->marca->imagem = $request->file('imagem')->store('imagens/marca', 'public');
        }

        $this->marca->save();

        return response()->json($this->marca, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Locadora\Marca  $marca
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id)
    {
        $req = [];
        if ($request->atributos) {
            $req = $this->marca->selectRaw($request->atributos);
        } else {
            $req = $this->marca;
        }

        if ($request->atributos_modelo) {
            $req = $req->with('modelos:id,' . $request->atributos_modelo)->find($id);
        } else {
            $req = $req->with('modelos')->find($id);
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

            $marca->fill($request->all());

            if ($request->file('imagem')) {

                $marca->imagem = $request->file('imagem')->store('imagens/marca', 'public');
                Storage::disk('public')->delete($marca->getOriginal()['imagem']);
            }

            $marca->save();

            return response()->json(['msg' => 'Marca atualizada com sucesso.', 'data' => $marca], 200);
        } else {
            $regrasDinamicas = [];

            foreach ($this->marca->rules() as $campo => $regra) {
                if (array_key_exists($campo, $request->all())) {
                    $regrasDinamicas[$campo] = $regra;
                }
            }

            $request->validate($regrasDinamicas, $marca->feedback());

            $marca->fill($request->all());

            if ($request->file('imagem')) {

                $marca->imagem = $request->file('imagem')->store('imagens/marca', 'public');
                Storage::disk('public')->delete($marca->getOriginal()['imagem']);
            }

            $marca->save();

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

        Storage::disk('public')->delete($marca->imagem);

        $marca->delete();
        return response()->json(['msg' => 'Marca deletada com sucesso.'], 200);
    }
}
