{{ csrf_field() }}

<div class="col-md-6">
    <div class="form-group">
        <label>@lang('site.title')</label>
        <input type="text" class="form-control  @error('title') is-invalid @enderror" name="title"
            value="{{ isset($row) ? $row->title : old('title') }}">
        @error('title')
            <small class=" text text-danger" role="alert">
                <strong>{{ $message }}</strong>
            </small>
        @enderror
    </div>
</div>

<div class="col-md-6">
    <div class="form-group">
        <label>@lang('site.creater_id')</label>
        <select name="creater_id"  class='form-control'>
            <option value="">@lang('site.choose_creater_id')</option>
            @foreach(\App\Models\Client::all() as $cli)
                <option value="{{$cli->id}}" @if((isset($row) && $row->creater_id==$cli->id) || ($cli->id==old('creater_id'))) selected  @endif>{{$cli->user_name}}</option>
            @endforeach
        </select>
        @error('creater_id')
            <small class=" text text-danger" role="alert">
                <strong>{{ $message }}</strong>
            </small>
        @enderror
    </div>
</div>
<div class="col-md-6">
    <div class="form-group">
        <label>@lang('site.challenge_id')</label>
        <select name="challenge_id"  class='form-control'>
            <option value="">@lang('site.choose_challenge_id')</option>
            @foreach(\App\Models\Challenge::all() as $challenge)
                <option value="{{$challenge->id}}" @if((isset($row) && $row->challenge_id==$challenge->id) || ($challenge->id==old('challenge_id'))) selected  @endif>{{$challenge->title}}</option>
            @endforeach
        </select>
        @error('challenge_id')
            <small class=" text text-danger" role="alert">
                <strong>{{ $message }}</strong>
            </small>
        @enderror
    </div>
</div>
<div class="col-md-6">
    <div class="form-group">
        <label>@lang('site.category_id')</label>
        <select name="category_id"  class='form-control'>
            <option value="">@lang('site.choose_category_id')</option>
            @foreach(\App\Models\Category::all() as $category)
                <option value="{{$category->id}}" @if((isset($row) && $category->id==$row->category_id)||($category->id==old('category_id'))) selected  @endif>{{$category->name}}</option>
            @endforeach
        </select>
        @error('category_id')
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
            <option value="">@lang('site.Choose_status')</option>
            <option value="pending" @if((isset($row) && $row->status =='pending')||old('status')=='pending') selected  @endif>@lang('site.pending')</option>
            <option value="accept" @if((isset($row) && $row->status =='accept')||old('status')=='accept') selected  @endif>@lang('site.accept')</option>
            <option value="reject" @if((isset($row) && $row->status =='reject')||old('status')=='reject') selected  @endif>@lang('site.reject')</option>
        </select>
    </div>
</div>
<div class="col-md-6">
    <div class="form-group">
        <label>@lang('site.feature')</label>
        <select name="featur_video" class='form-control'>
            <option value="">@lang('site.Choose featur_video type')</option>
            <option value="0" @if((isset($row) && $row->featur_video =='0')||old('featur_video')=='0') selected  @endif>@lang('site.false')</option>
            <option value="1" @if((isset($row) && $row->featur_video =='1')||old('featur_video')=='1') selected  @endif>@lang('site.true')</option>
        </select>
    </div>
</div>




