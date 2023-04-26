   
<div   class='container'>
    <div class="modal fade" id="status{{ $row->id }}" >
        <div class="modal-dialog" role="dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">{{$row->user_name}}</h4>
                </div>
                <div class="modal-body">
                    <form action="{{route('dashboard.changeChallengeStatus')}}" method="POST">
                        @csrf
                        <input type="hidden" name="id" value="{{$row->id}}">
                        <div class="form-group">
                            <select name="status" class='form-control' id="">
                                <option value="">@lang('site.choose_status')</option>
                                <option value="pending" {{ $row->status=='pending' ? 'selected' : ''}}>@lang('site.pending')</option>
                                <option value="accept" {{ $row->status=='accept' ? 'selected' : ''}}>@lang('site.accept')</option>
                                <option value="reject" {{ $row->status=='reject' ? 'selected' : ''}}>@lang('site.reject')</option>
                                <option value="close" {{ $row->status=='close' ? 'selected' : ''}}>@lang('site.close')</option>
                            </select>
                        </div>
                        <div class="form-group">
                           <input type="date" name="end_date" value="{{$row->end_date}}" class="form-control">
                        </div>
                        <div class="form-group">
                            @php $video=\App\Models\Video::where('client_id',$row->creater_id)->where('challenge_id',$row->id)->first(); @endphp
                            <div class="card direct-chat direct-chat-primary">
                                <div class="card-body" style="display: block;">
                                
                                    <div class="direct-chat-messages">
                                        @if(!empty($video) && $video->reasons->count()>0)
                                            @foreach($video->reasons as $reason)
                                                @if($reason->type=='client')
                                                    <div class="direct-chat-msg">
                                                        <a href="{{route('dashboard.client',$row->creater_id)}}">
                                                            <img class="direct-chat-img" src="{{$video->client->image_path}}" alt="message user image">
                                                        </a>    
                                                        <div class="direct-chat-text">
                                                            {{$reason->reason}}
                                                        </div>
                                                    </div>
                                                @else
                                                    <div class="direct-chat-msg right">
                                                        <img class="direct-chat-img" src="{{auth()->user()->image_path}}" alt="message user image">
                                                        <div class="direct-chat-text">
                                                            {{$reason->reason}}
                                                        </div>
                                                    </div>
                                                @endif    
                                            @endforeach
                                        @endif
                                        <div class="card-footer" style="display: block;">
                                            <form action="#" method="post">
                                                <div class="form-group">
                                                    <input type="text" name="reason" placeholder="Type Message ..." class="form-control" style="margin-bottom: 10px">
                                                    <button type="submit" class="btn btn-success" style="margin-left:45%">Send</button>
                                                    </span>
                                                </div>
                                            </form>
                                        </div>
                                    
                                    </div>
                                </div>    
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">@lang('site.Close')</button>
                </div>
            </div>
        </div>
    </div>
</div>
{{-- end modal --}}