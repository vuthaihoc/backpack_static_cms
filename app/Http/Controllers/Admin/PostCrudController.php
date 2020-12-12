<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\Operations\CreateOperation;
use App\Http\Controllers\Admin\Operations\DeleteOperation;
use App\Http\Controllers\Admin\Operations\UpdateOperation;
use App\Http\Requests\PostRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class PostCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class PostCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use CreateOperation;
    use UpdateOperation;
    use DeleteOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;

    /**
     * Configure the CrudPanel object. Apply settings to all operations.
     *
     * @return void
     */
    public function setup()
    {
        CRUD::setModel(\App\Models\Post::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/post');
        CRUD::setEntityNameStrings('post', 'posts');
    }

    /**
     * Define what happens when the List operation is loaded.
     *
     * @see  https://backpackforlaravel.com/docs/crud-operation-list-entries
     * @return void
     */
    protected function setupListOperation()
    {
        CRUD::addColumns([
            [
                'name' => 'title',
            ],[
                'name' => 'content',
            ],[
                'name' => 'updated_at',
            ]
        ]);
    }

    protected function setupShowOperation(){
        CRUD::addColumns([
            [
                'name' => 'title',
            ],
            [
                'name' => 'slug',
            ],
            [
                'name' => 'content',
                'type' => 'markdown',
            ],
            [
                'name' => 'path',
            ]
        ]);
    }

    /**
     * Define what happens when the Create operation is loaded.
     *
     * @see https://backpackforlaravel.com/docs/crud-operation-create
     * @return void
     */
    protected function setupCreateOperation()
    {
        CRUD::setValidation(PostRequest::class);

        CRUD::addFields([
            [
                'name' => 'title',
            ],
            [
                'name' => 'slug',
            ],
            [
                'name' => 'path',
                'type' => 'hidden',
            ],
            [
                'name' => 'content',
                'type' => 'simplemde',
            ]
        ]);
    }

    /**
     * Define what happens when the Update operation is loaded.
     *
     * @see https://backpackforlaravel.com/docs/crud-operation-update
     * @return void
     */
    protected function setupUpdateOperation()
    {
        $this->setupCreateOperation();
    }
}
