   
<div   class='container'>
    <div class="modal fade" id="status{{ $row->id }}" >
        <div class="modal-dialog" role="dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">{{$row->user_name}}</h4>
                </div>
                <div class="modal-body">
                    <form action="{{route('dashboard.changeStoryStatus')}}" method="POST">
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
                            <button type="submit" class="btn btn-success" style="margin-left:45%">Send</button>
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