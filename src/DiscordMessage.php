<?php

namespace GreenCheap\Discord;

use Carbon\Carbon;
use GuzzleHttp\Client;
use GuzzleHttp\RequestOptions;
use GreenCheap\Application as App;

/**
 * Class DiscordMessage
 * @package GreenCheap\Discord
 */
class DiscordMessage
{
    const COLOR_SUCCESS = 7921272;
    const COLOR_WARNING = 14592888;
    const COLOR_ERROR = 14579832;

    /**
     * @var string
     */
    protected string $title;

    /**
     * @var string
     */
    protected string $description;

    /**
     * @var string
     */
    protected string $url;

    /**
     * @var string
     */
    protected string $image = '';

    /**
     * @var string
     */
    protected string $timestamp;

    /**
     * @var string
     */
    protected string $footer;

    /**
     * @var string
     */
    protected string $color;

    /**
     * DiscordMessage constructor.
     */
    public function __construct()
    {
        $this->color = self::COLOR_SUCCESS;
        $this->timestamp(Carbon::now());
        $this->footer('GreenCheap CMS');

    }

    /**
     * @param $title
     * @return $this
     */
    public function title($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * @param $descriptionLines
     * @return $this
     */
    public function description($descriptionLines): self
    {
        if (!is_array($descriptionLines)) {
            $descriptionLines = [$descriptionLines];
        }

        $this->description = implode(PHP_EOL, $descriptionLines);

        return $this;
    }

    /**
     * @param $url
     * @return $this
     */
    public function url($url): self
    {
        $this->url = $url;

        return $this;
    }

    /**
     * @param $image
     * @return $this
     */
    public function image($image): self
    {
        $this->image = $image;
        return $this;
    }

    /**
     * @param Carbon $carbon
     * @return $this
     */
    public function timestamp(Carbon $carbon): self
    {
        $this->timestamp = $carbon->toIso8601String();

        return $this;
    }

    /**
     * @param string $footer
     * @return $this
     */
    public function footer(string $footer): self
    {
        $this->footer = $footer;

        return $this;
    }

    /**
     * @return $this
     */
    public function success(): self
    {
        $this->color = static::COLOR_SUCCESS;

        return $this;
    }

    /**
     * @return $this
     */
    public function warning(): self
    {
        $this->color = static::COLOR_WARNING;

        return $this;
    }

    /**
     * @return $this
     */
    public function error(): self
    {
        $this->color = static::COLOR_ERROR;

        return $this;
    }

    /**
     * Client
     */
    public function send()
    {
        $module = App::module('discord');

        if(!$module->get('config.webhook_uri')){
            return App::abort(404 , __('Not Found Discord WebHook Uri'));
        }

        $hookObject = [
            'embeds' => [
                [
                    'title' => $this->title,
                    'type' => 'rich',
                    'description' => $this->description,
                    'timestamp' => $this->timestamp,
                    'color' => $this->color,
                ]
            ]
        ];

        if($this->image){
            $hookObject['embeds'][0]['image'] = [
                'url' => App::url()->getStatic($this->image , [] , false)
            ];
        }

        if($this->url){
            $hookObject['embeds'][0]['url'] = $this->url;
        }

        if($this->footer){
            $hookObject['embeds'][0]['footer'] = [
                'text' => $this->footer,
            ];
        }

        (new Client())->post($module->get('config.webhook_uri'), [
            RequestOptions::JSON => $hookObject,
        ]);
    }
}
