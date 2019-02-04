<?php

namespace App\Repo;

use App\Entity\Talk;

final class TalkRepository
{
    /**
     * @var array[year][month][id]
     */
    private $talks;

    public function __construct()
    {
        $this->talks = json_decode(file_get_contents(__DIR__.'/../../resources/talks.json'), true);
    }

    public function fetchTalks(string $year, string $month): array
    {
        $month = strtolower($month);

        if (!isset($this->talks[$year][$month])) {
            return [];
        }

        $talks = $this->talks[$year][$month];

        if (!isset($talks)) {
            return [];
        }

        return array_map(
            function ($talk) use ($year, $month) {
                return Talk::createFromArray($year, $month, $talk);
            },
            $talks
        );
    }

    public function fetchTalk(string $year, string $month, string $key): Talk
    {
        $month = strtolower($month);

        $talk = $this->talks[$year][$month][$key];

        if (!isset($talk)) {
            return null;
        }

        return Talk::createFromArray($year, $month, $talk);
    }

    public function fetchAll(): array
    {
        return $this->talks;
    }
}
