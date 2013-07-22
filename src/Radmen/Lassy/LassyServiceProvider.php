<?php namespace Radmen\Lassy;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\ServiceProvider;

class LassyServiceProvider extends ServiceProvider {

  /**
   * Indicates if loading of the provider is deferred.
   *
   * @var bool
   */
  protected $defer = false;

  /**
   * Register the service provider.
   *
   * @return void
   */
  public function register() {
    $this->package('radmen/lassy', 'lassy', __DIR__.'/../../');
    $app = $this->app;

    $this->registerLassy();
    $this->registerCleanerCommand();
    $this->commands('command.lassy.cleaner');

    $this->app->after(function(Request $request, Response $response) use ($app) {
      $app['lassy']->save($request, $response);
    });
  }

  protected function registerLassy() {
    $this->app['lassy'] = $this->app->share(function($app) {
      $config = $app['config'];

      $lassy = new Lassy($config->get('lassy::output_dir'), $app['files']);
      $lassy->addFilter($config->get('lassy::filters'));

      return $lassy;
    });
  }

  protected function registerCleanerCommand() {
    $this->app['command.lassy.cleaner'] = $this->app->share(function($app) {
      $cleaner = new Cleaner($app['config']->get('lassy::output_dir'), $app['files']);
      $command = new Command\ClearFiles($cleaner);

      return $command;
    });
  }

  /**
   * Get the services provided by the provider.
   *
   * @return array
   */
  public function provides() {
    return array('lassy', 'command.lassy.cleaner');
  }

}
