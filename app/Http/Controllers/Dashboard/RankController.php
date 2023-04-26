<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Dashboard\BackEndController;
use App\Models\Rank;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class RankController extends BackEndController
{
    protected $model;
    public function __construct(Rank $model)
    {
        parent::__construct($model);
    }
    public function index(Request $request)
    {
        //get all data of Model
        $rows = $this->model->when($request->search,function($query) use ($request){
            $query->whereTranslationLike('title','%' .$request->search. '%');
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
            'ar.title'          => 'required|min:1|max:60|unique:rank_translations,title|string',
            'en.title'          => 'required|min:1|max:60|unique:rank_translations,title|string',
            'challenge_num'     => 'required|numeric',
            'like_num'          => 'required|numeric',
            'video_num'         => 'required|numeric',
            'invit_num'         => 'required|numeric',
            'image'             => 'required|mimes:jpg,jpeg,png,svg',
        ]);

        $request_data = $request->except(['_token','image']);
        if($request->hasFile('image'))
        {
            $request_data['image']=$this->uploadImage($request->file('image'),'ranks');
        }
        Rank::create($request_data);
        session()->flash('success', __('site.add_successfuly'));
        return redirect()->route('dashboard.'.$this->getClassNameFromModel().'.index');
    }


    public function update(Request $request, $id)
    {

        $rank = $this->model->findOrFail($id);
        $request->validate([
            'ar.title'          => ['required', 'min:1', Rule::unique('rank_translations','title')->ignore($rank->id, 'rank_id') ],
            'en.title'          => ['required', 'max:60','min:3', Rule::unique('rank_translations','title')->ignore($rank->id, 'rank_id') ],
            'image'            => 'nullable|mimes:jpg,jpeg,png,svg',
            'challenge_num'     => 'required|numeric',
            'like_num'          => 'required|numeric',
            'video_num'         => 'required|numeric',
            'invit_num'         => 'required|numeric',
            ]);
        $request_data = $request->except(['_token','image']);
        if($request->hasFile('image')){
            if ($rank->image != null) {
                Storage::disk('public_uploads')->delete('/ranks/' . $rank->image);
            }
            $request_data['image'] = $this->uploadImage($request->file('image'), 'ranks');
        }
        $rank->update($request_data);
        session()->flash('success', __('site.updated_successfuly'));
        return redirect()->route('dashboard.'.$this->getClassNameFromModel().'.index');
    }

    public function destroy($id, Request $request)
    {
        $rank = $this->model->findOrFail($id);
        if($rank->clients()->count() > 0)
        {
            session()->flash('error', __('site.there are clients in this Rank'));
            return redirect()->route('dashboard.'.$this->getClassNameFromModel().'.index');
        }
        if ($rank->image != null) {
            Storage::disk('public_uploads')->delete('/ranks/' . $rank->image);
        }
        $rank->delete();
        session()->flash('success', __('site.deleted_successfuly'));
        return redirect()->route('dashboard.'.$this->getClassNameFromModel().'.index');
    }
}
