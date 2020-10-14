<?php
namespace GreenCheap\Discord\Events;

use GreenCheap\Application as App;
use GreenCheap\Event\EventSubscriberInterface;
use GreenCheap\Discord\DiscordMessage;

/**
 * Class BlogListener
 * @package GreenCheap\Discord\Events
 */
class BlogListener implements EventSubscriberInterface
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
     * @param $post
     */
    public function onPostUpdate($event, $post)
    {
        if(!$post->isPublished() || !$this->module->get('config.pkgs.blogEvent.active')){
            return;
        }

        $post->excerpt = App::content()->applyPlugins($post->excerpt, ['post' => $post, 'markdown' => $post->get('markdown')]);
        $post->content = App::content()->applyPlugins($post->content, ['post' => $post, 'markdown' => $post->get('markdown')]);

        $description = $post->get('meta.og:description');
        if (!$description) {
            $description = strip_tags($post->excerpt ?: $post->content);
            $description = rtrim(mb_substr($description, 0, 150), " \t\n\r\0\x0B.,") . '...';
        }

        $message = new DiscordMessage();

        $message->title($post->get('meta.og:title') ?: $post->title)
        ->description($description)
        ->image($post->get('image.src') ? App::url()->getStatic($post->get('image.src'), [], 0):null)
        ->url(App::url('@blog/id', ['id' => $post->id], 0))
        ->send();
    }

    /**
     * {@inheritdoc}
     */
    public function subscribe()
    {
        return [
            'model.post.updated' => 'onPostUpdate'
        ];
    }
}
