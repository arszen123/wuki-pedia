<!-- Left Side Of Navbar -->
<ul class="navbar-nav mr-auto">

</ul>

<!-- Right Side Of Navbar -->
<ul class="navbar-nav ml-auto">
    <!-- Authentication Links -->
    @guest
        <li class="nav-item">
            <a class="nav-link btn btn-outline-primary " href="{{ route('login') }}">{{ __('Login') }}</a>
        </li>
        @if (Route::has('register'))
            <li class="nav-item">
                <a class="nav-link btn btn-outline-primary " href="{{ route('register') }}">{{ __('Register') }}</a>
            </li>
        @endif
    @else
        <li class="nav-item">
            <a class="nav-link btn btn-outline-primary" href="{{ route('user.view', ['id' => 'me']) }}">
                Profile
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link btn btn-outline-primary" href="{{ route('language.select') }}">
                Languages
            </a>
        </li>
        <li class="nav-item dropdown">
            <a class="nav-link btn btn-outline-primary dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                Article
            </a>
            <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                @if(!\Auth::user()->isUser())
                    <a class="dropdown-item" href="{{ route('article.suggest_list') }}">{{ __('Modification requests') }}</a>
                    <div class="dropdown-divider"></div>
                @endif
                <a class="dropdown-item" href="{{ route('admin.article.list') }}">{{ __('My articles') }}</a>
                <div class="dropdown-divider"></div>
                <a class="dropdown-item" href="{{ route('article.create') }}">{{ __('Create article') }}</a>
            </div>
        </li>
        <li class="nav-item">
            <a class="nav-link btn btn-outline-danger" href="{{ route('logout') }}"
               onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                {{ __('Logout') }}
            </a>

            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                @csrf
            </form>
        </li>
    @endguest
</ul>