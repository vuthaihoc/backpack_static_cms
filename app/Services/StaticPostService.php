<?php


namespace App\Services;


use App\Models\Post;
use Carbon\Carbon;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use League\Flysystem\Adapter\Local;
use League\Flysystem\Config;

class StaticPostService
{

    protected $file_system;

    /**
     * StaticPostService constructor.
     * @param $root
     */
    public function __construct()
    {
        $this->file_system = new Local(storage_path('post'));
    }

    public function all(){
        $files = $this->file_system->listContents();
        $result = [];
        foreach ($files as $file){
            if(strpos($file['path'], ".") === 0 || $file['type'] == 'dir'){
                continue;
            }
            $additions = [
                'path' => $file['path'],
                'updated_at' => Carbon::createFromTimestamp($file['timestamp']),
            ];
            $result[] = json_decode(
                file_get_contents(
                    $this->file_system->applyPathPrefix($file['path'])
                ),
                true
            ) + $additions;
        }

        if(count($result) == 0){
            return [
                [
                    'title' => 'demo',
                    'slug' => 'demo',
                    'path' => 'demo.json',
                    'content' => 'No content',
                ]
            ];
        }

        return $result;
    }

    public function store($data){
        if(empty($data['slug'])){
            $data['slug'] = Str::slug($data['title']);
        }
        $data['path'] = $data['slug'] . ".json";

        $this->file_system->write($data['path'], json_encode($data), new Config());
    }

    public function delete($id){
        $data = Post::find($id);
        if($data){
            $this->file_system->delete($data['path']);
        }
    }

}
