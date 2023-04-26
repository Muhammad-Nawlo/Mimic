   
<div   class='container'>
    <div class="modal fade" id="status{{ $row->id }}" >
        <div class="modal-dialog" role="dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">{{$row->user_name}}</h4>
                </div>
                <div class="modal-body">
                    <form action="{{route('dashboard.changeStatus')}}" method="POST">
                        @csrf
                        <input type="hidden" name="type" value="{{$module_name_plural}}">
                        <input type="hidden" name="id" value="{{$row->id}}">
                        <div class="form-group">
                            <select name="status" class='form-control' id="">
                                <option value="">@lang('site.choose_status')</option>
                                <option value="Pending" {{ $row->status=='Pending' ? 'selected' : ''}}>@lang('site.Pending')</option>
                                <option value="Blocked" {{ $row->status=='Blocked' ? 'selected' : ''}}>@lang('site.Blocked')</option>
                                <option value="UnBlocked" {{ $row->status=='UnBlocked' ? 'selected' : ''}}>@lang('site.UnBlocked')</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <textarea name="reason"  class="form-control">{{$row->reason}}</textarea>
                        </div>
                        <div class="form-group">
                            <button type="submit" class='btn btn-sm btn-primary'>@lang('site.edit')</button>
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