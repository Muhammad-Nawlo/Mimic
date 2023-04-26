{{ csrf_field() }}
<div class="row" style="margin: 0 !important;">

<div class="col-md-6">
    <div class="form-group">
        <label>@lang('site.title')</label>
        <input type="title" class="form-control  @error('title') is-invalid @enderror" name="title"
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