<div class="sidebar pe-4 pb-3">
  <nav class="navbar bg-light navbar-light">
      <a href="index.html" class="navbar-brand mx-4 mb-3">
          <h3 class="text-primary"><i class="fa fa-hashtag me-2"></i>Task Manager</h3>
      </a>
      <div class="d-flex align-items-center ms-4 mb-4">
          <div class="position-relative">
              <img class="rounded-circle" src="{{asset('/assets/img/avatar.png')}}" alt="" style="width: 40px; height: 40px;">
              <div class="bg-success rounded-circle border border-2 border-white position-absolute end-0 bottom-0 p-1"></div>
          </div>
          <div class="ms-3">
              <h6 class="mb-0"> @auth {{ auth()->user()->name }} @endauth</h6>
              <span>Admin</span>
          </div>
      </div>
      <div class="navbar-nav w-100">          
        <a href="{{route('projects.index')}}" class="nav-item nav-link {{ (request()->routeIs('projects.index') || request()->is('/')) ? 'active' : '' }}"><i class="fa fa-th me-2"></i>Projects</a>
        <a href="{{route('tasks.index')}}" class="nav-item nav-link {{ Route::is('tasks.index') ? 'active' : '' }}"><i class="fa fa-keyboard me-2"></i>Task</a>
        <hr>
        <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="nav-item nav-link"><i class="fa fa-window-close" aria-hidden="true"></i>Logout</a>
        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
            @csrf
        </form>
    </div>
  </nav>
</div>