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
    const COLOR_SUCCESS = '#0B6623';
    const COLOR_WARNING = '#FD6A02';
    const COLOR_ERROR = '#ED2939';

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
    protected string $image;

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
    public function image($image = null): self
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
     * @return array|\array[][]
     */
    public function toArray(): array
    {
        $data = [
            'title' => $this->title,
            'type' => 'rich',
            'url' => $this->url ?? null,
            'description' => $this->description,
            'color' => hexdec($this->color),
            'footer' => [
                'text' => $this->footer ?? null,
            ],
            'timestamp' => $this->timestamp,
        ];

        if($this->image){
            $data['image'] = [
                'url' => $this->image
            ];
        }

        return [
            'embeds' => $data,
        ];
    }

    /**
     * Client
     */
    public function send() :void
    {
        try {
            $module = App::module('discord');
            if(!$module->get('config.webhook_uri')){
                throw new \Exception('Not Found WebHook Uri');
            }
            (new Client())->post($module->get('config.webhook_uri'), [
                RequestOptions::JSON => $this->toArray(),
            ]);
        } catch (\Exception $e) {
            
        }
    }
}
