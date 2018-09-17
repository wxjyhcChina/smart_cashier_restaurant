<?php

namespace App\Listeners\Backend\Access\User;

/**
 * Class UserEventListener.
 */
class UserEventListener
{
    /**
     * @var string
     */
    private $history_slug = 'User';

    /**
     * @param $event
     */
    public function onCreated($event)
    {

    }

    /**
     * @param $event
     */
    public function onUpdated($event)
    {

    }

    /**
     * @param $event
     */
    public function onDeleted($event)
    {

    }

    /**
     * @param $event
     */
    public function onRestored($event)
    {

    }

    /**
     * @param $event
     */
    public function onPermanentlyDeleted($event)
    {

    }

    /**
     * @param $event
     */
    public function onPasswordChanged($event)
    {

    }

    /**
     * @param $event
     */
    public function onDeactivated($event)
    {

    }

    /**
     * @param $event
     */
    public function onReactivated($event)
    {

    }

    /**
     * @param $event
     */
    public function onConfirmed($event)
    {

    }

    /**
     * @param $event
     */
    public function onUnconfirmed($event)
    {

    }

    /**
     * @param $event
     */
    public function onSocialDeleted($event)
    {

    }

    /**
     * Register the listeners for the subscriber.
     *
     * @param \Illuminate\Events\Dispatcher $events
     */
    public function subscribe($events)
    {
        $events->listen(
            \App\Events\Backend\Access\User\UserCreated::class,
            'App\Listeners\Backend\Access\User\UserEventListener@onCreated'
        );

        $events->listen(
            \App\Events\Backend\Access\User\UserUpdated::class,
            'App\Listeners\Backend\Access\User\UserEventListener@onUpdated'
        );

        $events->listen(
            \App\Events\Backend\Access\User\UserDeleted::class,
            'App\Listeners\Backend\Access\User\UserEventListener@onDeleted'
        );

        $events->listen(
            \App\Events\Backend\Access\User\UserRestored::class,
            'App\Listeners\Backend\Access\User\UserEventListener@onRestored'
        );

        $events->listen(
            \App\Events\Backend\Access\User\UserPermanentlyDeleted::class,
            'App\Listeners\Backend\Access\User\UserEventListener@onPermanentlyDeleted'
        );

        $events->listen(
            \App\Events\Backend\Access\User\UserPasswordChanged::class,
            'App\Listeners\Backend\Access\User\UserEventListener@onPasswordChanged'
        );

        $events->listen(
            \App\Events\Backend\Access\User\UserDeactivated::class,
            'App\Listeners\Backend\Access\User\UserEventListener@onDeactivated'
        );

        $events->listen(
            \App\Events\Backend\Access\User\UserReactivated::class,
            'App\Listeners\Backend\Access\User\UserEventListener@onReactivated'
        );

        $events->listen(
            \App\Events\Backend\Access\User\UserConfirmed::class,
            'App\Listeners\Backend\Access\User\UserEventListener@onConfirmed'
        );

        $events->listen(
            \App\Events\Backend\Access\User\UserUnconfirmed::class,
            'App\Listeners\Backend\Access\User\UserEventListener@onUnconfirmed'
        );

        $events->listen(
            \App\Events\Backend\Access\User\UserSocialDeleted::class,
            'App\Listeners\Backend\Access\User\UserEventListener@onSocialDeleted'
        );
    }
}
