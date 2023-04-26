<?php

namespace App\Http\Controllers\Dashboard;
use App\Models\Report;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
class ReportController extends BackEndController
{
    public function __construct(Report $model)
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
       $rows = $this->model->with(['video','client'])->when($request->date,function($q) use ($request){
            $q->whereDate('created_at',$request->date);
        })->when($request->status,function($q) use ($request){
            $q->where('status',$request->status);
        })->when($request->search,function($q) use ($request){
            $q->whereHas('client',function($q)use($request){
                $q->where('user_name','like','%'.$request->search.'%');
            })->whereHas('video',function($q)use($request){
                $q->where('title','like','%'.$request->search.'%');
            });
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
            'user_name'            => 'required|string|min:2|max:100',
            'email'                => 'required|email|max:255|unique:clients,email' ,
            'password'             => 'required|confirmed|min:6|max:255',
            'password_confirmation'=> "required|string|same:password",
            'country_id'           => 'required|exists:countries,id',
            'city_id'              => 'required|exists:cities,id',
            'rank_id'              => 'required|exists:ranks,id',
            'date_of_birth'        => 'nullable|date_format:Y-m-d|before:day',
            'cateories_ids'        => 'required|array',
            'cateories_ids.*'      => 'exists:categories,id',
            'image'                => 'nullable | mimes:jpg,jpeg,png,gif',
        ]);

        $request_data = $request->except(['_token', 'password', 'password_confirmation','cateories_ids']);
        $request_data['password'] = bcrypt($request->password);
        $request_data['cateories_ids'] = json_encode($request->cateories_ids);

        if ($request->hasFile('image')) {
            $request_data['image'] = $this->uploadImage($request->image, 'clients');
        }

        $this->model->create($request_data);

        session()->flash('success', __('site.add_successfuly'));
        return redirect()->route('dashboard.' . $this->getClassNameFromModel() . '.index');
    }


    public function update(Request $request, $id)
    {
        $client = $this->model->find($id);

        $request->validate([
            'user_name'            => 'required|string|min:2|max:100',
            'email'                => 'nullable|email|max:255|unique:clients,email,'.$id ,
            'password'             => 'nullable|confirmed|min:6|max:255',
            'password_confirmation'=> "nullable|string|same:password",
            'country_id'           => 'required|exists:countries,id',
            'city_id'              => 'required|exists:cities,id',
            'rank_id'              => 'required|exists:ranks,id',
            'date_of_birth'        => 'nullable|date_format:Y-m-d|before:day',
            'cateories_ids'        => 'nullable|array',
            'cateories_ids.*'      => 'exists:categories,id',
            'image'                => 'nullable | mimes:jpg,jpeg,png,gif,JPG',
        ]);

        $request_data = $request->except(['_token', 'password', 'password_confirmation']);
        if($request->password != null)
        {
            $request_data['password'] = bcrypt($request->password);
        }
        if($request->categories_ids != null)
        {
            $request_data['cateories_ids'] = json_encode($request->cateories_ids);
        }
        // $newuser = $this->model->create($request_data);
        if($request->hasFile('image')){
            if($client->image != null)
            {
                $oldImage = $client->image;
                if(file_exists(base_path('public/uploads/clients/') . $oldImage)){
                    unlink(base_path('public/uploads/clients/') . $oldImage);
                }
            }
            $request_data['image'] = $this->uploadImage($request->image, 'clients');
        }


        $client->update($request_data);
        // $user->syncRoles($request->role_id);

        session()->flash('success', __('site.updated_successfuly'));
        return redirect()->route('dashboard.' . $this->getClassNameFromModel() . '.index');
    }
    public function destroy($id, Request $request)
    {
        $report = $this->model->findOrFail($id);

        $report->delete();
        session()->flash('success', __('site.deleted_successfuly'));
        return redirect()->route('dashboard.'.$this->getClassNameFromModel().'.index');
    }
    public function reportStatus(Request $request)
    {
        $report=$this->model->find($request->id);
        $report->update([
            'status'=>$request->status,
        ]);
        session()->flash('success', __('site.updated_successfuly'));
        return redirect()->route('dashboard.' . $this->getClassNameFromModel() . '.index');
    }

}
