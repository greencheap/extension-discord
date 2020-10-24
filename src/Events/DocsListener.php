<?php
namespace GreenCheap\Discord\Events;

use GreenCheap\Application as App;
use GreenCheap\Event\EventSubscriberInterface;
use GreenCheap\Discord\DiscordMessage;

/**
 * Class DocsListener
 * @package GreenCheap\Discord\Events
 */
class DocsListener implements EventSubscriberInterface
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
     * @param $docs
     */
    public function onDocsUpdate($event, $docs)
    {
        if(!$docs->isPublished() || !$this->module->get('config.pkgs.docsEvent.active')){
            return;
        }
        
        $message = new DiscordMessage();
        $message->title(__('The document has been updated:').' '.$docs->title)
        ->description(__('A new content has been updated to this document.'))
        ->url(App::url('@docs/id', ['id' => $docs->id], 0))
        ->send();
    }

    /**
     * @param $event
     * @param $docs
     */
    public function onDocsCreated($event, $docs)
    {
        if(!$docs->isPublished() || !$this->module->get('config.pkgs.docsEvent.active')){
            return;
        }
       
        $message = new DiscordMessage();
        $message->title(__('The document has been created:').' '.$docs->title)
        ->description(__('A new content has been created to this document.'))
        ->url(App::url('@docs/id', ['id' => $docs->id], 0))
        ->send();
    }

    /**
     * {@inheritdoc}
     */
    public function subscribe()
    {
        return [
            'model.docs.updated' => 'onDocsUpdate',
            'model.docs.created' => 'onDocsCreated'
        ];
    }
}
