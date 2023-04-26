@extends('dashboard.layouts.app')

@section('content')

    <div class="content-wrapper" style="min-height: 0">

        <section class="content-header">

            <h1>@lang('site.dashboard') </h1>

            <ol class="breadcrumb">
                <li class="active"><i class="fa fa-dashboard"></i> @lang('site.dashboard')</li>
            </ol>
        </section>

        <section class="content">
            <div class="row">
                 
                    {{-- @if (auth()->user()->can('read-users'))
                        <div class="col-lg-3 col-xs-6">
                            <div class="small-box">
                                <div class="inner">
                                    <h3>{{count(App\User::all())}}</h3>
                                    <p>@lang('site.users')</p>
                                </div>
                                <div class="icon">
                                    <i class="fa fa-users"></i>
                                </div>
                                <a href="{{ route('dashboard.users.index') }}" class="small-box-footer">@lang('site.read') <i class="fa fa-arrow-circle-right"></i></a>
                            </div>
                        </div>
                    @endif
                    @if (auth()->user()->can('read-countries'))
                        <div class="col-lg-3 col-xs-6">
                            <div class="small-box">
                                <div class="inner">
                                    <h3>{{count(App\Models\Country::all())}}</h3>
                                    <p>@lang('site.countries')</p>
                                </div>
                                <div class="icon">
                                    <i class="fa fa-flag"></i>
                                </div>
                                <a href="{{ route('dashboard.countries.index') }}" class="small-box-footer">@lang('site.read') <i class="fa fa-arrow-circle-right"></i></a>
                            </div>
                        </div>
                    @endif
                    @if (auth()->user()->can('read-cities'))
                        <div class="col-lg-3 col-xs-6">
                            <div class="small-box">
                                <div class="inner">
                                    <h3>{{count(App\Models\City::all())}}</h3>
                                    <p>@lang('site.cities')</p>
                                </div>
                                <div class="icon">
                                    <i class="fa fa-fort-awesome"></i>
                                </div>
                                <a href="{{ route('dashboard.cities.index') }}" class="small-box-footer">@lang('site.read') <i class="fa fa-arrow-circle-right"></i></a>
                            </div>
                        </div>
                    @endif    
                    @if (auth()->user()->can('read-roles'))
                        <div class="col-lg-3 col-xs-6">
                            <div class="small-box">
                                <div class="inner">
                                    <h3>{{count(App\Role::all())}}</h3>
                                    <p>@lang('site.roles')</p>
                                </div>
                                <div class="icon">
                                    <i class="fa fa-hourglass-half"></i>
                                </div>
                                <a href="{{ route('dashboard.roles.index') }}" class="small-box-footer">@lang('site.read') <i class="fa fa-arrow-circle-right"></i></a>
                            </div>
                        </div>
                    @endif --}}
                    {{-- <div class="col-md-12" style="border:2px solid #333; margin-top:30px">
                        <p class="text-center">@lang('site.charts')</p>
                        <canvas id="money" style="height: 400px;"></canvas>
                    </div> --}}

                    <div class="col-md-3" style="border:2px solid #333;">
                        <p class="text-center">@lang('site.challenges')</p>
                        <canvas id="challenges" style="height: 400px;"></canvas>
                    </div>
                    <div class="col-md-3" style="border:2px solid #333;">
                        <p class="text-center">@lang('site.videos')</p>
                        <canvas id="videos" style="height: 400px;"></canvas>
                    </div>
                    <div class="col-md-3" style="border:2px solid #333;">
                        <p class="text-center">@lang('site.stories')</p>
                        <canvas id="stories" style="height: 400px;"></canvas>
                    </div>
                    <div class="col-md-3" style="border:2px solid #333;">
                        <p class="text-center">@lang('site.clients')</p>
                        <canvas id="clients" style="height: 400px;"></canvas>
                    </div>
                    <div class="col-md-12">
                        <h2 class="text-center">@lang('site.Statistics For All Data')</h2>
                    </div>
                    <div class="col-md-12" style="border:2px solid #333;">
                        <p class="text-center">@lang('site.statistics')</p>
                        <canvas id="statistics" style="height: 400px;"></canvas>
                    </div>
                </div>
         </section>

    </div>


@endsection
@section('scripts')
    <script>
        // charts
        // const ctx1 = document.getElementById('money');
        @php   
            //Video
            $pending=\App\Models\Video::where('status','pending')->count() ?? 0;
            $accept=\App\Models\Video::where('status','accept')->count() ?? 0;
            $reject=\App\Models\Video::where('status','reject')->count() ?? 0;
            $close=\App\Models\Video::where('status','close')->count() ?? 0;
            //challlenge
            $pendingChallenge=\App\Models\Challenge::where('status','pending')->count() ?? 0;
            $acceptChallenge=\App\Models\Challenge::where('status','accept')->count() ?? 0;
            $rejectChallenge=\App\Models\Challenge::where('status','reject')->count() ?? 0;
            $closeChallenge=\App\Models\Challenge::where('status','close')->count() ?? 0;
            //stories
            $pendingStory=\App\Models\Story::where('status','pending')->count() ?? 0;
            $acceptStory=\App\Models\Story::where('status','accept')->count() ?? 0;
            $rejectStory=\App\Models\Story::where('status','reject')->count() ?? 0;
            $closeStory=\App\Models\Story::where('status','close')->count() ?? 0;
            //clinet
            $pendingClient=\App\Models\Client::where('status','Pending')->count() ?? 0;
            $blockClient=\App\Models\Client::where('status','Blocked')->count() ?? 0;
            $unblockClient=\App\Models\Client::where('status','UnBlocked')->count() ?? 0;
            //total
            $totalVideos=\App\Models\Video::count() ?? 0;
            $totalChallenges=\App\Models\Challenge::count() ?? 0;
            $totalStories=\App\Models\Story::count() ?? 0;
            $totalClients=\App\Models\Client::count() ?? 0;
            $totalRoles=\App\Role::count() ?? 0;
            $totalUsers=\App\User::count() ?? 0;
            $totalCountries=\App\Models\Country::count() ?? 0;
            $totalRanks=\App\Models\Rank::count() ?? 0;
            $totalCategories=\App\Models\Category::count() ?? 0;
            
        @endphp
        // const ctx2 = document.getElementById('service');

        const pending = document.getElementById('videos');
        chart('pie',pending,[<?php echo '"'.implode('","', [__('site.pending'),__('site.accept'),__('site.reject'),__('site.close')]).'"' ?>],[<?php echo '"'.implode('","', [$pending,$accept,$reject,$close]).'"' ?>]);
        const challenge = document.getElementById('challenges');
        chart('pie',challenge,[<?php echo '"'.implode('","', [__('site.pending'),__('site.accept'),__('site.reject'),__('site.close')]).'"' ?>],[<?php echo '"'.implode('","', [$pendingChallenge,$acceptChallenge,$rejectChallenge,$closeChallenge]).'"' ?>]);
        const story = document.getElementById('stories');
        chart('pie',story,[<?php echo '"'.implode('","', [__('site.pending'),__('site.accept'),__('site.reject'),__('site.close')]).'"' ?>],[<?php echo '"'.implode('","', [$pendingStory,$acceptStory,$rejectStory,$closeStory]).'"' ?>]);
        const clients = document.getElementById('clients');
        chart('pie',clients,[<?php echo '"'.implode('","', [__('site.Pending'),__('site.Blocked'),__('site.UnBlocked')]).'"' ?>],[<?php echo '"'.implode('","', [$pendingClient,$blockClient,$unblockClient]).'"' ?>]);

        const statistics = document.getElementById('statistics');
        chart('bar',statistics,[<?php echo '"'.implode('","', [__('site.totalVideos'),__('site.totalChallenges'),__('site.totalStories'),__('site.totalClients'),__('site.totalRoles'),__('site.totalUsers'),__('site.totalCountries'),__('site.totalRanks'),__('site.totalCategories')]).'"' ?>],[<?php echo '"'.implode('","', [$totalVideos,$totalChallenges,$totalStories,$totalClients,$totalRoles,$totalUsers,$totalCountries,$totalRanks,$totalCategories]).'"' ?>]);

        function chart(type,ctx,label,data){
            const myChart = new Chart(ctx, {
            type: type,
            data: {
                labels: label,
                datasets: [{
                    label: '# of Votes',
                    data: data,
                       backgroundColor: [
                        'rgb(75, 192, 192)',
                        '#F5C0C0',
                        'rgb(54, 162, 235)',
                        'rgb(255, 99, 132)',
                        '#93FFD8',
                        'rgb(255, 205, 86)',
                        '#F2DDC1',
                        'rgb(201, 203, 207)',
                        '#E9896A'
                    ],
                    borderColor: [
                        'rgb(75, 192, 192)',
                        '#F5C0C0',
                        'rgb(54, 162, 235)',
                        'rgb(255, 99, 132)',
                        '#93FFD8',
                        'rgb(255, 205, 86)',
                        '#F2DDC1',
                        'rgb(201, 203, 207)',
                        '#E9896A'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                animation: true,
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
            });
        }
    </script>
@endsection

