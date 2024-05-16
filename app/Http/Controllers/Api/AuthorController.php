<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Author;
use Illuminate\Http\Request;

class AuthorController extends Controller
{
    /**
     * Mostra a lista de Autores, com paginação.
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        // Defini como 10 o número padrão de resultados por página.
        $perPage = $request->query('per_page', 10);

        // Busca a lista de autores, paginada.
        $authors = Author::paginate($perPage);

        // Caso não haja nenhum author cadastrado a API vai tratar com um erro Not Found
        if ($authors->isEmpty()) {
            return response()->json([
                'message' => 'Nenhum resultado encontrado.'
            ], 404);
        }

        // Retornar a resposta paginada como JSON
        return response()->json($authors);
    }

    /**
     * Método de gravação de um novo Autor  
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:authors',
            'date_of_birth' => 'required|date',
        ]);

        $author = Author::create([
            'name' => $request->name,
            'date_of_birth' => $request->date_of_birth,
        ]);

        return response()->json($author, 201);
    }

    /**
     * Display the specified resource.
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(string $id)
    {
        // Primeiro vamos buscar o autor pelo seu ID
        $author = Author::find($id);

        // Caso o autor não exista, vamos retornar um erro informando que ele não foi encontrado.
        if (!$author) {
            return response()->json([
                'message' => 'Autor não encontrado.'
            ], 404);
        }

        // Caso contrário o autor é retornado para o usuário.
        return response()->json($author);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
