<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DinamicApiController extends Controller
{
    //

    public function executarApiPost(Request $request)
    {
        $id = $request->input('id');
        $input= $request->all();
        $caminho = storage_path("app/sistemas/{$id}/start.php");
        $saida= include $caminho;
       
        return $saida;
    }
}
