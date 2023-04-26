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
                                <label>@lang('site.search')</label>
                                <input type="text" name="search" value="{{ request()->search }}" class="form-control"
                                    placeholder="@lang('site.search')">
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label>@lang('site.status')</label>
                                    <select name="status" class='form-control' id="">
                                        <option value="">@lang('site.choose_status')</option>
                                        <option value="pending" {{ request()->status=='pending' ? 'selected' : ''}}>@lang('site.pending')</option>
                                        <option value="accept" {{ request()->status=='accept' ? 'selected' : ''}}>@lang('site.accept')</option>
                                        <option value="reject" {{ request()->status=='reject' ? 'selected' : ''}}>@lang('site.reject')</option>
                                        <option value="close" {{ request()->status=='close' ? 'selected' : ''}}>@lang('site.close')</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label>@lang('site.date')</label>
                                    <input type="date" name="date" value="{{ request()->date }}" class="form-control"
                                    placeholder="@lang('site.date')">
                                </div>
                            </div>
                             <div class="col-md-2">
                                <div class="form-group">
                                    <label>@lang('site.from_date')</label>
                                    <input type="date" name="from_date" value="{{ request()->from_date }}" class="form-control"
                                    placeholder="@lang('site.date')">
                                </div>
                            </div>
                             <div class="col-md-2">
                                <div class="form-group">
                                    <label>@lang('site.to_date')</label>
                                    <input type="date" name="to_date" value="{{ request()->to_date }}" class="form-control"
                                    placeholder="@lang('site.date')">
                                </div>
                            </div>
                            <div class="col-md-2">
                                <button class="btn btn-primary" type="submit"><i class="fa fa-search"></i>
                                    @lang('site.search')</button>
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
                                    <th>@lang('site.title')</th>
                                    <th>@lang('site.client')</th>
                                    <th>@lang('site.end_date')</th>
                                    <th>@lang('site.created_at')</th>
                                    <th>@lang('site.status')</th>
                                    <th>@lang('site.action')</th>
                                </tr>
                            </thead>

                            <tbody>
                                @foreach ($rows as $index => $row)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>
                                            <a title="@lang('site.videos')" href="{{route('dashboard.challengVideos',$row->id)}}">
                                                {{ $row->title }}
                                            </a>
                                        </td>
                                        <td>
                                            <a href="{{route('dashboard.client',$row->creater_id)}}">
                                                {{ $row->client->user_name ?? null }}
                                            </a>
                                        </td>
                                        <td>{{ $row->end_date }}</td>
                                        <td>{{ date('Y-m-d',strtotime($row->created_at)) }}</td>

                                        <td>
                                            <button type="button" class="btn btn-primary btn-xs" data-toggle="modal"  href='#status{{ $row->id }}'>@lang('site.'.$row->status)</button>
                                        </td>
                                        @include('dashboard.challenges.status')
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
