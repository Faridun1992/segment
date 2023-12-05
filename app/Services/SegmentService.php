<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class SegmentService
{
    public function getSegmentRFM(): array
    {
        $token = $this->getToken();

        $segmentID = $this->getSegmentID($token);

        if ($segmentID === null) {
            return [];
        }

        $response = Http::withHeaders(['Authorization' => "Bearer $token"])
            ->get("https://app.magic-of-numbers.ru/api/report/$segmentID/run");

        return $this->AddAdditionalColumns($response->json());
    }

    private function getToken(): string
    {
        $response = Http::get('https://app.magic-of-numbers.ru/api/user/token?username=username&password=password');

        return $response->json('access_token');
    }

    private function getSegmentID(string $token): string|null
    {
        $response = Http::withHeaders(['Authorization' => "Bearer $token"])
            ->get('https://app.magic-of-numbers.ru/api/reports');

        return $this->getIDFromGetSegmentRfm($response->json('grouped.General'));
    }


    private function getIDFromGetSegmentRfm(array $reports): string|null
    {
        foreach ($reports as $report) {
            if ($report['name'] === 'get_segment_rfm') {
                return $report['id'];
            }
        }
        return null;
    }

    private function AddAdditionalColumns(array $data): array
    {
        foreach ($data['aggregations']['segments']['buckets'] as &$bucket) {
            [$name, $color, $text] = $this->mapKeyToValues($bucket['key']);

            $bucket['name'] = $name;
            $bucket['color'] = $color;
            $bucket['text'] = $text;
        }

        return $data;
    }

    private function mapKeyToValues($key): array
    {
        $mapping = [
            'Champions' => ['Чемпионы', 'blue', 'Недавно покупали, покупают часто и больше всех'],
            'Loyal' => ['Лояльные', 'green', 'Делают частые покупки на крупные суммы, реагируют на рекламы'],
            'Treb vnimaniya' => ['Требуют внимания', 'red', 'Ранее часто тратили большие суммы, но давно у вас не было'],
            'Novichki' => ['Новички', 'purple', 'Недавно покупали, низкий уровень частоты и суммы покупок'],
            'Somnevaychiesya' => ['Сомневающиеся', 'yellow', 'Показатели давности, частоты и суммы ниже среднего'],
            'Spiachie' => ['Спящие', 'gray', 'Последняя покупка была давно. Низкие чеки и кол-вл покупок'],
        ];

        return $mapping[$key] ?? ['', '', ''];
    }
}
