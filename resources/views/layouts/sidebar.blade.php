@php
    use App\Models\Form;
    use App\Models\Booking;
    $user = \Auth::user();
    $languages = Utility::languages();
    // $role_id = $user->roles->first()->id;
    $user_id = $user->id;
    $forms = Form::all();
    $all_forms = Form::all();
   
@endphp
<nav class="dash-sidebar light-sidebar {{ $user->transprent_layout == 1 ? 'transprent-bg' : '' }}">
    <div class="navbar-wrapper">
        <div class="m-header">
            <a href="{{ route('home') }}" class="text-center b-brand">
                <h1 class="app-brand-text demo menu-text fw-bolder ms-2" style="color:#CC7722;"> {{ config('app.name', 'CLUSTERSIG') }}</h1></a>
            </a>
        </div>
        <div class="navbar-content">
            <ul class="dash-navbar d-block">
                <li class="dash-item dash-hasmenu {{ request()->is('dependencies*', 'design*') ? 'active' : '' }}">
                    <a href="{{ route('dependencies.index') }}" class="dash-link"><span class="dash-micon"><i
                                class="ti ti-home"></i></span>
                        <span class="dash-mtext custom-weight">{{ __('Dependencias') }}</span></a>
                </li>

                <li class="dash-item dash-hasmenu {{ request()->is('departments*', 'design*') ? 'active' : '' }}">
                    <a href="{{ route('departments.index') }}" class="dash-link"><span class="dash-micon"><i
                                class="ti ti-home"></i></span>
                        <span class="dash-mtext custom-weight">{{ __('Departamentos') }}</span></a>
                </li>

                <li class="dash-item dash-hasmenu {{ request()->is('forms*', 'design*') ? 'active' : '' }}">
                    <a href="{{ route('forms.index') }}" class="dash-link"><span class="dash-micon"><i
                                class="ti ti-home"></i></span>
                        <span class="dash-mtext custom-weight">{{ __('Formulario') }}</span></a>
                </li>

              

                <li class="dash-item dash-hasmenu {{ request()->is('usuarios*') ? 'active' : '' }}">
                    <a href="{{route('usuarios.index')}}" class="dash-link"><span class="dash-micon"><i
                                class="ti ti-user"></i></span>
                        <span class="dash-mtext custom-weight">{{ __('Usuarios') }}</span></a>
                </li>


                <li class="dash-item dash-hasmenu {{ request()->is('roles*') ? 'active' : '' }}">
                    <a href="{{route('roles.index')}}" class="dash-link"><span class="dash-micon"><i
                                class="ti ti-pencil"></i></span>
                        <span class="dash-mtext custom-weight">{{ __('Roles') }}</span></a>
                </li>
              
                @canany(['manage-user', 'manage-role'])
                    <li
                        class="dash-item dash-hasmenu {{ request()->is('users*') || request()->is('roles*') ? 'active dash-trigger' : 'collapsed' }}">
                        <a href="#!" class="dash-link"><span class="dash-micon"><i
                                    class="ti ti-layout-2"></i></span><span
                                class="dash-mtext">{{ __('User Management') }}</span><span class="dash-arrow"><i
                                    data-feather="chevron-right"></i></span></a>
                        <ul class="dash-submenu">
                            @can('manage-user')
                                <li class="dash-item {{ request()->is('users*') ? 'active' : '' }}">
                                    <a class="dash-link" href="{{ route('users.index') }}">{{ __('Users') }}</a>
                                </li>
                            @endcan
                            @can('manage-role')
                                <li class="dash-item {{ request()->is('roles*') ? 'active' : '' }}">
                                    <a class="dash-link" href="{{ route('roles.index') }}">{{ __('Roles') }}</a>
                                </li>
                            @endcan
                        </ul>
                    </li>
                @endcanany

                @canany(['manage-form', 'manage-submitted-form'])
                    <li
                        class="dash-item dash-hasmenu {{ request()->is('forms*', 'design*') || request()->is('formvalues*') ? 'active dash-trigger' : 'collapsed' }}">
                        <a href="#!" class="dash-link"><span class="dash-micon"><i class="ti ti-table"></i></span><span
                                class="dash-mtext">{{ __('Form') }}</span><span class="dash-arrow"><i
                                    data-feather="chevron-right"></i></span></a>
                        <ul class="dash-submenu">
                            @can('manage-form')
                                <li class="dash-item {{ request()->is('forms*', 'design*') ? 'active' : '' }}">
                                    <a class="dash-link" href="{{ route('forms.index') }}">{{ __('Forms') }}</a>
                                </li>
                            @endcan
                            @can('manage-submitted-form')
                                <li class="dash-item">
                                    <a href="#!" class="dash-link"><span
                                            class="dash-mtext custom-weight">{{ __('Submitted Forms') }}</span><span
                                            class="dash-arrow"><i data-feather="chevron-right"></i></span></a>
                                    <ul
                                        class="dash-submenu {{ Request::route()->getName() == 'view.form.values' ? 'd-block' : '' }}">
                                        @foreach ($forms as $form)
                                            <li class="dash-item">
                                                <a class="dash-link {{ Request::route()->getName() == 'view.form.values' ? 'show' : '' }}"
                                                    href="{{ route('view.form.values', $form->id) }}">{{ $form->title }}</a>
                                            </li>
                                        @endforeach
                                    </ul>
                                </li>
                            @endcan
                        </ul>
                    </li>
                @endcanany
            

            </ul>
        </div>
    </div>
</nav>
