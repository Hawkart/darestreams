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
$response = $client->get("https://darestreams.com/api/oauth/twitch", [
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
$response = $client->get("https://darestreams.com/api/oauth/twitch/callback", [
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
            "hours" => "et",
            "limit" => "odit",
            "skip" => "placeat",
            "game_id" => "vero",
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

<!-- START_8da852fa7211516a60376c79857de2d2 -->
## Get streams from channel

{slug} - slug or id of channel.

> Example request:

```php

$client = new \GuzzleHttp\Client();
$response = $client->get("https://darestreams.com/api/channels/1/streams", [
    'query' => [
            "include" => "game,tasks",
            "sort" => "-quantity_donators",
            "page" => "5",
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
`GET api/channels/{slug}/streams`

#### Query Parameters

Parameter | Status | Description
--------- | ------- | ------- | -----------
    include |  optional  | string String of connections: game, tasks, tasks.votes, tags, channel, user.
    sort |  optional  | string Sort items by fields: amount_donations, quantity_donators, quantity_donations, id. For desc use '-' prefix.
    page |  optional  | array Use as page[number]=1&page[size]=2.

<!-- END_8da852fa7211516a60376c79857de2d2 -->

<!-- START_49dde38880772c46db114f63233b9c8d -->
## Display a listing of the resource.

> Example request:

```php

$client = new \GuzzleHttp\Client();
$response = $client->get("https://darestreams.com/api/channels", [
    'query' => [
            "include" => "user,streams",
            "sort" => "-id",
            "page" => "16",
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
            "id": 37,
            "user_id": 38,
            "title": "vika_karter",
            "link": "https:\/\/www.twitch.tv\/vika_karter",
            "game_id": 3,
            "slug": "vika-karter",
            "description": "кибераутист",
            "provider": "twtich",
            "views": 4848372,
            "logo": "https:\/\/static-cdn.jtvnw.net\/jtv_user_pictures\/9dc75af9-44ad-4ad9-9cb5-23544ac3baae-profile_image-300x300.jpg",
            "overlay": "https:\/\/static-cdn.jtvnw.net\/jtv_user_pictures\/f4b283eb-d368-432b-bdef-83a04f0d3f67-channel_offline_image-1920x1080.jpeg",
            "created_at": "2019-09-06T12:08:35.000000Z",
            "user": {
                "id": 38,
                "name": "Dr. Roel Rogahn",
                "last_name": null,
                "middle_name": null,
                "full_name": "Dr. Roel Rogahn",
                "nickname": "vika_karter",
                "role_id": null,
                "avatar": "http:\/\/darestreams.local\/storage\/avatars\\d23521f4af9d6e56c628e045a5afc436.jpg",
                "overlay": "\/img\/default_overlay.jpg",
                "created_at": "2019-09-06T12:01:07.000000Z",
                "updated_at": "2019-09-06T12:01:07.000000Z",
                "lang": "ru"
            },
            "streams": []
        },
        {
            "id": 36,
            "user_id": 37,
            "title": "punshipun",
            "link": "https:\/\/www.twitch.tv\/punshipun",
            "game_id": 3,
            "slug": "punshipun",
            "description": "Аня развлекает аудиторию.",
            "provider": "twtich",
            "views": 2773751,
            "logo": "https:\/\/static-cdn.jtvnw.net\/jtv_user_pictures\/punshipun-profile_image-85520d4db8eca213-300x300.png",
            "overlay": "https:\/\/static-cdn.jtvnw.net\/jtv_user_pictures\/punshipun-channel_offline_image-05dda8dde6227467-1920x1080.jpeg",
            "created_at": "2019-09-06T12:08:32.000000Z",
            "user": {
                "id": 37,
                "name": "Mr. Reilly Vandervort PhD",
                "last_name": null,
                "middle_name": null,
                "full_name": "Mr. Reilly Vandervort PhD",
                "nickname": "punshipun",
                "role_id": null,
                "avatar": "http:\/\/darestreams.local\/storage\/avatars\\888f77746bd84ef6bcff565720a204ca.jpg",
                "overlay": "\/img\/default_overlay.jpg",
                "created_at": "2019-09-06T12:01:02.000000Z",
                "updated_at": "2019-09-06T12:01:02.000000Z",
                "lang": "ru"
            },
            "streams": []
        },
        {
            "id": 35,
            "user_id": 36,
            "title": "windy31",
            "link": "https:\/\/www.twitch.tv\/windy31",
            "game_id": 3,
            "slug": "windy31",
            "description": "",
            "provider": "twtich",
            "views": 1256674,
            "logo": "https:\/\/static-cdn.jtvnw.net\/jtv_user_pictures\/67fd1f1d-ab9c-4348-9575-ee91606bc01f-profile_image-300x300.jpg",
            "overlay": "https:\/\/static-cdn.jtvnw.net\/jtv_user_pictures\/574f8a86-ef3a-4e25-9175-c3a1cbec33e3-channel_offline_image-1920x1080.jpeg",
            "created_at": "2019-09-06T12:08:30.000000Z",
            "user": {
                "id": 36,
                "name": "Ms. Elaina Beatty III",
                "last_name": null,
                "middle_name": null,
                "full_name": "Ms. Elaina Beatty III",
                "nickname": "windy31",
                "role_id": null,
                "avatar": "http:\/\/darestreams.local\/storage\/avatars\\330a0303b48523a94f8a23c4397c4262.jpg",
                "overlay": "\/img\/default_overlay.jpg",
                "created_at": "2019-09-06T12:01:00.000000Z",
                "updated_at": "2019-09-06T12:01:00.000000Z",
                "lang": "ru"
            },
            "streams": []
        },
        {
            "id": 34,
            "user_id": 35,
            "title": "beastqt",
            "link": "https:\/\/www.twitch.tv\/beastqt",
            "game_id": 7,
            "slug": "beastqt",
            "description": " ",
            "provider": "twtich",
            "views": 10302252,
            "logo": "https:\/\/static-cdn.jtvnw.net\/jtv_user_pictures\/8db75fae-6138-4f16-ab18-a78bee03b8b0-profile_image-300x300.png",
            "overlay": "https:\/\/static-cdn.jtvnw.net\/jtv_user_pictures\/965345555c5a246c-channel_offline_image-1920x1080.jpeg",
            "created_at": "2019-09-06T12:08:28.000000Z",
            "user": {
                "id": 35,
                "name": "Clementina Kuvalis",
                "last_name": null,
                "middle_name": null,
                "full_name": "Clementina Kuvalis",
                "nickname": "beastqt",
                "role_id": null,
                "avatar": "http:\/\/darestreams.local\/storage\/avatars\\d0bca71b006d17d82826adb6ee08c736.jpg",
                "overlay": "\/img\/default_overlay.jpg",
                "created_at": "2019-09-06T12:00:59.000000Z",
                "updated_at": "2019-09-06T12:00:59.000000Z",
                "lang": "ru"
            },
            "streams": []
        },
        {
            "id": 33,
            "user_id": 34,
            "title": "scr3amqueen",
            "link": "https:\/\/www.twitch.tv\/scr3amqueen",
            "game_id": 9,
            "slug": "scr3amqueen",
            "description": "BOOM",
            "provider": "twtich",
            "views": 586053,
            "logo": "https:\/\/static-cdn.jtvnw.net\/jtv_user_pictures\/7d817865-3258-40b1-a110-6493f8c11842-profile_image-300x300.png",
            "overlay": "https:\/\/static-cdn.jtvnw.net\/jtv_user_pictures\/36f781ca-c6ac-49b2-88a4-18366e83e750-channel_offline_image-1920x1080.jpeg",
            "created_at": "2019-09-06T12:08:25.000000Z",
            "user": {
                "id": 34,
                "name": "Emmie Fisher",
                "last_name": null,
                "middle_name": null,
                "full_name": "Emmie Fisher",
                "nickname": "scr3amqueen",
                "role_id": null,
                "avatar": "http:\/\/darestreams.local\/storage\/avatars\\647305dc95e7baf1327a553f18c33e03.jpg",
                "overlay": "\/img\/default_overlay.jpg",
                "created_at": "2019-09-06T12:00:58.000000Z",
                "updated_at": "2019-09-06T12:00:58.000000Z",
                "lang": "ru"
            },
            "streams": []
        },
        {
            "id": 32,
            "user_id": 33,
            "title": "hellyeahplay",
            "link": "https:\/\/www.twitch.tv\/hellyeahplay",
            "game_id": 3,
            "slug": "hellyeahplay",
            "description": "Мизантроп с психическим расстройством.",
            "provider": "twtich",
            "views": 8594209,
            "logo": "https:\/\/static-cdn.jtvnw.net\/jtv_user_pictures\/hellyeahplay-profile_image-7b27caab4aefe1ad-300x300.png",
            "overlay": "https:\/\/static-cdn.jtvnw.net\/jtv_user_pictures\/hellyeahplay-channel_offline_image-17cf79d8aa5eb833-1920x1080.jpeg",
            "created_at": "2019-09-06T12:08:23.000000Z",
            "user": {
                "id": 33,
                "name": "Missouri Blanda",
                "last_name": null,
                "middle_name": null,
                "full_name": "Missouri Blanda",
                "nickname": "hellyeahplay",
                "role_id": null,
                "avatar": "http:\/\/darestreams.local\/storage\/avatars\\e101eae499b2d23c24a58530be98e987.jpg",
                "overlay": "\/img\/default_overlay.jpg",
                "created_at": "2019-09-06T12:00:57.000000Z",
                "updated_at": "2019-09-06T12:00:57.000000Z",
                "lang": "ru"
            },
            "streams": []
        },
        {
            "id": 31,
            "user_id": 32,
            "title": "mihalina_",
            "link": "https:\/\/www.twitch.tv\/mihalina_",
            "game_id": 3,
            "slug": "mihalina",
            "description": "",
            "provider": "twtich",
            "views": 2388150,
            "logo": "https:\/\/static-cdn.jtvnw.net\/jtv_user_pictures\/8d39e01d-87cd-4a48-84ca-3f378153c0ac-profile_image-300x300.png",
            "overlay": "https:\/\/static-cdn.jtvnw.net\/jtv_user_pictures\/70139f30-1bcc-45c8-b5e9-2150e0568b99-channel_offline_image-1920x1080.png",
            "created_at": "2019-09-06T12:08:21.000000Z",
            "user": {
                "id": 32,
                "name": "Catharine McCullough DDS",
                "last_name": null,
                "middle_name": null,
                "full_name": "Catharine McCullough DDS",
                "nickname": "mihalina_",
                "role_id": null,
                "avatar": "http:\/\/darestreams.local\/storage\/avatars\\163ed5263011649a33936d244c4eb162.jpg",
                "overlay": "\/img\/default_overlay.jpg",
                "created_at": "2019-09-06T12:00:56.000000Z",
                "updated_at": "2019-09-06T12:00:56.000000Z",
                "lang": "ru"
            },
            "streams": []
        },
        {
            "id": 30,
            "user_id": 31,
            "title": "olesyabulletka",
            "link": "https:\/\/www.twitch.tv\/olesyabulletka",
            "game_id": 3,
            "slug": "olesyabulletka",
            "description": "Та самая девушка с шестом..",
            "provider": "twtich",
            "views": 7896196,
            "logo": "https:\/\/static-cdn.jtvnw.net\/jtv_user_pictures\/36b1ff65-f060-49c2-8b67-f7233a928519-profile_image-300x300.png",
            "overlay": "https:\/\/static-cdn.jtvnw.net\/jtv_user_pictures\/2d305a45-0f8b-45bc-a0fe-3195148fea02-channel_offline_image-1920x1080.png",
            "created_at": "2019-09-06T12:08:18.000000Z",
            "user": {
                "id": 31,
                "name": "Kasey Labadie",
                "last_name": null,
                "middle_name": null,
                "full_name": "Kasey Labadie",
                "nickname": "olesyabulletka",
                "role_id": null,
                "avatar": "http:\/\/darestreams.local\/storage\/avatars\\336c5643b90e9a4fc9e1378cb76bc885.jpg",
                "overlay": "\/img\/default_overlay.jpg",
                "created_at": "2019-09-06T12:00:49.000000Z",
                "updated_at": "2019-09-06T12:00:49.000000Z",
                "lang": "ru"
            },
            "streams": []
        },
        {
            "id": 29,
            "user_id": 30,
            "title": "lasqa",
            "link": "https:\/\/www.twitch.tv\/lasqa",
            "game_id": 1,
            "slug": "lasqa",
            "description": "Группа ВК — vk.com\/LasqaTV",
            "provider": "twtich",
            "views": 15465780,
            "logo": "https:\/\/static-cdn.jtvnw.net\/jtv_user_pictures\/lasqa-profile_image-49dc25f1e724dbd6-300x300.jpeg",
            "overlay": "https:\/\/static-cdn.jtvnw.net\/jtv_user_pictures\/ed58375ada58371d-channel_offline_image-1920x1080.jpeg",
            "created_at": "2019-09-06T12:08:15.000000Z",
            "user": {
                "id": 30,
                "name": "Maverick Howell",
                "last_name": null,
                "middle_name": null,
                "full_name": "Maverick Howell",
                "nickname": "lasqa",
                "role_id": null,
                "avatar": "http:\/\/darestreams.local\/storage\/avatars\\cb2f704d59badcd4a75700436ffff7b2.jpg",
                "overlay": "\/img\/default_overlay.jpg",
                "created_at": "2019-09-06T12:00:40.000000Z",
                "updated_at": "2019-09-06T12:00:40.000000Z",
                "lang": "ru"
            },
            "streams": []
        },
        {
            "id": 28,
            "user_id": 29,
            "title": "gufovicky",
            "link": "https:\/\/www.twitch.tv\/gufovicky",
            "game_id": 1,
            "slug": "gufovicky",
            "description": "Потрясающие трансляции лайк подписка ",
            "provider": "twtich",
            "views": 3978117,
            "logo": "https:\/\/static-cdn.jtvnw.net\/jtv_user_pictures\/gufovicky-profile_image-48074644a6341ab3-300x300.jpeg",
            "overlay": "https:\/\/static-cdn.jtvnw.net\/jtv_user_pictures\/gufovicky-channel_offline_image-705cf58394f848ed-1920x1080.jpeg",
            "created_at": "2019-09-06T12:08:13.000000Z",
            "user": {
                "id": 29,
                "name": "Kelley Langworth",
                "last_name": null,
                "middle_name": null,
                "full_name": "Kelley Langworth",
                "nickname": "gufovicky",
                "role_id": null,
                "avatar": "http:\/\/darestreams.local\/storage\/avatars\\1e0f95dead43af70e7b9ec10af9dfc00.jpg",
                "overlay": "\/img\/default_overlay.jpg",
                "created_at": "2019-09-06T12:00:35.000000Z",
                "updated_at": "2019-09-06T12:00:35.000000Z",
                "lang": "ru"
            },
            "streams": []
        },
        {
            "id": 27,
            "user_id": 28,
            "title": "zanuda",
            "link": "https:\/\/www.twitch.tv\/zanuda",
            "game_id": 14,
            "slug": "zanuda",
            "description": "знд",
            "provider": "twtich",
            "views": 6018001,
            "logo": "https:\/\/static-cdn.jtvnw.net\/jtv_user_pictures\/d27cfa24-49d5-48c8-886d-dc360ea186e6-profile_image-300x300.png",
            "overlay": "https:\/\/static-cdn.jtvnw.net\/jtv_user_pictures\/3d29b8e3-c8e9-404e-b058-f05614a9e034-channel_offline_image-1920x1080.jpeg",
            "created_at": "2019-09-06T12:08:11.000000Z",
            "user": {
                "id": 28,
                "name": "Natasha Marquardt",
                "last_name": null,
                "middle_name": null,
                "full_name": "Natasha Marquardt",
                "nickname": "zanuda",
                "role_id": null,
                "avatar": "http:\/\/darestreams.local\/storage\/avatars\\1ab08cf3ead3f55049311edc85fa8823.jpg",
                "overlay": "\/img\/default_overlay.jpg",
                "created_at": "2019-09-06T12:00:31.000000Z",
                "updated_at": "2019-09-06T12:00:31.000000Z",
                "lang": "ru"
            },
            "streams": []
        },
        {
            "id": 26,
            "user_id": 27,
            "title": "sorabi_",
            "link": "https:\/\/www.twitch.tv\/sorabi_",
            "game_id": 3,
            "slug": "sorabi",
            "description": "\"Ты то, что ты ешь\", говорили они. Но я не помню, чтобы я ела такую красотку Kappa",
            "provider": "twtich",
            "views": 4619589,
            "logo": "https:\/\/static-cdn.jtvnw.net\/jtv_user_pictures\/d30e9c53-9263-44cf-8416-d6adf3094d97-profile_image-300x300.png",
            "overlay": "https:\/\/static-cdn.jtvnw.net\/jtv_user_pictures\/77f67ececffd7b36-channel_offline_image-1920x1080.jpeg",
            "created_at": "2019-09-06T12:08:08.000000Z",
            "user": {
                "id": 27,
                "name": "Cecelia Lindgren",
                "last_name": null,
                "middle_name": null,
                "full_name": "Cecelia Lindgren",
                "nickname": "sorabi_",
                "role_id": null,
                "avatar": "http:\/\/darestreams.local\/storage\/avatars\\f766b2b201be8ea74dcc6d7564c601fe.jpg",
                "overlay": "\/img\/default_overlay.jpg",
                "created_at": "2019-09-06T12:00:30.000000Z",
                "updated_at": "2019-09-06T12:00:30.000000Z",
                "lang": "ru"
            },
            "streams": []
        },
        {
            "id": 25,
            "user_id": 26,
            "title": "modestal",
            "link": "https:\/\/www.twitch.tv\/modestal",
            "game_id": 3,
            "slug": "modestal",
            "description": "Дело  семейное,да?;)",
            "provider": "twtich",
            "views": 4979298,
            "logo": "https:\/\/static-cdn.jtvnw.net\/jtv_user_pictures\/1c49e85f-765d-46cf-afb0-d446a57d3f43-profile_image-300x300.jpeg",
            "overlay": "https:\/\/static-cdn.jtvnw.net\/jtv_user_pictures\/3d0cfd5c-8bff-47a4-afdc-70ef9012ffc9-channel_offline_image-1920x1080.jpeg",
            "created_at": "2019-09-06T12:08:06.000000Z",
            "user": {
                "id": 26,
                "name": "Jovanny Veum III",
                "last_name": null,
                "middle_name": null,
                "full_name": "Jovanny Veum III",
                "nickname": "modestal",
                "role_id": null,
                "avatar": "http:\/\/darestreams.local\/storage\/avatars\\454dd747b4b2e802e8f994713c464433.jpg",
                "overlay": "\/img\/default_overlay.jpg",
                "created_at": "2019-09-06T12:00:29.000000Z",
                "updated_at": "2019-09-06T12:00:29.000000Z",
                "lang": "ru"
            },
            "streams": []
        },
        {
            "id": 24,
            "user_id": 25,
            "title": "happasc2",
            "link": "https:\/\/www.twitch.tv\/happasc2",
            "game_id": 5,
            "slug": "happasc2",
            "description": "Сашуля 29 годиков!",
            "provider": "twtich",
            "views": 17684791,
            "logo": "https:\/\/static-cdn.jtvnw.net\/jtv_user_pictures\/happasc2-profile_image-e9e00117c2df65ba-300x300.jpeg",
            "overlay": "https:\/\/static-cdn.jtvnw.net\/jtv_user_pictures\/817323e1-df5e-4f0e-b0b1-114c9584df3d-channel_offline_image-1920x1080.jpeg",
            "created_at": "2019-09-06T12:08:04.000000Z",
            "user": {
                "id": 25,
                "name": "Adela Rutherford",
                "last_name": null,
                "middle_name": null,
                "full_name": "Adela Rutherford",
                "nickname": "happasc2",
                "role_id": null,
                "avatar": "http:\/\/darestreams.local\/storage\/avatars\\9af17e7a02bdb4123d8ad4844020cbd0.jpg",
                "overlay": "\/img\/default_overlay.jpg",
                "created_at": "2019-09-06T12:00:29.000000Z",
                "updated_at": "2019-09-06T12:00:29.000000Z",
                "lang": "ru"
            },
            "streams": []
        },
        {
            "id": 23,
            "user_id": 24,
            "title": "denly",
            "link": "https:\/\/www.twitch.tv\/denly",
            "game_id": 7,
            "slug": "denly",
            "description": "Лиза",
            "provider": "twtich",
            "views": 5256539,
            "logo": "https:\/\/static-cdn.jtvnw.net\/jtv_user_pictures\/0e0aa7fb-af1d-4c74-af68-acf3c16c36e2-profile_image-300x300.png",
            "overlay": "https:\/\/static-cdn.jtvnw.net\/jtv_user_pictures\/c137d26c-8f68-406b-8505-1f52c1b3f497-channel_offline_image-1920x1080.jpeg",
            "created_at": "2019-09-06T12:08:01.000000Z",
            "user": {
                "id": 24,
                "name": "Kathryn Weimann",
                "last_name": null,
                "middle_name": null,
                "full_name": "Kathryn Weimann",
                "nickname": "denly",
                "role_id": null,
                "avatar": "http:\/\/darestreams.local\/storage\/avatars\\7067c5d4e69f0179e02c34888b6252f8.jpg",
                "overlay": "\/img\/default_overlay.jpg",
                "created_at": "2019-09-06T12:00:28.000000Z",
                "updated_at": "2019-09-06T12:00:28.000000Z",
                "lang": "ru"
            },
            "streams": []
        },
        {
            "id": 22,
            "user_id": 23,
            "title": "kuplinov",
            "link": "https:\/\/www.twitch.tv\/kuplinov",
            "game_id": 1,
            "slug": "kuplinov",
            "description": "Игры тут играются.",
            "provider": "twtich",
            "views": 2896200,
            "logo": "https:\/\/static-cdn.jtvnw.net\/jtv_user_pictures\/f4ca1d69-9eee-45a2-8509-079a3e5630df-profile_image-300x300.jpg",
            "overlay": "https:\/\/static-cdn.jtvnw.net\/jtv_user_pictures\/0e17368b48dce82a-channel_offline_image-1920x1080.jpeg",
            "created_at": "2019-09-06T12:07:59.000000Z",
            "user": {
                "id": 23,
                "name": "Michele Hahn",
                "last_name": null,
                "middle_name": null,
                "full_name": "Michele Hahn",
                "nickname": "kuplinov",
                "role_id": null,
                "avatar": "http:\/\/darestreams.local\/storage\/avatars\\778a82e28d987ee5abc2e904883b081b.jpg",
                "overlay": "\/img\/default_overlay.jpg",
                "created_at": "2019-09-06T12:00:27.000000Z",
                "updated_at": "2019-09-06T12:00:27.000000Z",
                "lang": "ru"
            },
            "streams": []
        },
        {
            "id": 21,
            "user_id": 22,
            "title": "ahrinyan",
            "link": "https:\/\/www.twitch.tv\/ahrinyan",
            "game_id": 3,
            "slug": "ahrinyan",
            "description": "За чо мама стримером родила",
            "provider": "twtich",
            "views": 4911672,
            "logo": "https:\/\/static-cdn.jtvnw.net\/jtv_user_pictures\/637e3960-e09a-476a-8ab1-8c87f09d26ce-profile_image-300x300.png",
            "overlay": "https:\/\/static-cdn.jtvnw.net\/jtv_user_pictures\/7ca52dda-4d4a-4f3d-8740-eea8dc5be465-channel_offline_image-1920x1080.png",
            "created_at": "2019-09-06T12:07:57.000000Z",
            "user": {
                "id": 22,
                "name": "Mrs. Joana Mertz DVM",
                "last_name": null,
                "middle_name": null,
                "full_name": "Mrs. Joana Mertz DVM",
                "nickname": "ahrinyan",
                "role_id": null,
                "avatar": "http:\/\/darestreams.local\/storage\/avatars\\99badc7faced50d633d6c4a0d302e7e1.jpg",
                "overlay": "\/img\/default_overlay.jpg",
                "created_at": "2019-09-06T12:00:26.000000Z",
                "updated_at": "2019-09-06T12:00:26.000000Z",
                "lang": "ru"
            },
            "streams": []
        },
        {
            "id": 20,
            "user_id": 21,
            "title": "mira",
            "link": "https:\/\/www.twitch.tv\/mira",
            "game_id": 3,
            "slug": "mira",
            "description": "=]",
            "provider": "twtich",
            "views": 18207595,
            "logo": "https:\/\/static-cdn.jtvnw.net\/jtv_user_pictures\/3bfa43d9-6ed6-4006-878a-35edc1e09213-profile_image-300x300.png",
            "overlay": "https:\/\/static-cdn.jtvnw.net\/jtv_user_pictures\/mira-channel_offline_image-a2f49cb3c2e4a096-1920x1080.jpeg",
            "created_at": "2019-09-06T12:07:54.000000Z",
            "user": {
                "id": 21,
                "name": "Joyce Schaden",
                "last_name": null,
                "middle_name": null,
                "full_name": "Joyce Schaden",
                "nickname": "mira",
                "role_id": null,
                "avatar": "http:\/\/darestreams.local\/storage\/avatars\\6f4f26027b25e00d8b2668174ca70cbc.jpg",
                "overlay": "\/img\/default_overlay.jpg",
                "created_at": "2019-09-06T12:00:25.000000Z",
                "updated_at": "2019-09-06T12:00:25.000000Z",
                "lang": "ru"
            },
            "streams": []
        },
        {
            "id": 19,
            "user_id": 20,
            "title": "tati",
            "link": "https:\/\/www.twitch.tv\/tati",
            "game_id": 7,
            "slug": "tati",
            "description": "",
            "provider": "twtich",
            "views": 12687055,
            "logo": "https:\/\/static-cdn.jtvnw.net\/jtv_user_pictures\/f40c1600-3127-4cf0-882b-3d09528bc738-profile_image-300x300.png",
            "overlay": "https:\/\/static-cdn.jtvnw.net\/jtv_user_pictures\/1ed13eaf-47c6-4e4b-a38f-6fe54e833065-channel_offline_image-1920x1080.jpeg",
            "created_at": "2019-09-06T12:07:52.000000Z",
            "user": {
                "id": 20,
                "name": "Corene Tillman",
                "last_name": null,
                "middle_name": null,
                "full_name": "Corene Tillman",
                "nickname": "tati",
                "role_id": null,
                "avatar": "http:\/\/darestreams.local\/storage\/avatars\\d66ac994d5aeb6d9e4ca8165e5437fe6.jpg",
                "overlay": "\/img\/default_overlay.jpg",
                "created_at": "2019-09-06T12:00:24.000000Z",
                "updated_at": "2019-09-06T12:00:24.000000Z",
                "lang": "ru"
            },
            "streams": []
        },
        {
            "id": 18,
            "user_id": 19,
            "title": "tenderlybae",
            "link": "https:\/\/www.twitch.tv\/tenderlybae",
            "game_id": 3,
            "slug": "tenderlybae",
            "description": "",
            "provider": "twtich",
            "views": 5476650,
            "logo": "https:\/\/static-cdn.jtvnw.net\/jtv_user_pictures\/6be91ff6-cd90-4797-89b1-e89329a44ce8-profile_image-300x300.png",
            "overlay": "https:\/\/static-cdn.jtvnw.net\/jtv_user_pictures\/8c2ef276-31df-4e17-b285-2ca852ac9c92-channel_offline_image-1920x1080.png",
            "created_at": "2019-09-06T12:07:50.000000Z",
            "user": {
                "id": 19,
                "name": "Mrs. Sincere Moore Jr.",
                "last_name": null,
                "middle_name": null,
                "full_name": "Mrs. Sincere Moore Jr.",
                "nickname": "tenderlybae",
                "role_id": null,
                "avatar": "http:\/\/darestreams.local\/storage\/avatars\\a037aa6931700df9d7e00ecd24a19802.jpg",
                "overlay": "\/img\/default_overlay.jpg",
                "created_at": "2019-09-06T12:00:23.000000Z",
                "updated_at": "2019-09-06T12:00:23.000000Z",
                "lang": "ru"
            },
            "streams": []
        },
        {
            "id": 17,
            "user_id": 18,
            "title": "olyashaa",
            "link": "https:\/\/www.twitch.tv\/olyashaa",
            "game_id": 3,
            "slug": "olyashaa",
            "description": "ничего не спрашивай, просто вступи в группу http:\/\/vk.com\/twitcholyashaa",
            "provider": "twtich",
            "views": 13685230,
            "logo": "https:\/\/static-cdn.jtvnw.net\/jtv_user_pictures\/olyashaa-profile_image-678836346723f273-300x300.png",
            "overlay": "https:\/\/static-cdn.jtvnw.net\/jtv_user_pictures\/12c32347-30a1-43dd-991b-910133fde4d1-channel_offline_image-1920x1080.png",
            "created_at": "2019-09-06T12:07:47.000000Z",
            "user": {
                "id": 18,
                "name": "Durward Rolfson",
                "last_name": null,
                "middle_name": null,
                "full_name": "Durward Rolfson",
                "nickname": "olyashaa",
                "role_id": null,
                "avatar": "http:\/\/darestreams.local\/storage\/avatars\\43abef60c09fd3c18c4cc7e81d75a6c7.jpg",
                "overlay": "\/img\/default_overlay.jpg",
                "created_at": "2019-09-06T12:00:22.000000Z",
                "updated_at": "2019-09-06T12:00:22.000000Z",
                "lang": "ru"
            },
            "streams": []
        },
        {
            "id": 16,
            "user_id": 17,
            "title": "jesusavgn",
            "link": "https:\/\/www.twitch.tv\/jesusavgn",
            "game_id": 3,
            "slug": "jesusavgn",
            "description": "",
            "provider": "twtich",
            "views": 24311721,
            "logo": "https:\/\/static-cdn.jtvnw.net\/jtv_user_pictures\/jesusavgn-profile_image-ef60f6d58af4ccef-300x300.jpeg",
            "overlay": "https:\/\/static-cdn.jtvnw.net\/jtv_user_pictures\/jesusavgn-channel_offline_image-d6fde5154b88da29-1920x1080.jpeg",
            "created_at": "2019-09-06T12:07:45.000000Z",
            "user": {
                "id": 17,
                "name": "Rogelio Robel",
                "last_name": null,
                "middle_name": null,
                "full_name": "Rogelio Robel",
                "nickname": "jesusavgn",
                "role_id": null,
                "avatar": "http:\/\/darestreams.local\/storage\/avatars\\651de4ba2b0d2456e4f55fcacbeb5015.jpg",
                "overlay": "\/img\/default_overlay.jpg",
                "created_at": "2019-09-06T12:00:20.000000Z",
                "updated_at": "2019-09-06T12:00:20.000000Z",
                "lang": "ru"
            },
            "streams": []
        },
        {
            "id": 15,
            "user_id": 15,
            "title": "stpeach",
            "link": "https:\/\/www.twitch.tv\/stpeach",
            "game_id": 2,
            "slug": "stpeach",
            "description": "Streamer from Canada Living in Califonia ♥",
            "provider": "twtich",
            "views": 26719952,
            "logo": "https:\/\/static-cdn.jtvnw.net\/jtv_user_pictures\/f8b8c774-e34d-40d9-ba21-9a81cfda73aa-profile_image-300x300.png",
            "overlay": "https:\/\/static-cdn.jtvnw.net\/jtv_user_pictures\/47385bfd-223f-4bc3-8e74-19a967b13eea-channel_offline_image-1920x1080.png",
            "created_at": "2019-09-06T12:07:40.000000Z",
            "user": {
                "id": 15,
                "name": "Prof. Lyda Langosh MD",
                "last_name": null,
                "middle_name": null,
                "full_name": "Prof. Lyda Langosh MD",
                "nickname": "stpeach",
                "role_id": null,
                "avatar": "http:\/\/darestreams.local\/storage\/avatars\\bb58f507874c89471438d74b06bb9bda.jpg",
                "overlay": "\/img\/default_overlay.jpg",
                "created_at": "2019-09-06T12:00:18.000000Z",
                "updated_at": "2019-09-06T12:00:18.000000Z",
                "lang": "ru"
            },
            "streams": []
        },
        {
            "id": 14,
            "user_id": 14,
            "title": "noway4u_sir",
            "link": "https:\/\/www.twitch.tv\/noway4u_sir",
            "game_id": 2,
            "slug": "noway4u-sir",
            "description": "Bonobo Content",
            "provider": "twtich",
            "views": 34648500,
            "logo": "https:\/\/static-cdn.jtvnw.net\/jtv_user_pictures\/9e619d88755f56a8-profile_image-300x300.png",
            "overlay": "https:\/\/static-cdn.jtvnw.net\/jtv_user_pictures\/noway4u_sir-channel_offline_image-ac5619d4e71d4525-1920x1080.jpeg",
            "created_at": "2019-09-06T12:07:38.000000Z",
            "user": {
                "id": 14,
                "name": "Rozella Bergstrom",
                "last_name": null,
                "middle_name": null,
                "full_name": "Rozella Bergstrom",
                "nickname": "noway4u_sir",
                "role_id": null,
                "avatar": "http:\/\/darestreams.local\/storage\/avatars\\4000e2a2e55d93a4d4e1e2b2dcc127ab.jpg",
                "overlay": "\/img\/default_overlay.jpg",
                "created_at": "2019-09-06T12:00:17.000000Z",
                "updated_at": "2019-09-06T12:00:17.000000Z",
                "lang": "ru"
            },
            "streams": []
        },
        {
            "id": 13,
            "user_id": 13,
            "title": "shroud",
            "link": "https:\/\/www.twitch.tv\/shroud",
            "game_id": 1,
            "slug": "shroud",
            "description": "Enjoy these highlights\/vods, and remember to follow!",
            "provider": "twtich",
            "views": 352268667,
            "logo": "https:\/\/static-cdn.jtvnw.net\/jtv_user_pictures\/7ed5e0c6-0191-4eef-8328-4af6e4ea5318-profile_image-300x300.png",
            "overlay": "https:\/\/static-cdn.jtvnw.net\/jtv_user_pictures\/f328a514-0cda-4239-9f99-24670b7ed7cb-channel_offline_image-1920x1080.png",
            "created_at": "2019-09-06T12:07:36.000000Z",
            "user": {
                "id": 13,
                "name": "Marcelle Gulgowski",
                "last_name": null,
                "middle_name": null,
                "full_name": "Marcelle Gulgowski",
                "nickname": "shroud",
                "role_id": null,
                "avatar": "http:\/\/darestreams.local\/storage\/avatars\\cf7e20da438542cc7546c6cc020b6441.jpg",
                "overlay": "\/img\/default_overlay.jpg",
                "created_at": "2019-09-06T12:00:16.000000Z",
                "updated_at": "2019-09-06T12:00:16.000000Z",
                "lang": "ru"
            },
            "streams": []
        },
        {
            "id": 12,
            "user_id": 12,
            "title": "yoda",
            "link": "https:\/\/www.twitch.tv\/yoda",
            "game_id": 15,
            "slug": "yoda",
            "description": "Felipe 'YoDa', Atleta de League of Legends e Streamer. Desde 2014 é o maior Streamer Gamer Brasileiro, onde alcança mais de 25K pessoas ao vivo por dia na plataforma Twitch.tv. É Proplayer do time da RED CANIDS Kalunga e embaixador de eSports mundial da RED BULL e Campeão Brasileiro do CBLOL. ",
            "provider": "twtich",
            "views": 140866600,
            "logo": "https:\/\/static-cdn.jtvnw.net\/jtv_user_pictures\/yoda-profile_image-63cdc656c9f91fb4-300x300.jpeg",
            "overlay": "https:\/\/static-cdn.jtvnw.net\/jtv_user_pictures\/b3b6d1fa-1aa5-470a-9a23-c435e300de68-channel_offline_image-1920x1080.png",
            "created_at": "2019-09-06T12:07:33.000000Z",
            "user": {
                "id": 12,
                "name": "Mrs. Cheyenne Cronin Jr.",
                "last_name": null,
                "middle_name": null,
                "full_name": "Mrs. Cheyenne Cronin Jr.",
                "nickname": "yoda",
                "role_id": null,
                "avatar": "http:\/\/darestreams.local\/storage\/avatars\\bba588aa58c0b24724eef6811715f776.jpg",
                "overlay": "\/img\/default_overlay.jpg",
                "created_at": "2019-09-06T12:00:15.000000Z",
                "updated_at": "2019-09-06T12:00:15.000000Z",
                "lang": "ru"
            },
            "streams": []
        },
        {
            "id": 11,
            "user_id": 11,
            "title": "copykat_",
            "link": "https:\/\/www.twitch.tv\/copykat_",
            "game_id": 1,
            "slug": "copykat",
            "description": "Mindfullness, relaxation, ASMR. ❤️",
            "provider": "twtich",
            "views": 3958062,
            "logo": "https:\/\/static-cdn.jtvnw.net\/jtv_user_pictures\/6e73a059-9b56-4cb9-a7ee-2785c2248476-profile_image-300x300.jpg",
            "overlay": "\/img\/default_channel_overlay.jpg",
            "created_at": "2019-09-06T12:07:31.000000Z",
            "user": {
                "id": 11,
                "name": "Joshua Brakus",
                "last_name": null,
                "middle_name": null,
                "full_name": "Joshua Brakus",
                "nickname": "copykat_",
                "role_id": null,
                "avatar": "http:\/\/darestreams.local\/storage\/avatars\\669644f7d320d1090945318306768ead.jpg",
                "overlay": "\/img\/default_overlay.jpg",
                "created_at": "2019-09-06T12:00:14.000000Z",
                "updated_at": "2019-09-06T12:00:14.000000Z",
                "lang": "ru"
            },
            "streams": []
        },
        {
            "id": 10,
            "user_id": 10,
            "title": "rocketbeanstv",
            "link": "https:\/\/www.twitch.tv\/rocketbeanstv",
            "game_id": 1,
            "slug": "rocketbeanstv",
            "description": "Rocket Beans TV, das sind Daniel Budiman (Budi), Simon Krätschmer, Etienne Gardé (Eddy) und Nils Bomhoff. Bekannt durch Formate wie GIGA oder Game One, machen wir hier alles, was woanders nicht reinpasst!",
            "provider": "twtich",
            "views": 77371327,
            "logo": "https:\/\/static-cdn.jtvnw.net\/jtv_user_pictures\/2ac31cc4-78cf-4fa3-b535-3b9c80d46250-profile_image-300x300.png",
            "overlay": "https:\/\/static-cdn.jtvnw.net\/jtv_user_pictures\/690507f5c8c406de-channel_offline_image-1920x1080.png",
            "created_at": "2019-09-06T12:07:29.000000Z",
            "user": {
                "id": 10,
                "name": "Asha Hickle",
                "last_name": null,
                "middle_name": null,
                "full_name": "Asha Hickle",
                "nickname": "rocketbeanstv",
                "role_id": null,
                "avatar": "http:\/\/darestreams.local\/storage\/avatars\\089aef56fb19abf5bac11f4de5ff42db.jpg",
                "overlay": "\/img\/default_overlay.jpg",
                "created_at": "2019-09-06T12:00:13.000000Z",
                "updated_at": "2019-09-06T12:00:13.000000Z",
                "lang": "ru"
            },
            "streams": []
        },
        {
            "id": 9,
            "user_id": 9,
            "title": "exbc",
            "link": "https:\/\/www.twitch.tv\/exbc",
            "game_id": 3,
            "slug": "exbc",
            "description": "http:\/\/www.youtube.com\/user\/exbctv ",
            "provider": "twtich",
            "views": 12024009,
            "logo": "https:\/\/static-cdn.jtvnw.net\/jtv_user_pictures\/exbc-profile_image-b123ccd6d2990eaa-300x300.jpeg",
            "overlay": "https:\/\/static-cdn.jtvnw.net\/jtv_user_pictures\/67961818-beff-4c8a-950c-a0eac91f7c36-channel_offline_image-1920x1080.jpeg",
            "created_at": "2019-09-06T12:07:26.000000Z",
            "user": {
                "id": 9,
                "name": "Prof. Rafaela Ferry",
                "last_name": null,
                "middle_name": null,
                "full_name": "Prof. Rafaela Ferry",
                "nickname": "exbc",
                "role_id": null,
                "avatar": "http:\/\/darestreams.local\/storage\/avatars\\fb62465acce16465f1e09f2fbd9336ad.jpg",
                "overlay": "\/img\/default_overlay.jpg",
                "created_at": "2019-09-06T12:00:12.000000Z",
                "updated_at": "2019-09-06T12:00:12.000000Z",
                "lang": "ru"
            },
            "streams": []
        },
        {
            "id": 8,
            "user_id": 8,
            "title": "asmr_kotya",
            "link": "https:\/\/www.twitch.tv\/asmr_kotya",
            "game_id": 1,
            "slug": "asmr-kotya",
            "description": "♥Стараюсь подарить Вам умиротворение и покой..Приходите на мой канал за Котятерапией♥",
            "provider": "twtich",
            "views": 2152578,
            "logo": "https:\/\/static-cdn.jtvnw.net\/jtv_user_pictures\/1a5944f7-1f9a-4d47-b372-6a6d3726c5fd-profile_image-300x300.png",
            "overlay": "https:\/\/static-cdn.jtvnw.net\/jtv_user_pictures\/2c783ec1-78aa-44c2-ae9d-1d49fb32fa6c-channel_offline_image-1920x1080.jpeg",
            "created_at": "2019-09-06T12:07:24.000000Z",
            "user": {
                "id": 8,
                "name": "Michale Brekke",
                "last_name": null,
                "middle_name": null,
                "full_name": "Michale Brekke",
                "nickname": "asmr_kotya",
                "role_id": null,
                "avatar": "http:\/\/darestreams.local\/storage\/avatars\\9e247c7b788c3231e5f27bf9c0461275.jpg",
                "overlay": "\/img\/default_overlay.jpg",
                "created_at": "2019-09-06T12:00:11.000000Z",
                "updated_at": "2019-09-06T12:00:11.000000Z",
                "lang": "ru"
            },
            "streams": []
        },
        {
            "id": 7,
            "user_id": 7,
            "title": "gaules",
            "link": "https:\/\/www.twitch.tv\/gaules",
            "game_id": 10,
            "slug": "gaules",
            "description": "Focado no e-Sport desde 1998, fiz parte da primeira geração de jogadores profissionais do mundo. Atuei 9 anos como jogador, 3 anos como técnico, sendo o primeiro a conquistar um título mundial no CS nesta função. Nos últimos 7 anos me dedico ao empreendedorismo no mercado de eSport.",
            "provider": "twtich",
            "views": 66756861,
            "logo": "https:\/\/static-cdn.jtvnw.net\/jtv_user_pictures\/c495b77e-7f47-4bc5-a216-3045d7545796-profile_image-300x300.png",
            "overlay": "https:\/\/static-cdn.jtvnw.net\/jtv_user_pictures\/3de00e1ebbe194c8-channel_offline_image-1920x1080.png",
            "created_at": "2019-09-06T12:07:22.000000Z",
            "user": {
                "id": 7,
                "name": "Dudley Schowalter",
                "last_name": null,
                "middle_name": null,
                "full_name": "Dudley Schowalter",
                "nickname": "gaules",
                "role_id": null,
                "avatar": "http:\/\/darestreams.local\/storage\/avatars\\2f47d423cb915f1431f8513ac64c4386.jpg",
                "overlay": "\/img\/default_overlay.jpg",
                "created_at": "2019-09-06T12:00:10.000000Z",
                "updated_at": "2019-09-06T12:00:10.000000Z",
                "lang": "ru"
            },
            "streams": []
        },
        {
            "id": 6,
            "user_id": 6,
            "title": "violettavalery",
            "link": "https:\/\/www.twitch.tv\/violettavalery",
            "game_id": 1,
            "slug": "violettavalery",
            "description": "Всем привет, меня зовут Виолетта (да, это моё настоящее имя). В основном на моем канале идут АСМР стримы, иногда играем в игры, иногда просто мартышкаем) ",
            "provider": "twtich",
            "views": 1108601,
            "logo": "https:\/\/static-cdn.jtvnw.net\/jtv_user_pictures\/aea78f81-2550-4e08-9ae4-90374a969ac9-profile_image-300x300.jpg",
            "overlay": "https:\/\/static-cdn.jtvnw.net\/jtv_user_pictures\/a4a0c1f55ac3c514-channel_offline_image-1920x1080.jpeg",
            "created_at": "2019-09-06T12:07:19.000000Z",
            "user": {
                "id": 6,
                "name": "Dr. Lacy Lang",
                "last_name": null,
                "middle_name": null,
                "full_name": "Dr. Lacy Lang",
                "nickname": "violettavalery",
                "role_id": null,
                "avatar": "http:\/\/darestreams.local\/storage\/avatars\\6d3c8e1db1b6ff692fcacbf617aa7a09.jpg",
                "overlay": "\/img\/default_overlay.jpg",
                "created_at": "2019-09-06T12:00:09.000000Z",
                "updated_at": "2019-09-06T12:00:09.000000Z",
                "lang": "ru"
            },
            "streams": []
        },
        {
            "id": 5,
            "user_id": 5,
            "title": "texaswildlife",
            "link": "https:\/\/www.twitch.tv\/texaswildlife",
            "game_id": 1,
            "slug": "texaswildlife",
            "description": "Live cameras of birds and other Texas wildlife. No telling what you will see when you join the stream!",
            "provider": "twtich",
            "views": 42024,
            "logo": "https:\/\/static-cdn.jtvnw.net\/jtv_user_pictures\/f313ac88-0a5b-4013-bbd4-34f831939d3b-profile_image-300x300.png",
            "overlay": "https:\/\/static-cdn.jtvnw.net\/jtv_user_pictures\/fadd1b64-df7b-43b5-b13e-19e479096091-channel_offline_image-1920x1080.jpeg",
            "created_at": "2019-09-06T12:07:16.000000Z",
            "user": {
                "id": 5,
                "name": "Lacy McLaughlin",
                "last_name": null,
                "middle_name": null,
                "full_name": "Lacy McLaughlin",
                "nickname": "texaswildlife",
                "role_id": null,
                "avatar": "http:\/\/darestreams.local\/storage\/avatars\\cca42dd70a5efca23b387f34ef77354a.jpg",
                "overlay": "\/img\/default_overlay.jpg",
                "created_at": "2019-09-06T12:00:08.000000Z",
                "updated_at": "2019-09-06T12:00:08.000000Z",
                "lang": "ru"
            },
            "streams": []
        },
        {
            "id": 4,
            "user_id": 4,
            "title": "sweet_anita",
            "link": "https:\/\/www.twitch.tv\/sweet_anita",
            "game_id": 3,
            "slug": "sweet-anita",
            "description": "Hi I'm Anita, I'm new to Twitch, and I have Tourette's syndrome. I tend to stay on push to talk in game so that other players can’t hear me tic. My stream is for a mature audience only, as I say a lot of inappropriate things due to my neurological disorder. ",
            "provider": "twtich",
            "views": 4724090,
            "logo": "https:\/\/static-cdn.jtvnw.net\/jtv_user_pictures\/ac5a0a03-0501-4559-8edc-61c395484150-profile_image-300x300.png",
            "overlay": "https:\/\/static-cdn.jtvnw.net\/jtv_user_pictures\/6e5f0195-f249-48b5-ab68-0c7a96b8fb23-channel_offline_image-1920x1080.png",
            "created_at": "2019-09-06T12:07:14.000000Z",
            "user": {
                "id": 4,
                "name": "Tremaine Abernathy",
                "last_name": null,
                "middle_name": null,
                "full_name": "Tremaine Abernathy",
                "nickname": "sweet_anita",
                "role_id": null,
                "avatar": "http:\/\/darestreams.local\/storage\/avatars\\69e6a94d5bfea8858faed897c9a09c54.jpg",
                "overlay": "\/img\/default_overlay.jpg",
                "created_at": "2019-09-06T12:00:06.000000Z",
                "updated_at": "2019-09-06T12:00:06.000000Z",
                "lang": "ru"
            },
            "streams": []
        },
        {
            "id": 3,
            "user_id": 3,
            "title": "gabepeixe",
            "link": "https:\/\/www.twitch.tv\/gabepeixe",
            "game_id": 3,
            "slug": "gabepeixe",
            "description": "XD",
            "provider": "twtich",
            "views": 9088768,
            "logo": "https:\/\/static-cdn.jtvnw.net\/jtv_user_pictures\/e81441ba-4786-4628-b9bb-77098e4a917f-profile_image-300x300.png",
            "overlay": "https:\/\/static-cdn.jtvnw.net\/jtv_user_pictures\/402daa6b-eff9-4c5d-8abe-5462ec056e4b-channel_offline_image-1920x1080.jpeg",
            "created_at": "2019-09-06T12:07:12.000000Z",
            "user": {
                "id": 3,
                "name": "Prof. Cristopher Trantow",
                "last_name": null,
                "middle_name": null,
                "full_name": "Prof. Cristopher Trantow",
                "nickname": "gabepeixe",
                "role_id": null,
                "avatar": "http:\/\/darestreams.local\/storage\/avatars\\bbe30554092388e2f68dbbd7f0cdbec3.jpg",
                "overlay": "\/img\/default_overlay.jpg",
                "created_at": "2019-09-06T11:59:50.000000Z",
                "updated_at": "2019-09-06T11:59:50.000000Z",
                "lang": "ru"
            },
            "streams": []
        },
        {
            "id": 2,
            "user_id": 2,
            "title": "hardgamechannel",
            "link": "https:\/\/www.twitch.tv\/hardgamechannel",
            "game_id": 1,
            "slug": "hardgamechannel",
            "description": "Я у мамы стример",
            "provider": "twtich",
            "views": 17298272,
            "logo": "https:\/\/static-cdn.jtvnw.net\/jtv_user_pictures\/a863789b-a8c0-44f3-88ed-6da7afc5aea9-profile_image-300x300.png",
            "overlay": "https:\/\/static-cdn.jtvnw.net\/jtv_user_pictures\/4d18d388-a1c3-47ae-8b67-8579814b9d47-channel_offline_image-1920x1080.png",
            "created_at": "2019-09-06T12:07:08.000000Z",
            "user": {
                "id": 2,
                "name": "Bertrand Boehm",
                "last_name": null,
                "middle_name": null,
                "full_name": "Bertrand Boehm",
                "nickname": "hardgamechannel",
                "role_id": null,
                "avatar": "http:\/\/darestreams.local\/storage\/avatars\\b892aa82af1168ef0ac322c982a286e0.jpg",
                "overlay": "\/img\/default_overlay.jpg",
                "created_at": "2019-09-06T11:59:49.000000Z",
                "updated_at": "2019-09-06T11:59:49.000000Z",
                "lang": "ru"
            },
            "streams": []
        },
        {
            "id": 1,
            "user_id": 1,
            "title": "amouranth",
            "link": "https:\/\/www.twitch.tv\/amouranth",
            "game_id": 1,
            "slug": "amouranth",
            "description": "Welcome to my stream! My name is Amouranth and I love being myself (crazy, weird, a bit cringe) and making art! I cosplay, paint, draw, play games badly (dance worse), and sing too much. Nice to meet you and welcome to my community!",
            "provider": "twtich",
            "views": 91605753,
            "logo": "https:\/\/static-cdn.jtvnw.net\/jtv_user_pictures\/ac3ae8d2-2a06-4c41-9e92-2de8e20b29c4-profile_image-300x300.png",
            "overlay": "https:\/\/static-cdn.jtvnw.net\/jtv_user_pictures\/2509e087-402b-4d18-a813-d9bd478b92c1-channel_offline_image-1920x1080.png",
            "created_at": "2019-09-06T12:07:06.000000Z",
            "user": {
                "id": 1,
                "name": "Boyd Schoen",
                "last_name": null,
                "middle_name": null,
                "full_name": "Boyd Schoen",
                "nickname": "amouranth",
                "email": "oernser@example.net",
                "role_id": null,
                "avatar": "http:\/\/darestreams.local\/storage\/avatars\\b711175a46ac8ed34e55e6c14c05ae49.jpg",
                "overlay": "\/img\/default_overlay.jpg",
                "created_at": "2019-09-06T11:59:41.000000Z",
                "updated_at": "2019-09-06T11:59:41.000000Z",
                "lang": "ru"
            },
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
        "title": "amouranth",
        "link": "https:\/\/www.twitch.tv\/amouranth",
        "game_id": 1,
        "slug": "amouranth",
        "description": "Welcome to my stream! My name is Amouranth and I love being myself (crazy, weird, a bit cringe) and making art! I cosplay, paint, draw, play games badly (dance worse), and sing too much. Nice to meet you and welcome to my community!",
        "provider": "twtich",
        "views": 91605753,
        "logo": "https:\/\/static-cdn.jtvnw.net\/jtv_user_pictures\/ac3ae8d2-2a06-4c41-9e92-2de8e20b29c4-profile_image-300x300.png",
        "overlay": "https:\/\/static-cdn.jtvnw.net\/jtv_user_pictures\/2509e087-402b-4d18-a813-d9bd478b92c1-channel_offline_image-1920x1080.png",
        "created_at": "2019-09-06T12:07:06.000000Z",
        "user": {
            "id": 1,
            "name": "Boyd Schoen",
            "last_name": null,
            "middle_name": null,
            "full_name": "Boyd Schoen",
            "nickname": "amouranth",
            "email": "oernser@example.net",
            "role_id": null,
            "avatar": "http:\/\/darestreams.local\/storage\/avatars\\b711175a46ac8ed34e55e6c14c05ae49.jpg",
            "overlay": "\/img\/default_overlay.jpg",
            "created_at": "2019-09-06T11:59:41.000000Z",
            "updated_at": "2019-09-06T11:59:41.000000Z",
            "lang": "ru"
        },
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
            "logo" => "voluptatem",
            "game_id" => "18",
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
            "limit" => "quibusdam",
            "skip" => "qui",
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
            "page" => "8",
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
            "id": 4,
            "title": "Fortnite",
            "title_short": "Fortnite",
            "popularity": 170479,
            "logo": "http:\/\/darestreams.local\/storage\/games\/11ca3bdea2c92e7a076f540d9c8440c2.jpg",
            "logo_small": "http:\/\/darestreams.local\/storage\/games\/cc6e31d5709bbb7256677039afab7873.jpg",
            "views": 46301,
            "streams": [],
            "tags": []
        },
        {
            "id": 11,
            "title": "Grand Theft Auto V",
            "title_short": "Grand Theft Auto V",
            "popularity": 138324,
            "logo": "http:\/\/darestreams.local\/storage\/games\/d633495b72c8cc0a242a5cef2d7995ee.jpg",
            "logo_small": "http:\/\/darestreams.local\/storage\/games\/1ec563e9b95b82a99143e7ca77ac7ac7.jpg",
            "views": 23616,
            "streams": [],
            "tags": []
        },
        {
            "id": 3,
            "title": "Just Chatting",
            "title_short": "Just Chatting",
            "popularity": 107029,
            "logo": "http:\/\/darestreams.local\/storage\/games\/b88b5377ab4cd9981337d4b7d2a892d1.jpg",
            "logo_small": "http:\/\/darestreams.local\/storage\/games\/d325e2628ba1b85ec138bb9adacee171.jpg",
            "views": 55495,
            "streams": [],
            "tags": []
        },
        {
            "id": 2,
            "title": "League of Legends",
            "title_short": "League of Legends",
            "popularity": 102377,
            "logo": "http:\/\/darestreams.local\/storage\/games\/b3aad6fa11fd2c7a080fac75b22cdfe4.jpg",
            "logo_small": "http:\/\/darestreams.local\/storage\/games\/6a38884f9ea907716d691ad8ec0cb78e.jpg",
            "views": 142267,
            "streams": [],
            "tags": []
        },
        {
            "id": 14,
            "title": "Minecraft",
            "title_short": "Minecraft",
            "popularity": 90272,
            "logo": "http:\/\/darestreams.local\/storage\/games\/85945fba4d5e4d3aa6cf8fd67dd75857.jpg",
            "logo_small": "http:\/\/darestreams.local\/storage\/games\/dac4b802ff70ff067c18fd989194831b.jpg",
            "views": 18837,
            "streams": [],
            "tags": []
        },
        {
            "id": 10,
            "title": "Counter-Strike: Global Offensive",
            "title_short": "Counter-Strike: Global Offensive",
            "popularity": 51465,
            "logo": "http:\/\/darestreams.local\/storage\/games\/c56630a2d8e079b098c112599399b2b2.jpg",
            "logo_small": "http:\/\/darestreams.local\/storage\/games\/4e1d83f6b84d93e8979926f704f0e2ea.jpg",
            "views": 25775,
            "streams": [],
            "tags": []
        },
        {
            "id": 12,
            "title": "Apex Legends",
            "title_short": "Apex Legends",
            "popularity": 43193,
            "logo": "http:\/\/darestreams.local\/storage\/games\/f44f0be4df71ce2d76be89850ff1d08b.jpg",
            "logo_small": "http:\/\/darestreams.local\/storage\/games\/5ab3b2c0aa785bdf759643c8d0c011c0.jpg",
            "views": 23248,
            "streams": [],
            "tags": []
        },
        {
            "id": 9,
            "title": "PLAYERUNKNOWN'S BATTLEGROUNDS",
            "title_short": "PLAYERUNKNOWN'S BATTLEGROUNDS",
            "popularity": 41749,
            "logo": "http:\/\/darestreams.local\/storage\/games\/e045b4a76f493cd61d261e9dd05ac568.jpg",
            "logo_small": "http:\/\/darestreams.local\/storage\/games\/c96eb94b5a674a44fa6e2cd0c9d209f1.jpg",
            "views": 27065,
            "streams": [],
            "tags": []
        },
        {
            "id": 7,
            "title": "Dota 2",
            "title_short": "Dota 2",
            "popularity": 39530,
            "logo": "http:\/\/darestreams.local\/storage\/games\/435673495b17be82b2379387535a7c94.jpg",
            "logo_small": "http:\/\/darestreams.local\/storage\/games\/a01c414b22593a6443d057a2506e2ef1.jpg",
            "views": 32905,
            "streams": [],
            "tags": []
        },
        {
            "id": 16,
            "title": "Slots",
            "title_short": "Slots",
            "popularity": 24561,
            "logo": "http:\/\/darestreams.local\/storage\/games\/2d18d14e3742549d64868714eca4481c.jpg",
            "logo_small": "http:\/\/darestreams.local\/storage\/games\/94b8252a73aae816fab9e8c5f7ff67b1.jpg",
            "views": 17653,
            "streams": [],
            "tags": []
        },
        {
            "id": 1,
            "title": "World of Warcraft",
            "title_short": "World of Warcraft",
            "popularity": 23976,
            "logo": "http:\/\/darestreams.local\/storage\/games\/35df01ee3b1b8eca8035a492b8dd32db.jpg",
            "logo_small": "http:\/\/darestreams.local\/storage\/games\/75f95c914506acf0bd2c86e6af9e825b.jpg",
            "views": 144035,
            "streams": [],
            "tags": []
        },
        {
            "id": 8,
            "title": "Overwatch",
            "title_short": "Overwatch",
            "popularity": 23171,
            "logo": "http:\/\/darestreams.local\/storage\/games\/9f7e428836de108d1c1526cf7e1905e8.jpg",
            "logo_small": "http:\/\/darestreams.local\/storage\/games\/9d4ec1b0a890d4b6259e92e563637230.jpg",
            "views": 27894,
            "streams": [],
            "tags": []
        },
        {
            "id": 5,
            "title": "Hearthstone",
            "title_short": "Hearthstone",
            "popularity": 18993,
            "logo": "http:\/\/darestreams.local\/storage\/games\/fc959dec3b0fdbe9dc88e121b6ef8f87.jpg",
            "logo_small": "http:\/\/darestreams.local\/storage\/games\/7918241f50ba0851f7616f40e5637c0e.jpg",
            "views": 45874,
            "streams": [],
            "tags": []
        },
        {
            "id": 17,
            "title": "Teamfight Tactics",
            "title_short": "Teamfight Tactics",
            "popularity": 14625,
            "logo": "http:\/\/darestreams.local\/storage\/games\/d0a35bbdff0a1ce2bc1b72baada0b1e8.jpg",
            "logo_small": "http:\/\/darestreams.local\/storage\/games\/10cada8fca53e52af4572a41a2052261.jpg",
            "views": 16776,
            "streams": [],
            "tags": []
        },
        {
            "id": 19,
            "title": "Dead by Daylight",
            "title_short": "Dead by Daylight",
            "popularity": 11705,
            "logo": "http:\/\/darestreams.local\/storage\/games\/e72e936e13284da9b4b71f4fe172437e.jpg",
            "logo_small": "http:\/\/darestreams.local\/storage\/games\/b818c5888be0e6604851a4e53ecbc58d.jpg",
            "views": 8130,
            "streams": [],
            "tags": []
        },
        {
            "id": 6,
            "title": "Monster Hunter World",
            "title_short": "Monster Hunter World",
            "popularity": 2407,
            "logo": "http:\/\/darestreams.local\/storage\/games\/01a4da8913ceebceac88668c6e163b01.jpg",
            "logo_small": "http:\/\/darestreams.local\/storage\/games\/f95584279b583958f55b6e1ca96a7b75.jpg",
            "views": 43352,
            "streams": [],
            "tags": []
        },
        {
            "id": 18,
            "title": "Gears 5",
            "title_short": "Gears 5",
            "popularity": 1367,
            "logo": "http:\/\/darestreams.local\/storage\/games\/aaddaa7d1d341d5580071dca8f3c4c48.jpg",
            "logo_small": "http:\/\/darestreams.local\/storage\/games\/d321b15afd27b68cbb2e0388de54a417.jpg",
            "views": 11688,
            "streams": [],
            "tags": []
        },
        {
            "id": 13,
            "title": "NBA 2K20",
            "title_short": "NBA 2K20",
            "popularity": 395,
            "logo": "http:\/\/darestreams.local\/storage\/games\/f55273aea3443485c61893983ba4495e.jpg",
            "logo_small": "http:\/\/darestreams.local\/storage\/games\/9bc022cb46ab9461dd5339206bc96f97.jpg",
            "views": 19697,
            "streams": [],
            "tags": []
        },
        {
            "id": 15,
            "title": "Green Hell",
            "title_short": "Green Hell",
            "popularity": 37,
            "logo": "http:\/\/darestreams.local\/storage\/games\/c12c26743cff6775453640d71ec0edf4.jpg",
            "logo_small": "http:\/\/darestreams.local\/storage\/games\/8069142003a21ae4e24900cd28f711c7.jpg",
            "views": 17704,
            "streams": [],
            "tags": []
        }
    ]
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
    "data": {
        "id": 1,
        "title": "World of Warcraft",
        "title_short": "World of Warcraft",
        "popularity": 23976,
        "logo": "http:\/\/darestreams.local\/storage\/games\/35df01ee3b1b8eca8035a492b8dd32db.jpg",
        "logo_small": "http:\/\/darestreams.local\/storage\/games\/75f95c914506acf0bd2c86e6af9e825b.jpg",
        "views": 144035,
        "streams": [],
        "tags": []
    }
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
            "amount" => "10",
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
            "limit" => "iusto",
            "skip" => "saepe",
            "game_id" => "debitis",
            "include" => "user,tasks",
            "sort" => "-views",
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
    limit |  optional  | Integer. Limit of top channels. Default: 3.
    skip |  optional  | Integer. Offset of top channels. Default: 0.
    game_id |  optional  | Integer. Filter channels by category.
    include |  optional  | string String of connections: user, tasks, tags, game.
    sort |  optional  | string Sort items by fields: amount_donations, views. For desc use '-' prefix.

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
            "page" => "4",
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
    include |  optional  | string String of connections: game, tasks, tasks.votes, tags, channel, user.
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
            "channel_id" => "20",
            "title" => "architecto",
            "link" => "et",
            "start_at" => "quisquam",
            "allow_task_before_stream" => "1",
            "allow_task_when_stream" => "",
            "min_amount_task_before_stream" => "quae",
            "min_amount_task_when_stream" => "inventore",
            "min_amount_donate_task_before_stream" => "ea",
            "min_amount_donate_task_when_stream" => "qui",
            "tags" => "sunt",
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
    include |  optional  | string String of connections: game, tasks, tasks.votes, tags, channel, user.

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
            "title" => "iure",
            "link" => "quo",
            "start_at" => "eum",
            "status" => "4",
            "allow_task_before_stream" => "1",
            "allow_task_when_stream" => "1",
            "min_amount_task_before_stream" => "suscipit",
            "min_amount_task_when_stream" => "distinctio",
            "min_amount_donate_task_before_stream" => "amet",
            "min_amount_donate_task_when_stream" => "dolor",
            "tags" => "velit",
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

<!-- START_65f8dab0cf9b9f35efdf588fdd6d339e -->
## Set vote for task.

<br><small style="padding: 1px 9px 2px;font-weight: bold;white-space: nowrap;color: #ffffff;-webkit-border-radius: 9px;-moz-border-radius: 9px;border-radius: 9px;background-color: #3a87ad;">Requires authentication</small>
{task} - integer id of task.

> Example request:

```php

$client = new \GuzzleHttp\Client();
$response = $client->patch("https://darestreams.com/api/tasks/1/set-vote", [
    'headers' => [
            "Content-Type" => "application/json",
        ],
    'json' => [
            "vote" => "7",
        ],
]);
$body = $response->getBody();
print_r(json_decode((string) $body));
```




### HTTP Request
`PATCH api/tasks/{task}/set-vote`

#### Body Parameters

Parameter | Type | Status | Description
--------- | ------- | ------- | ------- | -----------
    vote | integer |  optional  | Vote parameter, 1-Yes, 2-No, 0 - Pending.

<!-- END_65f8dab0cf9b9f35efdf588fdd6d339e -->

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
    'query' => [
            "stream_id" => "dolores",
            "include" => "user,stream",
            "sort" => "-amount_donations",
            "page" => "7",
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
`GET api/tasks`

#### Query Parameters

Parameter | Status | Description
--------- | ------- | ------- | -----------
    stream_id |  optional  | integer required
    include |  optional  | string String of connections: user, stream, transactions.
    sort |  optional  | string Sort items by fields: amount_donations, id. For desc use '-' prefix.
    page |  optional  | array Use as page[number]=1&page[size]=2.

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
            "stream_id" => "9",
            "small_text" => "quia",
            "full_text" => "dolor",
            "interval_time" => "3",
            "is_superbowl" => "1",
            "tags" => "eum",
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
            "status" => "5",
            "small_text" => "libero",
            "full_text" => "deleniti",
            "interval_time" => "10",
            "is_superbowl" => "1",
            "tags" => "iure",
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
            "body" => "quos",
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

#Users
<!-- START_5e22c7fd2fa5a148cdcf15a149cb7b69 -->
## api/users/{user}/login
> Example request:

```php

$client = new \GuzzleHttp\Client();
$response = $client->get("https://darestreams.com/api/users/1/login", [
]);
$body = $response->getBody();
print_r(json_decode((string) $body));
```



> Example response (200):

```json
{
    "token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOlwvXC9sb2NhbGhvc3RcL2FwaVwvdXNlcnNcLzFcL2xvZ2luIiwiaWF0IjoxNTY4MTAzOTQ0LCJleHAiOjE1NjgxOTAzNDQsIm5iZiI6MTU2ODEwMzk0NCwianRpIjoiTDNnb09jc2pTTUF4ckJPNyIsInN1YiI6MSwicHJ2IjoiMjNiZDVjODk0OWY2MDBhZGIzOWU3MDFjNDAwODcyZGI3YTU5NzZmNyJ9.4BTTIqA6Xb7bQwFdv_3WRJmmjCbwXezXiASPbaJQVg8",
    "token_type": "bearer",
    "expires_in": 1568190344
}
```

### HTTP Request
`GET api/users/{user}/login`


<!-- END_5e22c7fd2fa5a148cdcf15a149cb7b69 -->

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



> Example response (200):

```json
{
    "data": {
        "id": 1,
        "name": "Boyd Schoen",
        "last_name": null,
        "middle_name": null,
        "full_name": "Boyd Schoen",
        "nickname": "amouranth",
        "email": "oernser@example.net",
        "role_id": null,
        "avatar": "http:\/\/darestreams.local\/storage\/avatars\\b711175a46ac8ed34e55e6c14c05ae49.jpg",
        "overlay": "\/img\/default_overlay.jpg",
        "created_at": "2019-09-06T11:59:41.000000Z",
        "updated_at": "2019-09-06T11:59:41.000000Z",
        "lang": "ru",
        "channel": {
            "id": 1,
            "user_id": 1,
            "title": "amouranth",
            "link": "https:\/\/www.twitch.tv\/amouranth",
            "game_id": 1,
            "slug": "amouranth",
            "description": "Welcome to my stream! My name is Amouranth and I love being myself (crazy, weird, a bit cringe) and making art! I cosplay, paint, draw, play games badly (dance worse), and sing too much. Nice to meet you and welcome to my community!",
            "provider": "twtich",
            "views": 91605753,
            "logo": "https:\/\/static-cdn.jtvnw.net\/jtv_user_pictures\/ac3ae8d2-2a06-4c41-9e92-2de8e20b29c4-profile_image-300x300.png",
            "overlay": "https:\/\/static-cdn.jtvnw.net\/jtv_user_pictures\/2509e087-402b-4d18-a813-d9bd478b92c1-channel_offline_image-1920x1080.png",
            "created_at": "2019-09-06T12:07:06.000000Z"
        },
        "tasks": []
    }
}
```

### HTTP Request
`GET api/users/me`

#### Query Parameters

Parameter | Status | Description
--------- | ------- | ------- | -----------
    include |  optional  | string String of connections: tasks, streams, channel, account.

<!-- END_8d1e53fcf4d2d02a6144ed392de856bf -->

<!-- START_0372b4ae6a0be225c924b9e63841d639 -->
## Get user&#039;s donation (sent and received) transaction

<br><small style="padding: 1px 9px 2px;font-weight: bold;white-space: nowrap;color: #ffffff;-webkit-border-radius: 9px;-moz-border-radius: 9px;border-radius: 9px;background-color: #3a87ad;">Requires authentication</small>
> Example request:

```php

$client = new \GuzzleHttp\Client();
$response = $client->get("https://darestreams.com/api/users/me/get-donates-by-date", [
]);
$body = $response->getBody();
print_r(json_decode((string) $body));
```



> Example response (200):

```json
[]
```

### HTTP Request
`GET api/users/me/get-donates-by-date`


<!-- END_0372b4ae6a0be225c924b9e63841d639 -->

<!-- START_2e7155f0d02153a27498ca2b6badfd37 -->
## Get user&#039;s donation (sent and received) transaction by date and stream

<br><small style="padding: 1px 9px 2px;font-weight: bold;white-space: nowrap;color: #ffffff;-webkit-border-radius: 9px;-moz-border-radius: 9px;border-radius: 9px;background-color: #3a87ad;">Requires authentication</small>
> Example request:

```php

$client = new \GuzzleHttp\Client();
$response = $client->get("https://darestreams.com/api/users/me/get-donates-by-date/1/1", [
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
`GET api/users/me/get-donates-by-date/{date}/{stream}`


<!-- END_2e7155f0d02153a27498ca2b6badfd37 -->

<!-- START_db527bf05a41a7f72f2d9a870d18e2f8 -->
## Get user&#039;s donation (sent and received) transaction by date

<br><small style="padding: 1px 9px 2px;font-weight: bold;white-space: nowrap;color: #ffffff;-webkit-border-radius: 9px;-moz-border-radius: 9px;border-radius: 9px;background-color: #3a87ad;">Requires authentication</small>
> Example request:

```php

$client = new \GuzzleHttp\Client();
$response = $client->get("https://darestreams.com/api/users/me/get-donates-by-date/1", [
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
`GET api/users/me/get-donates-by-date/{date}`


<!-- END_db527bf05a41a7f72f2d9a870d18e2f8 -->

<!-- START_2543b49b33996ffffee0ee6cb663a0f0 -->
## Get user&#039;s dates of withdraws and debits

<br><small style="padding: 1px 9px 2px;font-weight: bold;white-space: nowrap;color: #ffffff;-webkit-border-radius: 9px;-moz-border-radius: 9px;border-radius: 9px;background-color: #3a87ad;">Requires authentication</small>
> Example request:

```php

$client = new \GuzzleHttp\Client();
$response = $client->get("https://darestreams.com/api/users/me/get-debit-withdraw-by-date", [
]);
$body = $response->getBody();
print_r(json_decode((string) $body));
```



> Example response (200):

```json
[]
```

### HTTP Request
`GET api/users/me/get-debit-withdraw-by-date`


<!-- END_2543b49b33996ffffee0ee6cb663a0f0 -->

<!-- START_f9ea4f883c6fefef0af3d0810cb28e31 -->
## Get user&#039;s dates of withdraws and debits

> Example request:

```php

$client = new \GuzzleHttp\Client();
$response = $client->get("https://darestreams.com/api/users/me/get-debit-withdraw-by-date/1", [
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
`GET api/users/me/get-debit-withdraw-by-date/{date}`


<!-- END_f9ea4f883c6fefef0af3d0810cb28e31 -->

<!-- START_f09d589f509808e4d6fc27d1ca229006 -->
## Get top donators

> Example request:

```php

$client = new \GuzzleHttp\Client();
$response = $client->get("https://darestreams.com/api/users/top", [
    'query' => [
            "limit" => "consequatur",
            "skip" => "ut",
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
    "data": [
        {
            "id": 28,
            "name": "Natasha Marquardt",
            "last_name": null,
            "middle_name": null,
            "full_name": "Natasha Marquardt",
            "nickname": "zanuda",
            "role_id": null,
            "avatar": "http:\/\/darestreams.local\/storage\/avatars\\1ab08cf3ead3f55049311edc85fa8823.jpg",
            "overlay": "\/img\/default_overlay.jpg",
            "created_at": "2019-09-06T12:00:31.000000Z",
            "updated_at": "2019-09-06T12:00:31.000000Z",
            "lang": "ru",
            "channel": {
                "id": 27,
                "user_id": 28,
                "title": "zanuda",
                "link": "https:\/\/www.twitch.tv\/zanuda",
                "game_id": 14,
                "slug": "zanuda",
                "description": "знд",
                "provider": "twtich",
                "views": 6018001,
                "logo": "https:\/\/static-cdn.jtvnw.net\/jtv_user_pictures\/d27cfa24-49d5-48c8-886d-dc360ea186e6-profile_image-300x300.png",
                "overlay": "https:\/\/static-cdn.jtvnw.net\/jtv_user_pictures\/3d29b8e3-c8e9-404e-b058-f05614a9e034-channel_offline_image-1920x1080.jpeg",
                "created_at": "2019-09-06T12:08:11.000000Z"
            },
            "tasks": []
        },
        {
            "id": 60,
            "name": "Geo Bayer",
            "last_name": null,
            "middle_name": null,
            "full_name": "Geo Bayer",
            "nickname": "zakvielchannel",
            "role_id": null,
            "avatar": "http:\/\/darestreams.local\/storage\/avatars\\98b99ff0802e6cf5c7fbea5ff42f9d3e.jpg",
            "overlay": "\/img\/default_overlay.jpg",
            "created_at": "2019-09-06T12:01:41.000000Z",
            "updated_at": "2019-09-06T12:01:41.000000Z",
            "lang": "ru",
            "channel": null,
            "tasks": []
        },
        {
            "id": 86,
            "name": "Rhiannon West",
            "last_name": null,
            "middle_name": null,
            "full_name": "Rhiannon West",
            "nickname": "yuuechka",
            "role_id": null,
            "avatar": "http:\/\/darestreams.local\/storage\/avatars\\ed5e1eab36f311ccf99e4e1a92a84a1d.jpg",
            "overlay": "\/img\/default_overlay.jpg",
            "created_at": "2019-09-06T12:02:15.000000Z",
            "updated_at": "2019-09-06T12:02:15.000000Z",
            "lang": "ru",
            "channel": null,
            "tasks": []
        },
        {
            "id": 100,
            "name": "Lorenz Schumm",
            "last_name": null,
            "middle_name": null,
            "full_name": "Lorenz Schumm",
            "nickname": "yuki2yuki",
            "role_id": null,
            "avatar": "http:\/\/darestreams.local\/storage\/avatars\\ec06f25428533435a9d6f6ea9ff2836a.jpg",
            "overlay": "\/img\/default_overlay.jpg",
            "created_at": "2019-09-06T12:02:31.000000Z",
            "updated_at": "2019-09-06T12:02:31.000000Z",
            "lang": "ru",
            "channel": null,
            "tasks": []
        },
        {
            "id": 12,
            "name": "Mrs. Cheyenne Cronin Jr.",
            "last_name": null,
            "middle_name": null,
            "full_name": "Mrs. Cheyenne Cronin Jr.",
            "nickname": "yoda",
            "role_id": null,
            "avatar": "http:\/\/darestreams.local\/storage\/avatars\\bba588aa58c0b24724eef6811715f776.jpg",
            "overlay": "\/img\/default_overlay.jpg",
            "created_at": "2019-09-06T12:00:15.000000Z",
            "updated_at": "2019-09-06T12:00:15.000000Z",
            "lang": "ru",
            "channel": {
                "id": 12,
                "user_id": 12,
                "title": "yoda",
                "link": "https:\/\/www.twitch.tv\/yoda",
                "game_id": 15,
                "slug": "yoda",
                "description": "Felipe 'YoDa', Atleta de League of Legends e Streamer. Desde 2014 é o maior Streamer Gamer Brasileiro, onde alcança mais de 25K pessoas ao vivo por dia na plataforma Twitch.tv. É Proplayer do time da RED CANIDS Kalunga e embaixador de eSports mundial da RED BULL e Campeão Brasileiro do CBLOL. ",
                "provider": "twtich",
                "views": 140866600,
                "logo": "https:\/\/static-cdn.jtvnw.net\/jtv_user_pictures\/yoda-profile_image-63cdc656c9f91fb4-300x300.jpeg",
                "overlay": "https:\/\/static-cdn.jtvnw.net\/jtv_user_pictures\/b3b6d1fa-1aa5-470a-9a23-c435e300de68-channel_offline_image-1920x1080.png",
                "created_at": "2019-09-06T12:07:33.000000Z"
            },
            "tasks": []
        },
        {
            "id": 36,
            "name": "Ms. Elaina Beatty III",
            "last_name": null,
            "middle_name": null,
            "full_name": "Ms. Elaina Beatty III",
            "nickname": "windy31",
            "role_id": null,
            "avatar": "http:\/\/darestreams.local\/storage\/avatars\\330a0303b48523a94f8a23c4397c4262.jpg",
            "overlay": "\/img\/default_overlay.jpg",
            "created_at": "2019-09-06T12:01:00.000000Z",
            "updated_at": "2019-09-06T12:01:00.000000Z",
            "lang": "ru",
            "channel": {
                "id": 35,
                "user_id": 36,
                "title": "windy31",
                "link": "https:\/\/www.twitch.tv\/windy31",
                "game_id": 3,
                "slug": "windy31",
                "description": "",
                "provider": "twtich",
                "views": 1256674,
                "logo": "https:\/\/static-cdn.jtvnw.net\/jtv_user_pictures\/67fd1f1d-ab9c-4348-9575-ee91606bc01f-profile_image-300x300.jpg",
                "overlay": "https:\/\/static-cdn.jtvnw.net\/jtv_user_pictures\/574f8a86-ef3a-4e25-9175-c3a1cbec33e3-channel_offline_image-1920x1080.jpeg",
                "created_at": "2019-09-06T12:08:30.000000Z"
            },
            "tasks": []
        },
        {
            "id": 6,
            "name": "Dr. Lacy Lang",
            "last_name": null,
            "middle_name": null,
            "full_name": "Dr. Lacy Lang",
            "nickname": "violettavalery",
            "role_id": null,
            "avatar": "http:\/\/darestreams.local\/storage\/avatars\\6d3c8e1db1b6ff692fcacbf617aa7a09.jpg",
            "overlay": "\/img\/default_overlay.jpg",
            "created_at": "2019-09-06T12:00:09.000000Z",
            "updated_at": "2019-09-06T12:00:09.000000Z",
            "lang": "ru",
            "channel": {
                "id": 6,
                "user_id": 6,
                "title": "violettavalery",
                "link": "https:\/\/www.twitch.tv\/violettavalery",
                "game_id": 1,
                "slug": "violettavalery",
                "description": "Всем привет, меня зовут Виолетта (да, это моё настоящее имя). В основном на моем канале идут АСМР стримы, иногда играем в игры, иногда просто мартышкаем) ",
                "provider": "twtich",
                "views": 1108601,
                "logo": "https:\/\/static-cdn.jtvnw.net\/jtv_user_pictures\/aea78f81-2550-4e08-9ae4-90374a969ac9-profile_image-300x300.jpg",
                "overlay": "https:\/\/static-cdn.jtvnw.net\/jtv_user_pictures\/a4a0c1f55ac3c514-channel_offline_image-1920x1080.jpeg",
                "created_at": "2019-09-06T12:07:19.000000Z"
            },
            "tasks": []
        },
        {
            "id": 38,
            "name": "Dr. Roel Rogahn",
            "last_name": null,
            "middle_name": null,
            "full_name": "Dr. Roel Rogahn",
            "nickname": "vika_karter",
            "role_id": null,
            "avatar": "http:\/\/darestreams.local\/storage\/avatars\\d23521f4af9d6e56c628e045a5afc436.jpg",
            "overlay": "\/img\/default_overlay.jpg",
            "created_at": "2019-09-06T12:01:07.000000Z",
            "updated_at": "2019-09-06T12:01:07.000000Z",
            "lang": "ru",
            "channel": {
                "id": 37,
                "user_id": 38,
                "title": "vika_karter",
                "link": "https:\/\/www.twitch.tv\/vika_karter",
                "game_id": 3,
                "slug": "vika-karter",
                "description": "кибераутист",
                "provider": "twtich",
                "views": 4848372,
                "logo": "https:\/\/static-cdn.jtvnw.net\/jtv_user_pictures\/9dc75af9-44ad-4ad9-9cb5-23544ac3baae-profile_image-300x300.jpg",
                "overlay": "https:\/\/static-cdn.jtvnw.net\/jtv_user_pictures\/f4b283eb-d368-432b-bdef-83a04f0d3f67-channel_offline_image-1920x1080.jpeg",
                "created_at": "2019-09-06T12:08:35.000000Z"
            },
            "tasks": []
        },
        {
            "id": 70,
            "name": "Virgil Prohaska",
            "last_name": null,
            "middle_name": null,
            "full_name": "Virgil Prohaska",
            "nickname": "unique",
            "role_id": null,
            "avatar": "http:\/\/darestreams.local\/storage\/avatars\\c7b1e708eb50817602c94ae71d5720f6.jpg",
            "overlay": "\/img\/default_overlay.jpg",
            "created_at": "2019-09-06T12:01:55.000000Z",
            "updated_at": "2019-09-06T12:01:55.000000Z",
            "lang": "ru",
            "channel": null,
            "tasks": []
        },
        {
            "id": 62,
            "name": "Leann Hane",
            "last_name": null,
            "middle_name": null,
            "full_name": "Leann Hane",
            "nickname": "tiggra",
            "role_id": null,
            "avatar": "http:\/\/darestreams.local\/storage\/avatars\\a07f6f1c2e899270f6ed1d8415d51284.jpg",
            "overlay": "\/img\/default_overlay.jpg",
            "created_at": "2019-09-06T12:01:43.000000Z",
            "updated_at": "2019-09-06T12:01:43.000000Z",
            "lang": "ru",
            "channel": null,
            "tasks": []
        },
        {
            "id": 104,
            "name": "Mabel Davis",
            "last_name": null,
            "middle_name": null,
            "full_name": "Mabel Davis",
            "nickname": "theveronicous",
            "role_id": null,
            "avatar": "http:\/\/darestreams.local\/storage\/avatars\\12328e405dfb8a75955a85f3aee6499d.jpg",
            "overlay": "\/img\/default_overlay.jpg",
            "created_at": "2019-09-06T12:02:35.000000Z",
            "updated_at": "2019-09-06T12:02:35.000000Z",
            "lang": "ru",
            "channel": null,
            "tasks": []
        },
        {
            "id": 55,
            "name": "Dr. Luisa Quigley",
            "last_name": null,
            "middle_name": null,
            "full_name": "Dr. Luisa Quigley",
            "nickname": "thethomasavengers",
            "role_id": null,
            "avatar": "http:\/\/darestreams.local\/storage\/avatars\\dc200f3a51074dc95b604bb724f91464.jpg",
            "overlay": "\/img\/default_overlay.jpg",
            "created_at": "2019-09-06T12:01:36.000000Z",
            "updated_at": "2019-09-06T12:01:36.000000Z",
            "lang": "ru",
            "channel": null,
            "tasks": []
        },
        {
            "id": 5,
            "name": "Lacy McLaughlin",
            "last_name": null,
            "middle_name": null,
            "full_name": "Lacy McLaughlin",
            "nickname": "texaswildlife",
            "role_id": null,
            "avatar": "http:\/\/darestreams.local\/storage\/avatars\\cca42dd70a5efca23b387f34ef77354a.jpg",
            "overlay": "\/img\/default_overlay.jpg",
            "created_at": "2019-09-06T12:00:08.000000Z",
            "updated_at": "2019-09-06T12:00:08.000000Z",
            "lang": "ru",
            "channel": {
                "id": 5,
                "user_id": 5,
                "title": "texaswildlife",
                "link": "https:\/\/www.twitch.tv\/texaswildlife",
                "game_id": 1,
                "slug": "texaswildlife",
                "description": "Live cameras of birds and other Texas wildlife. No telling what you will see when you join the stream!",
                "provider": "twtich",
                "views": 42024,
                "logo": "https:\/\/static-cdn.jtvnw.net\/jtv_user_pictures\/f313ac88-0a5b-4013-bbd4-34f831939d3b-profile_image-300x300.png",
                "overlay": "https:\/\/static-cdn.jtvnw.net\/jtv_user_pictures\/fadd1b64-df7b-43b5-b13e-19e479096091-channel_offline_image-1920x1080.jpeg",
                "created_at": "2019-09-06T12:07:16.000000Z"
            },
            "tasks": []
        },
        {
            "id": 19,
            "name": "Mrs. Sincere Moore Jr.",
            "last_name": null,
            "middle_name": null,
            "full_name": "Mrs. Sincere Moore Jr.",
            "nickname": "tenderlybae",
            "role_id": null,
            "avatar": "http:\/\/darestreams.local\/storage\/avatars\\a037aa6931700df9d7e00ecd24a19802.jpg",
            "overlay": "\/img\/default_overlay.jpg",
            "created_at": "2019-09-06T12:00:23.000000Z",
            "updated_at": "2019-09-06T12:00:23.000000Z",
            "lang": "ru",
            "channel": {
                "id": 18,
                "user_id": 19,
                "title": "tenderlybae",
                "link": "https:\/\/www.twitch.tv\/tenderlybae",
                "game_id": 3,
                "slug": "tenderlybae",
                "description": "",
                "provider": "twtich",
                "views": 5476650,
                "logo": "https:\/\/static-cdn.jtvnw.net\/jtv_user_pictures\/6be91ff6-cd90-4797-89b1-e89329a44ce8-profile_image-300x300.png",
                "overlay": "https:\/\/static-cdn.jtvnw.net\/jtv_user_pictures\/8c2ef276-31df-4e17-b285-2ca852ac9c92-channel_offline_image-1920x1080.png",
                "created_at": "2019-09-06T12:07:50.000000Z"
            },
            "tasks": []
        },
        {
            "id": 20,
            "name": "Corene Tillman",
            "last_name": null,
            "middle_name": null,
            "full_name": "Corene Tillman",
            "nickname": "tati",
            "role_id": null,
            "avatar": "http:\/\/darestreams.local\/storage\/avatars\\d66ac994d5aeb6d9e4ca8165e5437fe6.jpg",
            "overlay": "\/img\/default_overlay.jpg",
            "created_at": "2019-09-06T12:00:24.000000Z",
            "updated_at": "2019-09-06T12:00:24.000000Z",
            "lang": "ru",
            "channel": {
                "id": 19,
                "user_id": 20,
                "title": "tati",
                "link": "https:\/\/www.twitch.tv\/tati",
                "game_id": 7,
                "slug": "tati",
                "description": "",
                "provider": "twtich",
                "views": 12687055,
                "logo": "https:\/\/static-cdn.jtvnw.net\/jtv_user_pictures\/f40c1600-3127-4cf0-882b-3d09528bc738-profile_image-300x300.png",
                "overlay": "https:\/\/static-cdn.jtvnw.net\/jtv_user_pictures\/1ed13eaf-47c6-4e4b-a38f-6fe54e833065-channel_offline_image-1920x1080.jpeg",
                "created_at": "2019-09-06T12:07:52.000000Z"
            },
            "tasks": []
        },
        {
            "id": 42,
            "name": "Prof. Gwen Marquardt",
            "last_name": null,
            "middle_name": null,
            "full_name": "Prof. Gwen Marquardt",
            "nickname": "tangerin",
            "role_id": null,
            "avatar": "http:\/\/darestreams.local\/storage\/avatars\\355689ccda985503c6d1752fbd041b37.jpg",
            "overlay": "\/img\/default_overlay.jpg",
            "created_at": "2019-09-06T12:01:22.000000Z",
            "updated_at": "2019-09-06T12:01:22.000000Z",
            "lang": "ru",
            "channel": null,
            "tasks": []
        },
        {
            "id": 4,
            "name": "Tremaine Abernathy",
            "last_name": null,
            "middle_name": null,
            "full_name": "Tremaine Abernathy",
            "nickname": "sweet_anita",
            "role_id": null,
            "avatar": "http:\/\/darestreams.local\/storage\/avatars\\69e6a94d5bfea8858faed897c9a09c54.jpg",
            "overlay": "\/img\/default_overlay.jpg",
            "created_at": "2019-09-06T12:00:06.000000Z",
            "updated_at": "2019-09-06T12:00:06.000000Z",
            "lang": "ru",
            "channel": {
                "id": 4,
                "user_id": 4,
                "title": "sweet_anita",
                "link": "https:\/\/www.twitch.tv\/sweet_anita",
                "game_id": 3,
                "slug": "sweet-anita",
                "description": "Hi I'm Anita, I'm new to Twitch, and I have Tourette's syndrome. I tend to stay on push to talk in game so that other players can’t hear me tic. My stream is for a mature audience only, as I say a lot of inappropriate things due to my neurological disorder. ",
                "provider": "twtich",
                "views": 4724090,
                "logo": "https:\/\/static-cdn.jtvnw.net\/jtv_user_pictures\/ac5a0a03-0501-4559-8edc-61c395484150-profile_image-300x300.png",
                "overlay": "https:\/\/static-cdn.jtvnw.net\/jtv_user_pictures\/6e5f0195-f249-48b5-ab68-0c7a96b8fb23-channel_offline_image-1920x1080.png",
                "created_at": "2019-09-06T12:07:14.000000Z"
            },
            "tasks": []
        },
        {
            "id": 15,
            "name": "Prof. Lyda Langosh MD",
            "last_name": null,
            "middle_name": null,
            "full_name": "Prof. Lyda Langosh MD",
            "nickname": "stpeach",
            "role_id": null,
            "avatar": "http:\/\/darestreams.local\/storage\/avatars\\bb58f507874c89471438d74b06bb9bda.jpg",
            "overlay": "\/img\/default_overlay.jpg",
            "created_at": "2019-09-06T12:00:18.000000Z",
            "updated_at": "2019-09-06T12:00:18.000000Z",
            "lang": "ru",
            "channel": {
                "id": 15,
                "user_id": 15,
                "title": "stpeach",
                "link": "https:\/\/www.twitch.tv\/stpeach",
                "game_id": 2,
                "slug": "stpeach",
                "description": "Streamer from Canada Living in Califonia ♥",
                "provider": "twtich",
                "views": 26719952,
                "logo": "https:\/\/static-cdn.jtvnw.net\/jtv_user_pictures\/f8b8c774-e34d-40d9-ba21-9a81cfda73aa-profile_image-300x300.png",
                "overlay": "https:\/\/static-cdn.jtvnw.net\/jtv_user_pictures\/47385bfd-223f-4bc3-8e74-19a967b13eea-channel_offline_image-1920x1080.png",
                "created_at": "2019-09-06T12:07:40.000000Z"
            },
            "tasks": []
        },
        {
            "id": 78,
            "name": "Miss Breana Lesch",
            "last_name": null,
            "middle_name": null,
            "full_name": "Miss Breana Lesch",
            "nickname": "stopannya",
            "role_id": null,
            "avatar": "http:\/\/darestreams.local\/storage\/avatars\\553afefa87415ceb2d07a4f94c3dd018.jpg",
            "overlay": "\/img\/default_overlay.jpg",
            "created_at": "2019-09-06T12:02:05.000000Z",
            "updated_at": "2019-09-06T12:02:05.000000Z",
            "lang": "ru",
            "channel": null,
            "tasks": []
        },
        {
            "id": 105,
            "name": "Isai Jones V",
            "last_name": null,
            "middle_name": null,
            "full_name": "Isai Jones V",
            "nickname": "steel",
            "role_id": null,
            "avatar": "http:\/\/darestreams.local\/storage\/avatars\\b4b146e99c98446f3092ed62e09b0f85.jpg",
            "overlay": "\/img\/default_overlay.jpg",
            "created_at": "2019-09-06T12:02:36.000000Z",
            "updated_at": "2019-09-06T12:02:36.000000Z",
            "lang": "ru",
            "channel": null,
            "tasks": []
        },
        {
            "id": 27,
            "name": "Cecelia Lindgren",
            "last_name": null,
            "middle_name": null,
            "full_name": "Cecelia Lindgren",
            "nickname": "sorabi_",
            "role_id": null,
            "avatar": "http:\/\/darestreams.local\/storage\/avatars\\f766b2b201be8ea74dcc6d7564c601fe.jpg",
            "overlay": "\/img\/default_overlay.jpg",
            "created_at": "2019-09-06T12:00:30.000000Z",
            "updated_at": "2019-09-06T12:00:30.000000Z",
            "lang": "ru",
            "channel": {
                "id": 26,
                "user_id": 27,
                "title": "sorabi_",
                "link": "https:\/\/www.twitch.tv\/sorabi_",
                "game_id": 3,
                "slug": "sorabi",
                "description": "\"Ты то, что ты ешь\", говорили они. Но я не помню, чтобы я ела такую красотку Kappa",
                "provider": "twtich",
                "views": 4619589,
                "logo": "https:\/\/static-cdn.jtvnw.net\/jtv_user_pictures\/d30e9c53-9263-44cf-8416-d6adf3094d97-profile_image-300x300.png",
                "overlay": "https:\/\/static-cdn.jtvnw.net\/jtv_user_pictures\/77f67ececffd7b36-channel_offline_image-1920x1080.jpeg",
                "created_at": "2019-09-06T12:08:08.000000Z"
            },
            "tasks": []
        },
        {
            "id": 102,
            "name": "Miss Meta Flatley II",
            "last_name": null,
            "middle_name": null,
            "full_name": "Miss Meta Flatley II",
            "nickname": "shtyr",
            "role_id": null,
            "avatar": "http:\/\/darestreams.local\/storage\/avatars\\e657ea0778a7dabb7b6e9ebad541e173.jpg",
            "overlay": "\/img\/default_overlay.jpg",
            "created_at": "2019-09-06T12:02:33.000000Z",
            "updated_at": "2019-09-06T12:02:33.000000Z",
            "lang": "ru",
            "channel": null,
            "tasks": []
        },
        {
            "id": 13,
            "name": "Marcelle Gulgowski",
            "last_name": null,
            "middle_name": null,
            "full_name": "Marcelle Gulgowski",
            "nickname": "shroud",
            "role_id": null,
            "avatar": "http:\/\/darestreams.local\/storage\/avatars\\cf7e20da438542cc7546c6cc020b6441.jpg",
            "overlay": "\/img\/default_overlay.jpg",
            "created_at": "2019-09-06T12:00:16.000000Z",
            "updated_at": "2019-09-06T12:00:16.000000Z",
            "lang": "ru",
            "channel": {
                "id": 13,
                "user_id": 13,
                "title": "shroud",
                "link": "https:\/\/www.twitch.tv\/shroud",
                "game_id": 1,
                "slug": "shroud",
                "description": "Enjoy these highlights\/vods, and remember to follow!",
                "provider": "twtich",
                "views": 352268667,
                "logo": "https:\/\/static-cdn.jtvnw.net\/jtv_user_pictures\/7ed5e0c6-0191-4eef-8328-4af6e4ea5318-profile_image-300x300.png",
                "overlay": "https:\/\/static-cdn.jtvnw.net\/jtv_user_pictures\/f328a514-0cda-4239-9f99-24670b7ed7cb-channel_offline_image-1920x1080.png",
                "created_at": "2019-09-06T12:07:36.000000Z"
            },
            "tasks": []
        },
        {
            "id": 74,
            "name": "Ferne Adams I",
            "last_name": null,
            "middle_name": null,
            "full_name": "Ferne Adams I",
            "nickname": "sholidays",
            "role_id": null,
            "avatar": "http:\/\/darestreams.local\/storage\/avatars\\a02e63058b9fa26018f4ac4eecc6cdff.jpg",
            "overlay": "\/img\/default_overlay.jpg",
            "created_at": "2019-09-06T12:01:59.000000Z",
            "updated_at": "2019-09-06T12:01:59.000000Z",
            "lang": "ru",
            "channel": null,
            "tasks": []
        },
        {
            "id": 54,
            "name": "Chaim Torp",
            "last_name": null,
            "middle_name": null,
            "full_name": "Chaim Torp",
            "nickname": "segall",
            "role_id": null,
            "avatar": "http:\/\/darestreams.local\/storage\/avatars\\060681bd80654c0aa48abff9cfdd3e6c.jpg",
            "overlay": "\/img\/default_overlay.jpg",
            "created_at": "2019-09-06T12:01:35.000000Z",
            "updated_at": "2019-09-06T12:01:35.000000Z",
            "lang": "ru",
            "channel": null,
            "tasks": []
        },
        {
            "id": 34,
            "name": "Emmie Fisher",
            "last_name": null,
            "middle_name": null,
            "full_name": "Emmie Fisher",
            "nickname": "scr3amqueen",
            "role_id": null,
            "avatar": "http:\/\/darestreams.local\/storage\/avatars\\647305dc95e7baf1327a553f18c33e03.jpg",
            "overlay": "\/img\/default_overlay.jpg",
            "created_at": "2019-09-06T12:00:58.000000Z",
            "updated_at": "2019-09-06T12:00:58.000000Z",
            "lang": "ru",
            "channel": {
                "id": 33,
                "user_id": 34,
                "title": "scr3amqueen",
                "link": "https:\/\/www.twitch.tv\/scr3amqueen",
                "game_id": 9,
                "slug": "scr3amqueen",
                "description": "BOOM",
                "provider": "twtich",
                "views": 586053,
                "logo": "https:\/\/static-cdn.jtvnw.net\/jtv_user_pictures\/7d817865-3258-40b1-a110-6493f8c11842-profile_image-300x300.png",
                "overlay": "https:\/\/static-cdn.jtvnw.net\/jtv_user_pictures\/36f781ca-c6ac-49b2-88a4-18366e83e750-channel_offline_image-1920x1080.jpeg",
                "created_at": "2019-09-06T12:08:25.000000Z"
            },
            "tasks": []
        },
        {
            "id": 53,
            "name": "Clementina Nolan",
            "last_name": null,
            "middle_name": null,
            "full_name": "Clementina Nolan",
            "nickname": "saddrama",
            "role_id": null,
            "avatar": "http:\/\/darestreams.local\/storage\/avatars\\24e60cced27ed90cf85f5e2bccc3954e.jpg",
            "overlay": "\/img\/default_overlay.jpg",
            "created_at": "2019-09-06T12:01:34.000000Z",
            "updated_at": "2019-09-06T12:01:34.000000Z",
            "lang": "ru",
            "channel": null,
            "tasks": []
        },
        {
            "id": 76,
            "name": "Margret Herman",
            "last_name": null,
            "middle_name": null,
            "full_name": "Margret Herman",
            "nickname": "rootyasha",
            "role_id": null,
            "avatar": "http:\/\/darestreams.local\/storage\/avatars\\580f35658d095ec1f1181251d292f6a4.jpg",
            "overlay": "\/img\/default_overlay.jpg",
            "created_at": "2019-09-06T12:02:02.000000Z",
            "updated_at": "2019-09-06T12:02:02.000000Z",
            "lang": "ru",
            "channel": null,
            "tasks": []
        },
        {
            "id": 72,
            "name": "Everardo Kohler",
            "last_name": null,
            "middle_name": null,
            "full_name": "Everardo Kohler",
            "nickname": "romanovalera",
            "role_id": null,
            "avatar": "http:\/\/darestreams.local\/storage\/avatars\\0bd4edc7c6165d75bbb357fb18fd56b2.jpg",
            "overlay": "\/img\/default_overlay.jpg",
            "created_at": "2019-09-06T12:01:57.000000Z",
            "updated_at": "2019-09-06T12:01:57.000000Z",
            "lang": "ru",
            "channel": null,
            "tasks": []
        },
        {
            "id": 10,
            "name": "Asha Hickle",
            "last_name": null,
            "middle_name": null,
            "full_name": "Asha Hickle",
            "nickname": "rocketbeanstv",
            "role_id": null,
            "avatar": "http:\/\/darestreams.local\/storage\/avatars\\089aef56fb19abf5bac11f4de5ff42db.jpg",
            "overlay": "\/img\/default_overlay.jpg",
            "created_at": "2019-09-06T12:00:13.000000Z",
            "updated_at": "2019-09-06T12:00:13.000000Z",
            "lang": "ru",
            "channel": {
                "id": 10,
                "user_id": 10,
                "title": "rocketbeanstv",
                "link": "https:\/\/www.twitch.tv\/rocketbeanstv",
                "game_id": 1,
                "slug": "rocketbeanstv",
                "description": "Rocket Beans TV, das sind Daniel Budiman (Budi), Simon Krätschmer, Etienne Gardé (Eddy) und Nils Bomhoff. Bekannt durch Formate wie GIGA oder Game One, machen wir hier alles, was woanders nicht reinpasst!",
                "provider": "twtich",
                "views": 77371327,
                "logo": "https:\/\/static-cdn.jtvnw.net\/jtv_user_pictures\/2ac31cc4-78cf-4fa3-b535-3b9c80d46250-profile_image-300x300.png",
                "overlay": "https:\/\/static-cdn.jtvnw.net\/jtv_user_pictures\/690507f5c8c406de-channel_offline_image-1920x1080.png",
                "created_at": "2019-09-06T12:07:29.000000Z"
            },
            "tasks": []
        },
        {
            "id": 81,
            "name": "Ola Medhurst",
            "last_name": null,
            "middle_name": null,
            "full_name": "Ola Medhurst",
            "nickname": "revnyasha",
            "role_id": null,
            "avatar": "http:\/\/darestreams.local\/storage\/avatars\\d0fdff8a6b27112b92c93b0cd64181c9.jpg",
            "overlay": "\/img\/default_overlay.jpg",
            "created_at": "2019-09-06T12:02:08.000000Z",
            "updated_at": "2019-09-06T12:02:08.000000Z",
            "lang": "ru",
            "channel": null,
            "tasks": []
        },
        {
            "id": 37,
            "name": "Mr. Reilly Vandervort PhD",
            "last_name": null,
            "middle_name": null,
            "full_name": "Mr. Reilly Vandervort PhD",
            "nickname": "punshipun",
            "role_id": null,
            "avatar": "http:\/\/darestreams.local\/storage\/avatars\\888f77746bd84ef6bcff565720a204ca.jpg",
            "overlay": "\/img\/default_overlay.jpg",
            "created_at": "2019-09-06T12:01:02.000000Z",
            "updated_at": "2019-09-06T12:01:02.000000Z",
            "lang": "ru",
            "channel": {
                "id": 36,
                "user_id": 37,
                "title": "punshipun",
                "link": "https:\/\/www.twitch.tv\/punshipun",
                "game_id": 3,
                "slug": "punshipun",
                "description": "Аня развлекает аудиторию.",
                "provider": "twtich",
                "views": 2773751,
                "logo": "https:\/\/static-cdn.jtvnw.net\/jtv_user_pictures\/punshipun-profile_image-85520d4db8eca213-300x300.png",
                "overlay": "https:\/\/static-cdn.jtvnw.net\/jtv_user_pictures\/punshipun-channel_offline_image-05dda8dde6227467-1920x1080.jpeg",
                "created_at": "2019-09-06T12:08:32.000000Z"
            },
            "tasks": []
        },
        {
            "id": 68,
            "name": "Thurman Daniel",
            "last_name": null,
            "middle_name": null,
            "full_name": "Thurman Daniel",
            "nickname": "promotive",
            "role_id": null,
            "avatar": "http:\/\/darestreams.local\/storage\/avatars\\0dbea71f16f6d927496e0148876347cb.jpg",
            "overlay": "\/img\/default_overlay.jpg",
            "created_at": "2019-09-06T12:01:53.000000Z",
            "updated_at": "2019-09-06T12:01:53.000000Z",
            "lang": "ru",
            "channel": null,
            "tasks": []
        },
        {
            "id": 67,
            "name": "Dr. Merritt Adams MD",
            "last_name": null,
            "middle_name": null,
            "full_name": "Dr. Merritt Adams MD",
            "nickname": "playwithserch",
            "role_id": null,
            "avatar": "http:\/\/darestreams.local\/storage\/avatars\\c220ac3c1392799f78c8c4ca657c3fc2.jpg",
            "overlay": "\/img\/default_overlay.jpg",
            "created_at": "2019-09-06T12:01:50.000000Z",
            "updated_at": "2019-09-06T12:01:50.000000Z",
            "lang": "ru",
            "channel": null,
            "tasks": []
        },
        {
            "id": 46,
            "name": "Casimir McKenzie",
            "last_name": null,
            "middle_name": null,
            "full_name": "Casimir McKenzie",
            "nickname": "playbetterpro",
            "role_id": null,
            "avatar": "http:\/\/darestreams.local\/storage\/avatars\\4c3c0d7d1b064ad9d3776bf0ba96b969.jpg",
            "overlay": "\/img\/default_overlay.jpg",
            "created_at": "2019-09-06T12:01:26.000000Z",
            "updated_at": "2019-09-06T12:01:26.000000Z",
            "lang": "ru",
            "channel": null,
            "tasks": []
        },
        {
            "id": 93,
            "name": "Dr. Okey Treutel",
            "last_name": null,
            "middle_name": null,
            "full_name": "Dr. Okey Treutel",
            "nickname": "panteleev",
            "role_id": null,
            "avatar": "http:\/\/darestreams.local\/storage\/avatars\\0cf18672e5469bf6ad1c1cba0c9985a3.jpg",
            "overlay": "\/img\/default_overlay.jpg",
            "created_at": "2019-09-06T12:02:23.000000Z",
            "updated_at": "2019-09-06T12:02:23.000000Z",
            "lang": "ru",
            "channel": null,
            "tasks": []
        },
        {
            "id": 18,
            "name": "Durward Rolfson",
            "last_name": null,
            "middle_name": null,
            "full_name": "Durward Rolfson",
            "nickname": "olyashaa",
            "role_id": null,
            "avatar": "http:\/\/darestreams.local\/storage\/avatars\\43abef60c09fd3c18c4cc7e81d75a6c7.jpg",
            "overlay": "\/img\/default_overlay.jpg",
            "created_at": "2019-09-06T12:00:22.000000Z",
            "updated_at": "2019-09-06T12:00:22.000000Z",
            "lang": "ru",
            "channel": {
                "id": 17,
                "user_id": 18,
                "title": "olyashaa",
                "link": "https:\/\/www.twitch.tv\/olyashaa",
                "game_id": 3,
                "slug": "olyashaa",
                "description": "ничего не спрашивай, просто вступи в группу http:\/\/vk.com\/twitcholyashaa",
                "provider": "twtich",
                "views": 13685230,
                "logo": "https:\/\/static-cdn.jtvnw.net\/jtv_user_pictures\/olyashaa-profile_image-678836346723f273-300x300.png",
                "overlay": "https:\/\/static-cdn.jtvnw.net\/jtv_user_pictures\/12c32347-30a1-43dd-991b-910133fde4d1-channel_offline_image-1920x1080.png",
                "created_at": "2019-09-06T12:07:47.000000Z"
            },
            "tasks": []
        },
        {
            "id": 31,
            "name": "Kasey Labadie",
            "last_name": null,
            "middle_name": null,
            "full_name": "Kasey Labadie",
            "nickname": "olesyabulletka",
            "role_id": null,
            "avatar": "http:\/\/darestreams.local\/storage\/avatars\\336c5643b90e9a4fc9e1378cb76bc885.jpg",
            "overlay": "\/img\/default_overlay.jpg",
            "created_at": "2019-09-06T12:00:49.000000Z",
            "updated_at": "2019-09-06T12:00:49.000000Z",
            "lang": "ru",
            "channel": {
                "id": 30,
                "user_id": 31,
                "title": "olesyabulletka",
                "link": "https:\/\/www.twitch.tv\/olesyabulletka",
                "game_id": 3,
                "slug": "olesyabulletka",
                "description": "Та самая девушка с шестом..",
                "provider": "twtich",
                "views": 7896196,
                "logo": "https:\/\/static-cdn.jtvnw.net\/jtv_user_pictures\/36b1ff65-f060-49c2-8b67-f7233a928519-profile_image-300x300.png",
                "overlay": "https:\/\/static-cdn.jtvnw.net\/jtv_user_pictures\/2d305a45-0f8b-45bc-a0fe-3195148fea02-channel_offline_image-1920x1080.png",
                "created_at": "2019-09-06T12:08:18.000000Z"
            },
            "tasks": []
        },
        {
            "id": 87,
            "name": "Evalyn Littel",
            "last_name": null,
            "middle_name": null,
            "full_name": "Evalyn Littel",
            "nickname": "olesha",
            "role_id": null,
            "avatar": "http:\/\/darestreams.local\/storage\/avatars\\450e48650691fa04b20a24315dede99a.jpg",
            "overlay": "\/img\/default_overlay.jpg",
            "created_at": "2019-09-06T12:02:17.000000Z",
            "updated_at": "2019-09-06T12:02:17.000000Z",
            "lang": "ru",
            "channel": null,
            "tasks": []
        },
        {
            "id": 14,
            "name": "Rozella Bergstrom",
            "last_name": null,
            "middle_name": null,
            "full_name": "Rozella Bergstrom",
            "nickname": "noway4u_sir",
            "role_id": null,
            "avatar": "http:\/\/darestreams.local\/storage\/avatars\\4000e2a2e55d93a4d4e1e2b2dcc127ab.jpg",
            "overlay": "\/img\/default_overlay.jpg",
            "created_at": "2019-09-06T12:00:17.000000Z",
            "updated_at": "2019-09-06T12:00:17.000000Z",
            "lang": "ru",
            "channel": {
                "id": 14,
                "user_id": 14,
                "title": "noway4u_sir",
                "link": "https:\/\/www.twitch.tv\/noway4u_sir",
                "game_id": 2,
                "slug": "noway4u-sir",
                "description": "Bonobo Content",
                "provider": "twtich",
                "views": 34648500,
                "logo": "https:\/\/static-cdn.jtvnw.net\/jtv_user_pictures\/9e619d88755f56a8-profile_image-300x300.png",
                "overlay": "https:\/\/static-cdn.jtvnw.net\/jtv_user_pictures\/noway4u_sir-channel_offline_image-ac5619d4e71d4525-1920x1080.jpeg",
                "created_at": "2019-09-06T12:07:38.000000Z"
            },
            "tasks": []
        },
        {
            "id": 51,
            "name": "Margaretta Walsh DVM",
            "last_name": null,
            "middle_name": null,
            "full_name": "Margaretta Walsh DVM",
            "nickname": "nemagiaru",
            "role_id": null,
            "avatar": "http:\/\/darestreams.local\/storage\/avatars\\ba3e97dc08c67d836d87464d0659dd6d.jpg",
            "overlay": "\/img\/default_overlay.jpg",
            "created_at": "2019-09-06T12:01:32.000000Z",
            "updated_at": "2019-09-06T12:01:32.000000Z",
            "lang": "ru",
            "channel": null,
            "tasks": []
        },
        {
            "id": 83,
            "name": "Camille Mertz",
            "last_name": null,
            "middle_name": null,
            "full_name": "Camille Mertz",
            "nickname": "nelyaray",
            "role_id": null,
            "avatar": "http:\/\/darestreams.local\/storage\/avatars\\c78a6bb2abf96964e5a0b94e23d87120.jpg",
            "overlay": "\/img\/default_overlay.jpg",
            "created_at": "2019-09-06T12:02:10.000000Z",
            "updated_at": "2019-09-06T12:02:10.000000Z",
            "lang": "ru",
            "channel": null,
            "tasks": []
        },
        {
            "id": 57,
            "name": "Serena Ziemann",
            "last_name": null,
            "middle_name": null,
            "full_name": "Serena Ziemann",
            "nickname": "murochka_ua",
            "role_id": null,
            "avatar": "http:\/\/darestreams.local\/storage\/avatars\\1c27960ed50dc2fd22f039419550cb97.jpg",
            "overlay": "\/img\/default_overlay.jpg",
            "created_at": "2019-09-06T12:01:38.000000Z",
            "updated_at": "2019-09-06T12:01:38.000000Z",
            "lang": "ru",
            "channel": null,
            "tasks": []
        },
        {
            "id": 80,
            "name": "Dr. Winnifred Towne",
            "last_name": null,
            "middle_name": null,
            "full_name": "Dr. Winnifred Towne",
            "nickname": "msmaggiezolin",
            "role_id": null,
            "avatar": "http:\/\/darestreams.local\/storage\/avatars\\5f68bc4f7bc1f14d534094cbab8de1c9.jpg",
            "overlay": "\/img\/default_overlay.jpg",
            "created_at": "2019-09-06T12:02:07.000000Z",
            "updated_at": "2019-09-06T12:02:07.000000Z",
            "lang": "ru",
            "channel": null,
            "tasks": []
        },
        {
            "id": 50,
            "name": "Haylie Wilkinson",
            "last_name": null,
            "middle_name": null,
            "full_name": "Haylie Wilkinson",
            "nickname": "morphia",
            "role_id": null,
            "avatar": "http:\/\/darestreams.local\/storage\/avatars\\6000ed62b835ab82acf3bb0a7e72e816.jpg",
            "overlay": "\/img\/default_overlay.jpg",
            "created_at": "2019-09-06T12:01:31.000000Z",
            "updated_at": "2019-09-06T12:01:31.000000Z",
            "lang": "ru",
            "channel": null,
            "tasks": []
        },
        {
            "id": 61,
            "name": "Dr. May Mills",
            "last_name": null,
            "middle_name": null,
            "full_name": "Dr. May Mills",
            "nickname": "morganrandom",
            "role_id": null,
            "avatar": "http:\/\/darestreams.local\/storage\/avatars\\f56d957ebc2c351e0f8e4063ee326430.jpg",
            "overlay": "\/img\/default_overlay.jpg",
            "created_at": "2019-09-06T12:01:42.000000Z",
            "updated_at": "2019-09-06T12:01:42.000000Z",
            "lang": "ru",
            "channel": null,
            "tasks": []
        },
        {
            "id": 26,
            "name": "Jovanny Veum III",
            "last_name": null,
            "middle_name": null,
            "full_name": "Jovanny Veum III",
            "nickname": "modestal",
            "role_id": null,
            "avatar": "http:\/\/darestreams.local\/storage\/avatars\\454dd747b4b2e802e8f994713c464433.jpg",
            "overlay": "\/img\/default_overlay.jpg",
            "created_at": "2019-09-06T12:00:29.000000Z",
            "updated_at": "2019-09-06T12:00:29.000000Z",
            "lang": "ru",
            "channel": {
                "id": 25,
                "user_id": 26,
                "title": "modestal",
                "link": "https:\/\/www.twitch.tv\/modestal",
                "game_id": 3,
                "slug": "modestal",
                "description": "Дело  семейное,да?;)",
                "provider": "twtich",
                "views": 4979298,
                "logo": "https:\/\/static-cdn.jtvnw.net\/jtv_user_pictures\/1c49e85f-765d-46cf-afb0-d446a57d3f43-profile_image-300x300.jpeg",
                "overlay": "https:\/\/static-cdn.jtvnw.net\/jtv_user_pictures\/3d0cfd5c-8bff-47a4-afdc-70ef9012ffc9-channel_offline_image-1920x1080.jpeg",
                "created_at": "2019-09-06T12:08:06.000000Z"
            },
            "tasks": []
        },
        {
            "id": 39,
            "name": "Prof. Carolanne Volkman PhD",
            "last_name": null,
            "middle_name": null,
            "full_name": "Prof. Carolanne Volkman PhD",
            "nickname": "mob5tertv",
            "role_id": null,
            "avatar": "http:\/\/darestreams.local\/storage\/avatars\\34025bd82fc91425da8e0112d99844fa.jpg",
            "overlay": "\/img\/default_overlay.jpg",
            "created_at": "2019-09-06T12:01:08.000000Z",
            "updated_at": "2019-09-06T12:01:08.000000Z",
            "lang": "ru",
            "channel": null,
            "tasks": []
        },
        {
            "id": 21,
            "name": "Joyce Schaden",
            "last_name": null,
            "middle_name": null,
            "full_name": "Joyce Schaden",
            "nickname": "mira",
            "role_id": null,
            "avatar": "http:\/\/darestreams.local\/storage\/avatars\\6f4f26027b25e00d8b2668174ca70cbc.jpg",
            "overlay": "\/img\/default_overlay.jpg",
            "created_at": "2019-09-06T12:00:25.000000Z",
            "updated_at": "2019-09-06T12:00:25.000000Z",
            "lang": "ru",
            "channel": {
                "id": 20,
                "user_id": 21,
                "title": "mira",
                "link": "https:\/\/www.twitch.tv\/mira",
                "game_id": 3,
                "slug": "mira",
                "description": "=]",
                "provider": "twtich",
                "views": 18207595,
                "logo": "https:\/\/static-cdn.jtvnw.net\/jtv_user_pictures\/3bfa43d9-6ed6-4006-878a-35edc1e09213-profile_image-300x300.png",
                "overlay": "https:\/\/static-cdn.jtvnw.net\/jtv_user_pictures\/mira-channel_offline_image-a2f49cb3c2e4a096-1920x1080.jpeg",
                "created_at": "2019-09-06T12:07:54.000000Z"
            },
            "tasks": []
        },
        {
            "id": 82,
            "name": "Emely Kuphal",
            "last_name": null,
            "middle_name": null,
            "full_name": "Emely Kuphal",
            "nickname": "mikerina",
            "role_id": null,
            "avatar": "http:\/\/darestreams.local\/storage\/avatars\\69563499c41d8d4607ca80662620458f.jpg",
            "overlay": "\/img\/default_overlay.jpg",
            "created_at": "2019-09-06T12:02:09.000000Z",
            "updated_at": "2019-09-06T12:02:09.000000Z",
            "lang": "ru",
            "channel": null,
            "tasks": []
        },
        {
            "id": 32,
            "name": "Catharine McCullough DDS",
            "last_name": null,
            "middle_name": null,
            "full_name": "Catharine McCullough DDS",
            "nickname": "mihalina_",
            "role_id": null,
            "avatar": "http:\/\/darestreams.local\/storage\/avatars\\163ed5263011649a33936d244c4eb162.jpg",
            "overlay": "\/img\/default_overlay.jpg",
            "created_at": "2019-09-06T12:00:56.000000Z",
            "updated_at": "2019-09-06T12:00:56.000000Z",
            "lang": "ru",
            "channel": {
                "id": 31,
                "user_id": 32,
                "title": "mihalina_",
                "link": "https:\/\/www.twitch.tv\/mihalina_",
                "game_id": 3,
                "slug": "mihalina",
                "description": "",
                "provider": "twtich",
                "views": 2388150,
                "logo": "https:\/\/static-cdn.jtvnw.net\/jtv_user_pictures\/8d39e01d-87cd-4a48-84ca-3f378153c0ac-profile_image-300x300.png",
                "overlay": "https:\/\/static-cdn.jtvnw.net\/jtv_user_pictures\/70139f30-1bcc-45c8-b5e9-2150e0568b99-channel_offline_image-1920x1080.png",
                "created_at": "2019-09-06T12:08:21.000000Z"
            },
            "tasks": []
        },
        {
            "id": 77,
            "name": "Abby Lang",
            "last_name": null,
            "middle_name": null,
            "full_name": "Abby Lang",
            "nickname": "lucifer__chan",
            "role_id": null,
            "avatar": "http:\/\/darestreams.local\/storage\/avatars\\67ee592e0bcbfb3372dd25c4754384df.jpg",
            "overlay": "\/img\/default_overlay.jpg",
            "created_at": "2019-09-06T12:02:03.000000Z",
            "updated_at": "2019-09-06T12:02:03.000000Z",
            "lang": "ru",
            "channel": null,
            "tasks": []
        },
        {
            "id": 92,
            "name": "Randal Bechtelar",
            "last_name": null,
            "middle_name": null,
            "full_name": "Randal Bechtelar",
            "nickname": "lorinefairy",
            "role_id": null,
            "avatar": "http:\/\/darestreams.local\/storage\/avatars\\6e855f363f561577112a5f3c005ca328.jpg",
            "overlay": "\/img\/default_overlay.jpg",
            "created_at": "2019-09-06T12:02:22.000000Z",
            "updated_at": "2019-09-06T12:02:22.000000Z",
            "lang": "ru",
            "channel": null,
            "tasks": []
        },
        {
            "id": 75,
            "name": "Mr. Lonzo Stoltenberg I",
            "last_name": null,
            "middle_name": null,
            "full_name": "Mr. Lonzo Stoltenberg I",
            "nickname": "leyagornaya",
            "role_id": null,
            "avatar": "http:\/\/darestreams.local\/storage\/avatars\\4eecdad9a4d9ac5b40875cacc5940e1e.jpg",
            "overlay": "\/img\/default_overlay.jpg",
            "created_at": "2019-09-06T12:02:01.000000Z",
            "updated_at": "2019-09-06T12:02:01.000000Z",
            "lang": "ru",
            "channel": null,
            "tasks": []
        },
        {
            "id": 98,
            "name": "Rebeca Little",
            "last_name": null,
            "middle_name": null,
            "full_name": "Rebeca Little",
            "nickname": "leniniw",
            "role_id": null,
            "avatar": "http:\/\/darestreams.local\/storage\/avatars\\a290900e73ee661b3fdabd4ba319fee6.jpg",
            "overlay": "\/img\/default_overlay.jpg",
            "created_at": "2019-09-06T12:02:28.000000Z",
            "updated_at": "2019-09-06T12:02:28.000000Z",
            "lang": "ru",
            "channel": null,
            "tasks": []
        },
        {
            "id": 30,
            "name": "Maverick Howell",
            "last_name": null,
            "middle_name": null,
            "full_name": "Maverick Howell",
            "nickname": "lasqa",
            "role_id": null,
            "avatar": "http:\/\/darestreams.local\/storage\/avatars\\cb2f704d59badcd4a75700436ffff7b2.jpg",
            "overlay": "\/img\/default_overlay.jpg",
            "created_at": "2019-09-06T12:00:40.000000Z",
            "updated_at": "2019-09-06T12:00:40.000000Z",
            "lang": "ru",
            "channel": {
                "id": 29,
                "user_id": 30,
                "title": "lasqa",
                "link": "https:\/\/www.twitch.tv\/lasqa",
                "game_id": 1,
                "slug": "lasqa",
                "description": "Группа ВК — vk.com\/LasqaTV",
                "provider": "twtich",
                "views": 15465780,
                "logo": "https:\/\/static-cdn.jtvnw.net\/jtv_user_pictures\/lasqa-profile_image-49dc25f1e724dbd6-300x300.jpeg",
                "overlay": "https:\/\/static-cdn.jtvnw.net\/jtv_user_pictures\/ed58375ada58371d-channel_offline_image-1920x1080.jpeg",
                "created_at": "2019-09-06T12:08:15.000000Z"
            },
            "tasks": []
        },
        {
            "id": 59,
            "name": "Carlie Upton I",
            "last_name": null,
            "middle_name": null,
            "full_name": "Carlie Upton I",
            "nickname": "kyxnya",
            "role_id": null,
            "avatar": "http:\/\/darestreams.local\/storage\/avatars\\ef62766b3ba3a03081a9fb4deb155138.jpg",
            "overlay": "\/img\/default_overlay.jpg",
            "created_at": "2019-09-06T12:01:40.000000Z",
            "updated_at": "2019-09-06T12:01:40.000000Z",
            "lang": "ru",
            "channel": null,
            "tasks": []
        },
        {
            "id": 23,
            "name": "Michele Hahn",
            "last_name": null,
            "middle_name": null,
            "full_name": "Michele Hahn",
            "nickname": "kuplinov",
            "role_id": null,
            "avatar": "http:\/\/darestreams.local\/storage\/avatars\\778a82e28d987ee5abc2e904883b081b.jpg",
            "overlay": "\/img\/default_overlay.jpg",
            "created_at": "2019-09-06T12:00:27.000000Z",
            "updated_at": "2019-09-06T12:00:27.000000Z",
            "lang": "ru",
            "channel": {
                "id": 22,
                "user_id": 23,
                "title": "kuplinov",
                "link": "https:\/\/www.twitch.tv\/kuplinov",
                "game_id": 1,
                "slug": "kuplinov",
                "description": "Игры тут играются.",
                "provider": "twtich",
                "views": 2896200,
                "logo": "https:\/\/static-cdn.jtvnw.net\/jtv_user_pictures\/f4ca1d69-9eee-45a2-8509-079a3e5630df-profile_image-300x300.jpg",
                "overlay": "https:\/\/static-cdn.jtvnw.net\/jtv_user_pictures\/0e17368b48dce82a-channel_offline_image-1920x1080.jpeg",
                "created_at": "2019-09-06T12:07:59.000000Z"
            },
            "tasks": []
        },
        {
            "id": 90,
            "name": "Patrick O'Reilly",
            "last_name": null,
            "middle_name": null,
            "full_name": "Patrick O'Reilly",
            "nickname": "ksyasha",
            "role_id": null,
            "avatar": "http:\/\/darestreams.local\/storage\/avatars\\68c4e01c4cf0de01cad9dd3ab969548b.jpg",
            "overlay": "\/img\/default_overlay.jpg",
            "created_at": "2019-09-06T12:02:20.000000Z",
            "updated_at": "2019-09-06T12:02:20.000000Z",
            "lang": "ru",
            "channel": null,
            "tasks": []
        },
        {
            "id": 43,
            "name": "Carol Pfeffer I",
            "last_name": null,
            "middle_name": null,
            "full_name": "Carol Pfeffer I",
            "nickname": "kittyklawtv",
            "role_id": null,
            "avatar": "http:\/\/darestreams.local\/storage\/avatars\\ce4b035ebd9689ab530f5eb269e890be.jpg",
            "overlay": "\/img\/default_overlay.jpg",
            "created_at": "2019-09-06T12:01:23.000000Z",
            "updated_at": "2019-09-06T12:01:23.000000Z",
            "lang": "ru",
            "channel": null,
            "tasks": []
        },
        {
            "id": 73,
            "name": "Prof. Paula Lemke",
            "last_name": null,
            "middle_name": null,
            "full_name": "Prof. Paula Lemke",
            "nickname": "kati",
            "role_id": null,
            "avatar": "http:\/\/darestreams.local\/storage\/avatars\\2a673ddc7a8e677855cd93fec18a1851.jpg",
            "overlay": "\/img\/default_overlay.jpg",
            "created_at": "2019-09-06T12:01:58.000000Z",
            "updated_at": "2019-09-06T12:01:58.000000Z",
            "lang": "ru",
            "channel": null,
            "tasks": []
        },
        {
            "id": 96,
            "name": "Judson Terry DVM",
            "last_name": null,
            "middle_name": null,
            "full_name": "Judson Terry DVM",
            "nickname": "joskiyokda",
            "role_id": null,
            "avatar": "http:\/\/darestreams.local\/storage\/avatars\\031d341270822285b6efa5f15a9b6e84.jpg",
            "overlay": "\/img\/default_overlay.jpg",
            "created_at": "2019-09-06T12:02:26.000000Z",
            "updated_at": "2019-09-06T12:02:26.000000Z",
            "lang": "ru",
            "channel": null,
            "tasks": []
        },
        {
            "id": 91,
            "name": "Hoyt Schulist Jr.",
            "last_name": null,
            "middle_name": null,
            "full_name": "Hoyt Schulist Jr.",
            "nickname": "jointime",
            "role_id": null,
            "avatar": "http:\/\/darestreams.local\/storage\/avatars\\9204b5a894752a68d6355f57f1a62a45.jpg",
            "overlay": "\/img\/default_overlay.jpg",
            "created_at": "2019-09-06T12:02:21.000000Z",
            "updated_at": "2019-09-06T12:02:21.000000Z",
            "lang": "ru",
            "channel": null,
            "tasks": []
        },
        {
            "id": 103,
            "name": "Keanu Kassulke",
            "last_name": null,
            "middle_name": null,
            "full_name": "Keanu Kassulke",
            "nickname": "johnylemonade",
            "role_id": null,
            "avatar": "http:\/\/darestreams.local\/storage\/avatars\\e143b76086f0caf44cc87d0a2a2c913c.jpg",
            "overlay": "\/img\/default_overlay.jpg",
            "created_at": "2019-09-06T12:02:34.000000Z",
            "updated_at": "2019-09-06T12:02:34.000000Z",
            "lang": "ru",
            "channel": null,
            "tasks": []
        },
        {
            "id": 17,
            "name": "Rogelio Robel",
            "last_name": null,
            "middle_name": null,
            "full_name": "Rogelio Robel",
            "nickname": "jesusavgn",
            "role_id": null,
            "avatar": "http:\/\/darestreams.local\/storage\/avatars\\651de4ba2b0d2456e4f55fcacbeb5015.jpg",
            "overlay": "\/img\/default_overlay.jpg",
            "created_at": "2019-09-06T12:00:20.000000Z",
            "updated_at": "2019-09-06T12:00:20.000000Z",
            "lang": "ru",
            "channel": {
                "id": 16,
                "user_id": 17,
                "title": "jesusavgn",
                "link": "https:\/\/www.twitch.tv\/jesusavgn",
                "game_id": 3,
                "slug": "jesusavgn",
                "description": "",
                "provider": "twtich",
                "views": 24311721,
                "logo": "https:\/\/static-cdn.jtvnw.net\/jtv_user_pictures\/jesusavgn-profile_image-ef60f6d58af4ccef-300x300.jpeg",
                "overlay": "https:\/\/static-cdn.jtvnw.net\/jtv_user_pictures\/jesusavgn-channel_offline_image-d6fde5154b88da29-1920x1080.jpeg",
                "created_at": "2019-09-06T12:07:45.000000Z"
            },
            "tasks": []
        },
        {
            "id": 47,
            "name": "Dr. Angelo Price",
            "last_name": null,
            "middle_name": null,
            "full_name": "Dr. Angelo Price",
            "nickname": "jamclub",
            "role_id": null,
            "avatar": "http:\/\/darestreams.local\/storage\/avatars\\8c409341862adc43070ae1db8c7b199e.jpg",
            "overlay": "\/img\/default_overlay.jpg",
            "created_at": "2019-09-06T12:01:27.000000Z",
            "updated_at": "2019-09-06T12:01:27.000000Z",
            "lang": "ru",
            "channel": null,
            "tasks": []
        },
        {
            "id": 89,
            "name": "Dudley Hahn",
            "last_name": null,
            "middle_name": null,
            "full_name": "Dudley Hahn",
            "nickname": "insize",
            "role_id": null,
            "avatar": "http:\/\/darestreams.local\/storage\/avatars\\50d6a9754560268516b6a7604bcf1f91.jpg",
            "overlay": "\/img\/default_overlay.jpg",
            "created_at": "2019-09-06T12:02:19.000000Z",
            "updated_at": "2019-09-06T12:02:19.000000Z",
            "lang": "ru",
            "channel": null,
            "tasks": []
        },
        {
            "id": 64,
            "name": "Judson Fahey",
            "last_name": null,
            "middle_name": null,
            "full_name": "Judson Fahey",
            "nickname": "inmateoo",
            "role_id": null,
            "avatar": "http:\/\/darestreams.local\/storage\/avatars\\62e0d7c95e849a58c7babaa7e186ace2.jpg",
            "overlay": "\/img\/default_overlay.jpg",
            "created_at": "2019-09-06T12:01:45.000000Z",
            "updated_at": "2019-09-06T12:01:45.000000Z",
            "lang": "ru",
            "channel": null,
            "tasks": []
        },
        {
            "id": 33,
            "name": "Missouri Blanda",
            "last_name": null,
            "middle_name": null,
            "full_name": "Missouri Blanda",
            "nickname": "hellyeahplay",
            "role_id": null,
            "avatar": "http:\/\/darestreams.local\/storage\/avatars\\e101eae499b2d23c24a58530be98e987.jpg",
            "overlay": "\/img\/default_overlay.jpg",
            "created_at": "2019-09-06T12:00:57.000000Z",
            "updated_at": "2019-09-06T12:00:57.000000Z",
            "lang": "ru",
            "channel": {
                "id": 32,
                "user_id": 33,
                "title": "hellyeahplay",
                "link": "https:\/\/www.twitch.tv\/hellyeahplay",
                "game_id": 3,
                "slug": "hellyeahplay",
                "description": "Мизантроп с психическим расстройством.",
                "provider": "twtich",
                "views": 8594209,
                "logo": "https:\/\/static-cdn.jtvnw.net\/jtv_user_pictures\/hellyeahplay-profile_image-7b27caab4aefe1ad-300x300.png",
                "overlay": "https:\/\/static-cdn.jtvnw.net\/jtv_user_pictures\/hellyeahplay-channel_offline_image-17cf79d8aa5eb833-1920x1080.jpeg",
                "created_at": "2019-09-06T12:08:23.000000Z"
            },
            "tasks": []
        },
        {
            "id": 2,
            "name": "Bertrand Boehm",
            "last_name": null,
            "middle_name": null,
            "full_name": "Bertrand Boehm",
            "nickname": "hardgamechannel",
            "role_id": null,
            "avatar": "http:\/\/darestreams.local\/storage\/avatars\\b892aa82af1168ef0ac322c982a286e0.jpg",
            "overlay": "\/img\/default_overlay.jpg",
            "created_at": "2019-09-06T11:59:49.000000Z",
            "updated_at": "2019-09-06T11:59:49.000000Z",
            "lang": "ru",
            "channel": {
                "id": 2,
                "user_id": 2,
                "title": "hardgamechannel",
                "link": "https:\/\/www.twitch.tv\/hardgamechannel",
                "game_id": 1,
                "slug": "hardgamechannel",
                "description": "Я у мамы стример",
                "provider": "twtich",
                "views": 17298272,
                "logo": "https:\/\/static-cdn.jtvnw.net\/jtv_user_pictures\/a863789b-a8c0-44f3-88ed-6da7afc5aea9-profile_image-300x300.png",
                "overlay": "https:\/\/static-cdn.jtvnw.net\/jtv_user_pictures\/4d18d388-a1c3-47ae-8b67-8579814b9d47-channel_offline_image-1920x1080.png",
                "created_at": "2019-09-06T12:07:08.000000Z"
            },
            "tasks": []
        },
        {
            "id": 25,
            "name": "Adela Rutherford",
            "last_name": null,
            "middle_name": null,
            "full_name": "Adela Rutherford",
            "nickname": "happasc2",
            "role_id": null,
            "avatar": "http:\/\/darestreams.local\/storage\/avatars\\9af17e7a02bdb4123d8ad4844020cbd0.jpg",
            "overlay": "\/img\/default_overlay.jpg",
            "created_at": "2019-09-06T12:00:29.000000Z",
            "updated_at": "2019-09-06T12:00:29.000000Z",
            "lang": "ru",
            "channel": {
                "id": 24,
                "user_id": 25,
                "title": "happasc2",
                "link": "https:\/\/www.twitch.tv\/happasc2",
                "game_id": 5,
                "slug": "happasc2",
                "description": "Сашуля 29 годиков!",
                "provider": "twtich",
                "views": 17684791,
                "logo": "https:\/\/static-cdn.jtvnw.net\/jtv_user_pictures\/happasc2-profile_image-e9e00117c2df65ba-300x300.jpeg",
                "overlay": "https:\/\/static-cdn.jtvnw.net\/jtv_user_pictures\/817323e1-df5e-4f0e-b0b1-114c9584df3d-channel_offline_image-1920x1080.jpeg",
                "created_at": "2019-09-06T12:08:04.000000Z"
            },
            "tasks": []
        },
        {
            "id": 29,
            "name": "Kelley Langworth",
            "last_name": null,
            "middle_name": null,
            "full_name": "Kelley Langworth",
            "nickname": "gufovicky",
            "role_id": null,
            "avatar": "http:\/\/darestreams.local\/storage\/avatars\\1e0f95dead43af70e7b9ec10af9dfc00.jpg",
            "overlay": "\/img\/default_overlay.jpg",
            "created_at": "2019-09-06T12:00:35.000000Z",
            "updated_at": "2019-09-06T12:00:35.000000Z",
            "lang": "ru",
            "channel": {
                "id": 28,
                "user_id": 29,
                "title": "gufovicky",
                "link": "https:\/\/www.twitch.tv\/gufovicky",
                "game_id": 1,
                "slug": "gufovicky",
                "description": "Потрясающие трансляции лайк подписка ",
                "provider": "twtich",
                "views": 3978117,
                "logo": "https:\/\/static-cdn.jtvnw.net\/jtv_user_pictures\/gufovicky-profile_image-48074644a6341ab3-300x300.jpeg",
                "overlay": "https:\/\/static-cdn.jtvnw.net\/jtv_user_pictures\/gufovicky-channel_offline_image-705cf58394f848ed-1920x1080.jpeg",
                "created_at": "2019-09-06T12:08:13.000000Z"
            },
            "tasks": []
        },
        {
            "id": 16,
            "name": "Dr. Triston Heidenreich DVM",
            "last_name": null,
            "middle_name": null,
            "full_name": "Dr. Triston Heidenreich DVM",
            "nickname": "gladiatorpwnz",
            "role_id": null,
            "avatar": "http:\/\/darestreams.local\/storage\/avatars\\a8984f1cd64f3e88d9218da614a0ecc0.jpg",
            "overlay": "\/img\/default_overlay.jpg",
            "created_at": "2019-09-06T12:00:19.000000Z",
            "updated_at": "2019-09-06T12:00:19.000000Z",
            "lang": "ru",
            "channel": null,
            "tasks": []
        },
        {
            "id": 45,
            "name": "Dustin Dicki I",
            "last_name": null,
            "middle_name": null,
            "full_name": "Dustin Dicki I",
            "nickname": "gavrilka",
            "role_id": null,
            "avatar": "http:\/\/darestreams.local\/storage\/avatars\\1d546eb74e35a981933937c719f1e0f9.jpg",
            "overlay": "\/img\/default_overlay.jpg",
            "created_at": "2019-09-06T12:01:25.000000Z",
            "updated_at": "2019-09-06T12:01:25.000000Z",
            "lang": "ru",
            "channel": null,
            "tasks": []
        },
        {
            "id": 7,
            "name": "Dudley Schowalter",
            "last_name": null,
            "middle_name": null,
            "full_name": "Dudley Schowalter",
            "nickname": "gaules",
            "role_id": null,
            "avatar": "http:\/\/darestreams.local\/storage\/avatars\\2f47d423cb915f1431f8513ac64c4386.jpg",
            "overlay": "\/img\/default_overlay.jpg",
            "created_at": "2019-09-06T12:00:10.000000Z",
            "updated_at": "2019-09-06T12:00:10.000000Z",
            "lang": "ru",
            "channel": {
                "id": 7,
                "user_id": 7,
                "title": "gaules",
                "link": "https:\/\/www.twitch.tv\/gaules",
                "game_id": 10,
                "slug": "gaules",
                "description": "Focado no e-Sport desde 1998, fiz parte da primeira geração de jogadores profissionais do mundo. Atuei 9 anos como jogador, 3 anos como técnico, sendo o primeiro a conquistar um título mundial no CS nesta função. Nos últimos 7 anos me dedico ao empreendedorismo no mercado de eSport.",
                "provider": "twtich",
                "views": 66756861,
                "logo": "https:\/\/static-cdn.jtvnw.net\/jtv_user_pictures\/c495b77e-7f47-4bc5-a216-3045d7545796-profile_image-300x300.png",
                "overlay": "https:\/\/static-cdn.jtvnw.net\/jtv_user_pictures\/3de00e1ebbe194c8-channel_offline_image-1920x1080.png",
                "created_at": "2019-09-06T12:07:22.000000Z"
            },
            "tasks": []
        },
        {
            "id": 48,
            "name": "Prof. Eden Zboncak",
            "last_name": null,
            "middle_name": null,
            "full_name": "Prof. Eden Zboncak",
            "nickname": "gagatun",
            "role_id": null,
            "avatar": "http:\/\/darestreams.local\/storage\/avatars\\e0183adf117f710c5485d9771cdf3bbd.jpg",
            "overlay": "\/img\/default_overlay.jpg",
            "created_at": "2019-09-06T12:01:28.000000Z",
            "updated_at": "2019-09-06T12:01:28.000000Z",
            "lang": "ru",
            "channel": null,
            "tasks": []
        },
        {
            "id": 3,
            "name": "Prof. Cristopher Trantow",
            "last_name": null,
            "middle_name": null,
            "full_name": "Prof. Cristopher Trantow",
            "nickname": "gabepeixe",
            "role_id": null,
            "avatar": "http:\/\/darestreams.local\/storage\/avatars\\bbe30554092388e2f68dbbd7f0cdbec3.jpg",
            "overlay": "\/img\/default_overlay.jpg",
            "created_at": "2019-09-06T11:59:50.000000Z",
            "updated_at": "2019-09-06T11:59:50.000000Z",
            "lang": "ru",
            "channel": {
                "id": 3,
                "user_id": 3,
                "title": "gabepeixe",
                "link": "https:\/\/www.twitch.tv\/gabepeixe",
                "game_id": 3,
                "slug": "gabepeixe",
                "description": "XD",
                "provider": "twtich",
                "views": 9088768,
                "logo": "https:\/\/static-cdn.jtvnw.net\/jtv_user_pictures\/e81441ba-4786-4628-b9bb-77098e4a917f-profile_image-300x300.png",
                "overlay": "https:\/\/static-cdn.jtvnw.net\/jtv_user_pictures\/402daa6b-eff9-4c5d-8abe-5462ec056e4b-channel_offline_image-1920x1080.jpeg",
                "created_at": "2019-09-06T12:07:12.000000Z"
            },
            "tasks": []
        },
        {
            "id": 56,
            "name": "Jaylin Gerhold",
            "last_name": null,
            "middle_name": null,
            "full_name": "Jaylin Gerhold",
            "nickname": "fruktozka",
            "role_id": null,
            "avatar": "http:\/\/darestreams.local\/storage\/avatars\\d94a5d382327716778cbc819d8f5dc5f.jpg",
            "overlay": "\/img\/default_overlay.jpg",
            "created_at": "2019-09-06T12:01:37.000000Z",
            "updated_at": "2019-09-06T12:01:37.000000Z",
            "lang": "ru",
            "channel": null,
            "tasks": []
        },
        {
            "id": 9,
            "name": "Prof. Rafaela Ferry",
            "last_name": null,
            "middle_name": null,
            "full_name": "Prof. Rafaela Ferry",
            "nickname": "exbc",
            "role_id": null,
            "avatar": "http:\/\/darestreams.local\/storage\/avatars\\fb62465acce16465f1e09f2fbd9336ad.jpg",
            "overlay": "\/img\/default_overlay.jpg",
            "created_at": "2019-09-06T12:00:12.000000Z",
            "updated_at": "2019-09-06T12:00:12.000000Z",
            "lang": "ru",
            "channel": {
                "id": 9,
                "user_id": 9,
                "title": "exbc",
                "link": "https:\/\/www.twitch.tv\/exbc",
                "game_id": 3,
                "slug": "exbc",
                "description": "http:\/\/www.youtube.com\/user\/exbctv ",
                "provider": "twtich",
                "views": 12024009,
                "logo": "https:\/\/static-cdn.jtvnw.net\/jtv_user_pictures\/exbc-profile_image-b123ccd6d2990eaa-300x300.jpeg",
                "overlay": "https:\/\/static-cdn.jtvnw.net\/jtv_user_pictures\/67961818-beff-4c8a-950c-a0eac91f7c36-channel_offline_image-1920x1080.jpeg",
                "created_at": "2019-09-06T12:07:26.000000Z"
            },
            "tasks": []
        },
        {
            "id": 84,
            "name": "Katarina Klein",
            "last_name": null,
            "middle_name": null,
            "full_name": "Katarina Klein",
            "nickname": "eveliinushka",
            "role_id": null,
            "avatar": "http:\/\/darestreams.local\/storage\/avatars\\bca77ba00394d96852ae3d84387e3321.jpg",
            "overlay": "\/img\/default_overlay.jpg",
            "created_at": "2019-09-06T12:02:13.000000Z",
            "updated_at": "2019-09-06T12:02:13.000000Z",
            "lang": "ru",
            "channel": null,
            "tasks": []
        },
        {
            "id": 41,
            "name": "Kayden Mohr",
            "last_name": null,
            "middle_name": null,
            "full_name": "Kayden Mohr",
            "nickname": "elwycco",
            "role_id": null,
            "avatar": "http:\/\/darestreams.local\/storage\/avatars\\e76ea8cc86389a2a3561c6e5457a53de.jpg",
            "overlay": "\/img\/default_overlay.jpg",
            "created_at": "2019-09-06T12:01:21.000000Z",
            "updated_at": "2019-09-06T12:01:21.000000Z",
            "lang": "ru",
            "channel": null,
            "tasks": []
        },
        {
            "id": 58,
            "name": "Bailee Flatley",
            "last_name": null,
            "middle_name": null,
            "full_name": "Bailee Flatley",
            "nickname": "ellvi",
            "role_id": null,
            "avatar": "http:\/\/darestreams.local\/storage\/avatars\\2da2c954c7642cec88eb28f4e0b41e66.jpg",
            "overlay": "\/img\/default_overlay.jpg",
            "created_at": "2019-09-06T12:01:39.000000Z",
            "updated_at": "2019-09-06T12:01:39.000000Z",
            "lang": "ru",
            "channel": null,
            "tasks": []
        },
        {
            "id": 88,
            "name": "Cory Gutkowski IV",
            "last_name": null,
            "middle_name": null,
            "full_name": "Cory Gutkowski IV",
            "nickname": "elfiona",
            "role_id": null,
            "avatar": "http:\/\/darestreams.local\/storage\/avatars\\ad9a9ac24fe4f7f13bd41ae47b4d2243.jpg",
            "overlay": "\/img\/default_overlay.jpg",
            "created_at": "2019-09-06T12:02:18.000000Z",
            "updated_at": "2019-09-06T12:02:18.000000Z",
            "lang": "ru",
            "channel": null,
            "tasks": []
        },
        {
            "id": 97,
            "name": "Berenice Jerde",
            "last_name": null,
            "middle_name": null,
            "full_name": "Berenice Jerde",
            "nickname": "dtfru",
            "role_id": null,
            "avatar": "http:\/\/darestreams.local\/storage\/avatars\\0724ad130866ec41fa70a25f477eab4f.jpg",
            "overlay": "\/img\/default_overlay.jpg",
            "created_at": "2019-09-06T12:02:27.000000Z",
            "updated_at": "2019-09-06T12:02:27.000000Z",
            "lang": "ru",
            "channel": null,
            "tasks": []
        },
        {
            "id": 66,
            "name": "Jerald Grimes",
            "last_name": null,
            "middle_name": null,
            "full_name": "Jerald Grimes",
            "nickname": "dinablin",
            "role_id": null,
            "avatar": "http:\/\/darestreams.local\/storage\/avatars\\88e6c3bab4865cace6b19e135717c9c9.jpg",
            "overlay": "\/img\/default_overlay.jpg",
            "created_at": "2019-09-06T12:01:49.000000Z",
            "updated_at": "2019-09-06T12:01:49.000000Z",
            "lang": "ru",
            "channel": null,
            "tasks": []
        },
        {
            "id": 79,
            "name": "Julian Wilderman",
            "last_name": null,
            "middle_name": null,
            "full_name": "Julian Wilderman",
            "nickname": "dimaoneshot",
            "role_id": null,
            "avatar": "http:\/\/darestreams.local\/storage\/avatars\\d727ef6383aab4cbd7efaa0f9b9c2ad3.jpg",
            "overlay": "\/img\/default_overlay.jpg",
            "created_at": "2019-09-06T12:02:06.000000Z",
            "updated_at": "2019-09-06T12:02:06.000000Z",
            "lang": "ru",
            "channel": null,
            "tasks": []
        },
        {
            "id": 24,
            "name": "Kathryn Weimann",
            "last_name": null,
            "middle_name": null,
            "full_name": "Kathryn Weimann",
            "nickname": "denly",
            "role_id": null,
            "avatar": "http:\/\/darestreams.local\/storage\/avatars\\7067c5d4e69f0179e02c34888b6252f8.jpg",
            "overlay": "\/img\/default_overlay.jpg",
            "created_at": "2019-09-06T12:00:28.000000Z",
            "updated_at": "2019-09-06T12:00:28.000000Z",
            "lang": "ru",
            "channel": {
                "id": 23,
                "user_id": 24,
                "title": "denly",
                "link": "https:\/\/www.twitch.tv\/denly",
                "game_id": 7,
                "slug": "denly",
                "description": "Лиза",
                "provider": "twtich",
                "views": 5256539,
                "logo": "https:\/\/static-cdn.jtvnw.net\/jtv_user_pictures\/0e0aa7fb-af1d-4c74-af68-acf3c16c36e2-profile_image-300x300.png",
                "overlay": "https:\/\/static-cdn.jtvnw.net\/jtv_user_pictures\/c137d26c-8f68-406b-8505-1f52c1b3f497-channel_offline_image-1920x1080.jpeg",
                "created_at": "2019-09-06T12:08:01.000000Z"
            },
            "tasks": []
        },
        {
            "id": 95,
            "name": "Joelle Huels",
            "last_name": null,
            "middle_name": null,
            "full_name": "Joelle Huels",
            "nickname": "delaylamy",
            "role_id": null,
            "avatar": "http:\/\/darestreams.local\/storage\/avatars\\fdf2a611a80363900f35fa16453ce844.jpg",
            "overlay": "\/img\/default_overlay.jpg",
            "created_at": "2019-09-06T12:02:25.000000Z",
            "updated_at": "2019-09-06T12:02:25.000000Z",
            "lang": "ru",
            "channel": null,
            "tasks": []
        },
        {
            "id": 44,
            "name": "Dr. Hilbert Volkman Jr.",
            "last_name": null,
            "middle_name": null,
            "full_name": "Dr. Hilbert Volkman Jr.",
            "nickname": "dawgdebik",
            "role_id": null,
            "avatar": "http:\/\/darestreams.local\/storage\/avatars\\250e2d59946b9c39dbaf0dcb2f153d34.jpg",
            "overlay": "\/img\/default_overlay.jpg",
            "created_at": "2019-09-06T12:01:24.000000Z",
            "updated_at": "2019-09-06T12:01:24.000000Z",
            "lang": "ru",
            "channel": null,
            "tasks": []
        },
        {
            "id": 65,
            "name": "Shawn Schneider",
            "last_name": null,
            "middle_name": null,
            "full_name": "Shawn Schneider",
            "nickname": "dariya_willis",
            "role_id": null,
            "avatar": "http:\/\/darestreams.local\/storage\/avatars\\1d0acd4651ec7d4fe6bce451c66c0061.jpg",
            "overlay": "\/img\/default_overlay.jpg",
            "created_at": "2019-09-06T12:01:46.000000Z",
            "updated_at": "2019-09-06T12:01:46.000000Z",
            "lang": "ru",
            "channel": null,
            "tasks": []
        },
        {
            "id": 11,
            "name": "Joshua Brakus",
            "last_name": null,
            "middle_name": null,
            "full_name": "Joshua Brakus",
            "nickname": "copykat_",
            "role_id": null,
            "avatar": "http:\/\/darestreams.local\/storage\/avatars\\669644f7d320d1090945318306768ead.jpg",
            "overlay": "\/img\/default_overlay.jpg",
            "created_at": "2019-09-06T12:00:14.000000Z",
            "updated_at": "2019-09-06T12:00:14.000000Z",
            "lang": "ru",
            "channel": {
                "id": 11,
                "user_id": 11,
                "title": "copykat_",
                "link": "https:\/\/www.twitch.tv\/copykat_",
                "game_id": 1,
                "slug": "copykat",
                "description": "Mindfullness, relaxation, ASMR. ❤️",
                "provider": "twtich",
                "views": 3958062,
                "logo": "https:\/\/static-cdn.jtvnw.net\/jtv_user_pictures\/6e73a059-9b56-4cb9-a7ee-2785c2248476-profile_image-300x300.jpg",
                "overlay": "\/img\/default_channel_overlay.jpg",
                "created_at": "2019-09-06T12:07:31.000000Z"
            },
            "tasks": []
        },
        {
            "id": 49,
            "name": "Miss Emely Farrell Jr.",
            "last_name": null,
            "middle_name": null,
            "full_name": "Miss Emely Farrell Jr.",
            "nickname": "ciklonica",
            "role_id": null,
            "avatar": "http:\/\/darestreams.local\/storage\/avatars\\d43377328a8f7e6fc6c6fcce6563439b.jpg",
            "overlay": "\/img\/default_overlay.jpg",
            "created_at": "2019-09-06T12:01:29.000000Z",
            "updated_at": "2019-09-06T12:01:29.000000Z",
            "lang": "ru",
            "channel": null,
            "tasks": []
        },
        {
            "id": 52,
            "name": "Aryanna Bergnaum",
            "last_name": null,
            "middle_name": null,
            "full_name": "Aryanna Bergnaum",
            "nickname": "cemka",
            "role_id": null,
            "avatar": "http:\/\/darestreams.local\/storage\/avatars\\2beda6bd2574e169770d267462e66db1.jpg",
            "overlay": "\/img\/default_overlay.jpg",
            "created_at": "2019-09-06T12:01:33.000000Z",
            "updated_at": "2019-09-06T12:01:33.000000Z",
            "lang": "ru",
            "channel": null,
            "tasks": []
        },
        {
            "id": 99,
            "name": "Kayla Heller",
            "last_name": null,
            "middle_name": null,
            "full_name": "Kayla Heller",
            "nickname": "by_owl",
            "role_id": null,
            "avatar": "http:\/\/darestreams.local\/storage\/avatars\\10b215ef198a032931827f97dab5202a.jpg",
            "overlay": "\/img\/default_overlay.jpg",
            "created_at": "2019-09-06T12:02:30.000000Z",
            "updated_at": "2019-09-06T12:02:30.000000Z",
            "lang": "ru",
            "channel": null,
            "tasks": []
        },
        {
            "id": 106,
            "name": "Kristy Schumm",
            "last_name": null,
            "middle_name": null,
            "full_name": "Kristy Schumm",
            "nickname": "busya18plus",
            "role_id": null,
            "avatar": "http:\/\/darestreams.local\/storage\/avatars\\e116df06aa70e51a89346affca8f8d17.jpg",
            "overlay": "\/img\/default_overlay.jpg",
            "created_at": "2019-09-06T12:02:37.000000Z",
            "updated_at": "2019-09-06T12:02:37.000000Z",
            "lang": "ru",
            "channel": null,
            "tasks": []
        },
        {
            "id": 85,
            "name": "Prof. Lenna Gottlieb III",
            "last_name": null,
            "middle_name": null,
            "full_name": "Prof. Lenna Gottlieb III",
            "nickname": "bulochkaaa",
            "role_id": null,
            "avatar": "http:\/\/darestreams.local\/storage\/avatars\\68cbd381d5932635278e7f795bf9bc09.jpg",
            "overlay": "\/img\/default_overlay.jpg",
            "created_at": "2019-09-06T12:02:14.000000Z",
            "updated_at": "2019-09-06T12:02:14.000000Z",
            "lang": "ru",
            "channel": null,
            "tasks": []
        },
        {
            "id": 63,
            "name": "Reinhold McGlynn",
            "last_name": null,
            "middle_name": null,
            "full_name": "Reinhold McGlynn",
            "nickname": "bloody_elf",
            "role_id": null,
            "avatar": "http:\/\/darestreams.local\/storage\/avatars\\42e7a7be79f1fdc8120039644ba038ac.jpg",
            "overlay": "\/img\/default_overlay.jpg",
            "created_at": "2019-09-06T12:01:44.000000Z",
            "updated_at": "2019-09-06T12:01:44.000000Z",
            "lang": "ru",
            "channel": null,
            "tasks": []
        },
        {
            "id": 35,
            "name": "Clementina Kuvalis",
            "last_name": null,
            "middle_name": null,
            "full_name": "Clementina Kuvalis",
            "nickname": "beastqt",
            "role_id": null,
            "avatar": "http:\/\/darestreams.local\/storage\/avatars\\d0bca71b006d17d82826adb6ee08c736.jpg",
            "overlay": "\/img\/default_overlay.jpg",
            "created_at": "2019-09-06T12:00:59.000000Z",
            "updated_at": "2019-09-06T12:00:59.000000Z",
            "lang": "ru",
            "channel": {
                "id": 34,
                "user_id": 35,
                "title": "beastqt",
                "link": "https:\/\/www.twitch.tv\/beastqt",
                "game_id": 7,
                "slug": "beastqt",
                "description": " ",
                "provider": "twtich",
                "views": 10302252,
                "logo": "https:\/\/static-cdn.jtvnw.net\/jtv_user_pictures\/8db75fae-6138-4f16-ab18-a78bee03b8b0-profile_image-300x300.png",
                "overlay": "https:\/\/static-cdn.jtvnw.net\/jtv_user_pictures\/965345555c5a246c-channel_offline_image-1920x1080.jpeg",
                "created_at": "2019-09-06T12:08:28.000000Z"
            },
            "tasks": []
        },
        {
            "id": 69,
            "name": "Jermaine Metz",
            "last_name": null,
            "middle_name": null,
            "full_name": "Jermaine Metz",
            "nickname": "b_u_l_o_c_h_k_a",
            "role_id": null,
            "avatar": "http:\/\/darestreams.local\/storage\/avatars\\e7b62a6d1e6269dc478f9a38155d813c.jpg",
            "overlay": "\/img\/default_overlay.jpg",
            "created_at": "2019-09-06T12:01:54.000000Z",
            "updated_at": "2019-09-06T12:01:54.000000Z",
            "lang": "ru",
            "channel": null,
            "tasks": []
        },
        {
            "id": 8,
            "name": "Michale Brekke",
            "last_name": null,
            "middle_name": null,
            "full_name": "Michale Brekke",
            "nickname": "asmr_kotya",
            "role_id": null,
            "avatar": "http:\/\/darestreams.local\/storage\/avatars\\9e247c7b788c3231e5f27bf9c0461275.jpg",
            "overlay": "\/img\/default_overlay.jpg",
            "created_at": "2019-09-06T12:00:11.000000Z",
            "updated_at": "2019-09-06T12:00:11.000000Z",
            "lang": "ru",
            "channel": {
                "id": 8,
                "user_id": 8,
                "title": "asmr_kotya",
                "link": "https:\/\/www.twitch.tv\/asmr_kotya",
                "game_id": 1,
                "slug": "asmr-kotya",
                "description": "♥Стараюсь подарить Вам умиротворение и покой..Приходите на мой канал за Котятерапией♥",
                "provider": "twtich",
                "views": 2152578,
                "logo": "https:\/\/static-cdn.jtvnw.net\/jtv_user_pictures\/1a5944f7-1f9a-4d47-b372-6a6d3726c5fd-profile_image-300x300.png",
                "overlay": "https:\/\/static-cdn.jtvnw.net\/jtv_user_pictures\/2c783ec1-78aa-44c2-ae9d-1d49fb32fa6c-channel_offline_image-1920x1080.jpeg",
                "created_at": "2019-09-06T12:07:24.000000Z"
            },
            "tasks": []
        },
        {
            "id": 40,
            "name": "Eduardo Walker",
            "last_name": null,
            "middle_name": null,
            "full_name": "Eduardo Walker",
            "nickname": "ant1ka",
            "role_id": null,
            "avatar": "http:\/\/darestreams.local\/storage\/avatars\\37e679f4141fa67390b0fed367152eca.jpg",
            "overlay": "\/img\/default_overlay.jpg",
            "created_at": "2019-09-06T12:01:20.000000Z",
            "updated_at": "2019-09-06T12:01:20.000000Z",
            "lang": "ru",
            "channel": null,
            "tasks": []
        },
        {
            "id": 1,
            "name": "Boyd Schoen",
            "last_name": null,
            "middle_name": null,
            "full_name": "Boyd Schoen",
            "nickname": "amouranth",
            "email": "oernser@example.net",
            "role_id": null,
            "avatar": "http:\/\/darestreams.local\/storage\/avatars\\b711175a46ac8ed34e55e6c14c05ae49.jpg",
            "overlay": "\/img\/default_overlay.jpg",
            "created_at": "2019-09-06T11:59:41.000000Z",
            "updated_at": "2019-09-06T11:59:41.000000Z",
            "lang": "ru",
            "channel": {
                "id": 1,
                "user_id": 1,
                "title": "amouranth",
                "link": "https:\/\/www.twitch.tv\/amouranth",
                "game_id": 1,
                "slug": "amouranth",
                "description": "Welcome to my stream! My name is Amouranth and I love being myself (crazy, weird, a bit cringe) and making art! I cosplay, paint, draw, play games badly (dance worse), and sing too much. Nice to meet you and welcome to my community!",
                "provider": "twtich",
                "views": 91605753,
                "logo": "https:\/\/static-cdn.jtvnw.net\/jtv_user_pictures\/ac3ae8d2-2a06-4c41-9e92-2de8e20b29c4-profile_image-300x300.png",
                "overlay": "https:\/\/static-cdn.jtvnw.net\/jtv_user_pictures\/2509e087-402b-4d18-a813-d9bd478b92c1-channel_offline_image-1920x1080.png",
                "created_at": "2019-09-06T12:07:06.000000Z"
            },
            "tasks": []
        },
        {
            "id": 94,
            "name": "Lauren Hills",
            "last_name": null,
            "middle_name": null,
            "full_name": "Lauren Hills",
            "nickname": "ameriahime",
            "role_id": null,
            "avatar": "http:\/\/darestreams.local\/storage\/avatars\\cc3756c97192bde2b357a61026938593.jpg",
            "overlay": "\/img\/default_overlay.jpg",
            "created_at": "2019-09-06T12:02:24.000000Z",
            "updated_at": "2019-09-06T12:02:24.000000Z",
            "lang": "ru",
            "channel": null,
            "tasks": []
        },
        {
            "id": 22,
            "name": "Mrs. Joana Mertz DVM",
            "last_name": null,
            "middle_name": null,
            "full_name": "Mrs. Joana Mertz DVM",
            "nickname": "ahrinyan",
            "role_id": null,
            "avatar": "http:\/\/darestreams.local\/storage\/avatars\\99badc7faced50d633d6c4a0d302e7e1.jpg",
            "overlay": "\/img\/default_overlay.jpg",
            "created_at": "2019-09-06T12:00:26.000000Z",
            "updated_at": "2019-09-06T12:00:26.000000Z",
            "lang": "ru",
            "channel": {
                "id": 21,
                "user_id": 22,
                "title": "ahrinyan",
                "link": "https:\/\/www.twitch.tv\/ahrinyan",
                "game_id": 3,
                "slug": "ahrinyan",
                "description": "За чо мама стримером родила",
                "provider": "twtich",
                "views": 4911672,
                "logo": "https:\/\/static-cdn.jtvnw.net\/jtv_user_pictures\/637e3960-e09a-476a-8ab1-8c87f09d26ce-profile_image-300x300.png",
                "overlay": "https:\/\/static-cdn.jtvnw.net\/jtv_user_pictures\/7ca52dda-4d4a-4f3d-8740-eea8dc5be465-channel_offline_image-1920x1080.png",
                "created_at": "2019-09-06T12:07:57.000000Z"
            },
            "tasks": []
        },
        {
            "id": 71,
            "name": "Miss Lexi Considine DDS",
            "last_name": null,
            "middle_name": null,
            "full_name": "Miss Lexi Considine DDS",
            "nickname": "adam1tbc",
            "role_id": null,
            "avatar": "http:\/\/darestreams.local\/storage\/avatars\\7cfad50f4b76ca59d3ef7b67a893577e.jpg",
            "overlay": "\/img\/default_overlay.jpg",
            "created_at": "2019-09-06T12:01:56.000000Z",
            "updated_at": "2019-09-06T12:01:56.000000Z",
            "lang": "ru",
            "channel": null,
            "tasks": []
        },
        {
            "id": 101,
            "name": "Jarod Dietrich",
            "last_name": null,
            "middle_name": null,
            "full_name": "Jarod Dietrich",
            "nickname": "5live_bgd",
            "role_id": null,
            "avatar": "http:\/\/darestreams.local\/storage\/avatars\\db5a46b033d02c75175e184a957308d5.jpg",
            "overlay": "\/img\/default_overlay.jpg",
            "created_at": "2019-09-06T12:02:32.000000Z",
            "updated_at": "2019-09-06T12:02:32.000000Z",
            "lang": "ru",
            "channel": null,
            "tasks": []
        }
    ]
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
    "data": {
        "id": 1,
        "name": "Boyd Schoen",
        "last_name": null,
        "middle_name": null,
        "full_name": "Boyd Schoen",
        "nickname": "amouranth",
        "email": "oernser@example.net",
        "role_id": null,
        "avatar": "http:\/\/darestreams.local\/storage\/avatars\\b711175a46ac8ed34e55e6c14c05ae49.jpg",
        "overlay": "\/img\/default_overlay.jpg",
        "created_at": "2019-09-06T11:59:41.000000Z",
        "updated_at": "2019-09-06T11:59:41.000000Z",
        "lang": "ru",
        "channel": {
            "id": 1,
            "user_id": 1,
            "title": "amouranth",
            "link": "https:\/\/www.twitch.tv\/amouranth",
            "game_id": 1,
            "slug": "amouranth",
            "description": "Welcome to my stream! My name is Amouranth and I love being myself (crazy, weird, a bit cringe) and making art! I cosplay, paint, draw, play games badly (dance worse), and sing too much. Nice to meet you and welcome to my community!",
            "provider": "twtich",
            "views": 91605753,
            "logo": "https:\/\/static-cdn.jtvnw.net\/jtv_user_pictures\/ac3ae8d2-2a06-4c41-9e92-2de8e20b29c4-profile_image-300x300.png",
            "overlay": "https:\/\/static-cdn.jtvnw.net\/jtv_user_pictures\/2509e087-402b-4d18-a813-d9bd478b92c1-channel_offline_image-1920x1080.png",
            "created_at": "2019-09-06T12:07:06.000000Z"
        },
        "tasks": []
    }
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
            "last_name" => "necessitatibus",
            "middle_name" => "ex",
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



> Example response (200):

```json
{
    "data": {
        "id": 1,
        "user_id": 1,
        "currency": "USD",
        "amount": 1000000
    }
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
    "data": {
        "id": 1,
        "user_id": 1,
        "title": "amouranth",
        "link": "https:\/\/www.twitch.tv\/amouranth",
        "game_id": 1,
        "slug": "amouranth",
        "description": "Welcome to my stream! My name is Amouranth and I love being myself (crazy, weird, a bit cringe) and making art! I cosplay, paint, draw, play games badly (dance worse), and sing too much. Nice to meet you and welcome to my community!",
        "provider": "twtich",
        "views": 91605753,
        "logo": "https:\/\/static-cdn.jtvnw.net\/jtv_user_pictures\/ac3ae8d2-2a06-4c41-9e92-2de8e20b29c4-profile_image-300x300.png",
        "overlay": "https:\/\/static-cdn.jtvnw.net\/jtv_user_pictures\/2509e087-402b-4d18-a813-d9bd478b92c1-channel_offline_image-1920x1080.png",
        "created_at": "2019-09-06T12:07:06.000000Z"
    }
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
    "data": []
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
    "data": []
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



> Example response (200):

```json
{
    "data": []
}
```

### HTTP Request
`GET api/users/{user}/notifications/unread`


<!-- END_b40c1602e5d2b520e2f5e920cd782013 -->

<!-- START_8d8c0dddde23fdfdc74a23854a878b4e -->
## Set read all user&#039;s notifications.

<br><small style="padding: 1px 9px 2px;font-weight: bold;white-space: nowrap;color: #ffffff;-webkit-border-radius: 9px;-moz-border-radius: 9px;border-radius: 9px;background-color: #3a87ad;">Requires authentication</small>
> Example request:

```php

$client = new \GuzzleHttp\Client();
$response = $client->patch("https://darestreams.com/api/users/1/notifications/set-read-all", [
]);
$body = $response->getBody();
print_r(json_decode((string) $body));
```




### HTTP Request
`PATCH api/users/{user}/notifications/set-read-all`


<!-- END_8d8c0dddde23fdfdc74a23854a878b4e -->

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



> Example response (200):

```json
{
    "data": []
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



> Example response (200):

```json
{
    "error": "No query results for model [App\\Models\\Notification] 1"
}
```

### HTTP Request
`GET api/users/{user}/notifications/{notification}`


<!-- END_d46e764d0ef10a432f07c58f9db434a7 -->

<!-- START_da23f1e36291a20b7dd2347257e03bc6 -->
## Set read one user&#039;s notification.

<br><small style="padding: 1px 9px 2px;font-weight: bold;white-space: nowrap;color: #ffffff;-webkit-border-radius: 9px;-moz-border-radius: 9px;border-radius: 9px;background-color: #3a87ad;">Requires authentication</small>
> Example request:

```php

$client = new \GuzzleHttp\Client();
$response = $client->patch("https://darestreams.com/api/users/1/notifications/1/set-read", [
]);
$body = $response->getBody();
print_r(json_decode((string) $body));
```




### HTTP Request
`PATCH api/users/{user}/notifications/{notification}/set-read`


<!-- END_da23f1e36291a20b7dd2347257e03bc6 -->

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



> Example response (200):

```json
{
    "data": [
        {
            "id": 1,
            "user_id": 1,
            "provider": "twitch",
            "provider_user_id": "125387632",
            "access_token": null,
            "refresh_token": null,
            "json": null
        }
    ]
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



> Example response (500):

```json
{
    "message": "Server Error"
}
```

### HTTP Request
`GET api/users/{user}/oauthproviders/{oauthprovider}`


<!-- END_b54231aa40ef8f2bd13f2e93a346a406 -->

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


