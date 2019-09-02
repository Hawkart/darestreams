---
title: DareStreams API

language_tabs:
- php

includes:

search: true

---
<!-- START_INFO -->
# Info

Darestreams API v1.

<!-- END_INFO -->

#Auth
<!-- START_c3fa189a6c95ca36ad6ac4791a873d23 -->
## Handle a login request to the application.

> Example request:

```php

$client = new \GuzzleHttp\Client();
$response = $client->post("https://darestreams.com/api/login", [
    'headers' => [
            "Content-Type" => "application/json",
        ],
    'json' => [
            "email" => "example@example.ru",
            "password" => "jadfohasd092",
        ],
]);
$body = $response->getBody();
print_r(json_decode((string) $body));
```




### HTTP Request
`POST api/login`

#### Body Parameters

Parameter | Type | Status | Description
--------- | ------- | ------- | ------- | -----------
    email | string |  required  | User's email.
    password | string |  required  | User's password.

<!-- END_c3fa189a6c95ca36ad6ac4791a873d23 -->

<!-- START_d7b7952e7fdddc07c978c9bdaf757acf -->
## Handle a registration request for the application.

> Example request:

```php

$client = new \GuzzleHttp\Client();
$response = $client->post("https://darestreams.com/api/register", [
    'headers' => [
            "Content-Type" => "application/json",
        ],
    'json' => [
            "name" => "Archibald",
            "nickname" => "Archi89",
            "email" => "example@example.ru",
            "password" => "jadfohasd092",
            "lang" => "ru",
        ],
]);
$body = $response->getBody();
print_r(json_decode((string) $body));
```




### HTTP Request
`POST api/register`

#### Body Parameters

Parameter | Type | Status | Description
--------- | ------- | ------- | ------- | -----------
    name | string |  required  | User's name.
    nickname | string |  required  | User's nickname.
    email | string |  required  | User's email.
    password | string |  required  | User's password.
    lang | string |  required  | User's lang.

<!-- END_d7b7952e7fdddc07c978c9bdaf757acf -->

<!-- START_b7802a3a2092f162a21dc668479801f4 -->
## Send a reset link to the given user.

> Example request:

```php

$client = new \GuzzleHttp\Client();
$response = $client->post("https://darestreams.com/api/password/email", [
    'headers' => [
            "Content-Type" => "application/json",
        ],
    'json' => [
            "email" => "example@example.ru",
        ],
]);
$body = $response->getBody();
print_r(json_decode((string) $body));
```




### HTTP Request
`POST api/password/email`

#### Body Parameters

Parameter | Type | Status | Description
--------- | ------- | ------- | ------- | -----------
    email | string |  required  | User's email.

<!-- END_b7802a3a2092f162a21dc668479801f4 -->

<!-- START_8ad860d24dc1cc6dac772d99135ad13e -->
## Reset the given user&#039;s password.

> Example request:

```php

$client = new \GuzzleHttp\Client();
$response = $client->post("https://darestreams.com/api/password/reset", [
    'headers' => [
            "Content-Type" => "application/json",
        ],
    'json' => [
            "token" => "adnf981nsdvuohnlansdou1nj1",
            "email" => "example@example.ru",
            "password" => "jadfohasd092",
            "password_confirmation" => "jadfohasd092",
        ],
]);
$body = $response->getBody();
print_r(json_decode((string) $body));
```




### HTTP Request
`POST api/password/reset`

#### Body Parameters

Parameter | Type | Status | Description
--------- | ------- | ------- | ------- | -----------
    token | string |  required  | Token for operation.
    email | string |  required  | User's email.
    password | string |  required  | User's password.
    password_confirmation | string |  required  | User's password confirmation.

<!-- END_8ad860d24dc1cc6dac772d99135ad13e -->

<!-- START_4462ebc3daf169e23a09dc2b53459f02 -->
## Redirect the user to the provider authentication page.

{driver} - social provider: facebook, twitch, youtube, steam, discord. Example: twitch

> Example request:

```php

$client = new \GuzzleHttp\Client();
$response = $client->get("https://darestreams.com/api/oauth/1", [
]);
$body = $response->getBody();
print_r(json_decode((string) $body));
```



> Example response (200):

```json
[]
```
> Example response (404):

```json
[]
```

### HTTP Request
`GET api/oauth/{driver}`


<!-- END_4462ebc3daf169e23a09dc2b53459f02 -->

<!-- START_a327c0048fdbf1b4b697176ccfb4772d -->
## Obtain the user information from the provider.

{driver} - social provider: facebook, twitch, youtube, steam, discord, streamlabs

> Example request:

```php

$client = new \GuzzleHttp\Client();
$response = $client->get("https://darestreams.com/api/oauth/1/callback", [
]);
$body = $response->getBody();
print_r(json_decode((string) $body));
```



> Example response (200):

```json
[]
```
> Example response (404):

```json
[]
```

### HTTP Request
`GET api/oauth/{driver}/callback`


<!-- END_a327c0048fdbf1b4b697176ccfb4772d -->

<!-- START_4ac1f2974a517f5ccedee031d014865f -->
## Mark the user&#039;s email address as verified.

{user} - integer id of user in database.

> Example request:

```php

$client = new \GuzzleHttp\Client();
$response = $client->post("https://darestreams.com/api/email/verify/1", [
    'query' => [
            "signature" => "ce3f1f0f9f8ad3196f6157dd6f4e732c5d48ac46260cfcb7c24fe2615fb66567",
            "expires" => "1565160796",
        ],
]);
$body = $response->getBody();
print_r(json_decode((string) $body));
```




### HTTP Request
`POST api/email/verify/{user}`

#### Query Parameters

Parameter | Status | Description
--------- | ------- | ------- | -----------
    signature |  required  | The signature for verifying.
    expires |  required  | The time verify link will expired.

<!-- END_4ac1f2974a517f5ccedee031d014865f -->

<!-- START_007d2c80092c02b58e6bfecd510a3282 -->
## Resend the email verification notification.

> Example request:

```php

$client = new \GuzzleHttp\Client();
$response = $client->post("https://darestreams.com/api/email/resend", [
    'headers' => [
            "Content-Type" => "application/json",
        ],
    'json' => [
            "email" => "example@example.ru",
        ],
]);
$body = $response->getBody();
print_r(json_decode((string) $body));
```




### HTTP Request
`POST api/email/resend`

#### Body Parameters

Parameter | Type | Status | Description
--------- | ------- | ------- | ------- | -----------
    email | string |  required  | User's email.

<!-- END_007d2c80092c02b58e6bfecd510a3282 -->

<!-- START_61739f3220a224b34228600649230ad1 -->
## Log the user out of the application.

> Example request:

```php

$client = new \GuzzleHttp\Client();
$response = $client->post("https://darestreams.com/api/logout", [
]);
$body = $response->getBody();
print_r(json_decode((string) $body));
```




### HTTP Request
`POST api/logout`


<!-- END_61739f3220a224b34228600649230ad1 -->

#Channels
<!-- START_22b9daea8f2cab0035f861dfe7469fdc -->
## Get top channels

> Example request:

```php

$client = new \GuzzleHttp\Client();
$response = $client->get("https://darestreams.com/api/channels/top", [
    'query' => [
            "hours" => "autem",
            "limit" => "nostrum",
            "skip" => "voluptas",
            "game_id" => "unde",
            "include" => "user,streams",
        ],
]);
$body = $response->getBody();
print_r(json_decode((string) $body));
```



> Example response (200):

```json
[]
```
> Example response (404):

```json
[]
```

### HTTP Request
`GET api/channels/top`

#### Query Parameters

Parameter | Status | Description
--------- | ------- | ------- | -----------
    hours |  optional  | Integer Check amount donations sum for last N hours. Default: 240.
    limit |  optional  | Integer. Limit of top channels. Default: 10.
    skip |  optional  | Integer. Offset of top channels. Default: 0.
    game_id |  optional  | Integer. Filter channels by category.
    include |  optional  | string String of connections: user, streams, tags, game.

<!-- END_22b9daea8f2cab0035f861dfe7469fdc -->

<!-- START_49dde38880772c46db114f63233b9c8d -->
## Display a listing of the resource.

> Example request:

```php

$client = new \GuzzleHttp\Client();
$response = $client->get("https://darestreams.com/api/channels", [
    'query' => [
            "include" => "user,streams",
            "sort" => "-id",
            "page" => "20",
        ],
]);
$body = $response->getBody();
print_r(json_decode((string) $body));
```



> Example response (200):

```json
{
    "data": [
        {
            "id": 199,
            "user_id": 221,
            "title": "dtfru",
            "link": "https:\/\/www.twitch.tv\/dtfru",
            "game_id": 16,
            "slug": "dtfru",
            "description": "Frostpunk DLC | Владимир Акиньшин",
            "provider": "twitch",
            "views": 324,
            "logo": "https:\/\/static-cdn.jtvnw.net\/jtv_user_pictures\/79ae6951ee0bedba-profile_image-300x300.jpeg",
            "overlay": "https:\/\/static-cdn.jtvnw.net\/jtv_user_pictures\/dtfru-channel_offline_image-3fb66df368fc561f-1920x1080.png",
            "created_at": "2019-08-30T05:40:28.000000Z",
            "user": null,
            "streams": []
        },
        {
            "id": 197,
            "user_id": 220,
            "title": "violettavalery",
            "link": "https:\/\/www.twitch.tv\/violettavalery",
            "game_id": 17,
            "slug": "violettavalery",
            "description": "👄ASMR - Relax and Tingles ✨Сигна за саб✨ !INSTA !YT !VK !TINGLES !инфо",
            "provider": "twitch",
            "views": 783,
            "logo": "https:\/\/static-cdn.jtvnw.net\/jtv_user_pictures\/aea78f81-2550-4e08-9ae4-90374a969ac9-profile_image-300x300.jpg",
            "overlay": "https:\/\/static-cdn.jtvnw.net\/jtv_user_pictures\/a4a0c1f55ac3c514-channel_offline_image-1920x1080.jpeg",
            "created_at": "2019-08-30T05:40:27.000000Z",
            "user": null,
            "streams": []
        },
        {
            "id": 195,
            "user_id": 211,
            "title": "tenderlybae",
            "link": "https:\/\/www.twitch.tv\/tenderlybae",
            "game_id": 20,
            "slug": "tenderlybae",
            "description": "ты грызешь леденец",
            "provider": "twitch",
            "views": 491,
            "logo": "https:\/\/static-cdn.jtvnw.net\/jtv_user_pictures\/6be91ff6-cd90-4797-89b1-e89329a44ce8-profile_image-300x300.png",
            "overlay": "https:\/\/static-cdn.jtvnw.net\/jtv_user_pictures\/8c2ef276-31df-4e17-b285-2ca852ac9c92-channel_offline_image-1920x1080.png",
            "created_at": "2019-08-30T05:40:27.000000Z",
            "user": null,
            "streams": []
        },
        {
            "id": 193,
            "user_id": 210,
            "title": "bloody_elf",
            "link": "https:\/\/www.twitch.tv\/bloody_elf",
            "game_id": 3,
            "slug": "bloody-elf",
            "description": "алко💙1 SUB = 1 стопка🌈 !inst 🌈",
            "provider": "twitch",
            "views": 720,
            "logo": "https:\/\/static-cdn.jtvnw.net\/jtv_user_pictures\/93a81954-d99c-4699-aae3-839b6f06867d-profile_image-300x300.jpeg",
            "overlay": "https:\/\/static-cdn.jtvnw.net\/jtv_user_pictures\/6d096ca623fa08ab-channel_offline_image-1920x1080.jpeg",
            "created_at": "2019-08-30T05:40:26.000000Z",
            "user": null,
            "streams": []
        },
        {
            "id": 191,
            "user_id": 196,
            "title": "unique",
            "link": "https:\/\/www.twitch.tv\/unique",
            "game_id": 7,
            "slug": "unique",
            "description": "Unique Streamers Party",
            "provider": "twitch",
            "views": 727,
            "logo": "https:\/\/static-cdn.jtvnw.net\/jtv_user_pictures\/3ff3fa1c-6631-45b7-95e5-3f18ea0e193a-profile_image-300x300.png",
            "overlay": "http:\/\/darestreams.local\/storage\/games\/7663437150b3baa11035e65e8484804c.jpg",
            "created_at": "2019-08-30T05:40:26.000000Z",
            "user": null,
            "streams": []
        },
        {
            "id": 189,
            "user_id": 187,
            "title": "olesha",
            "link": "https:\/\/www.twitch.tv\/olesha",
            "game_id": 5,
            "slug": "olesha",
            "description": "Тсс, стрим спит, не шумите",
            "provider": "twitch",
            "views": 427,
            "logo": "https:\/\/static-cdn.jtvnw.net\/jtv_user_pictures\/133bc45d-2835-482b-a390-6d198e272095-profile_image-300x300.png",
            "overlay": "https:\/\/static-cdn.jtvnw.net\/jtv_user_pictures\/88a05b9bbf4eb3a7-channel_offline_image-1920x1080.jpeg",
            "created_at": "2019-08-30T05:40:26.000000Z",
            "user": null,
            "streams": []
        },
        {
            "id": 187,
            "user_id": 184,
            "title": "exbc",
            "link": "https:\/\/www.twitch.tv\/exbc",
            "game_id": 18,
            "slug": "exbc",
            "description": "fishing & bbq busan korea",
            "provider": "twitch",
            "views": 965,
            "logo": "https:\/\/static-cdn.jtvnw.net\/jtv_user_pictures\/exbc-profile_image-b123ccd6d2990eaa-300x300.jpeg",
            "overlay": "https:\/\/static-cdn.jtvnw.net\/jtv_user_pictures\/67961818-beff-4c8a-950c-a0eac91f7c36-channel_offline_image-1920x1080.jpeg",
            "created_at": "2019-08-30T05:40:25.000000Z",
            "user": null,
            "streams": []
        },
        {
            "id": 185,
            "user_id": 181,
            "title": "beastqt",
            "link": "https:\/\/www.twitch.tv\/beastqt",
            "game_id": 12,
            "slug": "beastqt",
            "description": "Качаем Рогу ✔️",
            "provider": "twitch",
            "views": 965,
            "logo": "https:\/\/static-cdn.jtvnw.net\/jtv_user_pictures\/8db75fae-6138-4f16-ab18-a78bee03b8b0-profile_image-300x300.png",
            "overlay": "https:\/\/static-cdn.jtvnw.net\/jtv_user_pictures\/965345555c5a246c-channel_offline_image-1920x1080.jpeg",
            "created_at": "2019-08-30T05:40:25.000000Z",
            "user": null,
            "streams": []
        },
        {
            "id": 183,
            "user_id": 180,
            "title": "tati",
            "link": "https:\/\/www.twitch.tv\/tati",
            "game_id": 16,
            "slug": "tati",
            "description": "boosted | instagram.com\/rijmij",
            "provider": "twitch",
            "views": 391,
            "logo": "https:\/\/static-cdn.jtvnw.net\/jtv_user_pictures\/f40c1600-3127-4cf0-882b-3d09528bc738-profile_image-300x300.png",
            "overlay": "https:\/\/static-cdn.jtvnw.net\/jtv_user_pictures\/1ed13eaf-47c6-4e4b-a38f-6fe54e833065-channel_offline_image-1920x1080.jpeg",
            "created_at": "2019-08-30T05:40:25.000000Z",
            "user": null,
            "streams": []
        },
        {
            "id": 181,
            "user_id": 178,
            "title": "5live_bgd",
            "link": "https:\/\/www.twitch.tv\/5live_bgd",
            "game_id": 9,
            "slug": "5live-bgd",
            "description": "⚡",
            "provider": "twitch",
            "views": 12,
            "logo": "https:\/\/static-cdn.jtvnw.net\/jtv_user_pictures\/a622c7ef-9ccc-4a81-ab9f-5c7efd2a457f-profile_image-300x300.jpg",
            "overlay": "https:\/\/static-cdn.jtvnw.net\/jtv_user_pictures\/5live_bgd-channel_offline_image-7502b83dd593ca79-1920x1080.jpeg",
            "created_at": "2019-08-30T05:40:24.000000Z",
            "user": null,
            "streams": []
        },
        {
            "id": 179,
            "user_id": 176,
            "title": "gavrilka",
            "link": "https:\/\/www.twitch.tv\/gavrilka",
            "game_id": 5,
            "slug": "gavrilka",
            "description": "She is on fire (sunburn) 🌞",
            "provider": "twitch",
            "views": 583,
            "logo": "https:\/\/static-cdn.jtvnw.net\/jtv_user_pictures\/90531b062846dd9a-profile_image-300x300.jpeg",
            "overlay": "https:\/\/static-cdn.jtvnw.net\/jtv_user_pictures\/1bbb4719427c3c30-channel_offline_image-1920x1080.jpeg",
            "created_at": "2019-08-30T05:40:24.000000Z",
            "user": null,
            "streams": []
        },
        {
            "id": 177,
            "user_id": 171,
            "title": "yoda",
            "link": "https:\/\/www.twitch.tv\/yoda",
            "game_id": 2,
            "slug": "yoda",
            "description": "TESTE 10 A VOLTA DO BIRITRAB",
            "provider": "twitch",
            "views": 955,
            "logo": "https:\/\/static-cdn.jtvnw.net\/jtv_user_pictures\/yoda-profile_image-63cdc656c9f91fb4-300x300.jpeg",
            "overlay": "https:\/\/static-cdn.jtvnw.net\/jtv_user_pictures\/b3b6d1fa-1aa5-470a-9a23-c435e300de68-channel_offline_image-1920x1080.png",
            "created_at": "2019-08-30T05:40:24.000000Z",
            "user": null,
            "streams": []
        },
        {
            "id": 175,
            "user_id": 166,
            "title": "morphia",
            "link": "https:\/\/www.twitch.tv\/morphia",
            "game_id": 13,
            "slug": "morphia",
            "description": "P E A C E",
            "provider": "twitch",
            "views": 3,
            "logo": "https:\/\/static-cdn.jtvnw.net\/jtv_user_pictures\/ba8501f8-5978-429a-abe8-9996e03e0189-profile_image-300x300.png",
            "overlay": "https:\/\/static-cdn.jtvnw.net\/jtv_user_pictures\/911ff4f6-2659-484e-aef4-e4eb01ccb0ef-channel_offline_image-1920x1080.jpeg",
            "created_at": "2019-08-30T05:40:23.000000Z",
            "user": null,
            "streams": []
        },
        {
            "id": 173,
            "user_id": 165,
            "title": "lorinefairy",
            "link": "https:\/\/www.twitch.tv\/lorinefairy",
            "game_id": 4,
            "slug": "lorinefairy",
            "description": "Чилл под инт",
            "provider": "twitch",
            "views": 896,
            "logo": "https:\/\/static-cdn.jtvnw.net\/jtv_user_pictures\/90568fa8-57fc-48df-87e8-c02aead4972b-profile_image-300x300.jpeg",
            "overlay": "https:\/\/static-cdn.jtvnw.net\/jtv_user_pictures\/c02bab13-2689-406b-8162-7cf4fbf9ccc4-channel_offline_image-1920x1080.jpeg",
            "created_at": "2019-08-30T05:40:23.000000Z",
            "user": null,
            "streams": []
        },
        {
            "id": 171,
            "user_id": 164,
            "title": "ahrinyan",
            "link": "https:\/\/www.twitch.tv\/ahrinyan",
            "game_id": 8,
            "slug": "ahrinyan",
            "description": "Поставила камеру вместо вебки !youtube !inst Чекайте инфу под стримом про рулетку",
            "provider": "twitch",
            "views": 317,
            "logo": "https:\/\/static-cdn.jtvnw.net\/jtv_user_pictures\/637e3960-e09a-476a-8ab1-8c87f09d26ce-profile_image-300x300.png",
            "overlay": "https:\/\/static-cdn.jtvnw.net\/jtv_user_pictures\/7ca52dda-4d4a-4f3d-8740-eea8dc5be465-channel_offline_image-1920x1080.png",
            "created_at": "2019-08-30T05:40:23.000000Z",
            "user": null,
            "streams": []
        },
        {
            "id": 169,
            "user_id": 158,
            "title": "gladiatorpwnz",
            "link": "https:\/\/www.twitch.tv\/gladiatorpwnz",
            "game_id": 8,
            "slug": "gladiatorpwnz",
            "description": "422",
            "provider": "twitch",
            "views": 476,
            "logo": "http:\/\/darestreams.local\/storage\/games\/c56630a2d8e079b098c112599399b2b2.jpg",
            "overlay": "http:\/\/darestreams.local\/storage\/games\/c56630a2d8e079b098c112599399b2b2.jpg",
            "created_at": "2019-08-30T05:40:22.000000Z",
            "user": null,
            "streams": []
        },
        {
            "id": 167,
            "user_id": 156,
            "title": "shroud",
            "link": "https:\/\/www.twitch.tv\/shroud",
            "game_id": 2,
            "slug": "shroud",
            "description": "Orc Shaman @ Faerlina | @shroud on socials for updates",
            "provider": "twitch",
            "views": 260,
            "logo": "https:\/\/static-cdn.jtvnw.net\/jtv_user_pictures\/7ed5e0c6-0191-4eef-8328-4af6e4ea5318-profile_image-300x300.png",
            "overlay": "https:\/\/static-cdn.jtvnw.net\/jtv_user_pictures\/f328a514-0cda-4239-9f99-24670b7ed7cb-channel_offline_image-1920x1080.png",
            "created_at": "2019-08-30T05:40:22.000000Z",
            "user": null,
            "streams": []
        },
        {
            "id": 165,
            "user_id": 151,
            "title": "ciklonica",
            "link": "https:\/\/www.twitch.tv\/ciklonica",
            "game_id": 16,
            "slug": "ciklonica",
            "description": "[RU\/ENG] HELLO👅",
            "provider": "twitch",
            "views": 636,
            "logo": "https:\/\/static-cdn.jtvnw.net\/jtv_user_pictures\/8dcb9543-3a88-4232-b0b9-2d51c56166c7-profile_image-300x300.jpg",
            "overlay": "https:\/\/static-cdn.jtvnw.net\/jtv_user_pictures\/ee8b7603-9a10-446e-9a28-60fed109c4fa-channel_offline_image-1920x1080.jpeg",
            "created_at": "2019-08-30T05:40:22.000000Z",
            "user": null,
            "streams": []
        },
        {
            "id": 163,
            "user_id": 147,
            "title": "playbetterpro",
            "link": "https:\/\/www.twitch.tv\/playbetterpro",
            "game_id": 6,
            "slug": "playbetterpro",
            "description": "ПАТРЕОН - ХОРОШО ИЛИ ПЛОХО?",
            "provider": "twitch",
            "views": 36,
            "logo": "https:\/\/static-cdn.jtvnw.net\/jtv_user_pictures\/89608be4-4225-4d35-a615-cce0532f320c-profile_image-300x300.png",
            "overlay": "https:\/\/static-cdn.jtvnw.net\/jtv_user_pictures\/a8fc25c9-ea22-49c7-bd74-5b5d6762710e-channel_offline_image-1920x1080.png",
            "created_at": "2019-08-30T05:40:21.000000Z",
            "user": null,
            "streams": []
        },
        {
            "id": 161,
            "user_id": 143,
            "title": "nemagiaru",
            "link": "https:\/\/www.twitch.tv\/nemagiaru",
            "game_id": 14,
            "slug": "nemagiaru",
            "description": "Ооо! Стримчанский! ツ [19.08.2019]",
            "provider": "twitch",
            "views": 451,
            "logo": "https:\/\/static-cdn.jtvnw.net\/jtv_user_pictures\/0266b7e7-1f6b-4267-8c85-9e7b6bbe7797-profile_image-300x300.jpg",
            "overlay": "https:\/\/static-cdn.jtvnw.net\/jtv_user_pictures\/nemagiaru-channel_offline_image-e7e2e7be51411eb8-1920x1080.jpeg",
            "created_at": "2019-08-30T05:40:21.000000Z",
            "user": null,
            "streams": []
        },
        {
            "id": 159,
            "user_id": 140,
            "title": "msmaggiezolin",
            "link": "https:\/\/www.twitch.tv\/msmaggiezolin",
            "game_id": 6,
            "slug": "msmaggiezolin",
            "description": "топ геймер Даша пабге ин зэ ворлд",
            "provider": "twitch",
            "views": 912,
            "logo": "https:\/\/static-cdn.jtvnw.net\/jtv_user_pictures\/1e44bba2-83c9-4eb9-bb0c-1ffe559e7b82-profile_image-300x300.png",
            "overlay": "https:\/\/static-cdn.jtvnw.net\/jtv_user_pictures\/64805459-c7b3-4e1f-828d-69edd65de5d7-channel_offline_image-1920x1080.jpeg",
            "created_at": "2019-08-30T05:40:21.000000Z",
            "user": null,
            "streams": []
        },
        {
            "id": 157,
            "user_id": 139,
            "title": "gabepeixe",
            "link": "https:\/\/www.twitch.tv\/gabepeixe",
            "game_id": 11,
            "slug": "gabepeixe",
            "description": "EU FNX JON JEFFAO E LUHZERAA  VS SHEVI  !INSTAGRAM",
            "provider": "twitch",
            "views": 161,
            "logo": "https:\/\/static-cdn.jtvnw.net\/jtv_user_pictures\/e81441ba-4786-4628-b9bb-77098e4a917f-profile_image-300x300.png",
            "overlay": "https:\/\/static-cdn.jtvnw.net\/jtv_user_pictures\/402daa6b-eff9-4c5d-8abe-5462ec056e4b-channel_offline_image-1920x1080.jpeg",
            "created_at": "2019-08-30T05:40:20.000000Z",
            "user": null,
            "streams": []
        },
        {
            "id": 155,
            "user_id": 137,
            "title": "elwycco",
            "link": "https:\/\/www.twitch.tv\/elwycco",
            "game_id": 14,
            "slug": "elwycco",
            "description": "Живой,опять",
            "provider": "twitch",
            "views": 672,
            "logo": "https:\/\/static-cdn.jtvnw.net\/jtv_user_pictures\/612cfd00-b73e-429d-931b-e218a77e8b40-profile_image-300x300.jpg",
            "overlay": "https:\/\/static-cdn.jtvnw.net\/jtv_user_pictures\/5d61bb90-ee84-4f68-b68b-b182200903c0-channel_offline_image-1920x1080.jpeg",
            "created_at": "2019-08-30T05:40:20.000000Z",
            "user": null,
            "streams": []
        },
        {
            "id": 153,
            "user_id": 129,
            "title": "gufovicky",
            "link": "https:\/\/www.twitch.tv\/gufovicky",
            "game_id": 10,
            "slug": "gufovicky",
            "description": "Не ну размялся можно и по вебке подглядывать ",
            "provider": "twitch",
            "views": 296,
            "logo": "https:\/\/static-cdn.jtvnw.net\/jtv_user_pictures\/gufovicky-profile_image-48074644a6341ab3-300x300.jpeg",
            "overlay": "https:\/\/static-cdn.jtvnw.net\/jtv_user_pictures\/gufovicky-channel_offline_image-705cf58394f848ed-1920x1080.jpeg",
            "created_at": "2019-08-30T05:40:19.000000Z",
            "user": null,
            "streams": []
        },
        {
            "id": 151,
            "user_id": 127,
            "title": "by_owl",
            "link": "https:\/\/www.twitch.tv\/by_owl",
            "game_id": 3,
            "slug": "by-owl",
            "description": "🌿 relax",
            "provider": "twitch",
            "views": 636,
            "logo": "https:\/\/static-cdn.jtvnw.net\/jtv_user_pictures\/3f7fc92d-f9f4-46bc-90d2-dcf8bc86a3cf-profile_image-300x300.png",
            "overlay": "https:\/\/static-cdn.jtvnw.net\/jtv_user_pictures\/bbe39a63-4af0-4435-9347-abebed0fbda4-channel_offline_image-1920x1080.jpg",
            "created_at": "2019-08-30T05:40:19.000000Z",
            "user": null,
            "streams": []
        },
        {
            "id": 149,
            "user_id": 126,
            "title": "hardgamechannel",
            "link": "https:\/\/www.twitch.tv\/hardgamechannel",
            "game_id": 8,
            "slug": "hardgamechannel",
            "description": "МОЙ ПЕРВЫЙ РАЗ #1  . Escape From Tarkov",
            "provider": "twitch",
            "views": 421,
            "logo": "https:\/\/static-cdn.jtvnw.net\/jtv_user_pictures\/a863789b-a8c0-44f3-88ed-6da7afc5aea9-profile_image-300x300.png",
            "overlay": "https:\/\/static-cdn.jtvnw.net\/jtv_user_pictures\/4d18d388-a1c3-47ae-8b67-8579814b9d47-channel_offline_image-1920x1080.png",
            "created_at": "2019-08-30T05:40:19.000000Z",
            "user": null,
            "streams": []
        },
        {
            "id": 147,
            "user_id": 125,
            "title": "modestal",
            "link": "https:\/\/www.twitch.tv\/modestal",
            "game_id": 12,
            "slug": "modestal",
            "description": "У АНДРЕЯ ПРОПАЛО ЯЙЦО",
            "provider": "twitch",
            "views": 400,
            "logo": "https:\/\/static-cdn.jtvnw.net\/jtv_user_pictures\/1c49e85f-765d-46cf-afb0-d446a57d3f43-profile_image-300x300.jpeg",
            "overlay": "https:\/\/static-cdn.jtvnw.net\/jtv_user_pictures\/3d0cfd5c-8bff-47a4-afdc-70ef9012ffc9-channel_offline_image-1920x1080.jpeg",
            "created_at": "2019-08-30T05:40:18.000000Z",
            "user": null,
            "streams": []
        },
        {
            "id": 145,
            "user_id": 117,
            "title": "kuplinov",
            "link": "https:\/\/www.twitch.tv\/kuplinov",
            "game_id": 19,
            "slug": "kuplinov",
            "description": "...",
            "provider": "twitch",
            "views": 403,
            "logo": "https:\/\/static-cdn.jtvnw.net\/jtv_user_pictures\/f4ca1d69-9eee-45a2-8509-079a3e5630df-profile_image-300x300.jpg",
            "overlay": "https:\/\/static-cdn.jtvnw.net\/jtv_user_pictures\/0e17368b48dce82a-channel_offline_image-1920x1080.jpeg",
            "created_at": "2019-08-30T05:40:18.000000Z",
            "user": null,
            "streams": []
        },
        {
            "id": 143,
            "user_id": 115,
            "title": "scr3amqueen",
            "link": "https:\/\/www.twitch.tv\/scr3amqueen",
            "game_id": 4,
            "slug": "scr3amqueen",
            "description": "[ENG] lurking at Tenerife",
            "provider": "twitch",
            "views": 790,
            "logo": "https:\/\/static-cdn.jtvnw.net\/jtv_user_pictures\/7d817865-3258-40b1-a110-6493f8c11842-profile_image-300x300.png",
            "overlay": "https:\/\/static-cdn.jtvnw.net\/jtv_user_pictures\/36f781ca-c6ac-49b2-88a4-18366e83e750-channel_offline_image-1920x1080.jpeg",
            "created_at": "2019-08-30T05:40:18.000000Z",
            "user": null,
            "streams": []
        },
        {
            "id": 141,
            "user_id": 112,
            "title": "jamclub",
            "link": "https:\/\/www.twitch.tv\/jamclub",
            "game_id": 12,
            "slug": "jamclub",
            "description": "ANIME ll Гинтама (Gintama)",
            "provider": "twitch",
            "views": 200,
            "logo": "https:\/\/static-cdn.jtvnw.net\/jtv_user_pictures\/jamclub-profile_image-ec49fac812d1cd96-300x300.jpeg",
            "overlay": "https:\/\/static-cdn.jtvnw.net\/jtv_user_pictures\/eb4613d1-8d1d-4b34-8a9d-ef6026022dbb-channel_offline_image-1920x1080.png",
            "created_at": "2019-08-30T05:40:17.000000Z",
            "user": null,
            "streams": []
        },
        {
            "id": 139,
            "user_id": 108,
            "title": "punshipun",
            "link": "https:\/\/www.twitch.tv\/punshipun",
            "game_id": 16,
            "slug": "punshipun",
            "description": "планирую переезд: смотрим недвижимость Эквадора,гуляем по гуглмапс и ОвЕрвочЧ",
            "provider": "twitch",
            "views": 165,
            "logo": "https:\/\/static-cdn.jtvnw.net\/jtv_user_pictures\/punshipun-profile_image-85520d4db8eca213-300x300.png",
            "overlay": "https:\/\/static-cdn.jtvnw.net\/jtv_user_pictures\/punshipun-channel_offline_image-05dda8dde6227467-1920x1080.jpeg",
            "created_at": "2019-08-30T05:40:17.000000Z",
            "user": null,
            "streams": []
        },
        {
            "id": 137,
            "user_id": 105,
            "title": "morganrandom",
            "link": "https:\/\/www.twitch.tv\/morganrandom",
            "game_id": 7,
            "slug": "morganrandom",
            "description": "АЛЬЯНС КРУТА",
            "provider": "twitch",
            "views": 792,
            "logo": "https:\/\/static-cdn.jtvnw.net\/jtv_user_pictures\/1acd64bc-7ea1-48c0-9b73-91b5066ec7d9-profile_image-300x300.png",
            "overlay": "https:\/\/static-cdn.jtvnw.net\/jtv_user_pictures\/61511f745a5c60d7-channel_offline_image-1920x1080.png",
            "created_at": "2019-08-30T05:40:17.000000Z",
            "user": null,
            "streams": []
        },
        {
            "id": 135,
            "user_id": 100,
            "title": "happasc2",
            "link": "https:\/\/www.twitch.tv\/happasc2",
            "game_id": 4,
            "slug": "happasc2",
            "description": "Спорный Контент под чутким надзором Добрых Зрителей! !info !кино",
            "provider": "twitch",
            "views": 795,
            "logo": "https:\/\/static-cdn.jtvnw.net\/jtv_user_pictures\/happasc2-profile_image-e9e00117c2df65ba-300x300.jpeg",
            "overlay": "https:\/\/static-cdn.jtvnw.net\/jtv_user_pictures\/817323e1-df5e-4f0e-b0b1-114c9584df3d-channel_offline_image-1920x1080.jpeg",
            "created_at": "2019-08-30T05:40:16.000000Z",
            "user": null,
            "streams": []
        },
        {
            "id": 133,
            "user_id": 99,
            "title": "nelyaray",
            "link": "https:\/\/www.twitch.tv\/nelyaray",
            "game_id": 13,
            "slug": "nelyaray",
            "description": "эволюционируем | !группа !инст \n",
            "provider": "twitch",
            "views": 216,
            "logo": "https:\/\/static-cdn.jtvnw.net\/jtv_user_pictures\/7fd9e482-342b-48d5-bdbc-a183ccbf0467-profile_image-300x300.png",
            "overlay": "https:\/\/static-cdn.jtvnw.net\/jtv_user_pictures\/384c48cf-fc51-4fdc-9749-0ce644a902a5-channel_offline_image-1920x1080.jpeg",
            "created_at": "2019-08-30T05:40:16.000000Z",
            "user": null,
            "streams": []
        },
        {
            "id": 131,
            "user_id": 98,
            "title": "tiggra",
            "link": "https:\/\/www.twitch.tv\/tiggra",
            "game_id": 19,
            "slug": "tiggra",
            "description": "(RU\\ENG) играем в крокодила🐅",
            "provider": "twitch",
            "views": 908,
            "logo": "https:\/\/static-cdn.jtvnw.net\/jtv_user_pictures\/6102b2ec-ea4c-4e41-8d80-fa61138086b5-profile_image-300x300.png",
            "overlay": "https:\/\/static-cdn.jtvnw.net\/jtv_user_pictures\/cff1be17-ca93-4a85-89c7-a0c7aa30ecc8-channel_offline_image-1920x1080.jpeg",
            "created_at": "2019-08-30T05:40:16.000000Z",
            "user": null,
            "streams": []
        },
        {
            "id": 129,
            "user_id": 97,
            "title": "murochka_ua",
            "link": "https:\/\/www.twitch.tv\/murochka_ua",
            "game_id": 17,
            "slug": "murochka-ua",
            "description": "тест",
            "provider": "twitch",
            "views": 787,
            "logo": "https:\/\/static-cdn.jtvnw.net\/jtv_user_pictures\/cd908e25-1d06-413d-ab49-49c9898c025c-profile_image-300x300.jpeg",
            "overlay": "https:\/\/static-cdn.jtvnw.net\/jtv_user_pictures\/murochka_ua-channel_offline_image-3a3e444b0c77cd65-1920x1080.jpeg",
            "created_at": "2019-08-30T05:40:15.000000Z",
            "user": null,
            "streams": []
        },
        {
            "id": 127,
            "user_id": 91,
            "title": "kittyklawtv",
            "link": "https:\/\/www.twitch.tv\/kittyklawtv",
            "game_id": 15,
            "slug": "kittyklawtv",
            "description": "✨Болтаем, общаемся и потом ASMR💖",
            "provider": "twitch",
            "views": 974,
            "logo": "https:\/\/static-cdn.jtvnw.net\/jtv_user_pictures\/kittyklawtv-profile_image-d1c53a9c1240f586-300x300.png",
            "overlay": "https:\/\/static-cdn.jtvnw.net\/jtv_user_pictures\/4dc6584e-6789-4788-ae63-941ac5269009-channel_offline_image-1920x1080.png",
            "created_at": "2019-08-30T05:40:15.000000Z",
            "user": null,
            "streams": []
        },
        {
            "id": 125,
            "user_id": 88,
            "title": "dariya_willis",
            "link": "https:\/\/www.twitch.tv\/dariya_willis",
            "game_id": 5,
            "slug": "dariya-willis",
            "description": "2-й стрим по игре [18+]",
            "provider": "twitch",
            "views": 247,
            "logo": "https:\/\/static-cdn.jtvnw.net\/jtv_user_pictures\/fbbc296f-70d7-47ad-8683-d85055f2ceef-profile_image-300x300.jpeg",
            "overlay": "https:\/\/static-cdn.jtvnw.net\/jtv_user_pictures\/9b094e53-aa57-410d-aa08-3e33feb0f900-channel_offline_image-1920x1080.png",
            "created_at": "2019-08-30T05:40:14.000000Z",
            "user": null,
            "streams": []
        },
        {
            "id": 123,
            "user_id": 87,
            "title": "texaswildlife",
            "link": "https:\/\/www.twitch.tv\/texaswildlife",
            "game_id": 5,
            "slug": "texaswildlife",
            "description": "ASMR Sounds of the Texas Hill Country Darkness! SUBS & VIP Controlled Cams! Feeder Bandits & Peaceful Sounds to help you #relax #sleep #ASMR",
            "provider": "twitch",
            "views": 97,
            "logo": "https:\/\/static-cdn.jtvnw.net\/jtv_user_pictures\/f313ac88-0a5b-4013-bbd4-34f831939d3b-profile_image-300x300.png",
            "overlay": "https:\/\/static-cdn.jtvnw.net\/jtv_user_pictures\/fadd1b64-df7b-43b5-b13e-19e479096091-channel_offline_image-1920x1080.jpeg",
            "created_at": "2019-08-30T05:40:14.000000Z",
            "user": null,
            "streams": []
        },
        {
            "id": 121,
            "user_id": 86,
            "title": "revnyasha",
            "link": "https:\/\/www.twitch.tv\/revnyasha",
            "game_id": 19,
            "slug": "revnyasha",
            "description": "И снова трейдинг ♡ Trading crypto",
            "provider": "twitch",
            "views": 527,
            "logo": "https:\/\/static-cdn.jtvnw.net\/jtv_user_pictures\/9fba2ff8-12d4-471b-b0a0-8a2d1f51f28c-profile_image-300x300.jpg",
            "overlay": "https:\/\/static-cdn.jtvnw.net\/jtv_user_pictures\/a7a5b6aa5d0465a9-channel_offline_image-1920x1080.png",
            "created_at": "2019-08-30T05:40:14.000000Z",
            "user": null,
            "streams": []
        },
        {
            "id": 119,
            "user_id": 84,
            "title": "busya18plus",
            "link": "https:\/\/www.twitch.tv\/busya18plus",
            "game_id": 13,
            "slug": "busya18plus",
            "description": "Бусинка под градусом.Шальная императрица ",
            "provider": "twitch",
            "views": 74,
            "logo": "https:\/\/static-cdn.jtvnw.net\/jtv_user_pictures\/6b89133d-1d39-4e3d-8214-f00a19b9d828-profile_image-300x300.png",
            "overlay": "https:\/\/static-cdn.jtvnw.net\/jtv_user_pictures\/855fbba1-fb01-41e6-8c33-6a5ec53ce498-channel_offline_image-1920x1080.jpeg",
            "created_at": "2019-08-30T05:40:13.000000Z",
            "user": null,
            "streams": []
        },
        {
            "id": 117,
            "user_id": 82,
            "title": "gagatun",
            "link": "https:\/\/www.twitch.tv\/gagatun",
            "game_id": 19,
            "slug": "gagatun",
            "description": "ЗАВТРА КИБЕРПАНК!",
            "provider": "twitch",
            "views": 1,
            "logo": "https:\/\/static-cdn.jtvnw.net\/jtv_user_pictures\/d51f7463-8770-4fd1-bbf1-d4dcad7f6475-profile_image-300x300.png",
            "overlay": "https:\/\/static-cdn.jtvnw.net\/jtv_user_pictures\/ca5d169f-9e3a-4382-b560-d89fab040e98-channel_offline_image-1920x1080.png",
            "created_at": "2019-08-30T05:40:13.000000Z",
            "user": null,
            "streams": []
        },
        {
            "id": 115,
            "user_id": 81,
            "title": "stpeach",
            "link": "https:\/\/www.twitch.tv\/stpeach",
            "game_id": 7,
            "slug": "stpeach",
            "description": "I WANT GOLD (ELO) SO BAD :( !video",
            "provider": "twitch",
            "views": 409,
            "logo": "https:\/\/static-cdn.jtvnw.net\/jtv_user_pictures\/f8b8c774-e34d-40d9-ba21-9a81cfda73aa-profile_image-300x300.png",
            "overlay": "https:\/\/static-cdn.jtvnw.net\/jtv_user_pictures\/47385bfd-223f-4bc3-8e74-19a967b13eea-channel_offline_image-1920x1080.png",
            "created_at": "2019-08-30T05:40:13.000000Z",
            "user": null,
            "streams": []
        },
        {
            "id": 113,
            "user_id": 79,
            "title": "noway4u_sir",
            "link": "https:\/\/www.twitch.tv\/noway4u_sir",
            "game_id": 13,
            "slug": "noway4u-sir",
            "description": "Bonobocontent",
            "provider": "twitch",
            "views": 335,
            "logo": "https:\/\/static-cdn.jtvnw.net\/jtv_user_pictures\/9e619d88755f56a8-profile_image-300x300.png",
            "overlay": "https:\/\/static-cdn.jtvnw.net\/jtv_user_pictures\/noway4u_sir-channel_offline_image-ac5619d4e71d4525-1920x1080.jpeg",
            "created_at": "2019-08-30T05:40:12.000000Z",
            "user": null,
            "streams": []
        },
        {
            "id": 111,
            "user_id": 78,
            "title": "jointime",
            "link": "https:\/\/www.twitch.tv\/jointime",
            "game_id": 5,
            "slug": "jointime",
            "description": "Индиана Джонс Марафон",
            "provider": "twitch",
            "views": 45,
            "logo": "https:\/\/static-cdn.jtvnw.net\/jtv_user_pictures\/0e8323d8-05e3-4097-9699-7fd1f02fe3ae-profile_image-300x300.jpg",
            "overlay": "https:\/\/static-cdn.jtvnw.net\/jtv_user_pictures\/5d21242e-da90-4d81-9907-0e0cb02c3423-channel_offline_image-1920x1080.png",
            "created_at": "2019-08-30T05:40:12.000000Z",
            "user": null,
            "streams": []
        },
        {
            "id": 109,
            "user_id": 77,
            "title": "leniniw",
            "link": "https:\/\/www.twitch.tv\/leniniw",
            "game_id": 6,
            "slug": "leniniw",
            "description": "Подводим итоги конкурса #МейджорЛайкБаттл и делаем прогнозы на Мажор",
            "provider": "twitch",
            "views": 51,
            "logo": "https:\/\/static-cdn.jtvnw.net\/jtv_user_pictures\/2d1cc6cf-8628-4c52-9b42-b966b49db350-profile_image-300x300.jpg",
            "overlay": "https:\/\/static-cdn.jtvnw.net\/jtv_user_pictures\/leniniw-channel_offline_image-9195fddcfb45d40c-1920x1080.png",
            "created_at": "2019-08-30T05:40:11.000000Z",
            "user": null,
            "streams": []
        },
        {
            "id": 107,
            "user_id": 72,
            "title": "asmr_kotya",
            "link": "https:\/\/www.twitch.tv\/asmr_kotya",
            "game_id": 8,
            "slug": "asmr-kotya",
            "description": "rerun💙💙new video on !YT❤️ASMR🎧💤💙Котятерапия💖Kotyatherapy💖!social !latestvid",
            "provider": "twitch",
            "views": 946,
            "logo": "https:\/\/static-cdn.jtvnw.net\/jtv_user_pictures\/1a5944f7-1f9a-4d47-b372-6a6d3726c5fd-profile_image-300x300.png",
            "overlay": "https:\/\/static-cdn.jtvnw.net\/jtv_user_pictures\/2c783ec1-78aa-44c2-ae9d-1d49fb32fa6c-channel_offline_image-1920x1080.jpeg",
            "created_at": "2019-08-30T05:40:11.000000Z",
            "user": null,
            "streams": []
        },
        {
            "id": 105,
            "user_id": 70,
            "title": "tangerin",
            "link": "https:\/\/www.twitch.tv\/tangerin",
            "game_id": 19,
            "slug": "tangerin",
            "description": "Привет",
            "provider": "twitch",
            "views": 444,
            "logo": "https:\/\/static-cdn.jtvnw.net\/jtv_user_pictures\/f89545a5-9abc-4aa7-bfa7-c499bd035fa9-profile_image-300x300.png",
            "overlay": "https:\/\/static-cdn.jtvnw.net\/jtv_user_pictures\/e0d79786c8136aca-channel_offline_image-1920x1080.png",
            "created_at": "2019-08-30T05:40:11.000000Z",
            "user": null,
            "streams": []
        },
        {
            "id": 103,
            "user_id": 69,
            "title": "dawgdebik",
            "link": "https:\/\/www.twitch.tv\/dawgdebik",
            "game_id": 7,
            "slug": "dawgdebik",
            "description": "Запустил",
            "provider": "twitch",
            "views": 296,
            "logo": "https:\/\/static-cdn.jtvnw.net\/jtv_user_pictures\/9109d26d-cb7d-4a8d-a04f-763694b79c55-profile_image-300x300.png",
            "overlay": "https:\/\/static-cdn.jtvnw.net\/jtv_user_pictures\/91ed23c4-2112-4e8c-94ff-e24229de4c65-channel_offline_image-1920x1080.jpeg",
            "created_at": "2019-08-30T05:40:10.000000Z",
            "user": null,
            "streams": []
        },
        {
            "id": 101,
            "user_id": 67,
            "title": "theveronicous",
            "link": "https:\/\/www.twitch.tv\/theveronicous",
            "game_id": 16,
            "slug": "theveronicous",
            "description": "🖤Да, у меня синдром Туретта, И ЧО?🖤",
            "provider": "twitch",
            "views": 996,
            "logo": "https:\/\/static-cdn.jtvnw.net\/jtv_user_pictures\/3ada49c8-9489-45f6-9e20-c1cf26d2ec17-profile_image-300x300.png",
            "overlay": "https:\/\/static-cdn.jtvnw.net\/jtv_user_pictures\/cf968964-bc89-472f-9502-e9e38748c55b-channel_offline_image-1920x1080.png",
            "created_at": "2019-08-30T05:40:10.000000Z",
            "user": null,
            "streams": []
        },
        {
            "id": 99,
            "user_id": 65,
            "title": "insize",
            "link": "https:\/\/www.twitch.tv\/insize",
            "game_id": 20,
            "slug": "insize",
            "description": "Одна игра интереснее другой 4Head \/ inst @the.insize",
            "provider": "twitch",
            "views": 797,
            "logo": "https:\/\/static-cdn.jtvnw.net\/jtv_user_pictures\/41730834-e1f7-4c62-8e29-af9d2a81257f-profile_image-300x300.png",
            "overlay": "https:\/\/static-cdn.jtvnw.net\/jtv_user_pictures\/779e5d826c15b402-channel_offline_image-1920x1080.jpeg",
            "created_at": "2019-08-30T05:40:10.000000Z",
            "user": null,
            "streams": []
        },
        {
            "id": 97,
            "user_id": 63,
            "title": "ksyasha",
            "link": "https:\/\/www.twitch.tv\/ksyasha",
            "game_id": 16,
            "slug": "ksyasha",
            "description": "FiveStar 2: промокод \"ksyasha\"",
            "provider": "twitch",
            "views": 869,
            "logo": "https:\/\/static-cdn.jtvnw.net\/jtv_user_pictures\/06a07db8-0f54-4be4-886b-c3681dd9b631-profile_image-300x300.png",
            "overlay": "https:\/\/static-cdn.jtvnw.net\/jtv_user_pictures\/ksyasha-channel_offline_image-1450ac95e15276c4-1920x1080.jpeg",
            "created_at": "2019-08-30T05:40:09.000000Z",
            "user": null,
            "streams": []
        },
        {
            "id": 95,
            "user_id": 60,
            "title": "johnylemonade",
            "link": "https:\/\/www.twitch.tv\/johnylemonade",
            "game_id": 10,
            "slug": "johnylemonade",
            "description": "Cooking Stream | Медовик",
            "provider": "twitch",
            "views": 707,
            "logo": "https:\/\/static-cdn.jtvnw.net\/jtv_user_pictures\/cbf52947-1541-44cc-9ea4-17839774b417-profile_image-300x300.jpeg",
            "overlay": "https:\/\/static-cdn.jtvnw.net\/jtv_user_pictures\/bbb39ea4fdd8a6bc-channel_offline_image-1920x1080.png",
            "created_at": "2019-08-30T05:40:09.000000Z",
            "user": null,
            "streams": []
        },
        {
            "id": 93,
            "user_id": 58,
            "title": "hellyeahplay",
            "link": "https:\/\/www.twitch.tv\/hellyeahplay",
            "game_id": 16,
            "slug": "hellyeahplay",
            "description": "💩 ЗАХОДИ. ВИДОСИКИ, ЛЮБОВЬ 💩",
            "provider": "twitch",
            "views": 18,
            "logo": "https:\/\/static-cdn.jtvnw.net\/jtv_user_pictures\/hellyeahplay-profile_image-7b27caab4aefe1ad-300x300.png",
            "overlay": "https:\/\/static-cdn.jtvnw.net\/jtv_user_pictures\/hellyeahplay-channel_offline_image-17cf79d8aa5eb833-1920x1080.jpeg",
            "created_at": "2019-08-30T05:40:09.000000Z",
            "user": null,
            "streams": []
        },
        {
            "id": 91,
            "user_id": 57,
            "title": "fruktozka",
            "link": "https:\/\/www.twitch.tv\/fruktozka",
            "game_id": 11,
            "slug": "fruktozka",
            "description": "Если не будешь занят, спасешь меня от апокалипсиса?",
            "provider": "twitch",
            "views": 950,
            "logo": "https:\/\/static-cdn.jtvnw.net\/jtv_user_pictures\/311f4e3d-8a75-45bb-868f-026e8191fd54-profile_image-300x300.png",
            "overlay": "https:\/\/static-cdn.jtvnw.net\/jtv_user_pictures\/6e84f8f09b497bec-channel_offline_image-1920x1080.jpeg",
            "created_at": "2019-08-30T05:40:08.000000Z",
            "user": null,
            "streams": []
        },
        {
            "id": 89,
            "user_id": 51,
            "title": "gaules",
            "link": "https:\/\/www.twitch.tv\/gaules",
            "game_id": 18,
            "slug": "gaules",
            "description": "MAJOR BERLIN NEW LEGENDS STAGE 2019 DIA2 (RERUN) - MIBR vs G2 SEXTA - PORTÕES ABERTOS 6am - DROP DE SUB A TODO MOMENTO",
            "provider": "twitch",
            "views": 576,
            "logo": "https:\/\/static-cdn.jtvnw.net\/jtv_user_pictures\/c495b77e-7f47-4bc5-a216-3045d7545796-profile_image-300x300.png",
            "overlay": "https:\/\/static-cdn.jtvnw.net\/jtv_user_pictures\/3de00e1ebbe194c8-channel_offline_image-1920x1080.png",
            "created_at": "2019-08-30T05:40:08.000000Z",
            "user": null,
            "streams": []
        },
        {
            "id": 87,
            "user_id": 50,
            "title": "ameriahime",
            "link": "https:\/\/www.twitch.tv\/ameriahime",
            "game_id": 9,
            "slug": "ameriahime",
            "description": "Стрим имени СТАРОГО НОСКА :D + Джаст Дэнс :3",
            "provider": "twitch",
            "views": 75,
            "logo": "https:\/\/static-cdn.jtvnw.net\/jtv_user_pictures\/118e9195-fac0-4914-a5fc-ba8b0595e541-profile_image-300x300.jpg",
            "overlay": "https:\/\/static-cdn.jtvnw.net\/jtv_user_pictures\/aea9ebf1-6259-4119-8979-caa43bacb961-channel_offline_image-1920x1080.png",
            "created_at": "2019-08-30T05:40:07.000000Z",
            "user": null,
            "streams": []
        },
        {
            "id": 85,
            "user_id": 49,
            "title": "sholidays",
            "link": "https:\/\/www.twitch.tv\/sholidays",
            "game_id": 16,
            "slug": "sholidays",
            "description": "STREAM HOLIDAYS RETRO - 3 ДЕНЬ",
            "provider": "twitch",
            "views": 960,
            "logo": "https:\/\/static-cdn.jtvnw.net\/jtv_user_pictures\/c9eecb3c-6394-4c08-99d3-2e770652675d-profile_image-300x300.png",
            "overlay": "https:\/\/static-cdn.jtvnw.net\/jtv_user_pictures\/bba88573-5f80-4bc2-8666-251c97ad6db4-channel_offline_image-1920x1080.png",
            "created_at": "2019-08-30T05:40:07.000000Z",
            "user": null,
            "streams": []
        },
        {
            "id": 83,
            "user_id": 48,
            "title": "thethomasavengers",
            "link": "https:\/\/www.twitch.tv\/thethomasavengers",
            "game_id": 5,
            "slug": "thethomasavengers",
            "description": "СМОТРЮ СВОЮ СВАДЬБУ",
            "provider": "twitch",
            "views": 692,
            "logo": "https:\/\/static-cdn.jtvnw.net\/jtv_user_pictures\/818e0f3c-d0d2-4a0b-aa5f-cac4c44fbb23-profile_image-300x300.jpg",
            "overlay": "https:\/\/static-cdn.jtvnw.net\/jtv_user_pictures\/fb971033-d18d-4501-a958-4daf1409d9d0-channel_offline_image-1920x1080.jpg",
            "created_at": "2019-08-30T05:40:06.000000Z",
            "user": null,
            "streams": []
        },
        {
            "id": 81,
            "user_id": 47,
            "title": "yuki2yuki",
            "link": "https:\/\/www.twitch.tv\/yuki2yuki",
            "game_id": 2,
            "slug": "yuki2yuki",
            "description": "-> здесь могла быть ваша реклама",
            "provider": "twitch",
            "views": 662,
            "logo": "https:\/\/static-cdn.jtvnw.net\/jtv_user_pictures\/d22e7494-53ea-44b3-a6bb-c2f31689aa2b-profile_image-300x300.jpg",
            "overlay": "https:\/\/static-cdn.jtvnw.net\/jtv_user_pictures\/6a31afde-8f15-47f5-a18e-eb3b899dfa8f-channel_offline_image-1920x1080.png",
            "created_at": "2019-08-30T05:40:05.000000Z",
            "user": null,
            "streams": []
        },
        {
            "id": 79,
            "user_id": 46,
            "title": "mihalina_",
            "link": "https:\/\/www.twitch.tv\/mihalina_",
            "game_id": 11,
            "slug": "mihalina",
            "description": "ПОСЛЕДНИЙ СТРИМ НА ЭТОЙ НЕДЕЛЕ",
            "provider": "twitch",
            "views": 70,
            "logo": "https:\/\/static-cdn.jtvnw.net\/jtv_user_pictures\/8d39e01d-87cd-4a48-84ca-3f378153c0ac-profile_image-300x300.png",
            "overlay": "https:\/\/static-cdn.jtvnw.net\/jtv_user_pictures\/70139f30-1bcc-45c8-b5e9-2150e0568b99-channel_offline_image-1920x1080.png",
            "created_at": "2019-08-30T05:40:05.000000Z",
            "user": null,
            "streams": []
        },
        {
            "id": 77,
            "user_id": 44,
            "title": "amouranth",
            "link": "https:\/\/www.twitch.tv\/amouranth",
            "game_id": 1,
            "slug": "amouranth",
            "description": "WARCRAFT ASMR [l i v e] use headphones!  🔔 SUBS GET SNAPCHAT",
            "provider": "twitch",
            "views": 732,
            "logo": "https:\/\/static-cdn.jtvnw.net\/jtv_user_pictures\/ac3ae8d2-2a06-4c41-9e92-2de8e20b29c4-profile_image-300x300.png",
            "overlay": "https:\/\/static-cdn.jtvnw.net\/jtv_user_pictures\/2509e087-402b-4d18-a813-d9bd478b92c1-channel_offline_image-1920x1080.png",
            "created_at": "2019-08-30T05:40:04.000000Z",
            "user": null,
            "streams": []
        },
        {
            "id": 75,
            "user_id": 43,
            "title": "segall",
            "link": "https:\/\/www.twitch.tv\/segall",
            "game_id": 20,
            "slug": "segall",
            "description": "ХЕЛП!!! 1 СЕНТЯБРЯ НА ВОСКРЕСЕНЬЕ ВЫПАДАЕТ, В ШКОЛУ НАДО???",
            "provider": "twitch",
            "views": 252,
            "logo": "https:\/\/static-cdn.jtvnw.net\/jtv_user_pictures\/862ca8f7-79e6-4f73-8614-3c1352206c14-profile_image-300x300.png",
            "overlay": "https:\/\/static-cdn.jtvnw.net\/jtv_user_pictures\/6691089d523a60a2-channel_offline_image-1920x1080.jpeg",
            "created_at": "2019-08-30T05:40:04.000000Z",
            "user": null,
            "streams": []
        },
        {
            "id": 73,
            "user_id": 42,
            "title": "rootyasha",
            "link": "https:\/\/www.twitch.tv\/rootyasha",
            "game_id": 14,
            "slug": "rootyasha",
            "description": "The Great Perhaps  !youtube  !футболка",
            "provider": "twitch",
            "views": 428,
            "logo": "https:\/\/static-cdn.jtvnw.net\/jtv_user_pictures\/8b7965cc-f6dd-46ce-93af-555dbc9da6f0-profile_image-300x300.png",
            "overlay": "https:\/\/static-cdn.jtvnw.net\/jtv_user_pictures\/56ffe36d-2bb9-4bf0-9e01-31d266fc0aee-channel_offline_image-1920x1080.jpeg",
            "created_at": "2019-08-30T05:40:03.000000Z",
            "user": null,
            "streams": []
        },
        {
            "id": 71,
            "user_id": 41,
            "title": "jesusavgn",
            "link": "https:\/\/www.twitch.tv\/jesusavgn",
            "game_id": 14,
            "slug": "jesusavgn",
            "description": "Мобильная трансляция jesusavgn",
            "provider": "twitch",
            "views": 843,
            "logo": "https:\/\/static-cdn.jtvnw.net\/jtv_user_pictures\/jesusavgn-profile_image-ef60f6d58af4ccef-300x300.jpeg",
            "overlay": "https:\/\/static-cdn.jtvnw.net\/jtv_user_pictures\/jesusavgn-channel_offline_image-d6fde5154b88da29-1920x1080.jpeg",
            "created_at": "2019-08-30T05:40:03.000000Z",
            "user": null,
            "streams": []
        },
        {
            "id": 69,
            "user_id": 39,
            "title": "steel",
            "link": "https:\/\/www.twitch.tv\/steel",
            "game_id": 7,
            "slug": "steel",
            "description": "фильм ДЖОННИ Д",
            "provider": "twitch",
            "views": 736,
            "logo": "https:\/\/static-cdn.jtvnw.net\/jtv_user_pictures\/295dbffa-2e51-4298-a079-61db41d12ebe-profile_image-300x300.png",
            "overlay": "https:\/\/static-cdn.jtvnw.net\/jtv_user_pictures\/9718c5fe-9e54-4b17-994e-e73cf5934408-channel_offline_image-1920x1080.jpeg",
            "created_at": "2019-08-30T05:40:02.000000Z",
            "user": null,
            "streams": []
        },
        {
            "id": 67,
            "user_id": 38,
            "title": "eveliinushka",
            "link": "https:\/\/www.twitch.tv\/eveliinushka",
            "game_id": 9,
            "slug": "eveliinushka",
            "description": "алло, да, ето ева",
            "provider": "twitch",
            "views": 378,
            "logo": "https:\/\/static-cdn.jtvnw.net\/jtv_user_pictures\/cc5c9a1c-4eb9-4d7a-8419-3244bcce9d73-profile_image-300x300.jpg",
            "overlay": "https:\/\/static-cdn.jtvnw.net\/jtv_user_pictures\/94055e6de1c34e26-channel_offline_image-1920x1080.jpeg",
            "created_at": "2019-08-30T05:40:02.000000Z",
            "user": null,
            "streams": []
        },
        {
            "id": 65,
            "user_id": 37,
            "title": "yuuechka",
            "link": "https:\/\/www.twitch.tv\/yuuechka",
            "game_id": 18,
            "slug": "yuuechka",
            "description": "иногда хочется просто.......\n",
            "provider": "twitch",
            "views": 283,
            "logo": "https:\/\/static-cdn.jtvnw.net\/jtv_user_pictures\/ae06ca58-36e2-460c-b985-fdd2229f3ce8-profile_image-300x300.png",
            "overlay": "https:\/\/static-cdn.jtvnw.net\/jtv_user_pictures\/e7c677d8-e2a0-4ec7-be89-3bb741677f00-channel_offline_image-1920x1080.jpeg",
            "created_at": "2019-08-30T05:40:02.000000Z",
            "user": null,
            "streams": []
        },
        {
            "id": 63,
            "user_id": 36,
            "title": "stopannya",
            "link": "https:\/\/www.twitch.tv\/stopannya",
            "game_id": 18,
            "slug": "stopannya",
            "description": "ЛИЛО И СТИЧ 1.10 ♡ СИГНА ЗА САБ И ПРАЙМ ♡ !youtube !inst (´｡• ᵕ •｡`)  ",
            "provider": "twitch",
            "views": 279,
            "logo": "https:\/\/static-cdn.jtvnw.net\/jtv_user_pictures\/039f5d88-155e-43ca-a806-a5264583c31b-profile_image-300x300.png",
            "overlay": "https:\/\/static-cdn.jtvnw.net\/jtv_user_pictures\/04f4cd3c-667c-45a3-8813-d5b4a71fcab1-channel_offline_image-1920x1080.png",
            "created_at": "2019-08-30T05:40:01.000000Z",
            "user": null,
            "streams": []
        },
        {
            "id": 61,
            "user_id": 35,
            "title": "adam1tbc",
            "link": "https:\/\/www.twitch.tv\/adam1tbc",
            "game_id": 1,
            "slug": "adam1tbc",
            "description": "123",
            "provider": "twitch",
            "views": 563,
            "logo": "https:\/\/static-cdn.jtvnw.net\/jtv_user_pictures\/59c1b675-0442-4c17-b7f1-216a527acc00-profile_image-300x300.png",
            "overlay": "https:\/\/static-cdn.jtvnw.net\/jtv_user_pictures\/21cca7e8-9f85-4643-ae2a-cb7d3e2bfa6d-channel_offline_image-1920x1080.jpg",
            "created_at": "2019-08-30T05:40:01.000000Z",
            "user": null,
            "streams": []
        },
        {
            "id": 59,
            "user_id": 34,
            "title": "promotive",
            "link": "https:\/\/www.twitch.tv\/promotive",
            "game_id": 13,
            "slug": "promotive",
            "description": "Собачья жизнь 2",
            "provider": "twitch",
            "views": 807,
            "logo": "https:\/\/static-cdn.jtvnw.net\/jtv_user_pictures\/91b4f99b-b9ab-4ee6-a314-330f75db755d-profile_image-300x300.png",
            "overlay": "https:\/\/static-cdn.jtvnw.net\/jtv_user_pictures\/promotive-channel_offline_image-dc39be92d722f578-1920x1080.png",
            "created_at": "2019-08-30T05:40:01.000000Z",
            "user": null,
            "streams": []
        },
        {
            "id": 57,
            "user_id": 33,
            "title": "playwithserch",
            "link": "https:\/\/www.twitch.tv\/playwithserch",
            "game_id": 7,
            "slug": "playwithserch",
            "description": "👑 REPLAY 👑  Стрим 30.08 в 20:00 по мск",
            "provider": "twitch",
            "views": 969,
            "logo": "https:\/\/static-cdn.jtvnw.net\/jtv_user_pictures\/3bd29c36-f9d4-45fa-8b1d-661b262af858-profile_image-300x300.png",
            "overlay": "https:\/\/static-cdn.jtvnw.net\/jtv_user_pictures\/e036001e-12cf-4323-a319-282d4067b16d-channel_offline_image-1920x1080.png",
            "created_at": "2019-08-30T05:40:00.000000Z",
            "user": null,
            "streams": []
        },
        {
            "id": 55,
            "user_id": 32,
            "title": "joskiyokda",
            "link": "https:\/\/www.twitch.tv\/joskiyokda",
            "game_id": 14,
            "slug": "joskiyokda",
            "description": "Смотрим видосы на YouTube",
            "provider": "twitch",
            "views": 878,
            "logo": "https:\/\/static-cdn.jtvnw.net\/jtv_user_pictures\/c7d0b506-80ad-452d-8160-5ce8a38b17bb-profile_image-300x300.png",
            "overlay": "https:\/\/static-cdn.jtvnw.net\/jtv_user_pictures\/3171e793-b45a-481a-94de-01d4c8acafb8-channel_offline_image-1920x1080.jpeg",
            "created_at": "2019-08-30T05:40:00.000000Z",
            "user": null,
            "streams": []
        },
        {
            "id": 53,
            "user_id": 31,
            "title": "inmateoo",
            "link": "https:\/\/www.twitch.tv\/inmateoo",
            "game_id": 20,
            "slug": "inmateoo",
            "description": "Друиды и мб aow",
            "provider": "twitch",
            "views": 787,
            "logo": "https:\/\/static-cdn.jtvnw.net\/jtv_user_pictures\/inmateoo-profile_image-6f6004c0a0e2aa5e-300x300.png",
            "overlay": "https:\/\/static-cdn.jtvnw.net\/jtv_user_pictures\/4d1d4cbc-bc0c-42eb-b4ad-b95785ad4508-channel_offline_image-1920x1080.png",
            "created_at": "2019-08-30T05:40:00.000000Z",
            "user": null,
            "streams": []
        },
        {
            "id": 51,
            "user_id": 29,
            "title": "panteleev",
            "link": "https:\/\/www.twitch.tv\/panteleev",
            "game_id": 15,
            "slug": "panteleev",
            "description": "ФИЛЬМ: АПГРЕЙД",
            "provider": "twitch",
            "views": 222,
            "logo": "https:\/\/static-cdn.jtvnw.net\/jtv_user_pictures\/9640e7b7-69ee-4fd6-b0ec-778ad01d1722-profile_image-300x300.png",
            "overlay": "https:\/\/static-cdn.jtvnw.net\/jtv_user_pictures\/4b6ad0cd-b0fa-43e4-807b-82795443ca58-channel_offline_image-1920x1080.png",
            "created_at": "2019-08-30T05:39:59.000000Z",
            "user": null,
            "streams": []
        },
        {
            "id": 49,
            "user_id": 28,
            "title": "rocketbeanstv",
            "link": "https:\/\/www.twitch.tv\/rocketbeanstv",
            "game_id": 10,
            "slug": "rocketbeanstv",
            "description": "Creepjack - Warcraft mit Florentin & Jannes ",
            "provider": "twitch",
            "views": 255,
            "logo": "https:\/\/static-cdn.jtvnw.net\/jtv_user_pictures\/2ac31cc4-78cf-4fa3-b535-3b9c80d46250-profile_image-300x300.png",
            "overlay": "https:\/\/static-cdn.jtvnw.net\/jtv_user_pictures\/690507f5c8c406de-channel_offline_image-1920x1080.png",
            "created_at": "2019-08-30T05:39:59.000000Z",
            "user": null,
            "streams": []
        },
        {
            "id": 47,
            "user_id": 27,
            "title": "lasqa",
            "link": "https:\/\/www.twitch.tv\/lasqa",
            "game_id": 14,
            "slug": "lasqa",
            "description": "Апаем 23-й левел, Пламегор \/ Вся инфа - !WOW",
            "provider": "twitch",
            "views": 754,
            "logo": "https:\/\/static-cdn.jtvnw.net\/jtv_user_pictures\/lasqa-profile_image-49dc25f1e724dbd6-300x300.jpeg",
            "overlay": "https:\/\/static-cdn.jtvnw.net\/jtv_user_pictures\/ed58375ada58371d-channel_offline_image-1920x1080.jpeg",
            "created_at": "2019-08-30T05:39:59.000000Z",
            "user": null,
            "streams": []
        },
        {
            "id": 45,
            "user_id": 26,
            "title": "delaylamy",
            "link": "https:\/\/www.twitch.tv\/delaylamy",
            "game_id": 14,
            "slug": "delaylamy",
            "description": "ИРИШКА ПОЛУСПОРТИК",
            "provider": "twitch",
            "views": 733,
            "logo": "https:\/\/static-cdn.jtvnw.net\/jtv_user_pictures\/5d499f66-0e73-4b92-9cbe-498a1cfdd893-profile_image-300x300.jpg",
            "overlay": "https:\/\/static-cdn.jtvnw.net\/jtv_user_pictures\/449c81a9-bacb-455c-b7c3-483cc5734f64-channel_offline_image-1920x1080.png",
            "created_at": "2019-08-30T05:39:58.000000Z",
            "user": null,
            "streams": []
        },
        {
            "id": 43,
            "user_id": 25,
            "title": "lucifer__chan",
            "link": "https:\/\/www.twitch.tv\/lucifer__chan",
            "game_id": 16,
            "slug": "lucifer-chan",
            "description": "АЛКОСТРИМ, с Днем Рождеееенияя Вииикаа",
            "provider": "twitch",
            "views": 973,
            "logo": "https:\/\/static-cdn.jtvnw.net\/jtv_user_pictures\/3f47289d-bca8-470e-9a54-7f042168c559-profile_image-300x300.png",
            "overlay": "https:\/\/static-cdn.jtvnw.net\/jtv_user_pictures\/1b9be910-65f2-4f36-b3eb-9a91e416f672-channel_offline_image-1920x1080.png",
            "created_at": "2019-08-30T05:39:58.000000Z",
            "user": null,
            "streams": []
        },
        {
            "id": 41,
            "user_id": 24,
            "title": "cemka",
            "link": "https:\/\/www.twitch.tv\/cemka",
            "game_id": 8,
            "slug": "cemka",
            "description": "Не играю в рогалик весь стрим",
            "provider": "twitch",
            "views": 220,
            "logo": "https:\/\/static-cdn.jtvnw.net\/jtv_user_pictures\/cemka-profile_image-38e81de032c2f9aa-300x300.png",
            "overlay": "https:\/\/static-cdn.jtvnw.net\/jtv_user_pictures\/cemka-channel_offline_image-444e3a72e99bed3a-1920x1080.png",
            "created_at": "2019-08-30T05:39:57.000000Z",
            "user": null,
            "streams": []
        },
        {
            "id": 39,
            "user_id": 23,
            "title": "denly",
            "link": "https:\/\/www.twitch.tv\/denly",
            "game_id": 18,
            "slug": "denly",
            "description": "мяу",
            "provider": "twitch",
            "views": 707,
            "logo": "https:\/\/static-cdn.jtvnw.net\/jtv_user_pictures\/0e0aa7fb-af1d-4c74-af68-acf3c16c36e2-profile_image-300x300.png",
            "overlay": "https:\/\/static-cdn.jtvnw.net\/jtv_user_pictures\/c137d26c-8f68-406b-8505-1f52c1b3f497-channel_offline_image-1920x1080.jpeg",
            "created_at": "2019-08-30T05:39:57.000000Z",
            "user": null,
            "streams": []
        },
        {
            "id": 37,
            "user_id": 22,
            "title": "ant1ka",
            "link": "https:\/\/www.twitch.tv\/ant1ka",
            "game_id": 3,
            "slug": "ant1ka",
            "description": "ШРЕК 2 !ШРЕК",
            "provider": "twitch",
            "views": 163,
            "logo": "https:\/\/static-cdn.jtvnw.net\/jtv_user_pictures\/c5b27425-a389-41ac-b5d7-32fa63da836e-profile_image-300x300.png",
            "overlay": "https:\/\/static-cdn.jtvnw.net\/jtv_user_pictures\/96f0baa8-fc61-4920-8cf0-064de8aa2ef0-channel_offline_image-1920x1080.jpeg",
            "created_at": "2019-08-30T05:39:56.000000Z",
            "user": null,
            "streams": []
        },
        {
            "id": 35,
            "user_id": 20,
            "title": "sorabi_",
            "link": "https:\/\/www.twitch.tv\/sorabi_",
            "game_id": 14,
            "slug": "sorabi",
            "description": "(RU\/ENG) Я ЖИРНАЯ УРОДИНА. ПОКА.",
            "provider": "twitch",
            "views": 274,
            "logo": "https:\/\/static-cdn.jtvnw.net\/jtv_user_pictures\/d30e9c53-9263-44cf-8416-d6adf3094d97-profile_image-300x300.png",
            "overlay": "https:\/\/static-cdn.jtvnw.net\/jtv_user_pictures\/77f67ececffd7b36-channel_offline_image-1920x1080.jpeg",
            "created_at": "2019-08-30T05:39:56.000000Z",
            "user": null,
            "streams": []
        },
        {
            "id": 33,
            "user_id": 19,
            "title": "kyxnya",
            "link": "https:\/\/www.twitch.tv\/kyxnya",
            "game_id": 18,
            "slug": "kyxnya",
            "description": "впервые на твиче!",
            "provider": "twitch",
            "views": 660,
            "logo": "https:\/\/static-cdn.jtvnw.net\/jtv_user_pictures\/d4daa53c-1144-40a7-970a-9a68f19dcf83-profile_image-300x300.png",
            "overlay": "https:\/\/static-cdn.jtvnw.net\/jtv_user_pictures\/1b388571-72a3-4411-8d53-e4ad611ad9bb-channel_offline_image-1920x1080.jpg",
            "created_at": "2019-08-30T05:39:55.000000Z",
            "user": null,
            "streams": []
        },
        {
            "id": 31,
            "user_id": 18,
            "title": "mira",
            "link": "https:\/\/www.twitch.tv\/mira",
            "game_id": 16,
            "slug": "mira",
            "description": "afk updates IG @mira_twitch",
            "provider": "twitch",
            "views": 901,
            "logo": "https:\/\/static-cdn.jtvnw.net\/jtv_user_pictures\/3bfa43d9-6ed6-4006-878a-35edc1e09213-profile_image-300x300.png",
            "overlay": "https:\/\/static-cdn.jtvnw.net\/jtv_user_pictures\/mira-channel_offline_image-a2f49cb3c2e4a096-1920x1080.jpeg",
            "created_at": "2019-08-30T05:39:55.000000Z",
            "user": null,
            "streams": []
        },
        {
            "id": 29,
            "user_id": 17,
            "title": "mikerina",
            "link": "https:\/\/www.twitch.tv\/mikerina",
            "game_id": 13,
            "slug": "mikerina",
            "description": "[RU\/ENG] Мувик",
            "provider": "twitch",
            "views": 530,
            "logo": "https:\/\/static-cdn.jtvnw.net\/jtv_user_pictures\/b1d0fb83-8fda-4313-8707-693d45968cdd-profile_image-300x300.png",
            "overlay": "https:\/\/static-cdn.jtvnw.net\/jtv_user_pictures\/7f082321-7c5f-4526-80dc-14f721fe60b0-channel_offline_image-1920x1080.png",
            "created_at": "2019-08-30T05:39:54.000000Z",
            "user": null,
            "streams": []
        },
        {
            "id": 27,
            "user_id": 16,
            "title": "kati",
            "link": "https:\/\/www.twitch.tv\/kati",
            "game_id": 19,
            "slug": "kati",
            "description": "yo",
            "provider": "twitch",
            "views": 452,
            "logo": "https:\/\/static-cdn.jtvnw.net\/jtv_user_pictures\/489fcad2-5690-4c0c-bd8e-e39f199c4c5c-profile_image-300x300.png",
            "overlay": "https:\/\/static-cdn.jtvnw.net\/jtv_user_pictures\/804ecf82-440f-4c87-b15f-c7906fe47499-channel_offline_image-1920x1080.png",
            "created_at": "2019-08-30T05:39:54.000000Z",
            "user": null,
            "streams": []
        },
        {
            "id": 25,
            "user_id": 14,
            "title": "zanuda",
            "link": "https:\/\/www.twitch.tv\/zanuda",
            "game_id": 8,
            "slug": "zanuda",
            "description": "моя летняя машина",
            "provider": "twitch",
            "views": 525,
            "logo": "https:\/\/static-cdn.jtvnw.net\/jtv_user_pictures\/d27cfa24-49d5-48c8-886d-dc360ea186e6-profile_image-300x300.png",
            "overlay": "https:\/\/static-cdn.jtvnw.net\/jtv_user_pictures\/3d29b8e3-c8e9-404e-b058-f05614a9e034-channel_offline_image-1920x1080.jpeg",
            "created_at": "2019-08-30T05:39:53.000000Z",
            "user": null,
            "streams": []
        },
        {
            "id": 23,
            "user_id": 13,
            "title": "sweet_anita",
            "link": "https:\/\/www.twitch.tv\/sweet_anita",
            "game_id": 15,
            "slug": "sweet-anita",
            "description": "Thirstday Pog | !discord !merch",
            "provider": "twitch",
            "views": 688,
            "logo": "https:\/\/static-cdn.jtvnw.net\/jtv_user_pictures\/ac5a0a03-0501-4559-8edc-61c395484150-profile_image-300x300.png",
            "overlay": "https:\/\/static-cdn.jtvnw.net\/jtv_user_pictures\/6e5f0195-f249-48b5-ab68-0c7a96b8fb23-channel_offline_image-1920x1080.png",
            "created_at": "2019-08-30T05:39:53.000000Z",
            "user": null,
            "streams": []
        },
        {
            "id": 21,
            "user_id": 12,
            "title": "saddrama",
            "link": "https:\/\/www.twitch.tv\/saddrama",
            "game_id": 19,
            "slug": "saddrama",
            "description": "👀 сдарова бл9дьть)",
            "provider": "twitch",
            "views": 96,
            "logo": "https:\/\/static-cdn.jtvnw.net\/jtv_user_pictures\/792a0188-8131-4ab5-856e-240782418e48-profile_image-300x300.png",
            "overlay": "https:\/\/static-cdn.jtvnw.net\/jtv_user_pictures\/34eeebeb-a352-45db-80c9-8057e8913922-channel_offline_image-1920x1080.jpeg",
            "created_at": "2019-08-30T05:39:53.000000Z",
            "user": null,
            "streams": []
        },
        {
            "id": 19,
            "user_id": 11,
            "title": "mob5tertv",
            "link": "https:\/\/www.twitch.tv\/mob5tertv",
            "game_id": 3,
            "slug": "mob5tertv",
            "description": "Самарский поток с 3д звуком",
            "provider": "twitch",
            "views": 677,
            "logo": "https:\/\/static-cdn.jtvnw.net\/jtv_user_pictures\/mob5tertv-profile_image-695ed5e0a42064bb-300x300.png",
            "overlay": "https:\/\/static-cdn.jtvnw.net\/jtv_user_pictures\/b806a248-d88d-4125-aac7-8cfba29b1c26-channel_offline_image-1920x1080.png",
            "created_at": "2019-08-30T05:39:52.000000Z",
            "user": null,
            "streams": []
        },
        {
            "id": 17,
            "user_id": 9,
            "title": "vika_karter",
            "link": "https:\/\/www.twitch.tv\/vika_karter",
            "game_id": 20,
            "slug": "vika-karter",
            "description": "ЗА ОРДУ И ДВОР (СЕРВЕР - пламегор) !wow \n",
            "provider": "twitch",
            "views": 31,
            "logo": "https:\/\/static-cdn.jtvnw.net\/jtv_user_pictures\/9dc75af9-44ad-4ad9-9cb5-23544ac3baae-profile_image-300x300.jpg",
            "overlay": "https:\/\/static-cdn.jtvnw.net\/jtv_user_pictures\/f4b283eb-d368-432b-bdef-83a04f0d3f67-channel_offline_image-1920x1080.jpeg",
            "created_at": "2019-08-30T05:39:52.000000Z",
            "user": null,
            "streams": []
        },
        {
            "id": 15,
            "user_id": 8,
            "title": "zakvielchannel",
            "link": "https:\/\/www.twitch.tv\/zakvielchannel",
            "game_id": 13,
            "slug": "zakvielchannel",
            "description": "НебоАлмазов День 26 - ЭКСПЕРТ МОД! Креатив крафты! Челлендж до НГ",
            "provider": "twitch",
            "views": 282,
            "logo": "https:\/\/static-cdn.jtvnw.net\/jtv_user_pictures\/zakvielchannel-profile_image-c75a56362510cf80-300x300.jpeg",
            "overlay": "https:\/\/static-cdn.jtvnw.net\/jtv_user_pictures\/zakvielchannel-channel_offline_image-9f1af18dcc74a082-1920x1080.jpeg",
            "created_at": "2019-08-30T05:39:52.000000Z",
            "user": null,
            "streams": []
        },
        {
            "id": 13,
            "user_id": 7,
            "title": "olyashaa",
            "link": "https:\/\/www.twitch.tv\/olyashaa",
            "game_id": 10,
            "slug": "olyashaa",
            "description": "ПРОЩАНИЕ С ОЧЕРЕДНОЙ ХАТОЙ",
            "provider": "twitch",
            "views": 680,
            "logo": "https:\/\/static-cdn.jtvnw.net\/jtv_user_pictures\/olyashaa-profile_image-678836346723f273-300x300.png",
            "overlay": "https:\/\/static-cdn.jtvnw.net\/jtv_user_pictures\/12c32347-30a1-43dd-991b-910133fde4d1-channel_offline_image-1920x1080.png",
            "created_at": "2019-08-30T05:39:51.000000Z",
            "user": null,
            "streams": []
        },
        {
            "id": 11,
            "user_id": 6,
            "title": "romanovalera",
            "link": "https:\/\/www.twitch.tv\/romanovalera",
            "game_id": 16,
            "slug": "romanovalera",
            "description": "В Москве чёли?",
            "provider": "twitch",
            "views": 426,
            "logo": "https:\/\/static-cdn.jtvnw.net\/jtv_user_pictures\/c14586b4-f595-4771-b85c-cbb490c38e9e-profile_image-300x300.png",
            "overlay": "https:\/\/static-cdn.jtvnw.net\/jtv_user_pictures\/81f2646a-980d-419c-8918-c24e8cb8c839-channel_offline_image-1920x1080.png",
            "created_at": "2019-08-30T05:39:51.000000Z",
            "user": null,
            "streams": []
        },
        {
            "id": 9,
            "user_id": 5,
            "title": "ellvi",
            "link": "https:\/\/www.twitch.tv\/ellvi",
            "game_id": 2,
            "slug": "ellvi",
            "description": "Я тут, а ты там !румтур !медиа",
            "provider": "twitch",
            "views": 719,
            "logo": "https:\/\/static-cdn.jtvnw.net\/jtv_user_pictures\/8de6aec7-e44f-4282-8d4a-ca185d0ec698-profile_image-300x300.png",
            "overlay": "https:\/\/static-cdn.jtvnw.net\/jtv_user_pictures\/db9526c3-9b53-482b-98c0-a1f597ad4683-channel_offline_image-1920x1080.png",
            "created_at": "2019-08-30T05:39:50.000000Z",
            "user": null,
            "streams": []
        },
        {
            "id": 7,
            "user_id": 4,
            "title": "windy31",
            "link": "https:\/\/www.twitch.tv\/windy31",
            "game_id": 17,
            "slug": "windy31",
            "description": "играем в игры",
            "provider": "twitch",
            "views": 536,
            "logo": "https:\/\/static-cdn.jtvnw.net\/jtv_user_pictures\/67fd1f1d-ab9c-4348-9575-ee91606bc01f-profile_image-300x300.jpg",
            "overlay": "https:\/\/static-cdn.jtvnw.net\/jtv_user_pictures\/574f8a86-ef3a-4e25-9175-c3a1cbec33e3-channel_offline_image-1920x1080.jpeg",
            "created_at": "2019-08-30T05:39:50.000000Z",
            "user": null,
            "streams": []
        },
        {
            "id": 5,
            "user_id": 3,
            "title": "elfiona",
            "link": "https:\/\/www.twitch.tv\/elfiona",
            "game_id": 6,
            "slug": "elfiona",
            "description": "Здрасте",
            "provider": "twitch",
            "views": 62,
            "logo": "https:\/\/static-cdn.jtvnw.net\/jtv_user_pictures\/2e794b33-6fcb-4468-a020-71586c5ca805-profile_image-300x300.jpeg",
            "overlay": "https:\/\/static-cdn.jtvnw.net\/jtv_user_pictures\/68556256-b796-498a-ae5c-74997f8ca454-channel_offline_image-1920x1080.jpg",
            "created_at": "2019-08-30T05:39:50.000000Z",
            "user": null,
            "streams": []
        },
        {
            "id": 3,
            "user_id": 2,
            "title": "dimaoneshot",
            "link": "https:\/\/www.twitch.tv\/dimaoneshot",
            "game_id": 4,
            "slug": "dimaoneshot",
            "description": "Играем с Зиминым в Ужастик :)",
            "provider": "twitch",
            "views": 543,
            "logo": "https:\/\/static-cdn.jtvnw.net\/jtv_user_pictures\/dimaoneshot-profile_image-35c9ece96a6bb0b7-300x300.jpeg",
            "overlay": "https:\/\/static-cdn.jtvnw.net\/jtv_user_pictures\/c844a76e-fd34-407c-88e0-d75f6d3e5f7f-channel_offline_image-1920x1080.jpeg",
            "created_at": "2019-08-30T05:39:49.000000Z",
            "user": null,
            "streams": []
        },
        {
            "id": 1,
            "user_id": 1,
            "title": "b_u_l_o_c_h_k_a",
            "link": "https:\/\/www.twitch.tv\/b_u_l_o_c_h_k_a",
            "game_id": 1,
            "slug": "b-u-l-o-c-h-k-a",
            "description": "Таиланд ,просто пляж",
            "provider": "twitch",
            "views": 731,
            "logo": "https:\/\/static-cdn.jtvnw.net\/jtv_user_pictures\/c46ded81-3177-4555-a731-74d1a2542cc6-profile_image-300x300.jpg",
            "overlay": "https:\/\/static-cdn.jtvnw.net\/jtv_user_pictures\/5fff535e-b025-40db-af48-15e0b60ac9af-channel_offline_image-1920x1080.png",
            "created_at": "2019-08-30T05:39:49.000000Z",
            "user": null,
            "streams": []
        }
    ]
}
```

### HTTP Request
`GET api/channels`

#### Query Parameters

Parameter | Status | Description
--------- | ------- | ------- | -----------
    include |  optional  | string String of connections: user, streams, tags, game.
    sort |  optional  | string Sort items by fields: title, id. For desc use '-' prefix.
    page |  optional  | array Use as page[number]=1&page[size]=2.

<!-- END_49dde38880772c46db114f63233b9c8d -->

<!-- START_0779c6dd057d85b1ecc8600867607a5a -->
## Detail channel&#039;s info.

> Example request:

```php

$client = new \GuzzleHttp\Client();
$response = $client->get("https://darestreams.com/api/channels/1", [
    'query' => [
            "include" => "user,streams",
        ],
]);
$body = $response->getBody();
print_r(json_decode((string) $body));
```



> Example response (200):

```json
{
    "data": {
        "id": 1,
        "user_id": 1,
        "title": "b_u_l_o_c_h_k_a",
        "link": "https:\/\/www.twitch.tv\/b_u_l_o_c_h_k_a",
        "game_id": 1,
        "slug": "b-u-l-o-c-h-k-a",
        "description": "Таиланд ,просто пляж",
        "provider": "twitch",
        "views": 731,
        "logo": "https:\/\/static-cdn.jtvnw.net\/jtv_user_pictures\/c46ded81-3177-4555-a731-74d1a2542cc6-profile_image-300x300.jpg",
        "overlay": "https:\/\/static-cdn.jtvnw.net\/jtv_user_pictures\/5fff535e-b025-40db-af48-15e0b60ac9af-channel_offline_image-1920x1080.png",
        "created_at": "2019-08-30T05:39:49.000000Z",
        "user": null,
        "streams": []
    }
}
```

### HTTP Request
`GET api/channels/{channel}`

#### Query Parameters

Parameter | Status | Description
--------- | ------- | ------- | -----------
    include |  optional  | string String of connections: user, streams, tags, game.

<!-- END_0779c6dd057d85b1ecc8600867607a5a -->

<!-- START_dee125be05d89dc3192127382ff45319 -->
## Update info about channel.

<br><small style="padding: 1px 9px 2px;font-weight: bold;white-space: nowrap;color: #ffffff;-webkit-border-radius: 9px;-moz-border-radius: 9px;border-radius: 9px;background-color: #3a87ad;">Requires authentication</small>
> Example request:

```php

$client = new \GuzzleHttp\Client();
$response = $client->put("https://darestreams.com/api/channels/1", [
    'headers' => [
            "Content-Type" => "application/json",
        ],
    'json' => [
            "description" => "Long description.",
            "logo" => "saepe",
            "game_id" => "11",
        ],
]);
$body = $response->getBody();
print_r(json_decode((string) $body));
```




### HTTP Request
`PUT api/channels/{channel}`

`PATCH api/channels/{channel}`

#### Body Parameters

Parameter | Type | Status | Description
--------- | ------- | ------- | ------- | -----------
    description | string |  optional  | Description of channel.
    logo | file |  optional  | Logo for your channel. Possible formats: png, jpg.
    game_id | integer |  optional  | Select category from games list.

<!-- END_dee125be05d89dc3192127382ff45319 -->

#Games
<!-- START_e13bc0a4ebf6ae5facd90bc628401ae8 -->
## Top categories

> Example request:

```php

$client = new \GuzzleHttp\Client();
$response = $client->get("https://darestreams.com/api/games/top", [
    'query' => [
            "hours" => "240",
            "limit" => "nesciunt",
            "skip" => "et",
            "include" => "tags,streams",
        ],
]);
$body = $response->getBody();
print_r(json_decode((string) $body));
```



> Example response (500):

```json
{
    "message": "Server Error"
}
```

### HTTP Request
`GET api/games/top`

#### Query Parameters

Parameter | Status | Description
--------- | ------- | ------- | -----------
    hours |  optional  | integer. Check amount donations sum for last N hours. Default: 240.
    limit |  optional  | integer. Limit of top categories. Default: 10.
    skip |  optional  | Integer. Offset of top categories. Default: 0.
    include |  optional  | string String of connections: streams,tags, channels.

<!-- END_e13bc0a4ebf6ae5facd90bc628401ae8 -->

<!-- START_a213ebd3dc2db295be3e4893926eb6d9 -->
## Display a listing of the resource.

> Example request:

```php

$client = new \GuzzleHttp\Client();
$response = $client->get("https://darestreams.com/api/games", [
    'query' => [
            "include" => "tags,streams",
            "sort" => "-popularity",
            "page" => "6",
        ],
]);
$body = $response->getBody();
print_r(json_decode((string) $body));
```



> Example response (200):

```json
{
    "data": []
}
```

### HTTP Request
`GET api/games`

#### Query Parameters

Parameter | Status | Description
--------- | ------- | ------- | -----------
    include |  optional  | string String of connections: streams,tags, channels.
    sort |  optional  | string Sort items by fields: title, popularity. For desc use '-' prefix.
    page |  optional  | array Use as page[number]=1&page[size]=2.

<!-- END_a213ebd3dc2db295be3e4893926eb6d9 -->

<!-- START_0ef38b88d2764ec45536939737c2cd9e -->
## Display the specified resource.

> Example request:

```php

$client = new \GuzzleHttp\Client();
$response = $client->get("https://darestreams.com/api/games/1", [
    'query' => [
            "include" => "tags,streams",
        ],
]);
$body = $response->getBody();
print_r(json_decode((string) $body));
```



> Example response (200):

```json
{
    "error": "No query results for model [App\\Models\\Game] 1"
}
```

### HTTP Request
`GET api/games/{game}`

#### Query Parameters

Parameter | Status | Description
--------- | ------- | ------- | -----------
    include |  optional  | string String of connections: streams,tags,channels.

<!-- END_0ef38b88d2764ec45536939737c2cd9e -->

<!-- START_1c911aeac664c589d28f801d250390eb -->
## Offer new category.

<br><small style="padding: 1px 9px 2px;font-weight: bold;white-space: nowrap;color: #ffffff;-webkit-border-radius: 9px;-moz-border-radius: 9px;border-radius: 9px;background-color: #3a87ad;">Requires authentication</small>
> Example request:

```php

$client = new \GuzzleHttp\Client();
$response = $client->post("https://darestreams.com/api/games/offer", [
    'headers' => [
            "Content-Type" => "application/json",
        ],
    'json' => [
            "title" => "New category.",
        ],
]);
$body = $response->getBody();
print_r(json_decode((string) $body));
```




### HTTP Request
`POST api/games/offer`

#### Body Parameters

Parameter | Type | Status | Description
--------- | ------- | ------- | ------- | -----------
    title | string |  required  | Title of new category.

<!-- END_1c911aeac664c589d28f801d250390eb -->

#Payments
<!-- START_40cac800c40f95f2695eeb00a15e43f4 -->
## Create a payment.

<br><small style="padding: 1px 9px 2px;font-weight: bold;white-space: nowrap;color: #ffffff;-webkit-border-radius: 9px;-moz-border-radius: 9px;border-radius: 9px;background-color: #3a87ad;">Requires authentication</small>
Add money to authorized user account. Donation to user or task.

{gate} - gateway required from Omipay. Only 'PayPal_Rest' right now. Example: PayPal_Rest
{user} - user integer id. Default: 0
{task} - task integer id. Default: 0
{user} and {task} both cannot be >0 or =0 at the same time.

> Example request:

```php

$client = new \GuzzleHttp\Client();
$response = $client->get("https://darestreams.com/api/payments/1/1/1/checkout", [
    'headers' => [
            "Content-Type" => "application/json",
        ],
    'json' => [
            "amount" => "20",
        ],
]);
$body = $response->getBody();
print_r(json_decode((string) $body));
```



> Example response (200):

```json
[]
```
> Example response (404):

```json
[]
```

### HTTP Request
`GET api/payments/{gateway}/{user}/{task}/checkout`

#### Body Parameters

Parameter | Type | Status | Description
--------- | ------- | ------- | ------- | -----------
    amount | integer |  required  | Amount for payment.

<!-- END_40cac800c40f95f2695eeb00a15e43f4 -->

<!-- START_9926a84f74637e04d93be8ad7da48ff5 -->
## Complete purchase.

> Example request:

```php

$client = new \GuzzleHttp\Client();
$response = $client->get("https://darestreams.com/api/payments/1/1/1/completed", [
]);
$body = $response->getBody();
print_r(json_decode((string) $body));
```



> Example response (200):

```json
[]
```
> Example response (404):

```json
[]
```

### HTTP Request
`GET api/payments/{gateway}/{user}/{task}/completed`


<!-- END_9926a84f74637e04d93be8ad7da48ff5 -->

<!-- START_c9441f6a1997866abd82bba56657e33a -->
## Payment cancel

> Example request:

```php

$client = new \GuzzleHttp\Client();
$response = $client->get("https://darestreams.com/api/payments/1/1/1/cancelled", [
]);
$body = $response->getBody();
print_r(json_decode((string) $body));
```



> Example response (200):

```json
[]
```
> Example response (404):

```json
[]
```

### HTTP Request
`GET api/payments/{gateway}/{user}/{task}/cancelled`


<!-- END_c9441f6a1997866abd82bba56657e33a -->

#Streams
<!-- START_f189a42cc1f675b327d443ea53153749 -->
## Get top streams

> Example request:

```php

$client = new \GuzzleHttp\Client();
$response = $client->get("https://darestreams.com/api/streams/top", [
    'query' => [
            "limit" => "quasi",
            "skip" => "sint",
            "include" => "user,tasks",
        ],
]);
$body = $response->getBody();
print_r(json_decode((string) $body));
```



> Example response (200):

```json
[]
```
> Example response (404):

```json
[]
```

### HTTP Request
`GET api/streams/top`

#### Query Parameters

Parameter | Status | Description
--------- | ------- | ------- | -----------
    limit |  optional  | Integer. Limit of top channels. Default: 10.
    skip |  optional  | Integer. Offset of top channels. Default: 0.
    include |  optional  | string String of connections: user, tasks, tags, game.

<!-- END_f189a42cc1f675b327d443ea53153749 -->

<!-- START_af97ccd0f06c32385d5f9ca49a007169 -->
## Get list of statuses

> Example request:

```php

$client = new \GuzzleHttp\Client();
$response = $client->get("https://darestreams.com/api/streams/statuses", [
]);
$body = $response->getBody();
print_r(json_decode((string) $body));
```



> Example response (200):

```json
{
    "Created": {
        "key": "Created",
        "value": 0,
        "description": "Created"
    },
    "Active": {
        "key": "Active",
        "value": 1,
        "description": "Active"
    },
    "Canceled": {
        "key": "Canceled",
        "value": 2,
        "description": "Canceled"
    },
    "FinishedWaitPay": {
        "key": "FinishedWaitPay",
        "value": 3,
        "description": "Finished, wait for pay"
    },
    "FinishedIsPayed": {
        "key": "FinishedIsPayed",
        "value": 4,
        "description": "Finished and payed"
    }
}
```

### HTTP Request
`GET api/streams/statuses`


<!-- END_af97ccd0f06c32385d5f9ca49a007169 -->

<!-- START_abb451296e8a2ba5637d0ec36abb578d -->
## Display a listing of the resource.

> Example request:

```php

$client = new \GuzzleHttp\Client();
$response = $client->get("https://darestreams.com/api/streams", [
    'query' => [
            "include" => "game,tasks",
            "sort" => "-quantity_donators",
            "page" => "16",
        ],
]);
$body = $response->getBody();
print_r(json_decode((string) $body));
```



> Example response (200):

```json
{
    "data": []
}
```

### HTTP Request
`GET api/streams`

#### Query Parameters

Parameter | Status | Description
--------- | ------- | ------- | -----------
    include |  optional  | string String of connections: game, tasks, tags, channel, user.
    sort |  optional  | string Sort items by fields: amount_donations, quantity_donators, quantity_donations, id. For desc use '-' prefix.
    page |  optional  | array Use as page[number]=1&page[size]=2.

<!-- END_abb451296e8a2ba5637d0ec36abb578d -->

<!-- START_bf44ada173010e2c208afd434eaa671b -->
## Create new stream.

<br><small style="padding: 1px 9px 2px;font-weight: bold;white-space: nowrap;color: #ffffff;-webkit-border-radius: 9px;-moz-border-radius: 9px;border-radius: 9px;background-color: #3a87ad;">Requires authentication</small>
> Example request:

```php

$client = new \GuzzleHttp\Client();
$response = $client->post("https://darestreams.com/api/streams", [
    'headers' => [
            "Content-Type" => "application/json",
        ],
    'json' => [
            "channel_id" => "17",
            "title" => "in",
            "link" => "sint",
            "start_at" => "fugiat",
            "allow_task_before_stream" => "",
            "allow_task_when_stream" => "",
            "min_amount_task_before_stream" => "et",
            "min_amount_task_when_stream" => "voluptas",
            "min_amount_donate_task_before_stream" => "saepe",
            "min_amount_donate_task_when_stream" => "optio",
            "tags" => "est",
        ],
]);
$body = $response->getBody();
print_r(json_decode((string) $body));
```




### HTTP Request
`POST api/streams`

#### Body Parameters

Parameter | Type | Status | Description
--------- | ------- | ------- | ------- | -----------
    channel_id | integer |  required  | Select channel.
    title | string |  required  | Title of stream.
    link | string |  required  | Link on the stream.
    start_at | datetime |  required  | Datetime of starting stream.
    allow_task_before_stream | boolean |  optional  | Allow to create task before stream starts.
    allow_task_when_stream | boolean |  optional  | Allow to create task while stream is active.
    min_amount_task_before_stream | decimal |  optional  | Min amount to create task before stream starts.
    min_amount_task_when_stream | decimal |  optional  | Min amount to create task while stream is active.
    min_amount_donate_task_before_stream | decimal |  optional  | Min donate for task before stream starts.
    min_amount_donate_task_when_stream | decimal |  optional  | Min donate for task while stream is active.
    tags | Additional |  optional  | tags to stream.

<!-- END_bf44ada173010e2c208afd434eaa671b -->

<!-- START_74a8d9fa827a7a3d2dd911edb687c7cf -->
## Display the specified resource.

> Example request:

```php

$client = new \GuzzleHttp\Client();
$response = $client->get("https://darestreams.com/api/streams/1", [
    'query' => [
            "include" => "game,tasks",
        ],
]);
$body = $response->getBody();
print_r(json_decode((string) $body));
```



> Example response (200):

```json
{
    "error": "No query results for model [App\\Models\\Stream] 1"
}
```

### HTTP Request
`GET api/streams/{stream}`

#### Query Parameters

Parameter | Status | Description
--------- | ------- | ------- | -----------
    include |  optional  | string String of connections: game, tasks, tags, channel, user.

<!-- END_74a8d9fa827a7a3d2dd911edb687c7cf -->

<!-- START_d0d9ff34c11558c8c22b9e8eb9692e1c -->
## Update stream.

<br><small style="padding: 1px 9px 2px;font-weight: bold;white-space: nowrap;color: #ffffff;-webkit-border-radius: 9px;-moz-border-radius: 9px;border-radius: 9px;background-color: #3a87ad;">Requires authentication</small>
Update before starting of the stream.

> Example request:

```php

$client = new \GuzzleHttp\Client();
$response = $client->put("https://darestreams.com/api/streams/1", [
    'headers' => [
            "Content-Type" => "application/json",
        ],
    'json' => [
            "title" => "qui",
            "link" => "dolor",
            "start_at" => "sint",
            "status" => "11",
            "allow_task_before_stream" => "",
            "allow_task_when_stream" => "",
            "min_amount_task_before_stream" => "quaerat",
            "min_amount_task_when_stream" => "qui",
            "min_amount_donate_task_before_stream" => "perspiciatis",
            "min_amount_donate_task_when_stream" => "ipsam",
            "tags" => "cum",
        ],
]);
$body = $response->getBody();
print_r(json_decode((string) $body));
```




### HTTP Request
`PUT api/streams/{stream}`

`PATCH api/streams/{stream}`

#### Body Parameters

Parameter | Type | Status | Description
--------- | ------- | ------- | ------- | -----------
    title | string |  optional  | Title of stream.
    link | string |  optional  | Link on the stream.
    start_at | datetime |  optional  | Datetime of starting stream.
    status | integer |  optional  | Status of stream.
    allow_task_before_stream | boolean |  optional  | Allow to create task before stream starts.
    allow_task_when_stream | boolean |  optional  | Allow to create task while stream is active.
    min_amount_task_before_stream | decimal |  optional  | Min amount to create task before stream starts.
    min_amount_task_when_stream | decimal |  optional  | Min amount to create task while stream is active.
    min_amount_donate_task_before_stream | decimal |  optional  | Min donate for task before stream starts.
    min_amount_donate_task_when_stream | decimal |  optional  | Min donate for task while stream is active.
    tags | Additional |  optional  | tags to stream.

<!-- END_d0d9ff34c11558c8c22b9e8eb9692e1c -->

<!-- START_0d2ac8fb0867d4e8e430d2616f94179d -->
## Get stream&#039;s chat info.

> Example request:

```php

$client = new \GuzzleHttp\Client();
$response = $client->get("https://darestreams.com/api/streams/1/thread", [
    'query' => [
            "include" => "messages",
        ],
]);
$body = $response->getBody();
print_r(json_decode((string) $body));
```



> Example response (200):

```json
{
    "error": "No query results for model [App\\Models\\Stream] 1"
}
```

### HTTP Request
`GET api/streams/{stream}/thread`

#### Query Parameters

Parameter | Status | Description
--------- | ------- | ------- | -----------
    include |  optional  | string String of connections: messages, participants.

<!-- END_0d2ac8fb0867d4e8e430d2616f94179d -->

#Streams tasks
<!-- START_952eb29033bb97a34bff613fd4ea1f00 -->
## Display a listing of the resource.

{stream} - stream integer id.

> Example request:

```php

$client = new \GuzzleHttp\Client();
$response = $client->get("https://darestreams.com/api/streams/1/tasks", [
    'query' => [
            "include" => "user,stream",
            "sort" => "-amount_donations",
            "page" => "11",
        ],
]);
$body = $response->getBody();
print_r(json_decode((string) $body));
```



> Example response (200):

```json
{
    "error": "No query results for model [App\\Models\\Stream] 1"
}
```

### HTTP Request
`GET api/streams/{stream}/tasks`

#### Query Parameters

Parameter | Status | Description
--------- | ------- | ------- | -----------
    include |  optional  | string String of connections: user, stream, transactions.
    sort |  optional  | string Sort items by fields: amount_donations, id. For desc use '-' prefix.
    page |  optional  | array Use as page[number]=1&page[size]=2.

<!-- END_952eb29033bb97a34bff613fd4ea1f00 -->

<!-- START_0113caa050341493c353e3589719a212 -->
## Create new task for stream.

<br><small style="padding: 1px 9px 2px;font-weight: bold;white-space: nowrap;color: #ffffff;-webkit-border-radius: 9px;-moz-border-radius: 9px;border-radius: 9px;background-color: #3a87ad;">Requires authentication</small>
{stream} - stream integer id.

> Example request:

```php

$client = new \GuzzleHttp\Client();
$response = $client->post("https://darestreams.com/api/streams/1/tasks", [
    'headers' => [
            "Content-Type" => "application/json",
        ],
    'json' => [
            "small_text" => "cum",
            "full_text" => "cupiditate",
            "interval_time" => "10",
            "is_superbowl" => "",
            "tags" => "minus",
        ],
]);
$body = $response->getBody();
print_r(json_decode((string) $body));
```




### HTTP Request
`POST api/streams/{stream}/tasks`

#### Body Parameters

Parameter | Type | Status | Description
--------- | ------- | ------- | ------- | -----------
    small_text | text |  required  | Short description.
    full_text | text |  required  | Full description.
    interval_time | integer |  required  | Time for finishing the task. 0 means until the end of the stream.
    is_superbowl | boolean |  optional  | Select superbowl or not.
    tags | Additional |  optional  | tags to task.

<!-- END_0113caa050341493c353e3589719a212 -->

<!-- START_4bba3341fb428ec89a2d6f8353ee5ecd -->
## Display the specified resource.

{stream} - stream integer id.
{$task} - $task integer id.

> Example request:

```php

$client = new \GuzzleHttp\Client();
$response = $client->get("https://darestreams.com/api/streams/1/tasks/1", [
    'query' => [
            "include" => "user,stream",
        ],
]);
$body = $response->getBody();
print_r(json_decode((string) $body));
```



> Example response (200):

```json
{
    "error": "No query results for model [App\\Models\\Stream] 1"
}
```

### HTTP Request
`GET api/streams/{stream}/tasks/{task}`

#### Query Parameters

Parameter | Status | Description
--------- | ------- | ------- | -----------
    include |  optional  | string String of connections: user, stream, transactions.

<!-- END_4bba3341fb428ec89a2d6f8353ee5ecd -->

<!-- START_7cfdb53f2a19ba600f15d6020ed3fbe8 -->
## Update task for stream.

<br><small style="padding: 1px 9px 2px;font-weight: bold;white-space: nowrap;color: #ffffff;-webkit-border-radius: 9px;-moz-border-radius: 9px;border-radius: 9px;background-color: #3a87ad;">Requires authentication</small>
{stream} - stream integer id.
{task} - task integer id.

> Example request:

```php

$client = new \GuzzleHttp\Client();
$response = $client->put("https://darestreams.com/api/streams/1/tasks/1", [
    'headers' => [
            "Content-Type" => "application/json",
        ],
    'json' => [
            "status" => "12",
            "small_text" => "in",
            "full_text" => "ratione",
            "interval_time" => "20",
            "is_superbowl" => "",
            "tags" => "et",
        ],
]);
$body = $response->getBody();
print_r(json_decode((string) $body));
```




### HTTP Request
`PUT api/streams/{stream}/tasks/{task}`

`PATCH api/streams/{stream}/tasks/{task}`

#### Body Parameters

Parameter | Type | Status | Description
--------- | ------- | ------- | ------- | -----------
    status | integer |  optional  | Status of task.
    small_text | text |  optional  | Short description.
    full_text | text |  optional  | Full description.
    interval_time | integer |  optional  | Time for finishing the task. 0 means until the end of the stream.
    is_superbowl | boolean |  optional  | Select superbowl or not.
    tags | Additional |  optional  | tags to task.

<!-- END_7cfdb53f2a19ba600f15d6020ed3fbe8 -->

#Streams tasks donations
<!-- START_19f253dcbafef68aeb1e6946585cf8df -->
## List of task&#039;s donations.

{stream} - stream integer id.
{task} - task integer id.

> Example request:

```php

$client = new \GuzzleHttp\Client();
$response = $client->get("https://darestreams.com/api/streams/1/tasks/1/transactions", [
    'query' => [
            "include" => "task",
            "sort" => "-created_at",
            "page" => "19",
        ],
]);
$body = $response->getBody();
print_r(json_decode((string) $body));
```



> Example response (200):

```json
{
    "error": "No query results for model [App\\Models\\Stream] 1"
}
```

### HTTP Request
`GET api/streams/{stream}/tasks/{task}/transactions`

#### Query Parameters

Parameter | Status | Description
--------- | ------- | ------- | -----------
    include |  optional  | string String of connections: account_sender, account_receiver, account_sender.user, account_receiver.user, task.
    sort |  optional  | string Sort items by fields: created_at, created_at. For desc use '-' prefix.
    page |  optional  | array Use as page[number]=1&page[size]=2.

<!-- END_19f253dcbafef68aeb1e6946585cf8df -->

<!-- START_20628eb60d6b21c80fa2870ddeaca2dc -->
## Detail donations of task.

{stream} - stream integer id.
{task} - task integer id.
{transaction} - transaction integer id.

> Example request:

```php

$client = new \GuzzleHttp\Client();
$response = $client->get("https://darestreams.com/api/streams/1/tasks/1/transactions/1", [
    'query' => [
            "include" => "task",
        ],
]);
$body = $response->getBody();
print_r(json_decode((string) $body));
```



> Example response (200):

```json
{
    "error": "No query results for model [App\\Models\\Stream] 1"
}
```

### HTTP Request
`GET api/streams/{stream}/tasks/{task}/transactions/{transaction}`

#### Query Parameters

Parameter | Status | Description
--------- | ------- | ------- | -----------
    include |  optional  | string String of connections: account_sender, account_receiver, account_sender.user, account_receiver.user, task.

<!-- END_20628eb60d6b21c80fa2870ddeaca2dc -->

#Tasks
<!-- START_e6830d2867c09e8a7383e8d9720e3659 -->
## Donate

> Example request:

```php

$client = new \GuzzleHttp\Client();
$response = $client->post("https://darestreams.com/api/tasks/1/donate", [
]);
$body = $response->getBody();
print_r(json_decode((string) $body));
```




### HTTP Request
`POST api/tasks/{task}/donate`


<!-- END_e6830d2867c09e8a7383e8d9720e3659 -->

<!-- START_01696fa8fa1188ab6c0699dfca26ee69 -->
## Set vote for task.

<br><small style="padding: 1px 9px 2px;font-weight: bold;white-space: nowrap;color: #ffffff;-webkit-border-radius: 9px;-moz-border-radius: 9px;border-radius: 9px;background-color: #3a87ad;">Requires authentication</small>
{task} - integer id of task.

> Example request:

```php

$client = new \GuzzleHttp\Client();
$response = $client->patch("https://darestreams.com/api/tasks/1/setVote", [
    'headers' => [
            "Content-Type" => "application/json",
        ],
    'json' => [
            "vote" => "4",
        ],
]);
$body = $response->getBody();
print_r(json_decode((string) $body));
```




### HTTP Request
`PATCH api/tasks/{task}/setVote`

#### Body Parameters

Parameter | Type | Status | Description
--------- | ------- | ------- | ------- | -----------
    vote | integer |  optional  | Vote parameter, 1-Yes, 2-No, 0 - Pending.

<!-- END_01696fa8fa1188ab6c0699dfca26ee69 -->

<!-- START_380f2ef1c15b13a7542de599e23c62a2 -->
## Get list of statuses

> Example request:

```php

$client = new \GuzzleHttp\Client();
$response = $client->get("https://darestreams.com/api/tasks/statuses", [
]);
$body = $response->getBody();
print_r(json_decode((string) $body));
```



> Example response (200):

```json
{
    "Created": {
        "key": "Created",
        "value": 0,
        "description": "Created"
    },
    "CheckedMediator": {
        "key": "CheckedMediator",
        "value": 1,
        "description": "Checked mediator"
    },
    "Active": {
        "key": "Active",
        "value": 2,
        "description": "Active"
    },
    "IntervalFinishedAllowVote": {
        "key": "IntervalFinishedAllowVote",
        "value": 3,
        "description": "Interval finished allow vote"
    },
    "AllowVote": {
        "key": "AllowVote",
        "value": 4,
        "description": "Allow vote"
    },
    "VoteFinished": {
        "key": "VoteFinished",
        "value": 5,
        "description": "Vote finished"
    },
    "PayFinished": {
        "key": "PayFinished",
        "value": 6,
        "description": "Pay finished"
    },
    "Canceled": {
        "key": "Canceled",
        "value": 7,
        "description": "Canceled"
    }
}
```

### HTTP Request
`GET api/tasks/statuses`


<!-- END_380f2ef1c15b13a7542de599e23c62a2 -->

<!-- START_4227b9e5e54912af051e8dd5472afbce -->
## Display a listing of the resource.

> Example request:

```php

$client = new \GuzzleHttp\Client();
$response = $client->get("https://darestreams.com/api/tasks", [
]);
$body = $response->getBody();
print_r(json_decode((string) $body));
```



> Example response (200):

```json
{
    "data": []
}
```

### HTTP Request
`GET api/tasks`


<!-- END_4227b9e5e54912af051e8dd5472afbce -->

<!-- START_4da0d9b378428dcc89ced395d4a806e7 -->
## Create new task for stream.

<br><small style="padding: 1px 9px 2px;font-weight: bold;white-space: nowrap;color: #ffffff;-webkit-border-radius: 9px;-moz-border-radius: 9px;border-radius: 9px;background-color: #3a87ad;">Requires authentication</small>
> Example request:

```php

$client = new \GuzzleHttp\Client();
$response = $client->post("https://darestreams.com/api/tasks", [
    'headers' => [
            "Content-Type" => "application/json",
        ],
    'json' => [
            "stream_id" => "1",
            "small_text" => "quo",
            "full_text" => "nemo",
            "interval_time" => "19",
            "is_superbowl" => "",
            "tags" => "rerum",
        ],
]);
$body = $response->getBody();
print_r(json_decode((string) $body));
```




### HTTP Request
`POST api/tasks`

#### Body Parameters

Parameter | Type | Status | Description
--------- | ------- | ------- | ------- | -----------
    stream_id | integer |  required  | Stream id.
    small_text | text |  required  | Short description.
    full_text | text |  required  | Full description.
    interval_time | integer |  required  | Time for finishing the task. 0 means until the end of the stream.
    is_superbowl | boolean |  optional  | Select superbowl or not.
    tags | Additional |  optional  | tags to task.

<!-- END_4da0d9b378428dcc89ced395d4a806e7 -->

<!-- START_5297efa151ae4fd515fec2efd5cb1e9a -->
## Display the specified resource.

> Example request:

```php

$client = new \GuzzleHttp\Client();
$response = $client->get("https://darestreams.com/api/tasks/1", [
]);
$body = $response->getBody();
print_r(json_decode((string) $body));
```



> Example response (200):

```json
{
    "error": "No query results for model [App\\Models\\Task] 1"
}
```

### HTTP Request
`GET api/tasks/{task}`


<!-- END_5297efa151ae4fd515fec2efd5cb1e9a -->

<!-- START_546f027bf591f2ef4a8a743f0a59051d -->
## Update task for stream.

<br><small style="padding: 1px 9px 2px;font-weight: bold;white-space: nowrap;color: #ffffff;-webkit-border-radius: 9px;-moz-border-radius: 9px;border-radius: 9px;background-color: #3a87ad;">Requires authentication</small>
{stream} - stream integer id.
{task} - task integer id.

> Example request:

```php

$client = new \GuzzleHttp\Client();
$response = $client->put("https://darestreams.com/api/tasks/1", [
    'headers' => [
            "Content-Type" => "application/json",
        ],
    'json' => [
            "status" => "8",
            "small_text" => "voluptas",
            "full_text" => "natus",
            "interval_time" => "1",
            "is_superbowl" => "1",
            "tags" => "a",
        ],
]);
$body = $response->getBody();
print_r(json_decode((string) $body));
```




### HTTP Request
`PUT api/tasks/{task}`

`PATCH api/tasks/{task}`

#### Body Parameters

Parameter | Type | Status | Description
--------- | ------- | ------- | ------- | -----------
    status | integer |  optional  | Status of task.
    small_text | text |  optional  | Short description.
    full_text | text |  optional  | Full description.
    interval_time | integer |  optional  | Time for finishing the task. 0 means until the end of the stream.
    is_superbowl | boolean |  optional  | Select superbowl or not.
    tags | Additional |  optional  | tags to task.

<!-- END_546f027bf591f2ef4a8a743f0a59051d -->

#Threads
<!-- START_0243e324e32b67b8af814736702d84a1 -->
## Display a listing of the resource.

> Example request:

```php

$client = new \GuzzleHttp\Client();
$response = $client->get("https://darestreams.com/api/threads", [
    'query' => [
            "include" => "messages",
            "sort" => "-id",
        ],
]);
$body = $response->getBody();
print_r(json_decode((string) $body));
```



> Example response (200):

```json
{
    "data": []
}
```

### HTTP Request
`GET api/threads`

#### Query Parameters

Parameter | Status | Description
--------- | ------- | ------- | -----------
    include |  optional  | string String of connections: messages, participants.
    sort |  optional  | string Sort items by fields: title, id. For desc use '-' prefix.

<!-- END_0243e324e32b67b8af814736702d84a1 -->

<!-- START_d25ac79489d881e2cd9b57627616c7bb -->
## Display the specified resource.

> Example request:

```php

$client = new \GuzzleHttp\Client();
$response = $client->get("https://darestreams.com/api/threads/1", [
    'query' => [
            "include" => "messages",
        ],
]);
$body = $response->getBody();
print_r(json_decode((string) $body));
```



> Example response (200):

```json
{
    "error": "No query results for model [App\\Models\\Thread] 1"
}
```

### HTTP Request
`GET api/threads/{thread}`

#### Query Parameters

Parameter | Status | Description
--------- | ------- | ------- | -----------
    include |  optional  | string String of connections: messages, participants.

<!-- END_d25ac79489d881e2cd9b57627616c7bb -->

#Threads messages
<!-- START_8a4daf2ee9600eb1493ed06b5ec68a12 -->
## Get messages of thread.

> Example request:

```php

$client = new \GuzzleHttp\Client();
$response = $client->get("https://darestreams.com/api/threads/1/messages", [
    'query' => [
            "include" => "user",
        ],
]);
$body = $response->getBody();
print_r(json_decode((string) $body));
```



> Example response (200):

```json
{
    "error": "No query results for model [App\\Models\\Thread] 1"
}
```

### HTTP Request
`GET api/threads/{thread}/messages`

#### Query Parameters

Parameter | Status | Description
--------- | ------- | ------- | -----------
    include |  optional  | string String of connections: user, thread.

<!-- END_8a4daf2ee9600eb1493ed06b5ec68a12 -->

<!-- START_9f621653bddb65eadb812b5229c8515e -->
## Create new message for thread.

> Example request:

```php

$client = new \GuzzleHttp\Client();
$response = $client->post("https://darestreams.com/api/threads/1/messages", [
    'headers' => [
            "Content-Type" => "application/json",
        ],
    'json' => [
            "body" => "mollitia",
        ],
]);
$body = $response->getBody();
print_r(json_decode((string) $body));
```




### HTTP Request
`POST api/threads/{thread}/messages`

#### Body Parameters

Parameter | Type | Status | Description
--------- | ------- | ------- | ------- | -----------
    body | text |  optional  | Message text.

<!-- END_9f621653bddb65eadb812b5229c8515e -->

<!-- START_5eac3967faea0453327dfea0b27e4d12 -->
## Detail message of thread.

{thread} - thread integer id.

> Example request:

```php

$client = new \GuzzleHttp\Client();
$response = $client->get("https://darestreams.com/api/threads/1/messages/1", [
    'query' => [
            "include" => "user",
        ],
]);
$body = $response->getBody();
print_r(json_decode((string) $body));
```



> Example response (200):

```json
{
    "error": "No query results for model [App\\Models\\Thread] 1"
}
```

### HTTP Request
`GET api/threads/{thread}/messages/{message}`

#### Query Parameters

Parameter | Status | Description
--------- | ------- | ------- | -----------
    include |  optional  | string String of connections: user, thread.

<!-- END_5eac3967faea0453327dfea0b27e4d12 -->

#Threads participants
<!-- START_6c536b81f96eb7e5f621850a695ea2bb -->
## Get participant users of the chat.

> Example request:

```php

$client = new \GuzzleHttp\Client();
$response = $client->get("https://darestreams.com/api/threads/1/participants", [
]);
$body = $response->getBody();
print_r(json_decode((string) $body));
```



> Example response (200):

```json
{
    "error": "No query results for model [App\\Models\\Thread] 1"
}
```

### HTTP Request
`GET api/threads/{thread}/participants`


<!-- END_6c536b81f96eb7e5f621850a695ea2bb -->

#Transactions
<!-- START_329f8bada65c21dd5868ca3838acc048 -->
## Get list of statuses

> Example request:

```php

$client = new \GuzzleHttp\Client();
$response = $client->get("https://darestreams.com/api/transactions/statuses", [
]);
$body = $response->getBody();
print_r(json_decode((string) $body));
```



> Example response (200):

```json
{
    "Created": {
        "key": "Created",
        "value": 0,
        "description": "Created"
    },
    "Holding": {
        "key": "Holding",
        "value": 1,
        "description": "Holding"
    },
    "Completed": {
        "key": "Completed",
        "value": 2,
        "description": "Completed"
    },
    "Canceled": {
        "key": "Canceled",
        "value": 3,
        "description": "Canceled"
    }
}
```

### HTTP Request
`GET api/transactions/statuses`


<!-- END_329f8bada65c21dd5868ca3838acc048 -->

<!-- START_7916f5673d233f58b076e235b3175983 -->
## Get list of types

> Example request:

```php

$client = new \GuzzleHttp\Client();
$response = $client->get("https://darestreams.com/api/transactions/types", [
]);
$body = $response->getBody();
print_r(json_decode((string) $body));
```



> Example response (200):

```json
{
    "Deposit": {
        "key": "Deposit",
        "value": 0,
        "description": "Deposit"
    },
    "Donation": {
        "key": "Donation",
        "value": 1,
        "description": "Donation"
    },
    "Withdraw": {
        "key": "Withdraw",
        "value": 2,
        "description": "Withdraw"
    }
}
```

### HTTP Request
`GET api/transactions/types`


<!-- END_7916f5673d233f58b076e235b3175983 -->

<!-- START_a524d236dd691776be3315d40786a1db -->
## Create new transaction.

<br><small style="padding: 1px 9px 2px;font-weight: bold;white-space: nowrap;color: #ffffff;-webkit-border-radius: 9px;-moz-border-radius: 9px;border-radius: 9px;background-color: #3a87ad;">Requires authentication</small>
> Example request:

```php

$client = new \GuzzleHttp\Client();
$response = $client->post("https://darestreams.com/api/transactions", [
    'headers' => [
            "Content-Type" => "application/json",
        ],
    'json' => [
            "task_id" => "10",
            "user_id" => "11",
            "amount" => "1",
        ],
]);
$body = $response->getBody();
print_r(json_decode((string) $body));
```




### HTTP Request
`POST api/transactions`

#### Body Parameters

Parameter | Type | Status | Description
--------- | ------- | ------- | ------- | -----------
    task_id | integer |  optional  | Task's id.
    user_id | integer |  optional  | User's id.
    amount | integer |  required  | Amount for payment.

<!-- END_a524d236dd691776be3315d40786a1db -->

#Users
<!-- START_f09d589f509808e4d6fc27d1ca229006 -->
## Get top donators

> Example request:

```php

$client = new \GuzzleHttp\Client();
$response = $client->get("https://darestreams.com/api/users/top", [
    'query' => [
            "limit" => "impedit",
            "skip" => "dolores",
            "include" => "tasks,channel",
        ],
]);
$body = $response->getBody();
print_r(json_decode((string) $body));
```



> Example response (200):

```json
[]
```
> Example response (404):

```json
[]
```

### HTTP Request
`GET api/users/top`

#### Query Parameters

Parameter | Status | Description
--------- | ------- | ------- | -----------
    limit |  optional  | Integer. Limit of top channels. Default: 10.
    skip |  optional  | Integer. Offset of top channels. Default: 0.
    include |  optional  | string String of connections: tasks, streams, channel.

<!-- END_f09d589f509808e4d6fc27d1ca229006 -->

<!-- START_8d1e53fcf4d2d02a6144ed392de856bf -->
## Get authorized user.

<br><small style="padding: 1px 9px 2px;font-weight: bold;white-space: nowrap;color: #ffffff;-webkit-border-radius: 9px;-moz-border-radius: 9px;border-radius: 9px;background-color: #3a87ad;">Requires authentication</small>
> Example request:

```php

$client = new \GuzzleHttp\Client();
$response = $client->get("https://darestreams.com/api/users/me", [
    'query' => [
            "include" => "tasks,channel",
        ],
]);
$body = $response->getBody();
print_r(json_decode((string) $body));
```



> Example response (400):

```json
{
    "error": "Unauthorized"
}
```

### HTTP Request
`GET api/users/me`

#### Query Parameters

Parameter | Status | Description
--------- | ------- | ------- | -----------
    include |  optional  | string String of connections: tasks, streams, channel.

<!-- END_8d1e53fcf4d2d02a6144ed392de856bf -->

<!-- START_13e2ad97e76ee65410a58b64ad67b7c8 -->
## Donate to user.

<br><small style="padding: 1px 9px 2px;font-weight: bold;white-space: nowrap;color: #ffffff;-webkit-border-radius: 9px;-moz-border-radius: 9px;border-radius: 9px;background-color: #3a87ad;">Requires authentication</small>
> Example request:

```php

$client = new \GuzzleHttp\Client();
$response = $client->post("https://darestreams.com/api/users/1/donate", [
]);
$body = $response->getBody();
print_r(json_decode((string) $body));
```




### HTTP Request
`POST api/users/{user}/donate`


<!-- END_13e2ad97e76ee65410a58b64ad67b7c8 -->

<!-- START_fc1e4f6a697e3c48257de845299b71d5 -->
## Display a listing of the resource.

> Example request:

```php

$client = new \GuzzleHttp\Client();
$response = $client->get("https://darestreams.com/api/users", [
    'query' => [
            "include" => "tasks,channel",
            "sort" => "-nickname",
        ],
]);
$body = $response->getBody();
print_r(json_decode((string) $body));
```



> Example response (200):

```json
{
    "data": []
}
```

### HTTP Request
`GET api/users`

#### Query Parameters

Parameter | Status | Description
--------- | ------- | ------- | -----------
    include |  optional  | string String of connections: tasks, streams, channel.
    sort |  optional  | string Sort items by fields: nickname, id. For desc use '-' prefix.

<!-- END_fc1e4f6a697e3c48257de845299b71d5 -->

<!-- START_8653614346cb0e3d444d164579a0a0a2 -->
## Display the specified resource.

> Example request:

```php

$client = new \GuzzleHttp\Client();
$response = $client->get("https://darestreams.com/api/users/1", [
    'query' => [
            "include" => "tasks,channel",
        ],
]);
$body = $response->getBody();
print_r(json_decode((string) $body));
```



> Example response (200):

```json
{
    "error": "No query results for model [App\\Models\\User] 1"
}
```

### HTTP Request
`GET api/users/{user}`

#### Query Parameters

Parameter | Status | Description
--------- | ------- | ------- | -----------
    include |  optional  | string String of connections: tasks, streams, channel.

<!-- END_8653614346cb0e3d444d164579a0a0a2 -->

<!-- START_48a3115be98493a3c866eb0e23347262 -->
## Update user fields.

<br><small style="padding: 1px 9px 2px;font-weight: bold;white-space: nowrap;color: #ffffff;-webkit-border-radius: 9px;-moz-border-radius: 9px;border-radius: 9px;background-color: #3a87ad;">Requires authentication</small>
> Example request:

```php

$client = new \GuzzleHttp\Client();
$response = $client->put("https://darestreams.com/api/users/1", [
    'headers' => [
            "Content-Type" => "application/json",
        ],
    'json' => [
            "name" => "Archibald",
            "last_name" => "molestias",
            "middle_name" => "consequatur",
            "email" => "example@example.ru",
        ],
]);
$body = $response->getBody();
print_r(json_decode((string) $body));
```




### HTTP Request
`PUT api/users/{user}`

`PATCH api/users/{user}`

#### Body Parameters

Parameter | Type | Status | Description
--------- | ------- | ------- | ------- | -----------
    name | string |  required  | User's first name.
    last_name | string |  optional  | User's last name.
    middle_name | string |  optional  | User's middle name.
    email | string |  required  | User's email.

<!-- END_48a3115be98493a3c866eb0e23347262 -->

<!-- START_7cc3051f0f8fb1bb7bb6bb2378539c7d -->
## User&#039;s account
{user} - user id integer.

<br><small style="padding: 1px 9px 2px;font-weight: bold;white-space: nowrap;color: #ffffff;-webkit-border-radius: 9px;-moz-border-radius: 9px;border-radius: 9px;background-color: #3a87ad;">Requires authentication</small>
> Example request:

```php

$client = new \GuzzleHttp\Client();
$response = $client->get("https://darestreams.com/api/users/1/account", [
]);
$body = $response->getBody();
print_r(json_decode((string) $body));
```



> Example response (400):

```json
{
    "error": "Unauthorized"
}
```

### HTTP Request
`GET api/users/{user}/account`


<!-- END_7cc3051f0f8fb1bb7bb6bb2378539c7d -->

<!-- START_fbb6055cfa923488b2aecfd64b5169f2 -->
## User&#039;s channel
{user} - user id integer.

> Example request:

```php

$client = new \GuzzleHttp\Client();
$response = $client->get("https://darestreams.com/api/users/1/channel", [
]);
$body = $response->getBody();
print_r(json_decode((string) $body));
```



> Example response (200):

```json
{
    "error": "No query results for model [App\\Models\\User] 1"
}
```

### HTTP Request
`GET api/users/{user}/channel`


<!-- END_fbb6055cfa923488b2aecfd64b5169f2 -->

<!-- START_c18e7a0ea5224add54ef7121f2ae52e3 -->
## Update user&#039;s avatar

<br><small style="padding: 1px 9px 2px;font-weight: bold;white-space: nowrap;color: #ffffff;-webkit-border-radius: 9px;-moz-border-radius: 9px;border-radius: 9px;background-color: #3a87ad;">Requires authentication</small>
> Example request:

```php

$client = new \GuzzleHttp\Client();
$response = $client->post("https://darestreams.com/api/users/1/avatar", [
]);
$body = $response->getBody();
print_r(json_decode((string) $body));
```




### HTTP Request
`POST api/users/{user}/avatar`


<!-- END_c18e7a0ea5224add54ef7121f2ae52e3 -->

<!-- START_31d6210e44c9878a8ffc28a444cc1559 -->
## Update user&#039;s overlay.

<br><small style="padding: 1px 9px 2px;font-weight: bold;white-space: nowrap;color: #ffffff;-webkit-border-radius: 9px;-moz-border-radius: 9px;border-radius: 9px;background-color: #3a87ad;">Requires authentication</small>
> Example request:

```php

$client = new \GuzzleHttp\Client();
$response = $client->post("https://darestreams.com/api/users/1/overlay", [
]);
$body = $response->getBody();
print_r(json_decode((string) $body));
```




### HTTP Request
`POST api/users/{user}/overlay`


<!-- END_31d6210e44c9878a8ffc28a444cc1559 -->

<!-- START_214b46866907f8337b7d82d3091c5f2a -->
## Update user&#039;s password.

<br><small style="padding: 1px 9px 2px;font-weight: bold;white-space: nowrap;color: #ffffff;-webkit-border-radius: 9px;-moz-border-radius: 9px;border-radius: 9px;background-color: #3a87ad;">Requires authentication</small>
> Example request:

```php

$client = new \GuzzleHttp\Client();
$response = $client->patch("https://darestreams.com/api/users/1/password", [
    'headers' => [
            "Content-Type" => "application/json",
        ],
    'json' => [
            "password" => "jadfohasd092",
        ],
]);
$body = $response->getBody();
print_r(json_decode((string) $body));
```




### HTTP Request
`PATCH api/users/{user}/password`

#### Body Parameters

Parameter | Type | Status | Description
--------- | ------- | ------- | ------- | -----------
    password | string |  required  | User's password.

<!-- END_214b46866907f8337b7d82d3091c5f2a -->

<!-- START_ab4903de81d5140154df1f3010ee83c8 -->
## Follow the user.

<br><small style="padding: 1px 9px 2px;font-weight: bold;white-space: nowrap;color: #ffffff;-webkit-border-radius: 9px;-moz-border-radius: 9px;border-radius: 9px;background-color: #3a87ad;">Requires authentication</small>
{user} - user id you want follow for.

> Example request:

```php

$client = new \GuzzleHttp\Client();
$response = $client->post("https://darestreams.com/api/users/1/follow", [
]);
$body = $response->getBody();
print_r(json_decode((string) $body));
```




### HTTP Request
`POST api/users/{user}/follow`


<!-- END_ab4903de81d5140154df1f3010ee83c8 -->

<!-- START_fb812a13da98d2159bf60eb1c6055808 -->
## Unfollow the user.

<br><small style="padding: 1px 9px 2px;font-weight: bold;white-space: nowrap;color: #ffffff;-webkit-border-radius: 9px;-moz-border-radius: 9px;border-radius: 9px;background-color: #3a87ad;">Requires authentication</small>
{user} - user id you want unfollow.

> Example request:

```php

$client = new \GuzzleHttp\Client();
$response = $client->patch("https://darestreams.com/api/users/1/unfollow", [
]);
$body = $response->getBody();
print_r(json_decode((string) $body));
```




### HTTP Request
`PATCH api/users/{user}/unfollow`


<!-- END_fb812a13da98d2159bf60eb1c6055808 -->

<!-- START_6404b83da363c00b12b9fc1e15ec86ba -->
## User&#039;s followers

{user} - user id you want follow for.

> Example request:

```php

$client = new \GuzzleHttp\Client();
$response = $client->get("https://darestreams.com/api/users/1/followers", [
]);
$body = $response->getBody();
print_r(json_decode((string) $body));
```



> Example response (200):

```json
{
    "error": "No query results for model [App\\Models\\User] 1"
}
```

### HTTP Request
`GET api/users/{user}/followers`


<!-- END_6404b83da363c00b12b9fc1e15ec86ba -->

<!-- START_b1af205299bf898aa2915c41a4e6bc1a -->
## Users followings
{user} - user id integer.

> Example request:

```php

$client = new \GuzzleHttp\Client();
$response = $client->get("https://darestreams.com/api/users/1/followings", [
]);
$body = $response->getBody();
print_r(json_decode((string) $body));
```



> Example response (200):

```json
{
    "error": "No query results for model [App\\Models\\User] 1"
}
```

### HTTP Request
`GET api/users/{user}/followings`


<!-- END_b1af205299bf898aa2915c41a4e6bc1a -->

#Users notifications
<!-- START_b40c1602e5d2b520e2f5e920cd782013 -->
## Get user&#039;s unread notifications.

<br><small style="padding: 1px 9px 2px;font-weight: bold;white-space: nowrap;color: #ffffff;-webkit-border-radius: 9px;-moz-border-radius: 9px;border-radius: 9px;background-color: #3a87ad;">Requires authentication</small>
> Example request:

```php

$client = new \GuzzleHttp\Client();
$response = $client->get("https://darestreams.com/api/users/1/notifications/unread", [
]);
$body = $response->getBody();
print_r(json_decode((string) $body));
```



> Example response (400):

```json
{
    "error": "Unauthorized"
}
```

### HTTP Request
`GET api/users/{user}/notifications/unread`


<!-- END_b40c1602e5d2b520e2f5e920cd782013 -->

<!-- START_33f358c2729e6c1f28e316d32d0e4f38 -->
## Set read all user&#039;s notifications.

<br><small style="padding: 1px 9px 2px;font-weight: bold;white-space: nowrap;color: #ffffff;-webkit-border-radius: 9px;-moz-border-radius: 9px;border-radius: 9px;background-color: #3a87ad;">Requires authentication</small>
> Example request:

```php

$client = new \GuzzleHttp\Client();
$response = $client->patch("https://darestreams.com/api/users/1/notifications/setReadAll", [
]);
$body = $response->getBody();
print_r(json_decode((string) $body));
```




### HTTP Request
`PATCH api/users/{user}/notifications/setReadAll`


<!-- END_33f358c2729e6c1f28e316d32d0e4f38 -->

<!-- START_f8d506e1d8bcfae538144e1a6f7d65b2 -->
## Get user&#039;s all notifications.

<br><small style="padding: 1px 9px 2px;font-weight: bold;white-space: nowrap;color: #ffffff;-webkit-border-radius: 9px;-moz-border-radius: 9px;border-radius: 9px;background-color: #3a87ad;">Requires authentication</small>
> Example request:

```php

$client = new \GuzzleHttp\Client();
$response = $client->get("https://darestreams.com/api/users/1/notifications", [
]);
$body = $response->getBody();
print_r(json_decode((string) $body));
```



> Example response (400):

```json
{
    "error": "Unauthorized"
}
```

### HTTP Request
`GET api/users/{user}/notifications`


<!-- END_f8d506e1d8bcfae538144e1a6f7d65b2 -->

<!-- START_d46e764d0ef10a432f07c58f9db434a7 -->
## Display user&#039;s notification.

<br><small style="padding: 1px 9px 2px;font-weight: bold;white-space: nowrap;color: #ffffff;-webkit-border-radius: 9px;-moz-border-radius: 9px;border-radius: 9px;background-color: #3a87ad;">Requires authentication</small>
> Example request:

```php

$client = new \GuzzleHttp\Client();
$response = $client->get("https://darestreams.com/api/users/1/notifications/1", [
]);
$body = $response->getBody();
print_r(json_decode((string) $body));
```



> Example response (400):

```json
{
    "error": "Unauthorized"
}
```

### HTTP Request
`GET api/users/{user}/notifications/{notification}`


<!-- END_d46e764d0ef10a432f07c58f9db434a7 -->

<!-- START_53f3d5e5bec75e23efe1f3968f82be15 -->
## Set read one user&#039;s notification.

<br><small style="padding: 1px 9px 2px;font-weight: bold;white-space: nowrap;color: #ffffff;-webkit-border-radius: 9px;-moz-border-radius: 9px;border-radius: 9px;background-color: #3a87ad;">Requires authentication</small>
> Example request:

```php

$client = new \GuzzleHttp\Client();
$response = $client->patch("https://darestreams.com/api/users/1/notifications/1/setRead", [
]);
$body = $response->getBody();
print_r(json_decode((string) $body));
```




### HTTP Request
`PATCH api/users/{user}/notifications/{notification}/setRead`


<!-- END_53f3d5e5bec75e23efe1f3968f82be15 -->

#Users oauth accounts
<!-- START_5d466822499977de34e9b7f781a67125 -->
## Get user&#039;s all social accounts.

<br><small style="padding: 1px 9px 2px;font-weight: bold;white-space: nowrap;color: #ffffff;-webkit-border-radius: 9px;-moz-border-radius: 9px;border-radius: 9px;background-color: #3a87ad;">Requires authentication</small>
> Example request:

```php

$client = new \GuzzleHttp\Client();
$response = $client->get("https://darestreams.com/api/users/1/oauthproviders", [
]);
$body = $response->getBody();
print_r(json_decode((string) $body));
```



> Example response (400):

```json
{
    "error": "Unauthorized"
}
```

### HTTP Request
`GET api/users/{user}/oauthproviders`


<!-- END_5d466822499977de34e9b7f781a67125 -->

<!-- START_b54231aa40ef8f2bd13f2e93a346a406 -->
## Get user&#039;s one social accounts.

<br><small style="padding: 1px 9px 2px;font-weight: bold;white-space: nowrap;color: #ffffff;-webkit-border-radius: 9px;-moz-border-radius: 9px;border-radius: 9px;background-color: #3a87ad;">Requires authentication</small>
> Example request:

```php

$client = new \GuzzleHttp\Client();
$response = $client->get("https://darestreams.com/api/users/1/oauthproviders/1", [
]);
$body = $response->getBody();
print_r(json_decode((string) $body));
```



> Example response (400):

```json
{
    "error": "Unauthorized"
}
```

### HTTP Request
`GET api/users/{user}/oauthproviders/{oauthprovider}`


<!-- END_b54231aa40ef8f2bd13f2e93a346a406 -->

#Users transactions
<!-- START_b9b7a838afe2673a31f59bf05378cd16 -->
## Get user&#039;s all transactions.

<br><small style="padding: 1px 9px 2px;font-weight: bold;white-space: nowrap;color: #ffffff;-webkit-border-radius: 9px;-moz-border-radius: 9px;border-radius: 9px;background-color: #3a87ad;">Requires authentication</small>
> Example request:

```php

$client = new \GuzzleHttp\Client();
$response = $client->get("https://darestreams.com/api/users/1/transactions", [
    'query' => [
            "include" => "task.",
            "page" => "12",
        ],
]);
$body = $response->getBody();
print_r(json_decode((string) $body));
```



> Example response (400):

```json
{
    "error": "Unauthorized"
}
```

### HTTP Request
`GET api/users/{user}/transactions`

#### Query Parameters

Parameter | Status | Description
--------- | ------- | ------- | -----------
    include |  optional  | string String of connections: ['account_sender', 'account_receiver', 'account_sender.user', 'account_receiver.user', 'task'].
    page |  optional  | array Use as page[number]=1&page[size]=2.

<!-- END_b9b7a838afe2673a31f59bf05378cd16 -->

<!-- START_67193fc3626d5523fe09e1f407109526 -->
## Get user&#039;s one transaction.

<br><small style="padding: 1px 9px 2px;font-weight: bold;white-space: nowrap;color: #ffffff;-webkit-border-radius: 9px;-moz-border-radius: 9px;border-radius: 9px;background-color: #3a87ad;">Requires authentication</small>
> Example request:

```php

$client = new \GuzzleHttp\Client();
$response = $client->get("https://darestreams.com/api/users/1/transactions/1", [
    'query' => [
            "include" => "task.",
        ],
]);
$body = $response->getBody();
print_r(json_decode((string) $body));
```



> Example response (400):

```json
{
    "error": "Unauthorized"
}
```

### HTTP Request
`GET api/users/{user}/transactions/{transaction}`

#### Query Parameters

Parameter | Status | Description
--------- | ------- | ------- | -----------
    include |  optional  | string String of connections: ['account_sender', 'account_receiver', 'account_sender.user', 'account_receiver.user', 'task'].

<!-- END_67193fc3626d5523fe09e1f407109526 -->

#Votes
<!-- START_d34571d4b3fb5ca4789570205f487271 -->
## Display a listing of the resource.

> Example request:

```php

$client = new \GuzzleHttp\Client();
$response = $client->get("https://darestreams.com/api/votes", [
]);
$body = $response->getBody();
print_r(json_decode((string) $body));
```



> Example response (200):

```json
{
    "data": []
}
```

### HTTP Request
`GET api/votes`


<!-- END_d34571d4b3fb5ca4789570205f487271 -->

<!-- START_776ad2521c7bfc48fab5b2b7e8bc101b -->
## Display the specified resource.

> Example request:

```php

$client = new \GuzzleHttp\Client();
$response = $client->get("https://darestreams.com/api/votes/1", [
]);
$body = $response->getBody();
print_r(json_decode((string) $body));
```



> Example response (200):

```json
{
    "error": "No query results for model [App\\Models\\Vote] 1"
}
```

### HTTP Request
`GET api/votes/{vote}`


<!-- END_776ad2521c7bfc48fab5b2b7e8bc101b -->


