<?php

namespace Dust\Providers;

use Dust\Console\Core\Commands\Database\SeedCommand;
use Dust\Console\Core\Commands\Dev\CastMakeCommand;
use Dust\Console\Core\Commands\Dev\ConsoleMakeCommand;
use Dust\Console\Core\Commands\Dev\ControllerMakeCommand;
use Dust\Console\Core\Commands\Dev\EventMakeCommand;
use Dust\Console\Core\Commands\Dev\ExceptionMakeCommand;
use Dust\Console\Core\Commands\Dev\FactoryMakeCommand;
use Dust\Console\Core\Commands\Dev\JobMakeCommand;
use Dust\Console\Core\Commands\Dev\ListenerMakeCommand;
use Dust\Console\Core\Commands\Dev\MailMakeCommand;
use Dust\Console\Core\Commands\Dev\MiddlewareMakeCommand;
use Dust\Console\Core\Commands\Dev\ModelMakeCommand;
use Dust\Console\Core\Commands\Dev\NotificationMakeCommand;
use Dust\Console\Core\Commands\Dev\ObserverMakeCommand;
use Dust\Console\Core\Commands\Dev\PolicyMakeCommand;
use Dust\Console\Core\Commands\Dev\RepositoryMakeCommand;
use Dust\Console\Core\Commands\Dev\RequestMakeCommand;
use Dust\Console\Core\Commands\Dev\ResourceMakeCommand;
use Dust\Console\Core\Commands\Dev\ResponseMakeCommand;
use Dust\Console\Core\Commands\Dev\SeederMakeCommand;
use Dust\Console\Core\Commands\Dev\ServiceMakeCommand;
use Dust\Console\Core\Commands\Dev\TestMakeCommand;
use Illuminate\Foundation\Providers\ArtisanServiceProvider as BaseArtisanServiceProvider;

class ArtisanServiceProvider extends BaseArtisanServiceProvider
{
    protected $commands = [
        'command.model.make' => ModelMakeCommand::class,
        'command.controller.make' => ControllerMakeCommand::class,
        'command.test.make' => TestMakeCommand::class,
        'command.cast.make' => CastMakeCommand::class,
        'command.console.make' => ConsoleMakeCommand::class,
        'command.event.make' => EventMakeCommand::class,
        'command.exception.make' => ExceptionMakeCommand::class,
        'command.factory.make' => FactoryMakeCommand::class,
        'command.job.make' => JobMakeCommand::class,
        'command.listener.make' => ListenerMakeCommand::class,
        'command.mail.make' => MailMakeCommand::class,
        'command.middleware.make' => MiddlewareMakeCommand::class,
        'command.notification.make' => NotificationMakeCommand::class,
        'command.observer.make' => ObserverMakeCommand::class,
        'command.policy.make' => PolicyMakeCommand::class,
        'command.request.make' => RequestMakeCommand::class,
        'command.resource.make' => ResourceMakeCommand::class,
        'command.seeder.make' => SeederMakeCommand::class,
        'command.response.make' => ResponseMakeCommand::class,
        'command.service.make' => ServiceMakeCommand::class,
        'command.repository.make' => RepositoryMakeCommand::class,
        'command.seed' => SeedCommand::class,
    ];

    public function register(): void
    {
        parent::register();
        $this->commands(
            ResponseMakeCommand::class,
            ServiceMakeCommand::class,
            RepositoryMakeCommand::class,
        );
    }
}
