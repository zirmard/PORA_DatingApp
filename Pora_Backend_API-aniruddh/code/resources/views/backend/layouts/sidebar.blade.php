<?php
use Illuminate\Support\Facades\Auth;
$routeArr = explode(".", app('request')->route()->getAction('as'));
?>
<!-- Main Sidebar Container -->
  <aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="{{ route('backend.home') }}" class="brand-link" style="text-align:center;">
      <img src="{{ asset('theme/dist/img/logo.png') }}" width="150" alt="{{ config('app.name') }}"/>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                <li class="nav-item">
                    <a href="{{route('backend.home')}}" class="nav-link <?= ($routeArr[1] == 'home') ? 'active' : '' ?>">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p>{{ HOME_PAGE_LABEL }}</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{route('backend.manage-users.index')}}" class="nav-link <?= ($routeArr[1] == 'manage-users') ? 'active' : '' ?>">
                        <i class="nav-icon fas fa-users"></i>
                        <p>{{ MANAGE_USER_LABEL }}</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{route('backend.indexNotification')}}" class="nav-link <?= ($routeArr[1] == 'show-notification') ? 'active' : '' ?>">
                        <i class="nav-icon fas fa-bell"></i>
                        <p>{{ SEND_NOTIFICATION }}</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ route('backend.contact-reasons.index') }}" class="nav-link <?= ($routeArr[1] == 'contact-reasons') ? 'active' : '' ?>">
                        <i class="fas fa-envelope nav-icon"></i>
                        <p>{{ CONTACT_REASON_LABEL }}</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ route('backend.report-reasons.index') }}" class="nav-link <?= ($routeArr[1] == 'report-reasons') ? 'active' : '' ?>">
                        <i class="fas fa-ban nav-icon"></i>
                        <p>{{ REPORT_REASON_LABEL }}</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ route('backend.reported-users.index') }}" class="nav-link <?= ($routeArr[1] == 'reported-users') ? 'active' : '' ?>">
                        <i class="fas fa-users-slash nav-icon"></i>
                        <p>{{ REPORTED_USERS }}</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{route('backend.updateVersion')}}" class="nav-link <?= ($routeArr[1] == 'update-version') ? 'active' : '' ?>">
                        <i class="nav-icon fas fa-bell"></i>
                        <p>{{ VERSION_LABEL }}</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('backend.faqs.index') }}" class="nav-link <?= ($routeArr[1] == 'faqs') ? 'active' : '' ?>">
                        <i class="far fa-question-circle nav-icon"></i>
                        <p>{{ FAQ_LABEL }}</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ route('backend.content_page.index') }}" class="nav-link <?= ($routeArr[1] == 'content_page') ? 'active' : '' ?>">
                        <i class="fas fa-file-alt nav-icon"></i>
                        <p>{{ CONTENT_PAGE_LABEL }}</p>
                    </a>
                </li>

                {{-- <li class="nav-item">
                    <a href="{{route('backend.logs')}}" class="nav-link <?= ($routeArr[1] == 'logs') ? 'active' : '' ?>">
                        <i class="nav-icon fas fa-history"></i>
                        <p>Logs</p>
                    </a>
                </li> --}}
            </ul>
        </nav>
    </div>
    <!-- /.sidebar -->
  </aside>
