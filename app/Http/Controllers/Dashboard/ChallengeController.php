<?php

namespace App\Http\Controllers\Dashboard;
use App\Models\Challenge;
use App\Models\Reason;
use App\Models\Video;
use App\Models\Winer;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
class ChallengeController extends BackEndController
{
    public function __construct(Challenge $model)
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
       $rows = $this->model->with(['client','videos'=>function($q){
            $q->with(['client','reasons']);
        }])->when($request->from_date,function($q)use($request){
            $q->whereDate('created_at','>=',$request->from_date)->whereDate('created_at','<=',$request->to_date);
        })->when($request->date,function($q) use ($request){
            $q->whereDate('created_at',$request->date);
        })->when($request->status,function($q)use($request){
            $q->where('status',$request->status);
        })->when($request->search,function($q) use ($request){
            $q->where('title','like','%' .$request->search . '%');
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
            'title'                => 'required|string|min:2|max:100',
            'description'          => 'required|max:1000|min:1' ,
            'end_date'             => 'required|date_format:Y-m-d|after_or_equal:day',
            'status'               => ['required',Rule::in('pending','accept','reject','close')],
            'creater_id'           => 'required|exists:clients,id',
            'category_id'          => 'required|exists:categories,id',
            'hashtags'             => 'required|array',
            'hashtags.*'           => 'exists:hashtags,id',
            // 'hashtageNmae'         => 'required | array',
            'feature'              => ['required',Rule::in('0','1')],
        ]);

        $request_data = $request->except(['_token']);
        $request_data['hashtags'] = json_encode($request->hashtags);

        $this->model->create($request_data);

        session()->flash('success', __('site.add_successfuly'));
        return redirect()->route('dashboard.' . $this->getClassNameFromModel() . '.index');
    }


    public function update(Request $request, $id)
    {
        $hashtag = $this->model->find($id);

        $request->validate([
            'title'                => 'required|string|min:2|max:100',
            'description'          => 'required|max:1000|min:1' ,
            'end_date'             => 'required|date_format:Y-m-d|after_or_equal:day',
            'status'               => ['required',Rule::in('pending','accept','reject','close')],
            'creater_id'           => 'required|exists:clients,id',
            'category_id'          => 'required|exists:categories,id',
            'hashtags'             => 'required|array',
            'hashtags.*'           => 'exists:hashtags,id',
            // 'hashatgeNmae'         => 'required| array',
            'feature'              => ['required',Rule::in('0','1')],
        ]);

        $request_data = $request->except(['_token']);

        if($request->hashtags != null)
        {
            $request_data['hashtags'] = json_encode($request->hashtags);
        }


        $hashtag->update($request_data);

        session()->flash('success', __('site.updated_successfuly'));
        return redirect()->route('dashboard.' . $this->getClassNameFromModel() . '.index');
    }
    public function destroy($id, Request $request)
    {
        $challenge = $this->model->findOrFail($id);
        if($challenge->videos->count() > 0)
        {
            foreach($challenge->videos as $video)
            {
                foreach(json_decode($video->videos) as $vid)
                {
                    if(file_exists(base_path('public/videos/') . $vid)){
                        unlink(base_path('public/videos/') . $vid);
                    }
                }
                $video->likes()->delete();
                $video->comments()->delete();
                $video->watchs()->delete();
                $video->reasons()->delete();
                $video->stories()->delete();

                $video->delete();
            }
        }
        $challenge->delete();
        session()->flash('success', __('site.deleted_successfuly'));
        return redirect()->route('dashboard.'.$this->getClassNameFromModel().'.index');
    }
    public function challenge($id){
        $rows = $this->model->where('id',$id)->paginate(10);
       $module_name_plural = $this->getClassNameFromModel();
       $module_name_singular = $this->getSingularModelName();
       // return $module_name_plural;
       return view('dashboard.' . $module_name_plural . '.index', compact('rows', 'module_name_singular', 'module_name_plural'));
   }
   public function changeChallengeStatus(Request $request)
   {
           $challenge=Challenge::findOrFail($request->id);
           $video=Video::where('client_id',$challenge->creater_id)->where('challenge_id',$challenge->id)->first();
           $challenge->update([
               'status'=>$request->status,
               'end_date'=>$request->end_date,
           ]);
           $challenge->update([
                'status'=>$request->status,
            ]);
           if($request->reason != null){
                Reason::create([
                    'video_id'   =>$video->id,
                    'reason'     =>$request->reason,
                    'type'       =>'admin',
                ]);
           }
            return redirect()->route('dashboard.'.$this->getClassNameFromModel().'.index');
    }
    public function Mychallenges($id){
        $rows = $this->model->where('creater_id',$id)->paginate(10);
       $module_name_plural = $this->getClassNameFromModel();
       $module_name_singular = $this->getSingularModelName();
       // return $module_name_plural;
       return view('dashboard.' . $module_name_plural . '.index', compact('rows', 'module_name_singular', 'module_name_plural'));
   }
   public function MyWinnerChallenges($id){
        $rows = $this->model->whereIn('id',Winer::where('client_id',$id)->pluck('challenge_id')->toArray())->paginate(10);
        $module_name_plural = $this->getClassNameFromModel();
        $module_name_singular = $this->getSingularModelName();
        // return $module_name_plural;
        return view('dashboard.' . $module_name_plural . '.index', compact('rows', 'module_name_singular', 'module_name_plural'));
    }
}

