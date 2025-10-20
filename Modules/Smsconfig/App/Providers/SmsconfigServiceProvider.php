<?php

namespace Modules\Smsconfig\App\Providers;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;
use Modules\Smsconfig\App\Repositories\BlackListedKeywordRepository;
use Modules\Smsconfig\App\Repositories\BlackListedKeywordRepositoryInterface;
use Modules\Smsconfig\App\Repositories\CountryRepository;
use Modules\Smsconfig\App\Repositories\CountryRepositoryInterface;
use Modules\Smsconfig\App\Repositories\OperatorRepository;
use Modules\Smsconfig\App\Repositories\OperatorRepositoryInterface;
use Modules\Smsconfig\App\Repositories\RateRepository;
use Modules\Smsconfig\App\Repositories\RateRepositoryInterface;
use Modules\Smsconfig\App\Repositories\RouteRepository;
use Modules\Smsconfig\App\Repositories\RouteRepositoryInterface;
use Modules\Smsconfig\App\Repositories\SenderIdRepository;
use Modules\Smsconfig\App\Repositories\SenderIdRepositoryInterface;
use Modules\Smsconfig\App\Repositories\ServiceProviderRepository;
use Modules\Smsconfig\App\Repositories\ServiceProviderRepositoryInterface;
use Modules\Smsconfig\App\Repositories\SettingsRepository;
use Modules\Smsconfig\App\Repositories\SettingsRepositoryInterface;
use Modules\Smsconfig\App\Repositories\MaskRepository;
use Modules\Smsconfig\App\Repositories\MaskRepositoryInterface;

class SmsconfigServiceProvider extends ServiceProvider
{
  protected string $moduleName = 'Smsconfig';

  protected string $moduleNameLower = 'smsconfig';

  /**
   * Boot the application events.
   */
  public function boot(): void
  {
    $this->registerCommands();
    $this->registerCommandSchedules();
    $this->registerTranslations();
    $this->registerConfig();
    $this->registerViews();
    $this->loadMigrationsFrom(module_path($this->moduleName, 'Database/migrations'));
  }

  /**
   * Register commands in the format of Command::class
   */
  protected function registerCommands(): void
  {
    // $this->commands([]);
  }

  /**
   * Register command Schedules.
   */
  protected function registerCommandSchedules(): void
  {
    // $this->app->booted(function () {
    //     $schedule = $this->app->make(Schedule::class);
    //     $schedule->command('inspire')->hourly();
    // });
  }

  /**
   * Register translations.
   */
  public function registerTranslations(): void
  {
    $langPath = resource_path('lang/modules/' . $this->moduleNameLower);

    if (is_dir($langPath)) {
      $this->loadTranslationsFrom($langPath, $this->moduleNameLower);
      $this->loadJsonTranslationsFrom($langPath);
    } else {
      $this->loadTranslationsFrom(module_path($this->moduleName, 'lang'), $this->moduleNameLower);
      $this->loadJsonTranslationsFrom(module_path($this->moduleName, 'lang'));
    }
  }

  /**
   * Register config.
   */
  protected function registerConfig(): void
  {
    $this->publishes(
      [module_path($this->moduleName, 'config/config.php') => config_path($this->moduleNameLower . '.php')],
      'config'
    );
    $this->mergeConfigFrom(module_path($this->moduleName, 'config/config.php'), $this->moduleNameLower);
  }

  /**
   * Register views.
   */
  public function registerViews(): void
  {
    $viewPath = resource_path('views/modules/' . $this->moduleNameLower);
    $sourcePath = module_path($this->moduleName, 'resources/views');

    $this->publishes([$sourcePath => $viewPath], ['views', $this->moduleNameLower . '-module-views']);

    $this->loadViewsFrom(array_merge($this->getPublishableViewPaths(), [$sourcePath]), $this->moduleNameLower);

    $componentNamespace = str_replace(
      '/',
      '\\',
      config('modules.namespace') . '\\' . $this->moduleName . '\\' . config(
        'modules.paths.generator.component-class.path'
      )
    );
    Blade::componentNamespace($componentNamespace, $this->moduleNameLower);
  }

  private function getPublishableViewPaths(): array
  {
    $paths = [];
    foreach (config('view.paths') as $path) {
      if (is_dir($path . '/modules/' . $this->moduleNameLower)) {
        $paths[] = $path . '/modules/' . $this->moduleNameLower;
      }
    }

    return $paths;
  }

  /**
   * Register the service provider.
   */
  public function register(): void
  {
    $this->app->register(RouteServiceProvider::class);
    $this->app->bind(BlackListedKeywordRepositoryInterface::class, BlackListedKeywordRepository::class);
    $this->app->bind(CountryRepositoryInterface::class, CountryRepository::class);
    $this->app->bind(OperatorRepositoryInterface::class, OperatorRepository::class);
    $this->app->bind(RateRepositoryInterface::class, RateRepository::class);
    $this->app->bind(RouteRepositoryInterface::class, RouteRepository::class);
    $this->app->bind(ServiceProviderRepositoryInterface::class, ServiceProviderRepository::class);
    $this->app->bind(SettingsRepositoryInterface::class, SettingsRepository::class);
    $this->app->bind(SenderIdRepositoryInterface::class, SenderIdRepository::class);
    $this->app->bind(MaskRepositoryInterface::class, MaskRepository::class);
  }

  /**
   * Get the services provided by the provider.
   */
  public function provides(): array
  {
    return [];
  }
}
