<?php

namespace App\Http\Controllers;

use App\Services\AnimalService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AnimalController extends Controller
{

    public function __construct(protected AnimalService $animalService){
    }

    public function store(Request $request){
        $request->validate([
            'name' => 'required|max:96',
            'description' => 'required| max:2000',
            'img' => 'required|image|max:2048'
        ]);


        $data = [
            'name' => $request->name,
            'description' => $request->description,
        ];

        $animal = $this->animalService->create($data);

        if($request->hasFile('img')){
            $file = $request->file('img')->store('images','s3');
            $url = Storage::url($file);
        }

        $animalImage = $this->animalService->setAnimalImage($animal, $url);

         if(!$animal || !$animalImage){
             return response()->json(['error' => 'Erro ao inserir dados do animal!', 404]);
         }

        return response()->json([
            'success' => 'Dados do Animal foram cadastrados com sucesso!'
        ],201);

    }

    public function getAllAnimals(){
        $animals = $this->animalService->getAll();

        if(!$animals){
            return response()->json(['error' => 'Erro recuperar dados do animais!', 404]);
        }

        return response()->json([
            'animals' => $animals,
            'success' => 'Dados dos Animais foram recuperados com sucesso!'
        ],200);
    }

    public function getAnimal($id){
        $animal = $this->animalService->getById($id);
        if(!$animal){
            return response()->json(['error' => 'Não foi possivel recuperar dados do animal!', 404]);
        }

        return response()->json([
            'animal' => $animal,
            'success' => 'Dados do Animal foram recuperados com sucesso!'
        ],200);
    }

    public function deleteAnimal($id){
        $deleteAnimal = $this->animalService->deleteAnimalById($id);

        if(!$deleteAnimal){
            return response()->json(['error' => 'Erro ao excluir dados do animal!', 500]);
        }

        return response()->json([
            'success' => 'Dados do Animal foram excluídos com sucesso!'
        ],200);
    }
}
