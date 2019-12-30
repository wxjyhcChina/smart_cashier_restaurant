<?php

namespace App\Listeners\Backend\Access\Role;

/**
 * Class RoleEventListener.
 */
class RoleEventListener
{
    /**
     * @var string
     */
    private $history_slug = 'Role';

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
     * Register the listeners for the subscriber.
     *
     * @param \Illuminate\Events\Dispatcher $events
     */
    public function subscribe($events)
    {
        $events->listen(
            \App\Events\Backend\Access\Role\RoleCreated::class,
            'App\Listeners\Backend\Access\Role\RoleEventListener@onCreated'
        );

        $events->listen(
            \App\Events\Backend\Access\Role\RoleUpdated::class,
            'App\Listeners\Backend\Access\Role\RoleEventListener@onUpdated'
        );

        $events->listen(
            \App\Events\Backend\Access\Role\RoleDeleted::class,
            'App\Listeners\Backend\Access\Role\RoleEventListener@onDeleted'
        );
    }
}
