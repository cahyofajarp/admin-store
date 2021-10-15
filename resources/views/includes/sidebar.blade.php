 <nav class="sidenav navbar navbar-vertical  fixed-left  navbar-expand-xs navbar-light bg-white" id="sidenav-main">
    <div class="scrollbar-inner">
      <!-- Brand -->
      <div class="sidenav-header  align-items-center">
        <a class="navbar-brand" href="javascript:void(0)">
          <img src="../assets/img/brand/blue.png" class="navbar-brand-img" alt="...">
        </a>
      </div>
      <div class="navbar-inner">
        <!-- Collapse -->
        <div class="collapse navbar-collapse" id="sidenav-collapse-main">
          <!-- Nav items -->
          <ul class="navbar-nav">
            <li class="nav-item">
              <a class="nav-link {{ request()->is('admin/dashboard*') ? 'active' : '' }}" href="{{ route('admin.dashboard') }}">
                <i class="ni ni-tv-2 text-primary"></i>
                <span class="nav-link-text">Dashboard</span>
              </a>
            </li>
            <li class="nav-item ">
              <a class="nav-link {{ request()->is('admin/sliders*') ? 'active' : '' }}" href="{{ route('admin.sliders') }}">
                <i class="ni ni-album-2 text-orange"></i>
                <span class="nav-link-text">Sliders</span>
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link {{ request()->is('admin/category*') ? 'active' : '' }}" href="{{ route('admin.category') }}">
                <i class="ni ni-bullet-list-67 text-primary"></i>
                <span class="nav-link-text">Category</span>
              </a>
            </li>

            <li class="nav-item">
              <a class="nav-link {{ request()->is('admin/product*') ? 'active' : '' }}" href="{{ route('admin.product') }}">
                <i class="ni ni-shop text-info"></i>
                <span class="nav-link-text">Product</span>
              </a>
            </li>
            
            <li class="nav-item">
              <a class="nav-link {{ request()->is('admin/gallery*') ? 'active' : '' }}" href="{{ route('admin.gallery') }}">
                <i class="ni ni-bag-17 text-success"></i>
                <span class="nav-link-text">Product Gallery</span>
              </a>
            </li>    
            
            <li class="nav-item">
              <a class="nav-link {{ request()->is('admin/flashsale*') ? 'active' : '' }}" href="{{ route('admin.flashsale') }}">
                <i class="ni ni-tag" style="color:#ff9800"></i>
                <span class="nav-link-text">Flashsale Product</span>
              </a>
            </li>

            <li class="nav-item">
              <a class="nav-link {{ request()->is('admin/order*') ? 'active' : '' }}" href="{{ route('admin.order') }}">
                <i class="ni ni-cart" style="color:#e91e63"></i>
                <span class="nav-link-text">Orders</span>
              </a>
            </li>

            <li class="nav-item">
              <a class="nav-link {{ request()->is('admin/hightlight*') ? 'active' : '' }}" href="{{ route('hightlight.index') }}">
                <i class="ni ni-image" style="color:#607d8b"></i>
                <span class="nav-link-text">Product Hightlight</span>
              </a>
            </li>
            
            {{-- <li class="nav-item">
              <a class="nav-link" href="profile.html">
                <i class="ni ni-single-02 text-yellow"></i>
                <span class="nav-link-text">Profile</span>
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="tables.html">
                <i class="ni ni-bullet-list-67 text-default"></i>
                <span class="nav-link-text">Tables</span>
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="login.html">
                <i class="ni ni-key-25 text-info"></i>
                <span class="nav-link-text">Login</span>
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="register.html">
                <i class="ni ni-circle-08 text-pink"></i>
                <span class="nav-link-text">Register</span>
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="upgrade.html">
                <i class="ni ni-send text-dark"></i>
                <span class="nav-link-text">Upgrade</span>
              </a>
            </li> --}}
          </ul>
        </div>
      </div>
    </div>
  </nav>