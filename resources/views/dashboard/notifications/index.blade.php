@extends('dashboard.layouts.app')

@section('title', __('site.'.$module_name_plural))


@section('content')

<div class="content-wrapper">

    <section class="content-header">

        <h1>@lang('site.'.$module_name_plural)</h1>

        <ol class="breadcrumb">
            <li> <a href="{{ route('dashboard.home') }}"><i class="fa fa-dashboard"></i> @lang('site.dashboard')</a>
            </li>
            <li class="active"><i class="fa fa-bell"></i> @lang('site.'.$module_name_plural)</li>
        </ol>
    </section>

    <section class="content">

        <div class="box box-primary">

            <div class="box-header with-border">
                <h1 class="box-title"> @lang('site.'.$module_name_plural) <small></small></h1>


            </div> {{--end of box header--}}

            <div class="box-body">
                <form action="{{route('dashboard.'.$module_name_plural.'.store')}}" method="post">
                    @csrf
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label >@lang('site.client')</label>
                                <select class="form-control" id="exampleFormControlSelect1"  name="client_id">
                                    <option value="0">@lang('site.choose_client')</option>
                                    <option value="all" style="color: red">@lang('site.choose_all')</option>
                                    @foreach ( App\Models\Client::get() as $client)
                                      <option {{ old('client_id') == $client->id ? 'selected style=color:red ': '' }} value="{{$client->id}}">{{$client->user_name . '-' . $client->email}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="">@lang('site.ar.body')</label>
                                <textarea name="body_ar" class="form-control ckeditor" >{{ old('body_ar') }}</textarea>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="">@lang('site.en.body')</label>
                                <textarea name="body_en" class="form-control ckeditor" >{{ old('body_en') }}</textarea>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <button class="btn btn-primary" type="submit"><i class=""></i>
                                @lang('site.send')</button>
                        </div>

                    </div>
                </form>

            </div> {{--end of box body--}}

        </div> {{--  end of box--}}

    </section><!-- end of content -->

</div><!-- end of content wrapper -->

@endsection
