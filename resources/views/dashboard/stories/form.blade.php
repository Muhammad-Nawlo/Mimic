{{ csrf_field() }}

<div class="col-md-6">
    <div class="form-group">
        <label>@lang('site.client_id')</label>
        <select name="client_id"  class='form-control'>
            <option value="">@lang('site.choose_client_id')</option>
            @foreach(\App\Models\Client::all() as $clie)
                <option value="{{$clie->id}}" @if((isset($row) && $clie->id==$row->client_id)||($clie->id==old('client_id'))) selected  @endif>{{$clie->user_name}}</option>
            @endforeach
        </select>
        @error('client_id')
            <small class=" text text-danger" role="alert">
                <strong>{{ $message }}</strong>
            </small>
        @enderror
    </div>
</div>
<div class="col-md-6">
    <div class="form-group">
        <label>@lang('site.status')</label>
        <select name="status" class='form-control'>
            <option value="">@lang('site.choose_status')</option>
            <option value="pending" @if((isset($row) && $row->status =='pending')||old('status')=='pending') selected  @endif>@lang('site.pending')</option>
            <option value="accept" @if((isset($row) && $row->status =='accept')||old('status')=='accept') selected  @endif>@lang('site.accept')</option>
            <option value="reject" @if((isset($row) && $row->status =='reject')||old('status')=='reject') selected  @endif>@lang('site.reject')</option>
        </select>
    </div>
</div>




