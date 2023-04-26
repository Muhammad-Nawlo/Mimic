<aside class="main-sidebar">

    <section class="sidebar">

        <div class="user-panel">
            <div class="pull-left image">
                <img src="{{auth()->user()->image_path}}" class="img-circle" alt="User Image">
            </div>
            <div class="pull-left info">
                <p>{{auth()->user()->name}}</p>
                <a href="#"><i class="fa fa-circle text-success"></i> @lang('site.status')</a>
            </div>
        </div>

        <ul class="sidebar-menu" data-widget="tree">

                <li class="{{request()->is(LaravelLocalization::getCurrentLocale().'/dashboard/home')? 'active':''}}"><a href="{{ route('dashboard.home') }}"><i
                class="fa fa-dashboard"></i><span>@lang('site.dashboard')</span></a></li>
        
                @if (auth()->user()->hasPermission('read-users'))
                <li class="{{request()->is(LaravelLocalization::getCurrentLocale().'/dashboard/users*')? 'active':''}}"><a href="{{ route('dashboard.users.index') }}"><i
                            class="fa fa-users"></i><span>@lang('site.users')</span></a></li>
                @endif

                @if (auth()->user()->can('read-roles'))
                    <li class="{{request()->is(LaravelLocalization::getCurrentLocale().'/dashboard/roles*')? 'active':''}}"><a href="{{ route('dashboard.roles.index') }}"><i
                            class="fa fa-hourglass-half"></i><span>@lang('site.roles')</span></a></li>
                @endif


                <li class="treeview" style="height: auto;">
                <a href="" ><i
                        class="fa fa-cog"></i><span>@lang('site.main data')</span></a>
                <ul class="treeview-menu" style="display: none;">
                        @if (auth()->user()->can('read-countries'))
                            <li class="{{request()->is(LaravelLocalization::getCurrentLocale().'/dashboard/countries*')? 'active':''}}">
                                <a href="{{ route('dashboard.countries.index') }}"><i
                                        class="fa fa-flag"></i> @lang('site.countries')</a></li>
                        @endif
                        @if (auth()->user()->can('read-cities'))
                            <li class="{{request()->is(LaravelLocalization::getCurrentLocale().'/dashboard/cities*')? 'active':''}}">
                                <a href="{{ route('dashboard.cities.index') }}"><i
                                        class="fa fa-fort-awesome"></i> @lang('site.cities')</a></li>
                        @endif
                        @if (auth()->user()->can('read-categories'))
                            <li class="{{request()->is(LaravelLocalization::getCurrentLocale().'/dashboard/categories*')? 'active':''}}">
                                <a href="{{ route('dashboard.categories.index') }}"><i
                                        class="fa fa-list-ol"></i> @lang('site.categories')</a></li>
                        @endif
                        @if (auth()->user()->can('read-ranks'))
                            <li class="{{request()->is(LaravelLocalization::getCurrentLocale().'/dashboard/ranks*')? 'active':''}}">
                                <a href="{{ route('dashboard.ranks.index') }}"><i
                                        class="fa fa-level-up"></i> @lang('site.ranks')</a></li>
                        @endif
                        @if (auth()->user()->can('read-hashtags'))
                            <li class="{{request()->is(LaravelLocalization::getCurrentLocale().'/dashboard/hashtags*')? 'active':''}}">
                                <a href="{{ route('dashboard.hashtags.index') }}"><i
                                        class="fa fa-hashtag"></i> @lang('site.hashtags')</a></li>
                        @endif
                    </ul>
                </li>
                @if (auth()->user()->can('read-clients'))
                    <li class="{{request()->is(LaravelLocalization::getCurrentLocale().'/dashboard/clients*')? 'active':''}}"><a href="{{ route('dashboard.clients.index') }}"><i
                            class="fa fa-user"></i><span>@lang('site.clients')</span></a></li>
                @endif
                @if (auth()->user()->can('read-challenges'))
                    <li class="{{request()->is(LaravelLocalization::getCurrentLocale().'/dashboard/challenges*')? 'active':''}}"><a href="{{ route('dashboard.challenges.index') }}"><i
                            class="fa fa-rocket"></i><span>@lang('site.challenges')</span></a></li>
                @endif
                @if (auth()->user()->can('read-videos'))
                    <li class="{{request()->is(LaravelLocalization::getCurrentLocale().'/dashboard/videos*')? 'active':''}}"><a href="{{ route('dashboard.videos.index') }}"><i
                            class="fa fa-video-camera"></i><span>@lang('site.videos')</span></a></li>
                @endif
                @if (auth()->user()->can('read-stories'))
                    <li class="{{request()->is(LaravelLocalization::getCurrentLocale().'/dashboard/stories*')? 'active':''}}"><a href="{{ route('dashboard.stories.index') }}"><i
                            class="fa fa-camera"></i><span>@lang('site.stories')</span></a></li>
                @endif
                @if (auth()->user()->can('read-reports'))
                <li class="{{request()->is(LaravelLocalization::getCurrentLocale().'/dashboard/reports*')? 'active':''}}"><a href="{{ route('dashboard.reports.index') }}"><i
                        class="fa fa-hand-paper-o"></i><span>@lang('site.reports')</span></a></li>
                @endif
                    @if (auth()->user()->can('read-notifications'))
                    <li class="{{request()->is(LaravelLocalization::getCurrentLocale().'/dashboard/notifications*')? 'active':''}}"><a href="{{ route('dashboard.notifications.index') }}"><i
                            class="fa fa-bell"></i><span>@lang('site.notifications')</span></a></li>
                @endif
        </ul>
    </section>

</aside>
