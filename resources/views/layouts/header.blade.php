@php
    $users = \Auth::user();
    $languages = Utility::languages();
@endphp
<header class="dash-header {{ $user->transprent_layout == 1 ? 'transprent-bg' : '' }}">
    <div class="header-wrapper">
        <div class="me-auto dash-mob-drp">
            <ul class="list-unstyled">
                <li class="dash-h-item mob-hamburger">
                    <a href="#!" class="dash-head-link" id="mobile-collapse">
                        <div class="hamburger hamburger--arrowturn">
                            <div class="hamburger-box">
                                <div class="hamburger-inner"></div>
                            </div>
                        </div>
                    </a>
                </li>
                <li class="dropdown dash-h-item">
                    <a class="dash-head-link dropdown-toggle arrow-none me-0" data-bs-toggle="dropdown" href="#"
                        role="button" aria-haspopup="false" aria-expanded="false">
                        
                        <img src="{{ asset('img/1.png') }}" class="user-avtar ms-2"  />
                        <span class="pr-1">
                            <h6 class="mb-0 f-w-500 fs-6 d-inline-flex">{{ Auth::user()->name }}</h6>
                        </span>
                        <i class="ti ti-chevron-down drp-arrow nocolor"></i>
                    </a>
                    <div class="dropdown-menu dash-h-dropdown">
                        <a href="{{ route('profile.index') }}" class="dropdown-item">
                            <i class="ti ti-user"></i>
                            <span>{{ __('Profile') }}</span>
                        </a>
                        <a href="{{ route('logout') }}"
                            onclick="event.preventDefault();document.getElementById('logout-form').submit();"
                            class="dropdown-item">
                            <i class="ti ti-power"></i>
                            <span>{{ __('Logout') }}</span>
                        </a>
                        {!! Form::open([
                            'route' => ['logout'],
                            'method' => 'POST',
                            'id' => 'logout-form',
                            'class' => 'd-none',
                        ]) !!}
                        {!! Form::close() !!}
                    </div>
                </li>
            </ul>
        </div>
        <div class="ms-auto">
            <ul class="list-unstyled">
             
                <li class="dash-h-item theme_mode">
                    <a class="dash-head-link add_dark_mode me-0" role="button">
                        <i class="ti {{ Utility::getsettings('dark_mode') == 'off' ? 'ti-sun' : 'ti-moon' }}"></i>
                    </a>
                </li>
                <li class="dropdown dash-h-item drp-notification">
                    <a class="dash-head-link dropdown-toggle arrow-none me-0" id="kt_activities_toggle"
                        data-bs-toggle="dropdown" href="#" role="button" aria-haspopup="false"
                        aria-expanded="false">
                        <i class="ti ti-bell"></i>
                        <span
                            class="bg-danger dash-h-badge"><span class="sr-only"></span></span>
                    </a>
                    <div class="dropdown-menu dash-h-dropdown dropdown-menu-end">

                        <div class="noti-header">
                            <h5 class="m-0">{{ __('Notification') }}</h5>
                        </div>
                        <div class="noti-body">

                        </div>
                    </div>
                </li>
            </ul>
        </div>
    </div>
</header>
