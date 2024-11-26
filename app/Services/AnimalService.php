<?php

namespace App\Services;
use App\Models\Animal;
use Illuminate\Support\Facades\Storage;

class AnimalService{
    protected $bucketUrl;
    public function __construct(){
        $this->bucketUrl = config('filesystems.disks.s3.url');
    }

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

        $relativePath = str_replace($this->bucketUrl . '/', '', $animal->img_url);

        $this->deleteImageFromBucket($relativePath);

        if(!$animal->delete()){
            return false;
        }

        return true;
    }

    public function updateAnimalById($id,$data){
        $animal = $this->getById($id);

        if(!$animal){
            return false;
        }

        if($data['file']){
            $file = $data['file'];
            $relativePath = str_replace($this->bucketUrl . '/', '', $animal->img_url);
            $this->deleteImageFromBucket($relativePath);
            $this->uploadImageToBucket($file);
        }

        if(!$animal->update($data)){
            return false;
        }

        return true;
    }

    public function deleteImageFromBucket($path){
        if (Storage::disk('s3')->exists($path)) {
            Storage::disk('s3')->delete($path);
        }
    }

    public function uploadImageToBucket($file){
        $path = $file->store('images', 's3');
        $url = Storage::url($path);

        return $url;
    }
}
