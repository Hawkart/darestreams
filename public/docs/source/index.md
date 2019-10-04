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
            "hours" => "nihil",
            "limit" => "ducimus",
            "skip" => "maxime",
            "game_id" => "velit",
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

For any connection may add _count for counting. Example: tasks_completed_count

> Example request:

```php

$client = new \GuzzleHttp\Client();
$response = $client->get("https://darestreams.com/api/channels/1/streams", [
    'query' => [
            "include" => "game,tasks",
            "sort" => "-quantity_donators",
            "page" => "11",
            "filter" => "ut",
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
    include |  optional  | string String of connections: game, tasks, tasks.votes, tags, channel, user, tasks_completed.
    sort |  optional  | string Sort items by fields: amount_donations, quantity_donators, quantity_donations, id. For desc use '-' prefix.
    page |  optional  | array Use as page[number]=1&page[size]=2.
    filter |  optional  | array Allows filter only by status. Use as filter[status]=1,2.

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
            "page" => "2",
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

Get by id or slug.

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



> Example response (404):

```json
{
    "error": "No query results for model [App\\Models\\Channel]."
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
            "logo" => "deserunt",
            "game_id" => "17",
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
            "limit" => "ad",
            "skip" => "doloribus",
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
            "page" => "12",
            "filter" => "ut",
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
    filter |  optional  | array Can filter by title/

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



> Example response (404):

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
            "amount" => "18",
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

#Rating Channels
<!-- START_e2cf52df507d02a2867a78c3c1dd77b5 -->
## Display a listing of the resource.

> Example request:

```php

$client = new \GuzzleHttp\Client();
$response = $client->get("https://darestreams.com/api/rating", [
    'query' => [
            "include" => "user,streams",
            "sort" => "-id",
            "page" => "11",
        ],
]);
$body = $response->getBody();
print_r(json_decode((string) $body));
```



> Example response (400):

```json
{
    "message": "Requested include(s) `user, streams` are not allowed. Allowed include(s) are `history, historyCount`."
}
```

### HTTP Request
`GET api/rating`

#### Query Parameters

Parameter | Status | Description
--------- | ------- | ------- | -----------
    include |  optional  | string String of connections: user, streams, tags, game.
    sort |  optional  | string Sort items by fields: title, id. For desc use '-' prefix.
    page |  optional  | array Use as page[number]=1&page[size]=2.

<!-- END_e2cf52df507d02a2867a78c3c1dd77b5 -->

#Streams
<!-- START_f189a42cc1f675b327d443ea53153749 -->
## Get top streams

> Example request:

```php

$client = new \GuzzleHttp\Client();
$response = $client->get("https://darestreams.com/api/streams/top", [
    'query' => [
            "limit" => "natus",
            "skip" => "aut",
            "game_id" => "est",
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
            "page" => "10",
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
            "channel_id" => "15",
            "title" => "quam",
            "link" => "et",
            "start_at" => "molestias",
            "allow_task_before_stream" => "",
            "allow_task_when_stream" => "1",
            "min_amount_task_before_stream" => "voluptatem",
            "min_amount_task_when_stream" => "possimus",
            "min_amount_donate_task_before_stream" => "dolores",
            "min_amount_donate_task_when_stream" => "et",
            "tags" => "optio",
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



> Example response (404):

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
            "title" => "quod",
            "link" => "beatae",
            "start_at" => "eligendi",
            "status" => "3",
            "allow_task_before_stream" => "1",
            "allow_task_when_stream" => "",
            "min_amount_task_before_stream" => "atque",
            "min_amount_task_when_stream" => "consequatur",
            "min_amount_donate_task_before_stream" => "quis",
            "min_amount_donate_task_when_stream" => "rerum",
            "tags" => "recusandae",
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



> Example response (404):

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
            "vote" => "1",
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
            "stream_id" => "accusamus",
            "include" => "user,stream",
            "sort" => "-amount_donations",
            "page" => "3",
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
            "stream_id" => "15",
            "small_text" => "eum",
            "full_text" => "sed",
            "interval_time" => "20",
            "is_superbowl" => "1",
            "tags" => "et",
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



> Example response (404):

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
            "small_text" => "ab",
            "full_text" => "ut",
            "interval_time" => "13",
            "is_superbowl" => "1",
            "tags" => "quasi",
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



> Example response (404):

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



> Example response (404):

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
            "body" => "ut",
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



> Example response (404):

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



> Example response (404):

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



> Example response (404):

```json
{
    "error": "No query results for model [App\\Models\\User] 1"
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



> Example response (401):

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
    include |  optional  | string String of connections: tasks, streams, channel, account.

<!-- END_8d1e53fcf4d2d02a6144ed392de856bf -->

<!-- START_43f78da269621c9fd3d18db0230c33cf -->
## User&#039;s account

<br><small style="padding: 1px 9px 2px;font-weight: bold;white-space: nowrap;color: #ffffff;-webkit-border-radius: 9px;-moz-border-radius: 9px;border-radius: 9px;background-color: #3a87ad;">Requires authentication</small>
> Example request:

```php

$client = new \GuzzleHttp\Client();
$response = $client->get("https://darestreams.com/api/users/me/account", [
]);
$body = $response->getBody();
print_r(json_decode((string) $body));
```



> Example response (401):

```json
{
    "error": "Unauthorized"
}
```

### HTTP Request
`GET api/users/me/account`


<!-- END_43f78da269621c9fd3d18db0230c33cf -->

<!-- START_22cac4e428316b498ee7155193eda3b4 -->
## Update user&#039;s avatar

<br><small style="padding: 1px 9px 2px;font-weight: bold;white-space: nowrap;color: #ffffff;-webkit-border-radius: 9px;-moz-border-radius: 9px;border-radius: 9px;background-color: #3a87ad;">Requires authentication</small>
> Example request:

```php

$client = new \GuzzleHttp\Client();
$response = $client->post("https://darestreams.com/api/users/me/avatar", [
]);
$body = $response->getBody();
print_r(json_decode((string) $body));
```




### HTTP Request
`POST api/users/me/avatar`


<!-- END_22cac4e428316b498ee7155193eda3b4 -->

<!-- START_d5ba76ee809819fea4021623ca26df5e -->
## Update user&#039;s overlay.

<br><small style="padding: 1px 9px 2px;font-weight: bold;white-space: nowrap;color: #ffffff;-webkit-border-radius: 9px;-moz-border-radius: 9px;border-radius: 9px;background-color: #3a87ad;">Requires authentication</small>
> Example request:

```php

$client = new \GuzzleHttp\Client();
$response = $client->post("https://darestreams.com/api/users/me/overlay", [
]);
$body = $response->getBody();
print_r(json_decode((string) $body));
```




### HTTP Request
`POST api/users/me/overlay`


<!-- END_d5ba76ee809819fea4021623ca26df5e -->

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



> Example response (401):

```json
{
    "error": "Unauthorized"
}
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



> Example response (401):

```json
{
    "error": "Unauthorized"
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



> Example response (401):

```json
{
    "error": "Unauthorized"
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



> Example response (401):

```json
{
    "error": "Unauthorized"
}
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



> Example response (401):

```json
{
    "error": "Unauthorized"
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
            "limit" => "et",
            "skip" => "explicabo",
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



> Example response (404):

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
            "last_name" => "eligendi",
            "middle_name" => "eos",
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



> Example response (404):

```json
{
    "error": "No query results for model [App\\Models\\User] 1"
}
```

### HTTP Request
`GET api/users/{user}/channel`


<!-- END_fbb6055cfa923488b2aecfd64b5169f2 -->

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



> Example response (404):

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



> Example response (404):

```json
{
    "error": "No query results for model [App\\Models\\User] 1"
}
```

### HTTP Request
`GET api/users/{user}/followings`


<!-- END_b1af205299bf898aa2915c41a4e6bc1a -->

<!-- START_eef0468389901660d9613170d5cf24a9 -->
## Check user already follows
{user} - user id you want follow for.

<br><small style="padding: 1px 9px 2px;font-weight: bold;white-space: nowrap;color: #ffffff;-webkit-border-radius: 9px;-moz-border-radius: 9px;border-radius: 9px;background-color: #3a87ad;">Requires authentication</small>
> Example request:

```php

$client = new \GuzzleHttp\Client();
$response = $client->get("https://darestreams.com/api/users/1/is-following", [
]);
$body = $response->getBody();
print_r(json_decode((string) $body));
```



> Example response (401):

```json
{
    "error": "Unauthorized"
}
```

### HTTP Request
`GET api/users/{user}/is-following`


<!-- END_eef0468389901660d9613170d5cf24a9 -->

#Users notifications
<!-- START_018a02d6f9b88d1018ad58bc22b4c278 -->
## Get user&#039;s unread notifications.

<br><small style="padding: 1px 9px 2px;font-weight: bold;white-space: nowrap;color: #ffffff;-webkit-border-radius: 9px;-moz-border-radius: 9px;border-radius: 9px;background-color: #3a87ad;">Requires authentication</small>
> Example request:

```php

$client = new \GuzzleHttp\Client();
$response = $client->get("https://darestreams.com/api/users/me/notifications/unread", [
]);
$body = $response->getBody();
print_r(json_decode((string) $body));
```



> Example response (401):

```json
{
    "error": "Unauthorized"
}
```

### HTTP Request
`GET api/users/me/notifications/unread`


<!-- END_018a02d6f9b88d1018ad58bc22b4c278 -->

<!-- START_23e23cb4c455b7d8d4852cf233618a69 -->
## Set read all user&#039;s notifications.

<br><small style="padding: 1px 9px 2px;font-weight: bold;white-space: nowrap;color: #ffffff;-webkit-border-radius: 9px;-moz-border-radius: 9px;border-radius: 9px;background-color: #3a87ad;">Requires authentication</small>
> Example request:

```php

$client = new \GuzzleHttp\Client();
$response = $client->patch("https://darestreams.com/api/users/me/notifications/set-read-all", [
]);
$body = $response->getBody();
print_r(json_decode((string) $body));
```




### HTTP Request
`PATCH api/users/me/notifications/set-read-all`


<!-- END_23e23cb4c455b7d8d4852cf233618a69 -->

<!-- START_d9c03edd91b488ee2656c5f1de6fd758 -->
## Set read one user&#039;s notification.

<br><small style="padding: 1px 9px 2px;font-weight: bold;white-space: nowrap;color: #ffffff;-webkit-border-radius: 9px;-moz-border-radius: 9px;border-radius: 9px;background-color: #3a87ad;">Requires authentication</small>
> Example request:

```php

$client = new \GuzzleHttp\Client();
$response = $client->patch("https://darestreams.com/api/users/me/notifications/1/set-read", [
]);
$body = $response->getBody();
print_r(json_decode((string) $body));
```




### HTTP Request
`PATCH api/users/me/notifications/{notification}/set-read`


<!-- END_d9c03edd91b488ee2656c5f1de6fd758 -->

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



> Example response (401):

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



> Example response (401):

```json
{
    "error": "Unauthorized"
}
```

### HTTP Request
`GET api/users/{user}/notifications/{notification}`


<!-- END_d46e764d0ef10a432f07c58f9db434a7 -->

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



> Example response (401):

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



> Example response (401):

```json
{
    "error": "Unauthorized"
}
```

### HTTP Request
`GET api/users/{user}/oauthproviders/{oauthprovider}`


<!-- END_b54231aa40ef8f2bd13f2e93a346a406 -->

#general
<!-- START_41d2f7697c6118f36f8b676e5bd19ea0 -->
## Login using the given user ID / email.

> Example request:

```php

$client = new \GuzzleHttp\Client();
$response = $client->get("https://darestreams.com/_dusk/login/1/1", [
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
`GET _dusk/login/{userId}/{guard?}`


<!-- END_41d2f7697c6118f36f8b676e5bd19ea0 -->

<!-- START_6375e7300024aae0f6af283b4a72cb1b -->
## Log the user out of the application.

> Example request:

```php

$client = new \GuzzleHttp\Client();
$response = $client->get("https://darestreams.com/_dusk/logout/1", [
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
`GET _dusk/logout/{guard?}`


<!-- END_6375e7300024aae0f6af283b4a72cb1b -->

<!-- START_09dcf3e9a9b6585fa40e4ead6c3c858a -->
## Retrieve the authenticated user identifier and class name.

> Example request:

```php

$client = new \GuzzleHttp\Client();
$response = $client->get("https://darestreams.com/_dusk/user/1", [
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
`GET _dusk/user/{guard?}`


<!-- END_09dcf3e9a9b6585fa40e4ead6c3c858a -->


