{{ csrf_field() }}

<div class="col-md-6">
    <div class="form-group">
        <label>@lang('site.user_name')</label>
        <input type="text" class="form-control  @error('user_name') is-invalid @enderror" name="user_name"
            value="{{ isset($row) ? $row->user_name : old('user_name') }}">
        @error('user_name')
            <small class=" text text-danger" role="alert">
                <strong>{{ $message }}</strong>
            </small>
        @enderror
    </div>
</div>

<div class="col-md-6">
    <div class="form-group">
        <label>@lang('site.email')</label>
        <input type="email" class="form-control  @error('email') is-invalid @enderror" name="email"
            value="{{ isset($row) ? $row->email : old('email') }}">
        @error('email')
            <small class=" text text-danger" role="alert">
                <strong>{{ $message }}</strong>
            </small>
        @enderror
    </div>
</div>


<div class="col-md-6">
    <div class="form-group">
        <label>@lang('site.password')</label>
        <input @if(isset($row))  @else required  @endif type="password" class="form-control  @error('password') is-invalid @enderror" name="password" value="">
        @error('password')
            <small class=" text text-danger" role="alert">
                <strong>{{ $message }}</strong>
            </small>
        @enderror
    </div>
</div>

<div class="col-md-6">
    <div class="form-group">
        <label>@lang('site.password_confirmation')</label>
        <input @if(isset($row))  @else required  @endif type="password" class="form-control  @error('password_confirmation') is-invalid @enderror"
            name="password_confirmation" value="">
        @error('password_confirmation')
            <small class=" text text-danger" role="alert">
                <strong>{{ $message }}</strong>
            </small>
        @enderror
    </div>
</div>


<div class="col-md-6">
    <div class="form-group">
        <label>@lang('site.date_of_birth')</label>
        <input type="date" class="form-control  @error('date_of_birth') is-invalid @enderror" name="date_of_birth"
            value="{{ isset($row) ? $row->date_of_birth : old('date_of_birth') }}">
        @error('date_of_birth')
            <small class=" text text-danger" role="alert">
                <strong>{{ $message }}</strong>
            </small>
        @enderror
    </div>
</div>

<div class="col-md-6">
    <div class="form-group">
        <label>@lang('site.country_id')</label>
        <select name="country_id"  class='form-control'>
            <option value="">@lang('site.choose_country_id')</option>
            @foreach(\App\Models\Country::all() as $cou)
                <option value="{{$cou->id}}" @if((isset($row) && $row->country_id==$cou->id) || ($cou->id==old('country_id'))) selected  @endif>{{$cou->name}}</option>
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
        <label>@lang('site.city_id')</label>
        <select name="city_id"  class='form-control'>
            <option value="">@lang('site.choose_city')</option>
          @if(isset($row))
                @foreach(\App\Models\City::where('country_id',$row->country_id)->get() as $ct)
                    <option value="{{$ct->id}}" @if(isset($row) && $row->city_id==$ct->id) selected  @endif>{{$ct->name}}</option>
                @endforeach
            @endif
        </select>
        @error('city_id')
            <small class=" text text-danger" role="alert">
                <strong>{{ $message }}</strong>
            </small>
        @enderror
    </div>
</div>
<div class="col-md-6">
    <div class="form-group">
        <label>@lang('site.rank_id')</label>
        <select name="rank_id"  class='form-control'>
            <option value="">@lang('site.choose_rank_id')</option>
            @foreach(\App\Models\Rank::all() as $rank)
                <option value="{{$rank->id}}" @if((isset($row) && $row->rank_id==$rank->id) ||($rank->rank_id==old('rank_id'))) selected  @endif>{{$rank->title}}</option>
            @endforeach
        </select>
        @error('rank_id')
            <small class=" text text-danger" role="alert">
                <strong>{{ $message }}</strong>
            </small>
        @enderror
    </div>
</div>
<div class="col-md-12">
    <div class="form-group">
        <label>@lang('site.status')</label>
        <select name="status" class='form-control'>
            <option value="">@lang('site.Choose status')</option>
            <option value="UnBlocked" @if((isset($row) && $row->status =='UnBlocked')||old('status')=='UnBlocked') selected  @endif>@lang('site.UnBlocked')</option>
            <option value="Blocked" @if((isset($row) && $row->status =='Blocked')||old('status')=='Blocked') selected  @endif>@lang('site.Blocked')</option>
        </select>
    </div>
</div>
<div class="col-md-6">
    <div class="form-group">
        <label>@lang('site.cateories_ids')</label>
        <select name="cateories_ids[]"  class='form-control' multiple>
            <option value="">@lang('site.choose_cateories_ids')</option>
            @foreach(\App\Models\Category::all() as $category)
                <option value="{{$category->id}}" @if((isset($row) && in_array($category->id,json_decode($row->cateories_ids) ?? []))) selected  @endif>{{$category->name}}</option>
            @endforeach
        </select>
        @error('cateories_ids')
            <small class=" text text-danger" role="alert">
                <strong>{{ $message }}</strong>
            </small>
        @enderror
    </div>
</div>

<div class="col-md-6">
    <div class="form-group">
        <label>@lang('site.verified_email')</label>
        <select name="verified_email" class='form-control'>
            <option value="">@lang('site.Choose verified type')</option>
            <option value="0" @if((isset($row) && $row->verified_email =='0')||old('verified_email')=='0') selected  @endif>@lang('site.false')</option>
            <option value="1" @if((isset($row) && $row->verified_email =='1')||old('verified_email')=='1') selected  @endif>@lang('site.true')</option>
        </select>
    </div>
</div>
<div class="col-md-3">
    <div class="form-group">
        <label>@lang('site.image')</label>
        <input type="file" name="image" class="form-control image @error('image') is-invalid @enderror">
        @error('image')
            <small class=" text text-danger" role="alert">
                <strong>{{ $message }}</strong>
            </small>
        @enderror
    </div>
</div>

<div class="form-group col-md-3">
    <img src="{{ isset($row) ? $row->image_path : asset('uploads/clients/default.png') }}" style="width: 115px;height: 80px;position: relative;
                    top: 14px;" class="img-thumbnail image-preview">
</div>




