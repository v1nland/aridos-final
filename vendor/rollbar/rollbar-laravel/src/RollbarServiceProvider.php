<?php namespace Rollbar\Laravel;

use Illuminate\Support\ServiceProvider;
use InvalidArgumentException;
use Rollbar\Rollbar;
use Rollbar\Laravel\RollbarLogHandler;

class RollbarServiceProvider extends ServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    /**
     * Bootstrap the application events.
     */
    public function boot()
    {
        // Don't boot rollbar if it is not configured.
        if ($this->stop() === true) {
            return;
        }

        $app = $this->app;

        // Listen to log messages.
        $app['log']->listen(function () use ($app) {
            
            try {
                
                $args = func_get_args();
    
                // Laravel 5.4 returns a MessageLogged instance only
                if (count($args) == 1) {
                    $level = $args[0]->level;
                    $message = $args[0]->message;
                    $context = $args[0]->context;
                } else {
                    $level = $args[0];
                    $message = $args[1];
                    $context = $args[2];
                }
    
                if (strpos($message, 'Unable to send messages to Rollbar API. Produced response: ') !== false) {
                    return;
                }
    
                $result = $app[RollbarLogHandler::class]->log($level, $message, $context);
                
                if (!$result || !$result->getStatus()) {
                    \Log::error(
                        'Unable to send messages to Rollbar API. Produced response: ' .
                        print_r($result, true)
                    );
                }
            
            } catch (\Exception $exception) {
            }
        });
    }

    /**
     * Register the service provider.
     */
    public function register()
    {
        // Don't register rollbar if it is not configured.
        if ($this->stop() === true) {
            return;
        }

        $app = $this->app;

        $this->app->singleton('Rollbar\RollbarLogger', function ($app) {

            $defaults = [
                'environment'       => $app->environment(),
                'root'              => base_path(),
                'handle_exception'  => true,
                'handle_error'      => true,
                'handle_fatal'      => true,
            ];
            $config = array_merge($defaults, $app['config']->get('services.rollbar', []));
            $config['access_token'] = getenv('ROLLBAR_TOKEN') ?: $app['config']->get('services.rollbar.access_token');

            if (empty($config['access_token'])) {
                throw new InvalidArgumentException('Rollbar access token not configured');
            }

            $handleException = (bool) array_pull($config, 'handle_exception');
            $handleError = (bool) array_pull($config, 'handle_error');
            $handleFatal = (bool) array_pull($config, 'handle_fatal');

            Rollbar::init($config, $handleException, $handleError, $handleFatal);

            return Rollbar::logger();
        });

        $this->app->singleton('Rollbar\Laravel\RollbarLogHandler', function ($app) {

            $level = getenv('ROLLBAR_LEVEL') ?: $app['config']->get('services.rollbar.level', 'debug');

            return new RollbarLogHandler($app['Rollbar\RollbarLogger'], $app, $level);
        });
    }

    /**
     * Check if we should prevent the service from registering
     *
     * @return boolean
     */
    public function stop()
    {
        $level = getenv('ROLLBAR_LEVEL') ?: $this->app->config->get('services.rollbar.level', null);
        $token = getenv('ROLLBAR_TOKEN') ?: $this->app->config->get('services.rollbar.access_token', null);
        $hasToken = empty($token) === false;

        return $hasToken === false || $level === 'none';
    }
}
