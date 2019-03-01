<ul class="navbar-nav align-items-center d-none d-md-flex">
    <li class="nav-item dropdown">
        <a class="nav-link pr-0" href="#" role="button" data-toggle="dropdown" aria-haspopup="true"
           aria-expanded="false">
            <div class="media align-items-center">
                                <span class="avatar avatar-sm rounded-circle">
                                  <img alt="Image placeholder" src="{{ asset('/assets/img/theme/team-4-800x800.jpg') }}">
                                </span>
                <div class="media-body ml-2 d-none d-lg-block">
                    @auth
                    <span class="mb-0 text-sm  font-weight-bold">{{ Auth::user()->name }}</span>
                    @endauth
                </div>
            </div>
        </a>
        <div class="dropdown-menu dropdown-menu-arrow dropdown-menu-right">
            <div class=" dropdown-header noti-title">
                <h6 class="text-overflow m-0">Welcome!</h6>
            </div>
            <a href="{{ route('profile') }}" class="dropdown-item">
                <i class="ni ni-single-02"></i>
                <span>My profile</span>
            </a>
            <div class="dropdown-divider"></div>
            <a href="{{ route('logout') }}"
                onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="dropdown-item">
                <i class="ni ni-user-run"></i>
                <span>Logout</span>
            </a>

            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                @csrf
            </form>
        </div>
    </li>
</ul>
