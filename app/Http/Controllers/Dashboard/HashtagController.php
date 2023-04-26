<?php

namespace App\Http\Controllers\Dashboard;

use App\Models\Hashtag;
use Illuminate\Http\Request;

class HashtagController extends BackEndController
{
    public function __construct(Hashtag $model)
    {
        parent::__construct($model);
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
       //get all data of Model
       $rows = $this->model->when($request->search,function($query) use ($request){
           $query->where('title','like','%' .$request->search . '%');

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
            'title'          => 'required|min:1|string',
            'category_id'=> 'required|exists:categories,id',

        ]);

        $request_data = $request->except(['_token']);

        $newuser = $this->model->create($request_data);

        session()->flash('success', __('site.add_successfuly'));
        return redirect()->route('dashboard.' . $this->getClassNameFromModel() . '.index');
    }


    public function update(Request $request, $id)
    {
        $hashtag = $this->model->find($id);

        $request->validate([
            'title'                  => 'required|min:1|string',
            'category_id'=> 'required|exists:categories,id',

        ]);

        $request_data = $request->except(['_token']);

        $hashtag->update($request_data);
        // $hashtag->syncRoles($request->role_id);

        session()->flash('success', __('site.updated_successfuly'));
        return redirect()->route('dashboard.' . $this->getClassNameFromModel() . '.index');
    }
    // public function destroy($id, Request $request)
    // {
    //     $hashtag = $this->model->findOrFail($id);

    //     $hashtag->delete();
    //     session()->flash('success', __('site.deleted_successfuly'));
    //     return redirect()->route('dashboard.'.$this->getClassNameFromModel().'.index');
    // }
}
