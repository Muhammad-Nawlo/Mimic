<?php

namespace App\Http\Controllers\Dashboard;
use App\Models\Story;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
class StoryController extends BackEndController
{
    public function __construct(Story $model)
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
        if($request->from_date != null && $request->to_date == null){
                $request['to_date']=date('Y-m-d');
            }

        if ($request->from_date > $request->to_date) {
            $swap = $request->to_date;
            $request['to_date'] = $request->from_date;
            $request['from_date'] = $swap;
        }
       //get all data of Model
       $rows = $this->model->with('client')->when($request->status,function($q)use($request){
            $q->where('status',$request->status);
        })->when($request->from_date,function($q)use($request){
            $q->whereDate('created_at','>=',$request->from_date)->whereDate('created_at','<=',$request->to_date);
        })
        ->when($request->date,function($q) use ($request){
            $q->whereDate('created_at',$request->date);
        })->when($request->search,function($q) use ($request){
            $q->whereHas('client',function($q)use($request){
                $q->where('user_name','like','%' .$request->search . '%');
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
            'status'               => ['required',Rule::in('pending','accept','reject')],
            'client_id'            => 'required|exists:clients,id',
        ]);

        $request_data = $request->except(['_token']);
        $request_data['hashtags'] = json_encode($request->hashtags);

        $this->model->create($request_data);

        session()->flash('success', __('site.add_successfuly'));
        return redirect()->route('dashboard.' . $this->getClassNameFromModel() . '.index');
    }


    public function update(Request $request, $id)
    {
        $video = $this->model->find($id);

        $request->validate([
            'status'               => ['required',Rule::in('pending','accept','reject')],
            'client_id'            => 'required|exists:clients,id',
        ]);

        $request_data = $request->except(['_token']);

        $video->update($request_data);

        session()->flash('success', __('site.updated_successfuly'));
        return redirect()->route('dashboard.' . $this->getClassNameFromModel() . '.index');
    }
    public function destroy($id, Request $request)
    {
        $story = $this->model->findOrFail($id);
        if($story->videos != null)
        {
            foreach(json_decode($story->videos) as $vid)
            {
                if(file_exists(base_path('public/stories/') . $vid)){
                    unlink(base_path('public/stories/') . $vid);
                }
            }
        }
        $story->reacts()->delete();
        $story->watchs()->delete();
        $story->delete();
        session()->flash('success', __('site.deleted_successfuly'));
        return redirect()->route('dashboard.'.$this->getClassNameFromModel().'.index');
    }
   public function changeStoryStatus(Request $request)
   {
           $story=Story::findOrFail($request->id);
           $story->update([
                'status'=>$request->status,
            ]);
            return redirect()->route('dashboard.'.$this->getClassNameFromModel().'.index');
    }
   }

