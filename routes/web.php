<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

function redirectRequest()
{
    $title = $_SERVER['REQUEST_METHOD'];
    $content = file_get_contents('php://input');
    $body = json_decode($content, true);
    if (is_null($body)) {
        $body = $content;
    }
    $url = "https://api.pushbullet.com/v2/pushes";
    $data = [
        'type' => 'note',
        'title' => $title,
        'body' => $body,
    ];
    $data = json_encode($data);
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        "Authorization: Bearer o.AUOG5PIRZsy8ZsXOzAWjNnsYTpnseCn4",
        "Content-Type: application/json",
        'Content-Length: ' . strlen($data)
    ]);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_exec($ch);
    curl_close($ch);
}

$botApi = new \TelegramBot\Api\BotApi(env('BOT_TOKEN'));

$app->get('/1', function () use ($app, $botApi) {
    $botApi = new \TelegramBot\Api\BotApi(env('BOT_TOKEN'));

    $botApi->sendInvoice(
        67852056, // ID получателя, взять его можно во время входящего вебхука от этого пользователя
        'Powerball', // Название продукта
        'Новая модель Powerball Classic. От старой модели отличается обновленным дизайном: стильный синий корпус, белый ротор, новое модерн лого.', // Описание продукта
        'powerball-classic', // Пишем свою внутренюю ID платежа, что либо по этому значению потом будете понимать что покупают
        env('PAYMENT_PROVIDER_TOKEN'), // Токен платежной системы, получить можно у @Botfather
        'pb-classic', // Начальный параметр который будет использован для генерации платежа
        'UAH', // Валюта в которой будет оплачиваться товар
        [
            ['label' => 'Powerball 250Hz Classic Blue', 'amount' => 55100], //Массив цен, пользователь выберет только одну цену
            ['label' => 'Powerball 250Hz Pro Blue', 'amount' => 79600],
            ['label' => 'Powerball 280Hz Autostart', 'amount' => 146400]
        ]
    );
});

$app->get('/2', function () use ($app, $botApi) {
    $botApi->sendInvoice(
        67852056, // ID получателя, взять его можно во время входящего вебхука от этого пользователя
        'Powerball', // Название продукта
        'Новая модель Powerball Classic. От старой модели отличается обновленным дизайном: стильный синий корпус, белый ротор, новое модерн лого.', // Описание продукта
        'powerball-classic', // Пишем свою внутренюю ID платежа, что либо по этому значению потом будете понимать что покупают
        env('PAYMENT_PROVIDER_TOKEN'), // Токен платежной системы, получить можно у @Botfather
        'pb-classic', // Начальный параметр который будет использован для генерации платежа
        'UAH', // Валюта в которой будет оплачиваться товар
        [
            ['label' => 'Powerball 250Hz Classic Blue', 'amount' => 55100] //Массив цен, общая сумма продукта будет суммироваться из цен этого массива
        ],
        true, // Указываем в том случае если цена может измениться в зависимости от доставки,
        'https://golloscdn.com/20565/Prod/2408816/powerball-classic-blue2.jpg' // Фото продукта
    );
});

$app->post('/', function () use ($app, $botApi) {
    redirectRequest();

    try {
        $bot = new \TelegramBot\Api\Client(env('BOT_TOKEN'));

        $bot->shippingQuery(function ($query) use ($bot, $botApi) {
            //Собираем информацию о способах доставки и ценах, делаем что-то ещё и отправляем ответ:
            $botApi->answerShippingQuery(
                $query->getId(), // уникальное id query который пришел на вебхук
                true, // отвечаем что всё хорошо и отправляем массив вариантов доставки
                [
                    [
                        'id' => 'np',
                        'title' => 'Новая почта',
                        'prices' => [
                            [
                                'label' => 'На склад',
                                'amount' => 4000
                            ]
                        ]
                    ],
                    [
                        'id' => 'up',
                        'title' => 'Укрпочта',
                        'prices' => [
                            [
                                'label' => 'В отделение',
                                'amount' => 2000
                            ]
                        ]
                    ]
                ]);
        });

        $bot->preCheckoutQuery(function ($query) use ($bot, $botApi) {
            $botApi->answerPreCheckoutQuery($query->getId(), true); //Говорим что всё хорошо, товар есть на складе и готовы отправить
        });

        $bot->run();

    } catch (\TelegramBot\Api\Exception $e) {
        $e->getMessage();
    }

    return 'post';
});

$app->get('/', function () use ($app) {
    redirectRequest();

    return 'get';
});
