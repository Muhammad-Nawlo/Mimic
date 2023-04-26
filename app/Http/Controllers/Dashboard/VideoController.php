<?php

namespace App\Http\Controllers\Dashboard;
use App\Models\Challenge;
use App\Models\Comment;
use App\Models\Reason;
use App\Models\Replay;
use App\Models\Video;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
class VideoController extends BackEndController
{
    public function __construct(Video $model)
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
       $rows = $this->model->with(['client','challenge','comments'=>function($q){
           $q->with(['likes','client','replies'=>function($q){
               $q->with(['likes','client']);
           }]);
       },'reasons'])
       ->when($request->from_date,function($q)use($request){
            $q->whereDate('created_at','>=',$request->from_date)->whereDate('created_at','<=',$request->to_date);
        })
       ->when($request->date,function($q) use ($request){
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
            'status'               => ['required',Rule::in('pending','accept','reject')],
            'client_id'            => 'required|exists:clients,id',
            'category_id'          => 'required|exists:categories,id',
            'challenge_id'         => 'required|exists:challenges,id',
            'featur_video'         => ['required',Rule::in('0','1')],
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
            'title'                => 'required|string|min:2|max:100',
            'status'               => ['required',Rule::in('pending','accept','reject')],
            'client_id'            => 'required|exists:clients,id',
            'category_id'          => 'required|exists:categories,id',
            'challenge_id'         => 'required|exists:challenges,id',
            'featur_video'         => ['required',Rule::in('0','1')],
        ]);

        $request_data = $request->except(['_token']);

        $video->update($request_data);

        session()->flash('success', __('site.updated_successfuly'));
        return redirect()->route('dashboard.' . $this->getClassNameFromModel() . '.index');
    }
    public function destroy($id, Request $request)
    {
        $video = $this->model->findOrFail($id);
        if($video->videos != null)
        {
            foreach(json_decode($video->videos) as $vid)
            {
                if(file_exists(base_path('public/videos/') . $vid)){
                    unlink(base_path('public/videos/') . $vid);
                }
            }
        }

        $video->likes()->delete();
        $video->comments()->delete();
        $video->watchs()->delete();
        $video->reasons()->delete();
        $video->reports()->delete();

        $video->delete();
        session()->flash('success', __('site.deleted_successfuly'));
        return redirect()->route('dashboard.'.$this->getClassNameFromModel().'.index');
    }
   public function changeVideoeStatus(Request $request)
   {
           $video=Video::findOrFail($request->id);
           $video->update([
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
    public function delet_comment($id)
    {
        $comment=Comment::find($id);
        $comment->replies()->delete();
        $comment->delete();
        session()->flash('success', __('site.deleted_successfuly'));
        return redirect()->route('dashboard.'.$this->getClassNameFromModel().'.index');
    }
    public function delet_replay($id)
    {
        $replay=Replay::find($id);
        $replay->delete();
        session()->flash('success', __('site.deleted_successfuly'));
        return redirect()->route('dashboard.'.$this->getClassNameFromModel().'.index');
    }
    public function video($id)
    {
        $rows=$this->model->where('id',$id)->paginate(10);
        $module_name_plural = $this->getClassNameFromModel();
        $module_name_singular = $this->getSingularModelName();
        // return $module_name_plural;
        return view('dashboard.' . $module_name_plural . '.index', compact('rows', 'module_name_singular', 'module_name_plural'));
   }
   public function challengVideos($id)
   {
       $rows=$this->model->where('challenge_id',$id)->paginate(10);
       $module_name_plural = $this->getClassNameFromModel();
       $module_name_singular = $this->getSingularModelName();
       // return $module_name_plural;
       return view('dashboard.' . $module_name_plural . '.index', compact('rows', 'module_name_singular', 'module_name_plural'));
  }
  public function clientVideos($id)
  {
      $rows=$this->model->where('client_id',$id)->paginate(10);
      $module_name_plural = $this->getClassNameFromModel();
      $module_name_singular = $this->getSingularModelName();
      // return $module_name_plural;
      return view('dashboard.' . $module_name_plural . '.index', compact('rows', 'module_name_singular', 'module_name_plural'));
 }
}

