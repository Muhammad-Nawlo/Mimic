<div   class='container'>
    <div class="modal fade" id="comment{{ $row->id }}" >
        <div class="modal-dialog" role="dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">@lang('site.comments')</h4>
                </div>
                <div class="modal-body">
                    <ul class="list-group">
                    @if ($row->comments->count() > 0)
                        @foreach ($row->comments as $comment)
                            <li class="list-group-item">
                                <div class="panel panel-primary">
                                    <div class="panel-heading">
                                        <div class="row">
                                            <div class="col-md-10">
                                                <a href="{{route('dashboard.client',$comment->client_id)}}">
                                                    <img src="{{ isset($comment->client->image_path) ?  $comment->client->image_path : '' }}" style="border-radius:50%; margin:7px ;" title="the title" width='20' height='20'>
                                                    <span style="color: #fff"> {{ isset($comment->client->user_name) ? $comment->client->user_name :  __('site.user_not_found') }} </figcaption>
                                                </a>        
                                            </div>
                                            <div class="col-md-2">
                                                <span>
                                                    <a href="{{route('dashboard.delet_comment', $comment->id)}}"  style="color:#fff!important;"  class="btn btn-danger  btn-xs">
                                                        <i class="fa fa-1x fa-trash"> @lang('site.delete')</i>
                                                    </a>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="panel-body">
                                        <p>{{ $comment->body}}</p>
                                        <span>{{ $comment->likes()->count() ?? 0 }}</span>
                                        <i class="fa fa-thumbs-o-up"></i>
                                    </div>
                                </div>
                                {{-- start replies --}}
                                @if($comment->replies()->count() > 0)
                                    <ul class="list-group">
                                        @if ($comment->replies()->count() > 0)
                                            @foreach ($comment->replies as $replay)
                                                <li class="list-group-item">
                                                    <div class="panel panel-info">
                                                        <div class="panel-heading">
                                                            <div class="row">
                                                                <div class="col-md-10">
                                                                    <a href="{{route('dashboard.client',$replay->client_id)}}">
                                                                        <img src="{{ isset($replay->client->image_path) ?  $replay->client->image_path : '' }}" style="border-radius:50%; margin:7px ;" title="the title" width='20' height='20'>
                                                                        <span  style="color:blue"> {{ isset($replay->client->user_name) ? $replay->client->user_name :  __('site.user_not_found') }} </figcaption>
                                                                    </a>
                                                                </div>
                                                                <div class="col-md-2">
                                                                    <span>
                                                                        <a href="{{route('dashboard.delet_replay', $replay->id)}}"  style="color:#fff!important;"  class="btn btn-danger  btn-xs">
                                                                            <i class="fa fa-1x fa-trash"> @lang('site.delete')</i>
                                                                        </a>
                                                                    </span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="panel-body">
                                                            <p>{{ $replay->body}}</p>
                                                            <span>{{ $replay->likes()->count() ?? 0 }}</span>
                                                            <i class="fa fa-thumbs-o-up"></i>
                                                        </div>
                                                    </div>
                                                </li>
                                            @endforeach
                                        @else
                                        <li class="list-group-item">@lang('site.no replies') </li>
                                        @endif
                                    </ul>
                                @endif
                                {{-- end replies --}}
                            </li>

                        @endforeach
                    @else
                    <li class="list-group-item">@lang('site.no comments') </li>
                    @endif
                    </ul>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">@lang('site.Close')</button>
                </div>
            </div>
        </div>
    </div>
</div>
{{-- end modal --}}
