<ul class="navbar-nav ml-auto">

    @auth
    <li class="nav-item">
        <a class="nav-link nav-link-icon" href="../index.html">
            <i class="ni ni-planet"></i>
            <span class="nav-link-inner--text">Dashboard</span>
        </a>
    </li>

    @else

    @if(Request::is('login'))
    <li class="nav-item">
        <a class="nav-link nav-link-icon" href="{{ route('register') }}">
            <i class="ni ni-circle-08"></i>
            <span class="nav-link-inner--text">Register</span>
        </a>
    </li>
    @endif

    @if(Request::is('register'))
        <li class="nav-item">
            <a class="nav-link nav-link-icon" href="{{ route('login') }}">
                <i class="ni ni-key-25"></i>
                <span class="nav-link-inner--text">Login</span>
            </a>
        </li>
    @endif

    @endauth

</ul>
