<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Client;
use Illuminate\Http\Request;
use App\Traits\NotificationTrait;
class NotificationController extends Controller
{
    use NotificationTrait;
    public function index(Request $request)
    {

        $module_name_plural   = 'notifications';
        $module_name_singular = 'notification';
        return view('dashboard.' . $module_name_plural . '.index', compact('module_name_singular', 'module_name_plural'));
    } //end of index



    public function store(Request $request)
    {
         $request->validate([
             'body_ar'          => 'required|min:1|max:3000',
             'body_en'          => 'required|min:1|max:3000',
         ]);
    
        if($request->client_id =='all' || $request->client_id > 0){
            $clients=Client::where('status','UnBlocked')->get();
            if($clients->count() <=0)
            {
               session()->flash('error', __('site.no_records'));
               return redirect()->route('dashboard.notifications.index');
            }
            if($request->client_id=='all')
            {
                foreach($clients as $client){
                    $this->sendNotification('مميك','Mimic',$request->body_ar,$request->body_en,null,$client->id,null,null,null,null,'admin');
                }
            }
            if($request->client_id > 0)
            {
                $this->sendNotification('مميك','Mimic',$request->body_ar,$request->body_en,null,$request->client_id,null,null,null,null,'admin');
            }
        }
        
       
        session()->flash('success', __('site.send_successfuly'));
        return redirect()->route('dashboard.notifications.index');
    }

}
