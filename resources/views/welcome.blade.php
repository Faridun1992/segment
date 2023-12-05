<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @vite('resources/css/app.css')

</head>
<body>
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 items-center justify-between">
    @foreach($segmentRFM['aggregations']['segments']['buckets'] as $segment)
    <div class="bg-white p-4 rounded-lg shadow-md">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-gray-600 text-lg font-semibold">{{$segment['name']}}</h2>
            <h3 class="relative z-10 rounded-full bg-{{$segment['color']}}-500 px-3 py-1.5 font-medium text-white hover:bg-{{$segment['color']}}-100">Всего {{round(($segment['doc_count'] * 100)/$segmentRFM['total'])}}%</h3>
        </div>
        <p class="flex mb-2">{{$segment['text']}}</p>
        <div class="flex items-center mb-2">
            <p class="relative z-10 rounded-full bg-gray-50 px-3 py-1.5 font-medium text-gray-600 hover:bg-gray-100">Кол-во клиентов: {{$segment['doc_count']}}</p>
        </div>
        <div class="flex items-center mb-2">
            <p class="relative z-10 rounded-full bg-gray-50 px-3 py-1.5 font-medium text-gray-600 hover:bg-gray-100">Сумма заказов: {{$segment['total']['value']}} ₽</p>
        </div>
        <div class="flex items-center mb-2">
            <p class="relative z-10 rounded-full bg-gray-50 px-3 py-1.5 font-medium text-gray-600 hover:bg-gray-100">Средний чек: {{round($segment['total']['value']/$segment['orders']['value'])}} ₽</p>
        </div>
    </div>
    @endforeach
</div>

</body>
</html>
