<?php

namespace App\Entity;

final class Talk
{
    private $abstract;
    private $avatar;
    private $cues;
    private $feedbackUrl;
    private $month;
    private $pdf;
    private $resources;
    private $speaker;
    private $title;
    private $video;
    private $year;
    private $twitter;
    private $date;
    private $ticketLink;

    private function __construct()
    {
    }

    public function __set($key, $value)
    {
        throw new \BadMethodCallException('Talk is Immutable');
    }

    public function getAbstract(): string
    {
        return $this->abstract;
    }

    public function getAvatar(): string
    {
        return $this->avatar;
    }

    public function getCues(): array
    {
        return $this->cues;
    }

    public function getFeedbackUrl(): string
    {
        return $this->feedbackUrl;
    }

    public function getMonth(): string
    {
        return $this->month;
    }

    public function getPdf(): string
    {
        return sprintf('/pdfs/%s', $this->pdf);
    }

    public function getResources(): array
    {
        return $this->resources;
    }

    public function getSpeaker(): string
    {
        return $this->speaker;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getVideo(): string
    {
        return $this->video;
    }

    public function getYear(): string
    {
        return $this->year;
    }

    public function getTwitter(): string
    {
        return $this->twitter;
    }

    public function getDate(): \DateTimeImmutable
    {
        return $this->date;
    }

    public function isInTheFuture(): bool
    {
        return $this->date > (new \DateTimeImmutable('now', new \DateTimeZone('Europe/London')));
    }

    public function getTicketLink(): string
    {
        return $this->ticketLink;
    }

    public static function createFromArray(string $year, string $month, array $data): self
    {
        $date = isset($data['date'])
            ? new \DateTimeImmutable($data['date'], new \DateTimeZone('Europe/London'))
            : new \DateTimeImmutable(sprintf('%s %s', $month, $year), new \DateTimeZone('Europe/London'));

        $talk = new self();
        $talk->abstract = $data['abstract'] ?? '';
        $talk->avatar = $data['avatar'] ?? '';
        $talk->date = $date;
        $talk->feedbackUrl = $data['feedbackUrl'] ?? '';
        $talk->month = $month;
        $talk->pdf = $data['slides'] ?? '';
        $talk->resources = $data['resources'] ?? [];
        $talk->speaker = $data['speaker'];
        $talk->title = $data['title'];
        $talk->video = $data['video'] ?? '';
        $talk->twitter = str_replace('https://twitter.com/', '', isset($data['twitter']) ? $data['twitter'] : null);
        $talk->year = $year;
        $talk->ticketLink = $data['ticketLink'] ?? 'https://phpdorset.eventbrite.co.uk';

        $talk->cues = array_map(function (string $cue) {
            list($mins, $secs) = explode(':', $cue);

            return ($mins * 60) + ($secs);
        }, $data['cues'] ?? []);

        return $talk;
    }
}
