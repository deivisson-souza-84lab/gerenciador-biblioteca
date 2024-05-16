<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Author;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

/**
 * Class AuthorsController
 * @package App\Http\Controllers\Api
 */
class AuthorsController extends Controller
{
    /**
     * O método `index` vai listar todos os autores.
     * 
     * Autores com livros associados trarão também um array 
     * com `id`, `title` e `publication_year` da tabela `books`.
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $authors = Author::with('books')->get();

        if ($authors->isEmpty()) {
            return response()->json(['message' => 'Nenhum resultado encontrado.'], 404);
        }

        return response()->json(['authors' => $authors], 200);
    }

    /**
     * O método `store` vai gravar os dados do novo autor.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:authors',
            'date_of_birth' => 'required|date',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }

        $author = Author::create($request->all());
        return response()->json(['author' => $author], 201);
    }

    /**
     * O método `show` vai buscar os dados de um autor específico.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(int $id)
    {
        $author = Author::with('books')->find($id);

        if (!$author) {
            return response()->json(['message' => 'Nenhum resultado encontrado.'], 404);
        }

        return response()->json(['author' => $author], 200);
    }

    /**
     * O método `update` vai atualizar os dados de um determinado autor.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, int $id)
    {
        $author = Author::with('books')->find($id);

        if (!$author) {
            return response()->json(['message' => 'Nenhum resultado encontrado.'], 404);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|required|string|max:255|unique:authors',
            'date_of_birth' => 'sometimes|required|date',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }

        $author->update($request->all());

        return response()->json(['author' => $author], 200);
    }

    /**
     * O método `destroy` vai excluir os dados de um determinado autor.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(int $id)
    {
        $author = Author::with('books')->find($id);

        if (!$author) {
            return response()->json(['message' => 'Nenhum resultado encontrado.'], 404);
        }

        $author->delete();

        return response()->json(['message' => 'Autor removido com sucesso.'], 200);
    }
}