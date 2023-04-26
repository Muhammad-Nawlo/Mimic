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
        <label>@lang('site.description')</label>
        <textarea class="form-control  @error('description') is-invalid @enderror" name="description">{{ isset($row) ? $row->description : old('description') }}</textarea>
        @error('description')
            <small class=" text text-danger" role="alert">
                <strong>{{ $message }}</strong>
            </small>
        @enderror
    </div>
</div>
<div class="col-md-6">
    <div class="form-group">
        <label>@lang('site.end_date')</label>
        <input type="date" class="form-control  @error('end_date') is-invalid @enderror" name="end_date"
            value="{{ isset($row) ? $row->end_date : old('end_date') }}">
        @error('end_date')
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
        @error('country_id')
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
        <label>@lang('site.hashtags')</label>
        <select name="hashtags[]"  class='form-control' multiple>
            <option value="">@lang('site.choose_hashtags')</option>
            @foreach(\App\Models\Hashtag::all() as $hashtag)
                <option value="{{$hashtag->id}}" @if((isset($row) && in_array($hashtag->id,json_decode($row->hashtags)))) selected  @endif>{{$hashtag->title}}</option>
            @endforeach
        </select>
        @error('hashtags')
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
            <option value="close" @if((isset($row) && $row->status =='close')||old('status')=='close') selected  @endif>@lang('site.close')</option>
        </select>
    </div>
</div>
<div class="col-md-6">
    <div class="form-group">
        <label>@lang('site.feature')</label>
        <select name="feature" class='form-control'>
            <option value="">@lang('site.Choose feature type')</option>
            <option value="0" @if((isset($row) && $row->feature =='0')||old('feature')=='0') selected  @endif>@lang('site.false')</option>
            <option value="1" @if((isset($row) && $row->feature =='1')||old('feature')=='1') selected  @endif>@lang('site.true')</option>
        </select>
    </div>
</div>




