<?php

namespace App\Services;
use App\Models\Animal;
use Illuminate\Support\Facades\Storage;

class AnimalService{

    public function getById($id){
        return Animal::find($id);
    }

    public function create(array $data){
        return Animal::create($data);
    }

    public function setAnimalImage(Animal $animal,$url){
        $animal->img_url = $url;
        if(!$animal->save()){
            return false;
        }

        return true;
    }

    public function getAll(){
        return Animal::get();
    }

    public function deleteAnimalById($id){
        $animal = $this->getById($id);
        if(!$animal){
            return false;
        }

        $bucketUrl = config('filesystems.disks.s3.url'); // Obtém a URL base do S3
        $relativePath = str_replace($bucketUrl . '/', '', $animal->img_url);

        if (Storage::disk('s3')->exists($relativePath)) {
            Storage::disk('s3')->delete($relativePath);
        }

        if(!$animal->delete()){
            return false;
        }

        return true;
    }
}
