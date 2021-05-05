<?php

namespace App\Providers;

use Illuminate\Console\Scheduling\ScheduleFinishCommand;
use Illuminate\Console\Scheduling\ScheduleListCommand;
use Illuminate\Console\Scheduling\ScheduleRunCommand;
use Illuminate\Console\Scheduling\ScheduleTestCommand;
use Illuminate\Console\Scheduling\ScheduleWorkCommand;
use Illuminate\Foundation\Providers\ArtisanServiceProvider as BaseArtisanServiceProvider;

final class ArtisanServiceProvider extends BaseArtisanServiceProvider
{
    /**
     * The commands to be registered.
     *
     * @var array<string, string|class-string>
     */
    protected $commands = [
        'CacheClear' => 'command.cache.clear',
        'CacheForget' => 'command.cache.forget',
        'ClearCompiled' => 'command.clear-compiled',
        'ConfigCache' => 'command.config.cache',
        'ConfigClear' => 'command.config.clear',
        'Down' => 'command.down',
        'Environment' => 'command.environment',
        'KeyGenerate' => 'command.key.generate',
        'Optimize' => 'command.optimize',
        'OptimizeClear' => 'command.optimize.clear',
        'PackageDiscover' => 'command.package.discover',
        'RouteCache' => 'command.route.cache',
        'RouteClear' => 'command.route.clear',
        'RouteList' => 'command.route.list',
        'ScheduleFinish' => ScheduleFinishCommand::class,
        'ScheduleList' => ScheduleListCommand::class,
        'ScheduleRun' => ScheduleRunCommand::class,
        'ScheduleTest' => ScheduleTestCommand::class,
        'ScheduleWork' => ScheduleWorkCommand::class,
        'StorageLink' => 'command.storage.link',
        'Up' => 'command.up',
        'ViewCache' => 'command.view.cache',
        'ViewClear' => 'command.view.clear',
    ];

    /**
     * The commands to be registered.
     *
     * @var array<string, string>
     */
    protected $devCommands = [
        'ComponentMake' => 'command.component.make',
        'ConsoleMake' => 'command.console.make',
        'ControllerMake' => 'command.controller.make',
        'ExceptionMake' => 'command.exception.make',
        'MiddlewareMake' => 'command.middleware.make',
        'ProviderMake' => 'command.provider.make',
        'RequestMake' => 'command.request.make',
        'ResourceMake' => 'command.resource.make',
        'RuleMake' => 'command.rule.make',
        'Serve' => 'command.serve',
        'StubPublish' => 'command.stub.publish',
        'TestMake' => 'command.test.make',
        'VendorPublish' => 'command.vendor.publish',
    ];

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register(): void
    {
        if ($this->app->environment('production')) {
            $this->registerCommands($this->commands);
        } else {
            $this->registerCommands(
                array_merge(
                    $this->commands,
                    $this->devCommands
                )
            );
        }
    }
}
