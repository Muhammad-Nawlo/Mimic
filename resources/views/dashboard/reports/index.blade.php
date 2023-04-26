@extends('dashboard.layouts.app')

@section('title', __('site.' . $module_name_plural))
@section('styles')
<link href="https://vjs.zencdn.net/7.2.3/video-js.css" rel="stylesheet">
@endsection

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
                                    <select name="status" class='form-control' id="">
                                        <option value="">@lang('site.choose_status')</option>
                                        <option value="new" {{ request()->status=='new' ? 'selected' : ''}}>@lang('site.new')</option>
                                        <option value="confirmed" {{ request()->status=='confirmed' ? 'selected' : ''}}>@lang('site.confirmed')</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <input type="date" name="date" value="{{ request()->date }}" class="form-control"
                                    placeholder="@lang('site.date')">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <button class="btn btn-primary" type="submit"><i class="fa fa-search"></i>
                                    @lang('site.search')</button>


                            </div>
                        </div>
                    </form>
                </div> {{-- end of box header --}}

                <div class="box-body">
                    @php $arr=array(); @endphp

                    @if ($rows->count() > 0)

                      
                <table class="table table-hover">

                    <thead class="thead-dark">
                                <tr>
                                    <th>#</th>
                                    <th>@lang('site.client')</th>
                                    <th>@lang('site.video')</th> 
                                    <th>@lang('site.report')</th>
                                    <th>@lang('site.created_at')</th>
                                    <th>@lang('site.action')</th>
                                </tr>
                            </thead>

                            <tbody>
                                @foreach ($rows as $index => $row)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>
                                            <a href="{{route('dashboard.client',$row->client_id)}}">
                                                {{ $row->client->user_name ?? null }}
                                            </a>
                                        </td>
                                    
                                        <td>
                                            <a href="{{route('dashboard.video',$row->video_id)}}">
                                                {{ $row->video->title ?? null }}
                                            </a>
                                        </td>
                                        <td>
                                            <button type="button" class="btn btn-primary btn-xs" data-toggle="modal"  href='#report{{ $row->id }}'>
                                                <i class="fa fa-news">@lang('site.'.$row->status)</i>
                                            </button>
                                        </td>
                                        @include('dashboard.reports.report')
                                        <td>{{date('Y-m-d',strtotime($row->created_at))}}</td>
                                        <td>
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
@section('scripts')
<script src="https://vjs.zencdn.net/ie8/ie8-version/videojs-ie8.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/videojs-contrib-hls/5.14.1/videojs-contrib-hls.js"></script>
<script src="https://vjs.zencdn.net/7.2.3/video.js"></script>
<script>
@foreach($arr as $ar)
   videojs(@php echo $ar; @endphp).pause();
@endforeach
// alert('hi')
//     function PlayVideo(val)
//     {
//         alert(val);
//         videojs(val).play();
//     }
</script>
@endsection

