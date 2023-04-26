@extends('dashboard.layouts.app')

@section('title', __('site.' . $module_name_plural))

@section('content')

    <div class="content-wrapper">

        <section class="content-header">

            <h1>@lang('site.'.$module_name_plural)</h1>

            <ol class="breadcrumb">
                <li> <a href="{{ route('dashboard.home') }}"><i class="fa fa-dashboard"></i> @lang('site.dashboard')</a>
                </li>
                <li class="active"><i class="fa fa-user-circle-o"></i> @lang('site.'.$module_name_plural)</li>
            </ol>
        </section>

        <section class="content">

            <div class="box box-primary">

                <div class="box-header with-border">
                    <h1 class="box-title"> @lang('site.'.$module_name_plural) <small>{{ $rows->total() }}</small></h1>

                    <form action="{{ route('dashboard.' . $module_name_plural . '.index') }}" method="get">

                        <div class="row">

                            <div class="col-md-3">
                                <input type="text" name="search" value="{{ request()->search }}" class="form-control"
                                    placeholder="@lang('site.search')">
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <select name="country_id"  class='form-control'>
                                        <option value="">@lang('site.choose_country_id')</option>
                                        @foreach(\App\Models\Country::all() as $cou)
                                            <option value="{{$cou->id}}" @if(request('country_id')==$cou->id) selected  @endif>{{$cou->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            
                            <div class="col-md-2">
                                <div class="form-group">
                                    <select name="city_id"  class='form-control'>
                                        <option value="">@lang('site.choose_city')</option>
                                    
                                    </select>
                                </div>
                            </div>
                            
                            <div class="col-md-3">
                                <button class="btn btn-primary" type="submit"><i class="fa fa-search"></i>
                                    @lang('site.search')</button>
                                    {{-- @if (auth()->user()->hasPermission('create-'.$module_name_plural))
                                    <a href="{{ route('dashboard.' . $module_name_plural . '.create') }}"
                                    class="btn btn-primary"><i class="fa fa-plus"></i> @lang('site.add')</a>
                                    @else
                                        <button disabled>add </button>
                                    @endif --}}


                            </div>
                        </div>
                    </form>
                </div> {{-- end of box header --}}

                <div class="box-body">

                    @if ($rows->count() > 0)

                      
                <table class="table table-hover">

                    <thead class="thead-dark">
                                <tr>
                                    <th>#</th>
                                    <th>@lang('site.username')</th>
                                    <th>@lang('site.winners')</th>
                                    <th>@lang('site.videos')</th>
                                    <th>@lang('site.email')</th>
                                    <th>@lang('site.date_of_birth')</th>
                                    <th>@lang('site.status')</th>
                                    <th>@lang('site.action')</th>
                                </tr>
                            </thead>

                            <tbody>
                                @foreach ($rows as $index => $row)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>
                                            <a title="@lang('site.challenges')"  href="{{route('dashboard.Mychallenges',$row->id)}}">
                                                {{ $row->user_name }}
                                            </a>    
                                        </td>
                                        <th>
                                            <a href="{{route('dashboard.MyWinnerChallenges',$row->id)}}">
                                                <span class="budget">{{$row->winners->count() ?? 0}}</span>
                                                <i class="fa fa-trophy colori"></i>
                                            </a>
                                        </th>
                                        <th>
                                            <a href="{{route('dashboard.clientVideos',$row->id)}}">
                                                <span class="budget">{{$row->videos->count() ?? 0}}</span>
                                                <i class="fa fa-camera colori"></i>
                                            </a>
                                        </th>
                                        <td><a href="mailto:{{$row->email}}">{{ $row->email }}</a></td>
                                        <td>{{$row->date_of_birth}}</td>
                                        <td>
                                            <button type="button" class="btn btn-primary btn-xs" data-toggle="modal"  href='#status{{ $row->id }}'>@lang('site.'.$row->status)</button>
                                        </td>
                                        @include('dashboard.clients.status')
                                        <td>
                                            @if (auth()->user()->hasPermission('update-'.$module_name_plural))
                                                @include('dashboard.buttons.edit')
                                            @else
                                                <input type="submit" value="edit" disabled>
                                            @endif

                                            @if (auth()->user()->hasPermission('delete-'.$module_name_plural))
                                            @include('dashboard.buttons.delete')
                                            @else
                                                <input type="submit" value="delete" disabled>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach

                            </tbody>

                        </table> {{-- end of table --}}

                        {{ $rows->appends(request()->query())->links() }}

                    @else
                        <tr>
                            <h4>@lang('site.no_records')</h4>
                        </tr>
                    @endif

                </div> {{-- end of box body --}}

            </div> {{-- end of box --}}

        </section><!-- end of content -->

    </div><!-- end of content wrapper -->

@endsection
