<?php
  use Illuminate\Support\Facades\Auth;
  use Illuminate\Support\Facades\Route;
  use Illuminate\Support\Facades\DB;
  $containerNav = ($configData['contentLayout'] === 'compact') ? 'container-xxl' : 'container-fluid';
  $navbarDetached = ($navbarDetached ?? '');
  
  // Get user group title if user is authenticated
  $userGroup = null;
  if (Auth::check()) {
    $userGroup = DB::table('user_group')->where('id', Auth::user()->id_user_group)->value('title');
  }
?>

<style>
.user-info-rubber {
  background: linear-gradient(145deg, #ffffff, #f8f9fa);
  border: 1px solid rgba(0,0,0,0.08);
  border-radius: 50px;
  box-shadow: 
    0 2px 8px rgba(0,0,0,0.1),
    0 1px 3px rgba(0,0,0,0.08),
    inset 0 1px 0 rgba(255,255,255,0.8);
  transition: all 0.2s ease;
  backdrop-filter: blur(10px);
}

.user-info-rubber:hover {
  transform: translateY(-1px);
  box-shadow: 
    0 4px 12px rgba(0,0,0,0.15),
    0 2px 6px rgba(0,0,0,0.1),
    inset 0 1px 0 rgba(255,255,255,0.9);
}

.user-info-rubber:active {
  transform: translateY(0);
  box-shadow: 
    0 1px 4px rgba(0,0,0,0.2),
    inset 0 1px 3px rgba(0,0,0,0.1);
}
</style>

  <!-- Navbar -->
<?php if(isset($navbarDetached) && $navbarDetached == 'navbar-detached'): ?>
  <nav class="layout-navbar <?php echo e($containerNav); ?> navbar navbar-expand-xl <?php echo e($navbarDetached); ?> align-items-center bg-navbar-theme" id="layout-navbar">
    <?php endif; ?>
    <?php if(isset($navbarDetached) && $navbarDetached == ''): ?>
      <nav class="layout-navbar navbar navbar-expand-xl align-items-center bg-navbar-theme" id="layout-navbar">
        <div class="<?php echo e($containerNav); ?>">
          <?php endif; ?>

          <div class="layout-menu-toggle navbar-nav align-items-xl-center me-4 me-xl-0 d-xl-none">
              <a class="nav-item nav-link px-0 me-xl-6" href="javascript:void(0)">
                <i class="ri-menu-fill ri-22px"></i>
              </a>
          </div>

          <div class="navbar-nav-right d-flex align-items-center" id="navbar-collapse">

            

            <!-- User -->
            <ul class="navbar-nav flex-row align-items-center ms-auto">
              <li class="nav-item navbar-dropdown dropdown-user dropdown">
                <a class="nav-link dropdown-toggle hide-arrow d-flex align-items-center p-0" href="javascript:void(0);" data-bs-toggle="dropdown">
                  <?php if(Auth::check()): ?>
                    <div class="d-flex align-items-center px-3 py-2">
                      <div class="d-flex flex-column text-end me-3">
                        <span class="fw-medium text-dark small mb-0" style="line-height: 1.2;"><?php echo e(Auth::user()->name); ?></span>
                        <small class="text-muted" style="font-size: 0.75rem; line-height: 1;"><?php echo e($userGroup); ?></small>
                      </div>
                      <div class="avatar avatar-online">
                        <img src="<?php echo e(Auth::check() && Auth::user()->photo ? Auth::user()->photo : asset('assets/img/avatars/1.png')); ?>" alt class="rounded-circle">
                      </div>
                    </div>
                  <?php else: ?>
                    <div class="avatar avatar-online">
                      <img src="<?php echo e(asset('assets/img/avatars/1.png')); ?>" alt class="rounded-circle">
                    </div>
                  <?php endif; ?>
                </a>
                <ul class="dropdown-menu dropdown-menu-end">
                  <li>
                    <a class="dropdown-item" href="<?php echo e(url('profile')); ?>">
                      <div class="d-flex">
                        <div class="flex-shrink-0 me-2">
                          <div class="avatar avatar-online">
                            <img src="<?php echo e(Auth::check() && Auth::user()->photo ? Auth::user()->photo : asset('assets/img/avatars/1.png')); ?>" alt class="rounded-circle">
                          </div>
                        </div>
                        <div class="flex-grow-1">
                      <span class="fw-medium d-block small">
                        <?php if(Auth::check()): ?>
                          <?php echo e(Auth::user()->name); ?>

                        <?php else: ?>
                          John Doe
                        <?php endif; ?>
                      </span>
                          <small class="text-muted"><?php echo e($userGroup); ?></small>
                        </div>
                      </div>
                    </a>
                  </li>
                  <li>
                    <div class="dropdown-divider"></div>
                  </li>
                  <li>
                    <a class="dropdown-item" href="<?php echo e(url('profile')); ?>">
                      <i class="ri-user-3-line ri-22px me-3"></i><span class="align-middle">My Profile</span>
                    </a>
                  </li>
                  <li>
                    <?php if(\Illuminate\Support\Facades\Session::has('hasClonedUser')): ?>
                      <a class="dropdown-item" href="<?php echo e(route('users-login-as', \Illuminate\Support\Facades\Session::get('hasClonedUser'))); ?>">
                        <i class="ri-user-3-line ri-22px me-3"></i><span class="align-middle">Back to Users</span>
                    <?php endif; ?>
                    <?php if(\Illuminate\Support\Facades\Session::has('hasClonedAnotherUser')): ?>
                      <a class="dropdown-item" href="<?php echo e(route('users-login-as', \Illuminate\Support\Facades\Session::get('hasClonedAnotherUser'))); ?>">
                        <i class="ri-user-3-line ri-22px me-3"></i><span class="align-middle">Back to Admin</span>
                    <?php endif; ?>
                  </li>
                  <li>
                    <div class="dropdown-divider"></div>
                  </li>
                  <?php if(Auth::check()): ?>
                    <li>
                      <div class="d-grid px-4 pt-2 pb-1">
                        <a class="btn btn-sm btn-danger d-flex" href="<?php echo e(route('logout')); ?>" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                          <small class="align-middle">Logout</small>
                          <i class="ri-logout-box-r-line ms-2 ri-16px"></i>
                        </a>
                      </div>
                    </li>
                    <form method="POST" id="logout-form" action="<?php echo e(route('logout')); ?>">
                      <?php echo csrf_field(); ?>
                    </form>
                  <?php else: ?>
                    <li>
                      <div class="d-grid px-4 pt-2 pb-1">
                        <a class="btn btn-sm btn-danger d-flex" href="<?php echo e(Route::has('login') ? route('login') : url('auth/login-basic')); ?>">
                          <small class="align-middle">Login</small>
                          <i class="ri-logout-box-r-line ms-2 ri-16px"></i>
                        </a>
                      </div>
                    </li>
                  <?php endif; ?>
                </ul>
              </li>
            </ul>
            <!--/ User -->
          </div>





          <!-- Search Small Screens -->
          <div class="navbar-search-wrapper search-input-wrapper <?php echo e(isset($menuHorizontal) ? $containerNav : ''); ?> d-none">
            <input type="text" class="form-control search-input <?php echo e(isset($menuHorizontal) ? '' : $containerNav); ?> border-0" placeholder="Search..." aria-label="Search...">
            <i class="ri-close-fill search-toggler cursor-pointer"></i>
          </div>
          <!--/ Search Small Screens -->
          <?php if(!isset($navbarDetached)): ?>
        </div>
        <?php endif; ?>
      </nav>
      <!-- / Navbar -->
<?php /**PATH C:\xampp\htdocs\nbr\resources\views/layouts/sections/navbar/navbar.blade.php ENDPATH**/ ?>