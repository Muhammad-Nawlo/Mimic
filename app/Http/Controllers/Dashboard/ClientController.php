<?php

namespace App\Http\Controllers\Dashboard;
use App\Models\Client;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
class ClientController extends BackEndController
{
    public function __construct(Client $model)
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
       $rows = $this->model->with(['city','country','rank'])->when($request->country_id,function($q)use($request){
            $q->where('country_id',$request->country_id);
        })->when($request->city_id,function($q)use($request){
            $q->where('city_id',$request->city_id);
        })->when($request->search,function($q) use ($request){
            $q->where('user_name','like','%' .$request->search . '%')
                  ->orWhere('email', 'like','%' . $request->search . '%')
                  ->orWhere('phone', 'like','%' . $request->search . '%');

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
        $client = $this->model->findOrFail($id);
        if($client->challenges()->count())
        {
            session()->flash('error', __('site.Please Delete The Client Challenge First'));
            return redirect()->route('dashboard.'.$this->getClassNameFromModel().'.index');
        }

        if($client->image != null)
        {
            if(file_exists(base_path('public/uploads/clients/') . $client->image)){
                unlink(base_path('public/uploads/clients/') . $client->image);
            }
        }

        $client->likes()->delete();
        $client->comments()->delete();
        $client->watchs()->delete();
        $client->reasons()->delete();
        $client->reports()->delete();

        $client->delete();
        session()->flash('success', __('site.deleted_successfuly'));
        return redirect()->route('dashboard.'.$this->getClassNameFromModel().'.index');
    }
    public function client($id){
        $rows = $this->model->where('id',$id)->paginate(10);
       $module_name_plural = $this->getClassNameFromModel();
       $module_name_singular = $this->getSingularModelName();
       // return $module_name_plural;
       return view('dashboard.' . $module_name_plural . '.index', compact('rows', 'module_name_singular', 'module_name_plural'));
   }
   public function changeStatus(Request $request)
   {
       $who=null;
       $rwos=null;
       $module_name_plural=null;
       $module_name_singular=null;
       if($request->type=='clients'){
            $who=Client::find($request->id);
            $rows=new Client();
            $module_name_plural = 'clients';
            $module_name_singular = 'client';
       }
    //    if($request->type=='companies'){
    //         $who=Company::find($request->id);
    //         $rows=new Company();

    //         $module_name_plural = 'companies';
    //         $module_name_singular = 'company';
    //     }
    //     if($request->type=='units'){
    //         $who=Unit::find($request->id);
    //         $rows=new Unit();

    //         $module_name_plural = 'units';
    //         $module_name_singular = 'unit';
    //     }
    //     if($request->type=='projects'){
    //         $who=Project::find($request->id);
    //         $rows=new Project();

    //         $module_name_plural = 'projects';
    //         $module_name_singular = 'project';
    //     }
    //     if($request->type=='banners'){
    //         $who=Banner::find($request->id);
    //         $rows=new Banner();

    //         $module_name_plural = 'banners';
    //         $module_name_singular = 'banner';
    //     }
       if(!empty($who)){
           $who->update([
               'status'=>$request->status,
               'reason'=>$request->reason,
           ]);
           return redirect()->route('dashboard.' . $module_name_plural . '.index');
       }
   }
}
