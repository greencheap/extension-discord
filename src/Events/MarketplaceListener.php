<?php
namespace GreenCheap\Discord\Events;

use GreenCheap\Application as App;
use GreenCheap\Event\EventSubscriberInterface;
use GreenCheap\Discord\DiscordMessage;

/**
 * Class MarketplaceListener
 * @package GreenCheap\Discord\Events
 */
class MarketplaceListener implements EventSubscriberInterface
{
    protected $module;

    /**
     * @param object $module
     */
    public function __construct($module)
    {
        $this->module = $module;
    }

    /**
     * @param $event
     * @param $marketplace
     */
    public function onMarketplaceUpdate($event, $marketplace)
    {
        if(!$marketplace->isPublished() || !$this->module->get('config.pkgs.brainEvent.active')){
            return;
        }

        $message = new DiscordMessage();
        $message->title(__('The package has been updated:').' '.$marketplace->title)
        ->description(__('If you want to review what you wonder about package updates and other information, click on the title.'))
        ->image(App::url()->getStatic($marketplace->get('image.src') ?? false , [] , true))
        ->url(App::url('@marketplace/package/id' , ['id' => $marketplace->id] , 0))
        ->send();
    }

    /**
     * @param $event
     * @param $marketplace
     */
    public function onMarketplaceCreated($event, $marketplace)
    {
        if(!$marketplace->isPublished() || !$this->module->get('config.pkgs.brainEvent.active')){
            return;
        }

        $message = new DiscordMessage();
        $message->title(__('The package has been created:').' '.$marketplace->title)
        ->description(__('If you want to review what you wonder about package updates and other information, click on the title.'))
        ->image(App::url()->getStatic($marketplace->get('image.src') ?? false , [] , true))
        ->url(App::url('@marketplace/package/id' , ['id' => $marketplace->id] , 0))
        ->send();
    }

    /**
     * {@inheritdoc}
     */
    public function subscribe()
    {
        return [
            'model.marketplace.updated' => 'onMarketplaceUpdate',
            'model.marketplace.created' => 'onMarketplaceCreated'
        ];
    }
}
