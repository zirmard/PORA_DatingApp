<html>
<!-- Navbar -->
<nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
        <li class="nav-item">
            <a class="nav-link" data-widget="pushmenu" href="#" role="button">
                {{-- <i class="fas fa-bars"></i> --}}
            </a>
        </li>
    </ul>

    <!-- Right navbar links -->

    <div class="navbar-custom-menu">
        <ul class="nav navbar-nav">
            <!-- User Account Menu -->
            <li class="dropdown user user-menu">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                    @if(!empty(Auth::user()->vImage))
                        <img src="{{ url('storage/uploads/'.Auth::user()->vImage) }}" class="user-image" alt="">
                    @else
                        <img src="{{ url('theme/dist/img/user_placeholder.png') }}" class="user-image" alt="">
                    @endif
                    <strong>{{ Auth::user()->vName }}</strong><span class="caret"></span>
                </a>
                <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                    <a href="{{ route('backend.profile') }}" class="dropdown-item">
                        <i class="fas fa-user"></i> My Profile
                    </a>
                    <div class="dropdown-divider"></div>
                    <a href="{{ route('backend.change_password') }}" class="dropdown-item">
                        <i class="fas fa-lock"></i> Change Password
                    </a>
                    <div class="dropdown-divider"></div>
                    <a href="{{ route('backend.logout') }}" class="dropdown-item">
                        <i class="fas fa-power-off"></i> Logout
                    </a>
                </div>
            </li>
        </ul>
    </div>

</nav>
<!-- /.navbar -->
