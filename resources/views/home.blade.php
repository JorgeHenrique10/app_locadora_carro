@extends('layouts.app')

@section('content')
    <card-component titulo="Dashboard">
        <template v-slot:conteudo>
            <h1>Indicadores</h1>
        </template>
        <template v-slot:rodape>
        </template>
    </card-component>
@endsection
