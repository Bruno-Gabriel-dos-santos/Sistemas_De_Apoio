@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <h1 class="text-center">Financeiro</h1>
    <p class="text-center">Bem-vindo à área financeira do sistema.</p>
    <div class="flex flex-wrap justify-center gap-4 mt-8">
        <button class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded shadow">IRPF</button>
        <button class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-6 rounded shadow">Projeções e Tabelas</button>
        <button class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-6 rounded shadow">Capital Geral e Atualizado</button>
        <button class="bg-yellow-500 hover:bg-yellow-600 text-white font-bold py-2 px-6 rounded shadow">Metas e Planejamentos</button>
        <button class="bg-purple-600 hover:bg-purple-700 text-white font-bold py-2 px-6 rounded shadow">Bens e Recursos</button>
    </div>
</div>
@endsection 