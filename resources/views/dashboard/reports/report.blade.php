<div   class='container'>
    <div class="modal fade" id="report{{ $row->id }}" >
        <div class="modal-dialog" role="dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">@lang('site.comments')</h4>
                </div>
                <div class="modal-body">
                    <div class="panel panel-info">
                        <div class="panel-heading">
                            {{$row->client->user_name ?? null}}
                        </div>
                        <div class="panel-body">
                            {{$row->text ?? null}}
                        </div>
                        <form action="{{route('dashboard.reportStatus')}}" method="POST" style="padding: 20px">
                            @csrf
                            <input type="hidden" name="id" value="{{$row->id}}">
                            <div class="form-group">
                                <select name="status" class='form-control' id="">
                                    <option value="">@lang('site.choose_status')</option>
                                    <option value="new" {{ $row->status=='new' ? 'selected' : ''}}>@lang('site.new')</option>
                                    <option value="confirmed" {{ $row->status=='confirmed' ? 'selected' : ''}}>@lang('site.confirmed')</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <button type="submit" class='btn btn-sm btn-primary'>@lang('site.edit')</button>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">@lang('site.Close')</button>
                </div>
            </div>
        </div>
    </div>
</div>
{{-- end modal --}}
