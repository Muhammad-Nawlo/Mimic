{{ csrf_field() }}

@foreach (config('translatable.locales') as $index => $locale)
    <div class="col-md-6">
        <div class="form-group">
            <label>@lang('site.' . $locale . '.title')</label>
            <input type="text" class="form-control @error($locale . ' .title') is-invalid
        @enderror " name=" {{ $locale }}[title]"
                value="{{ isset($row) ? $row->translate($locale)->title : old($locale . '.title') }}">

            @error($locale . '.title')
                <small class=" text text-danger" role="alert">
                    <strong>{{ $message }}</strong>
                </small>
            @enderror
        </div>
    </div>
@endforeach

<div class="col-md-6">
    <div class="form-group">
        <label>@lang('site.challenge_num')</label>
        <input type="number" class="form-control  @error('challenge_num') is-invalid @enderror" name="challenge_num"
            value="{{ isset($row) ? $row->challenge_num : old('challenge_num') }}">
        @error('challenge_num')
            <small class=" text text-danger" role="alert">
                <strong>{{ $message }}</strong>
            </small>
        @enderror
    </div>
</div>
<div class="col-md-6">
    <div class="form-group">
        <label>@lang('site.like_num')</label>
        <input type="number" class="form-control  @error('like_num') is-invalid @enderror" name="like_num"
            value="{{ isset($row) ? $row->like_num : old('like_num') }}">
        @error('like_num')
            <small class=" text text-danger" role="alert">
                <strong>{{ $message }}</strong>
            </small>
        @enderror
    </div>
</div>
<div class="col-md-6">
    <div class="form-group">
        <label>@lang('site.video_num')</label>
        <input type="number" class="form-control  @error('video_num') is-invalid @enderror" name="video_num"
            value="{{ isset($row) ? $row->video_num : old('video_num') }}">
        @error('video_num')
            <small class=" text text-danger" role="alert">
                <strong>{{ $message }}</strong>
            </small>
        @enderror
    </div>
</div>
<div class="col-md-6">
    <div class="form-group">
        <label>@lang('site.invit_num')</label>
        <input type="number" class="form-control  @error('invit_num') is-invalid @enderror" name="invit_num"
            value="{{ isset($row) ? $row->invit_num : old('invit_num') }}">
        @error('invit_num')
            <small class=" text text-danger" role="alert">
                <strong>{{ $message }}</strong>
            </small>
        @enderror
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
    <img src="{{ isset($row) ? $row->image_path : asset('uploads/ranks/default.png') }}" style="width: 115px;height: 80px;position: relative;
                    top: 14px;" class="img-thumbnail image-preview">
</div>
