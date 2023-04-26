<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Dashboard\BackEndController;
use App\Models\Category;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CategoryController extends BackEndController
{
    protected $model;
    public function __construct(Category $model)
    {
        parent::__construct($model);
    }
    public function index(Request $request)
    {
        //get all data of Model
        $rows = $this->model->when($request->search,function($query) use ($request){
            $query->whereTranslationLike('name','%' .$request->search. '%')
                  ->orWhereTranslationLike('description','%' .$request->search. '%');


        });
        $rows = $this->filter($rows,$request);
         $module_name_plural = $this->getClassNameFromModel();
         $module_name_singular = $this->getSingularModelName();
         // return $module_name_plural;
         return view('dashboard.' . $module_name_plural . '.index', compact('rows', 'module_name_singular', 'module_name_plural'));
    } //end of ind

    public function store(Request $request)
    {
        $request->validate([
            'ar.name'          => 'required|min:3|max:60|unique:category_translations,name|string',
            'en.name'          => 'required|min:3|max:60|unique:category_translations,name|string',
            'ar.description'   => 'nullable|min:3|max:600|string',
            'en.description'   => 'nullable|min:3|max:600|string',
            'image'            => 'required|mimes:jpg,jpeg,png,svg',
        ]);

        $request_data = $request->except(['_token','image']);
        if($request->hasFile('image'))
        {
            $request_data['image']=$this->uploadImage($request->file('image'),'categories');
        }
        Category::create($request_data);
        session()->flash('success', __('site.add_successfuly'));
        return redirect()->route('dashboard.'.$this->getClassNameFromModel().'.index');
    }


    public function update(Request $request, $id)
    {

        $category = $this->model->findOrFail($id);
        $request->validate([
            'ar.name'          => ['required', 'min:5', Rule::unique('category_translations','name')->ignore($category->id, 'category_id') ],
            'en.name'          => ['required', 'max:60','min:3', Rule::unique('category_translations','name')->ignore($category->id, 'category_id') ],
            'ar.description'   => 'nullable|min:3|max:600|string',
            'en.description'   => 'nullable|min:3|max:600|string',
            'image'            => 'nullable|mimes:jpg,jpeg,png,svg',
            ]);
        $request_data = $request->except(['_token','image']);
        if($request->hasFile('image')){
            if ($category->image != null) {
                Storage::disk('public_uploads')->delete('/categories/' . $category->image);
            }
            $request_data['image'] = $this->uploadImage($request->file('image'), 'categories');
        }
        $category->update($request_data);
        session()->flash('success', __('site.updated_successfuly'));
        return redirect()->route('dashboard.'.$this->getClassNameFromModel().'.index');
    }

    // public function destroy($id, Request $request)
    // {
    //     $category = $this->model->findOrFail($id);
    //     if ($category->image != null) {
    //         Storage::disk('public_uploads')->delete('/categories/' . $category->image);
    //     }
    //     $category->delete();
    //     session()->flash('success', __('site.deleted_successfuly'));
    //     return redirect()->route('dashboard.'.$this->getClassNameFromModel().'.index');
    // }
}
