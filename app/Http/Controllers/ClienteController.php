<?php

namespace App\Http\Controllers;

use App\Models\Locadora\Cliente;
use App\Repositories\Locadora\ClienteRepository;
use Illuminate\Http\Request;

class ClienteController extends Controller
{
    public function __construct(Cliente $cliente)
    {
        $this->cliente = $cliente;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $clienteRepository = new ClienteRepository($this->cliente);

        if ($request->atributos) {
            $clienteRepository->selectAtributosRegistro($request->atributos);
        }
        if ($request->filtros) {
            $clienteRepository->filtro($request->filtros);
        }
        // $cliente = $clienteRepository->getResultado();
        $cliente = $clienteRepository->getResultadoPaginado(2);

        if ($cliente == null) {
            return response()->json(['msg' => 'Não foi encontrado nenhum registro.'], 404);
        }
        return response()->json($cliente, 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate($this->cliente->rules(), $this->cliente->feedback());

        $resp = $this->cliente->create($request->all());

        return response()->json($resp, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Locadora\Cliente  $cliente
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id)
    {
        $req = [];
        if ($request->atributos) {
            $req = $this->cliente->selectRaw($request->atributos);
        } else {
            $req = $this->cliente;
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
     * @param  \App\Models\Locadora\Cliente  $cliente
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $cliente = $this->cliente->find($id);

        if ($cliente == null) {
            return response()->json(['msg' => 'Não foi encontrado nenhum registro com este id.'], 404);
        }

        if ($request->method() === 'PUT') {
            $request->validate($cliente->rules(), $cliente->feedback());

            $cliente->update($request->all());

            return response()->json(['msg' => 'Cliente atualizado com sucesso.', 'data' => $cliente], 200);
        } else {
            $regrasDinamicas = [];

            foreach ($this->cliente->rules() as $campo => $regra) {
                if (array_key_exists($campo, $request->all())) {
                    $regrasDinamicas[$campo] = $regra;
                }
            }

            $request->validate($regrasDinamicas, $cliente->feedback());

            $cliente->update($request->all());

            return response()->json(['msg' => 'Cliente atualizado com sucesso.', 'data' => $cliente], 200);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Locadora\Cliente  $cliente
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $cliente = $this->cliente->find($id);

        if ($cliente == null) {
            return response()->json(['msg' => 'Não foi encontrado nenhum registro com este id.'], 404);
        }

        $cliente->delete();
        return response()->json(['msg' => 'Cliente deletada com sucesso.'], 200);
    }
}
