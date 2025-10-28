<?php
use Illuminate\Support\Facades\Route;
$configData = Helper::appClasses();
?>

<aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">

  <!-- Hide app brand if navbar-full -->
  <?php if(!isset($navbarFull)): ?>
  <div class="app-brand demo">
    <a href="<?php echo e(url('/')); ?>" class="app-brand-link">
      <span class="app-brand-text demo menu-text text-center fw-semibold ms-2" style="margin-left: 3.5rem !important;"><?php echo e(config('variables.templateName')); ?></span>
    </a>

    <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto">
      <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
        <path d="M8.47365 11.7183C8.11707 12.0749 8.11707 12.6531 8.47365 13.0097L12.071 16.607C12.4615 16.9975 12.4615 17.6305 12.071 18.021C11.6805 18.4115 11.0475 18.4115 10.657 18.021L5.83009 13.1941C5.37164 12.7356 5.37164 11.9924 5.83009 11.5339L10.657 6.707C11.0475 6.31653 11.6805 6.31653 12.071 6.707C12.4615 7.09747 12.4615 7.73053 12.071 8.121L8.47365 11.7183Z" fill-opacity="0.9" />
        <path d="M14.3584 11.8336C14.0654 12.1266 14.0654 12.6014 14.3584 12.8944L18.071 16.607C18.4615 16.9975 18.4615 17.6305 18.071 18.021C17.6805 18.4115 17.0475 18.4115 16.657 18.021L11.6819 13.0459C11.3053 12.6693 11.3053 12.0587 11.6819 11.6821L16.657 6.707C17.0475 6.31653 17.6805 6.31653 18.071 6.707C18.4615 7.09747 18.4615 7.73053 18.071 8.121L14.3584 11.8336Z" fill-opacity="0.4" />
      </svg>
    </a>
  </div>
  <?php endif; ?>

  <div class="menu-inner-shadow"></div>

  <!-- Menu Items -->
  <ul class="menu-inner py-1">
  <?php $__currentLoopData = $menuData[0]->menu; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $menuItem): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
      
      
      <?php
      $activeClass = '';
      $currentRouteName = Route::currentRouteName();

      if ($currentRouteName === $menuItem->slug) {
        $activeClass = 'active';
      } elseif (isset($menuItem->submenu)) {
        foreach ($menuItem->submenu as $subItem) {
          if ($currentRouteName === $subItem->slug) {
            $activeClass = 'active open';
            break;
          }
        }
      }
      ?>

      <!-- Single Menu Item -->
      <li class="menu-item <?php echo e($activeClass); ?>">
        <a href="<?php echo e(isset($menuItem->url) ? url($menuItem->url) : 'javascript:void(0);'); ?>"
           class="menu-link <?php echo e(isset($menuItem->submenu) ? 'menu-toggle' : ''); ?>">
          <i class="<?php echo e($menuItem->icon); ?>"></i>
          <div><?php echo e($menuItem->name); ?></div>
        </a>

        
        <?php if(isset($menuItem->submenu)): ?>
        <ul class="menu-sub">
          <?php $__currentLoopData = $menuItem->submenu; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $subItem): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
          <li class="menu-item <?php echo e($currentRouteName === $subItem->slug ? 'active' : ''); ?>">
            <a href="<?php echo e(url($subItem->url)); ?>" class="menu-link">
              <div><?php echo e($subItem->name); ?></div>
            </a>
          </li>
          <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </ul>
        <?php endif; ?>
      </li>

    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
  </ul>
</aside>

<?php /**PATH C:\xampp\htdocs\metronetsms\resources\views/layouts/sections/menu/verticalMenu.blade.php ENDPATH**/ ?>