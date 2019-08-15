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
<!-- START_ba35aa39474cb98cfb31829e70eb8b74 -->
## Handle a login request to the application.

> Example request:

```php

$client = new \GuzzleHttp\Client();
$response = $client->post("https://api.darestreams.com/login", [
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
`POST login`

#### Body Parameters

Parameter | Type | Status | Description
--------- | ------- | ------- | ------- | -----------
    email | string |  required  | User's email.
    password | string |  required  | User's password.

<!-- END_ba35aa39474cb98cfb31829e70eb8b74 -->

<!-- START_d7aad7b5ac127700500280d511a3db01 -->
## Handle a registration request for the application.

> Example request:

```php

$client = new \GuzzleHttp\Client();
$response = $client->post("https://api.darestreams.com/register", [
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
`POST register`

#### Body Parameters

Parameter | Type | Status | Description
--------- | ------- | ------- | ------- | -----------
    name | string |  required  | User's name.
    nickname | string |  required  | User's nickname.
    email | string |  required  | User's email.
    password | string |  required  | User's password.
    lang | string |  required  | User's lang.

<!-- END_d7aad7b5ac127700500280d511a3db01 -->

<!-- START_feb40f06a93c80d742181b6ffb6b734e -->
## Send a reset link to the given user.

> Example request:

```php

$client = new \GuzzleHttp\Client();
$response = $client->post("https://api.darestreams.com/password/email", [
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
`POST password/email`

#### Body Parameters

Parameter | Type | Status | Description
--------- | ------- | ------- | ------- | -----------
    email | string |  required  | User's email.

<!-- END_feb40f06a93c80d742181b6ffb6b734e -->

<!-- START_cafb407b7a846b31491f97719bb15aef -->
## Reset the given user&#039;s password.

> Example request:

```php

$client = new \GuzzleHttp\Client();
$response = $client->post("https://api.darestreams.com/password/reset", [
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
`POST password/reset`

#### Body Parameters

Parameter | Type | Status | Description
--------- | ------- | ------- | ------- | -----------
    token | string |  required  | Token for operation.
    email | string |  required  | User's email.
    password | string |  required  | User's password.
    password_confirmation | string |  required  | User's password confirmation.

<!-- END_cafb407b7a846b31491f97719bb15aef -->

<!-- START_850c93628de5bf65202e8795bcfbba2e -->
## Redirect the user to the provider authentication page.

{driver} - social provider: facebook, twitch, youtube, steam, discord

> Example request:

```php

$client = new \GuzzleHttp\Client();
$response = $client->get("https://api.darestreams.com/oauth/1", [
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
`GET oauth/{driver}`


<!-- END_850c93628de5bf65202e8795bcfbba2e -->

<!-- START_23d437009cae5f8da02265956315673e -->
## Obtain the user information from the provider.

{driver} - social provider: facebook, twitch, youtube, steam, discord

> Example request:

```php

$client = new \GuzzleHttp\Client();
$response = $client->get("https://api.darestreams.com/oauth/1/callback", [
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
`GET oauth/{driver}/callback`


<!-- END_23d437009cae5f8da02265956315673e -->

<!-- START_e7face0d62c9d0dfb9b017041e8e3d66 -->
## Mark the user&#039;s email address as verified.

{user} - integer id of user in database.

> Example request:

```php

$client = new \GuzzleHttp\Client();
$response = $client->post("https://api.darestreams.com/email/verify/1", [
    'query' => [
            "signature" => "ce3f1f0f9f8ad3196f6157dd6f4e732c5d48ac46260cfcb7c24fe2615fb66567",
            "expires" => "1565160796",
        ],
]);
$body = $response->getBody();
print_r(json_decode((string) $body));
```




### HTTP Request
`POST email/verify/{user}`

#### Query Parameters

Parameter | Status | Description
--------- | ------- | ------- | -----------
    signature |  required  | The signature for verifying.
    expires |  required  | The time verify link will expired.

<!-- END_e7face0d62c9d0dfb9b017041e8e3d66 -->

<!-- START_38334d357e7e155bf70b9ab94619ca3d -->
## Resend the email verification notification.

> Example request:

```php

$client = new \GuzzleHttp\Client();
$response = $client->post("https://api.darestreams.com/email/resend", [
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
`POST email/resend`

#### Body Parameters

Parameter | Type | Status | Description
--------- | ------- | ------- | ------- | -----------
    email | string |  required  | User's email.

<!-- END_38334d357e7e155bf70b9ab94619ca3d -->

<!-- START_e65925f23b9bc6b93d9356895f29f80c -->
## Log the user out of the application.

> Example request:

```php

$client = new \GuzzleHttp\Client();
$response = $client->post("https://api.darestreams.com/logout", [
]);
$body = $response->getBody();
print_r(json_decode((string) $body));
```




### HTTP Request
`POST logout`


<!-- END_e65925f23b9bc6b93d9356895f29f80c -->

#Channels
<!-- START_b6cc973b55b7bb22ca0c1c79e337106d -->
## Get top channels

> Example request:

```php

$client = new \GuzzleHttp\Client();
$response = $client->get("https://api.darestreams.com/channels/top", [
    'query' => [
            "hours" => "ut",
            "limit" => "sit",
            "game_id" => "enim",
            "include" => "user,streams",
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
`GET channels/top`

#### Query Parameters

Parameter | Status | Description
--------- | ------- | ------- | -----------
    hours |  optional  | Integer Check amount donations sum for last N hours. Default: 240.
    limit |  optional  | Integer. Limit of top channels. Default: 10.
    game_id |  optional  | Integer. Filter channels by category.
    include |  optional  | string String of connections: user, streams, tags, game.

<!-- END_b6cc973b55b7bb22ca0c1c79e337106d -->

<!-- START_810e42410c294440a2f4edc230d3c14b -->
## Display a listing of the resource.

> Example request:

```php

$client = new \GuzzleHttp\Client();
$response = $client->get("https://api.darestreams.com/channels", [
    'query' => [
            "include" => "user,streams",
            "sort" => "-id",
            "page" => "16",
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
`GET channels`

#### Query Parameters

Parameter | Status | Description
--------- | ------- | ------- | -----------
    include |  optional  | string String of connections: user, streams, tags, game.
    sort |  optional  | string Sort items by fields: title, id. For desc use '-' prefix.
    page |  optional  | array Use as page[number]=1&page[size]=2.

<!-- END_810e42410c294440a2f4edc230d3c14b -->

<!-- START_416190d15e65072bfc0174a4d781685e -->
## Detail channel&#039;s info.

> Example request:

```php

$client = new \GuzzleHttp\Client();
$response = $client->get("https://api.darestreams.com/channels/1", [
    'query' => [
            "include" => "user,streams",
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
`GET channels/{channel}`

#### Query Parameters

Parameter | Status | Description
--------- | ------- | ------- | -----------
    include |  optional  | string String of connections: user, streams, tags, game.

<!-- END_416190d15e65072bfc0174a4d781685e -->

<!-- START_2b969ff25feac1c03921afdbf3acb70d -->
## Update info about channel.

<br><small style="padding: 1px 9px 2px;font-weight: bold;white-space: nowrap;color: #ffffff;-webkit-border-radius: 9px;-moz-border-radius: 9px;border-radius: 9px;background-color: #3a87ad;">Requires authentication</small>
> Example request:

```php

$client = new \GuzzleHttp\Client();
$response = $client->put("https://api.darestreams.com/channels/1", [
    'headers' => [
            "Content-Type" => "application/json",
        ],
    'json' => [
            "description" => "Long description.",
            "logo" => "minima",
            "game_id" => "3",
        ],
]);
$body = $response->getBody();
print_r(json_decode((string) $body));
```




### HTTP Request
`PUT channels/{channel}`

`PATCH channels/{channel}`

#### Body Parameters

Parameter | Type | Status | Description
--------- | ------- | ------- | ------- | -----------
    description | string |  optional  | Description of channel.
    logo | file |  optional  | Logo for your channel. Possible formats: png, jpg.
    game_id | integer |  optional  | Select category from games list.

<!-- END_2b969ff25feac1c03921afdbf3acb70d -->

#Games
<!-- START_c8d03de77f2b355bda22bcdc5ac5e78c -->
## Top categories

> Example request:

```php

$client = new \GuzzleHttp\Client();
$response = $client->get("https://api.darestreams.com/games/top", [
    'query' => [
            "hours" => "nam",
            "limit" => "laboriosam",
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
`GET games/top`

#### Query Parameters

Parameter | Status | Description
--------- | ------- | ------- | -----------
    hours |  optional  | integer Check amount donations sum for last N hours. Default: 240.
    limit |  optional  | integer. Limit of top categories. Default: 10.
    include |  optional  | string String of connections: streams,tags, channels.

<!-- END_c8d03de77f2b355bda22bcdc5ac5e78c -->

<!-- START_af3cb2deabe4f6e96b412e19f04ade03 -->
## Display a listing of the resource.

> Example request:

```php

$client = new \GuzzleHttp\Client();
$response = $client->get("https://api.darestreams.com/games", [
    'query' => [
            "include" => "tags,streams",
            "sort" => "-popularity",
            "page" => "5",
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
`GET games`

#### Query Parameters

Parameter | Status | Description
--------- | ------- | ------- | -----------
    include |  optional  | string String of connections: streams,tags, channels.
    sort |  optional  | string Sort items by fields: title, popularity. For desc use '-' prefix.
    page |  optional  | array Use as page[number]=1&page[size]=2.

<!-- END_af3cb2deabe4f6e96b412e19f04ade03 -->

<!-- START_e424ca64247beff60bfc99293d4a674b -->
## Display the specified resource.

> Example request:

```php

$client = new \GuzzleHttp\Client();
$response = $client->get("https://api.darestreams.com/games/1", [
    'query' => [
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
`GET games/{game}`

#### Query Parameters

Parameter | Status | Description
--------- | ------- | ------- | -----------
    include |  optional  | string String of connections: streams,tags,channels.

<!-- END_e424ca64247beff60bfc99293d4a674b -->

<!-- START_7e8f7da0ff91ff4bacb18a5cac03a1da -->
## Offer new category.

<br><small style="padding: 1px 9px 2px;font-weight: bold;white-space: nowrap;color: #ffffff;-webkit-border-radius: 9px;-moz-border-radius: 9px;border-radius: 9px;background-color: #3a87ad;">Requires authentication</small>
> Example request:

```php

$client = new \GuzzleHttp\Client();
$response = $client->post("https://api.darestreams.com/games/offer", [
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
`POST games/offer`

#### Body Parameters

Parameter | Type | Status | Description
--------- | ------- | ------- | ------- | -----------
    title | string |  required  | Title of new category.

<!-- END_7e8f7da0ff91ff4bacb18a5cac03a1da -->

#Streams
<!-- START_3d1bd0245cf4ecb9cb9fd2ead213c1b2 -->
## Display a listing of the resource.

> Example request:

```php

$client = new \GuzzleHttp\Client();
$response = $client->get("https://api.darestreams.com/streams", [
    'query' => [
            "include" => "game,streams",
            "sort" => "-quantity_donators",
            "page" => "1",
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
`GET streams`

#### Query Parameters

Parameter | Status | Description
--------- | ------- | ------- | -----------
    include |  optional  | string String of connections: game, streams, tags, channel.
    sort |  optional  | string Sort items by fields: amount_donations, quantity_donators, quantity_donations, id. For desc use '-' prefix.
    page |  optional  | array Use as page[number]=1&page[size]=2.

<!-- END_3d1bd0245cf4ecb9cb9fd2ead213c1b2 -->

<!-- START_428f1c1dcacde3c1f8743fa0d3d3dcfa -->
## Create new stream.

<br><small style="padding: 1px 9px 2px;font-weight: bold;white-space: nowrap;color: #ffffff;-webkit-border-radius: 9px;-moz-border-radius: 9px;border-radius: 9px;background-color: #3a87ad;">Requires authentication</small>
> Example request:

```php

$client = new \GuzzleHttp\Client();
$response = $client->post("https://api.darestreams.com/streams", [
    'headers' => [
            "Content-Type" => "application/json",
        ],
    'json' => [
            "game_id" => "3",
            "link" => "aut",
            "start_at" => "provident",
            "tags" => "quaerat",
        ],
]);
$body = $response->getBody();
print_r(json_decode((string) $body));
```




### HTTP Request
`POST streams`

#### Body Parameters

Parameter | Type | Status | Description
--------- | ------- | ------- | ------- | -----------
    game_id | integer |  required  | Select category from games list.
    link | string |  required  | Link on the stream.
    start_at | datetime |  required  | Datetime of starting stream.
    tags | Additional |  optional  | tags to stream.

<!-- END_428f1c1dcacde3c1f8743fa0d3d3dcfa -->

<!-- START_f2675ec42aa925cc5652431ea42519fb -->
## Display the specified resource.

> Example request:

```php

$client = new \GuzzleHttp\Client();
$response = $client->get("https://api.darestreams.com/streams/1", [
    'query' => [
            "include" => "game,streams",
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
`GET streams/{stream}`

#### Query Parameters

Parameter | Status | Description
--------- | ------- | ------- | -----------
    include |  optional  | string String of connections: game, streams, tags, channel.

<!-- END_f2675ec42aa925cc5652431ea42519fb -->

<!-- START_633c367f419daa968232191480a554a8 -->
## Update stream.

<br><small style="padding: 1px 9px 2px;font-weight: bold;white-space: nowrap;color: #ffffff;-webkit-border-radius: 9px;-moz-border-radius: 9px;border-radius: 9px;background-color: #3a87ad;">Requires authentication</small>
Update before starting of the stream.

> Example request:

```php

$client = new \GuzzleHttp\Client();
$response = $client->put("https://api.darestreams.com/streams/1", [
    'headers' => [
            "Content-Type" => "application/json",
        ],
    'json' => [
            "game_id" => "2",
            "link" => "recusandae",
            "start_at" => "hic",
            "tags" => "quasi",
        ],
]);
$body = $response->getBody();
print_r(json_decode((string) $body));
```




### HTTP Request
`PUT streams/{stream}`

`PATCH streams/{stream}`

#### Body Parameters

Parameter | Type | Status | Description
--------- | ------- | ------- | ------- | -----------
    game_id | integer |  optional  | Select category from games list.
    link | string |  optional  | Link on the stream.
    start_at | datetime |  required  | Datetime of starting stream.
    tags | Additional |  optional  | tags to stream.

<!-- END_633c367f419daa968232191480a554a8 -->

<!-- START_f70a70c246494542a8e74f1a17145d4c -->
## Get stream&#039;s chat info.

> Example request:

```php

$client = new \GuzzleHttp\Client();
$response = $client->get("https://api.darestreams.com/streams/1/thread", [
    'query' => [
            "include" => "messages",
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
`GET streams/{stream}/thread`

#### Query Parameters

Parameter | Status | Description
--------- | ------- | ------- | -----------
    include |  optional  | string String of connections: messages, participants.

<!-- END_f70a70c246494542a8e74f1a17145d4c -->

#Streams tasks
<!-- START_342b0cc4ed6c5ff7ac255d7b5c299215 -->
## Display a listing of the resource.

{stream} - stream integer id.

> Example request:

```php

$client = new \GuzzleHttp\Client();
$response = $client->get("https://api.darestreams.com/streams/1/tasks", [
    'query' => [
            "include" => "user,stream",
            "sort" => "-amount_donations",
            "page" => "17",
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
`GET streams/{stream}/tasks`

#### Query Parameters

Parameter | Status | Description
--------- | ------- | ------- | -----------
    include |  optional  | string String of connections: user, stream, transactions.
    sort |  optional  | string Sort items by fields: amount_donations, id. For desc use '-' prefix.
    page |  optional  | array Use as page[number]=1&page[size]=2.

<!-- END_342b0cc4ed6c5ff7ac255d7b5c299215 -->

<!-- START_acfa4689ca22ff7376582fd1bb20692e -->
## Create new task for stream.

<br><small style="padding: 1px 9px 2px;font-weight: bold;white-space: nowrap;color: #ffffff;-webkit-border-radius: 9px;-moz-border-radius: 9px;border-radius: 9px;background-color: #3a87ad;">Requires authentication</small>
{stream} - stream integer id.

> Example request:

```php

$client = new \GuzzleHttp\Client();
$response = $client->post("https://api.darestreams.com/streams/1/tasks", [
    'headers' => [
            "Content-Type" => "application/json",
        ],
    'json' => [
            "small_text" => "reprehenderit",
            "full_text" => "repudiandae",
            "interval_time" => "6",
            "min_amount" => "20",
            "is_superbowl" => "1",
            "min_amount_superbowl" => "10",
            "tags" => "voluptatem",
        ],
]);
$body = $response->getBody();
print_r(json_decode((string) $body));
```




### HTTP Request
`POST streams/{stream}/tasks`

#### Body Parameters

Parameter | Type | Status | Description
--------- | ------- | ------- | ------- | -----------
    small_text | text |  required  | Short description.
    full_text | text |  required  | Full description.
    interval_time | integer |  required  | Time for finishing the task. 0 means until the end of the stream.
    min_amount | integer |  required  | Min amount for donation.
    is_superbowl | boolean |  optional  | Select superbowl or not.
    min_amount_superbowl | integer |  optional  | If is_superbowl is true required min amount for donation.
    tags | Additional |  optional  | tags to task.

<!-- END_acfa4689ca22ff7376582fd1bb20692e -->

<!-- START_34efd41199d8b1eab493dec2da748c34 -->
## Display the specified resource.

{stream} - stream integer id.
{$task} - $task integer id.

> Example request:

```php

$client = new \GuzzleHttp\Client();
$response = $client->get("https://api.darestreams.com/streams/1/tasks/1", [
    'query' => [
            "include" => "user,stream",
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
`GET streams/{stream}/tasks/{task}`

#### Query Parameters

Parameter | Status | Description
--------- | ------- | ------- | -----------
    include |  optional  | string String of connections: user, stream, transactions.

<!-- END_34efd41199d8b1eab493dec2da748c34 -->

<!-- START_66357439ac2a14eb53f8da1b1889a149 -->
## Update task for stream.

<br><small style="padding: 1px 9px 2px;font-weight: bold;white-space: nowrap;color: #ffffff;-webkit-border-radius: 9px;-moz-border-radius: 9px;border-radius: 9px;background-color: #3a87ad;">Requires authentication</small>
{stream} - stream integer id.
{task} - task integer id.

> Example request:

```php

$client = new \GuzzleHttp\Client();
$response = $client->put("https://api.darestreams.com/streams/1/tasks/1", [
    'headers' => [
            "Content-Type" => "application/json",
        ],
    'json' => [
            "small_text" => "tempore",
            "full_text" => "sed",
            "interval_time" => "13",
            "min_amount" => "13",
            "is_superbowl" => "1",
            "min_amount_superbowl" => "6",
            "tags" => "qui",
        ],
]);
$body = $response->getBody();
print_r(json_decode((string) $body));
```




### HTTP Request
`PUT streams/{stream}/tasks/{task}`

`PATCH streams/{stream}/tasks/{task}`

#### Body Parameters

Parameter | Type | Status | Description
--------- | ------- | ------- | ------- | -----------
    small_text | text |  optional  | Short description.
    full_text | text |  optional  | Full description.
    interval_time | integer |  optional  | Time for finishing the task. 0 means until the end of the stream.
    min_amount | integer |  optional  | Min amount for donation.
    is_superbowl | boolean |  optional  | Select superbowl or not.
    min_amount_superbowl | integer |  optional  | If is_superbowl is true required min amount for donation.
    tags | Additional |  optional  | tags to task.

<!-- END_66357439ac2a14eb53f8da1b1889a149 -->

#Streams tasks donations
<!-- START_07bb6347f2ecf4a57ee8e1063dfd7a1f -->
## List of task&#039;s donations.

{stream} - stream integer id.
{task} - task integer id.

> Example request:

```php

$client = new \GuzzleHttp\Client();
$response = $client->get("https://api.darestreams.com/streams/1/tasks/1/transactions", [
    'query' => [
            "include" => "task",
            "sort" => "-created_at",
            "page" => "11",
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
`GET streams/{stream}/tasks/{task}/transactions`

#### Query Parameters

Parameter | Status | Description
--------- | ------- | ------- | -----------
    include |  optional  | string String of connections: account_sender, account_receiver, account_sender.user, account_receiver.user, task.
    sort |  optional  | string Sort items by fields: created_at, created_at. For desc use '-' prefix.
    page |  optional  | array Use as page[number]=1&page[size]=2.

<!-- END_07bb6347f2ecf4a57ee8e1063dfd7a1f -->

<!-- START_351a346643a8b21d5875d9f8200878d4 -->
## Create new task for stream.

<br><small style="padding: 1px 9px 2px;font-weight: bold;white-space: nowrap;color: #ffffff;-webkit-border-radius: 9px;-moz-border-radius: 9px;border-radius: 9px;background-color: #3a87ad;">Requires authentication</small>
{stream} - stream integer id.

> Example request:

```php

$client = new \GuzzleHttp\Client();
$response = $client->post("https://api.darestreams.com/streams/1/tasks/1/transactions", [
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




### HTTP Request
`POST streams/{stream}/tasks/{task}/transactions`

#### Body Parameters

Parameter | Type | Status | Description
--------- | ------- | ------- | ------- | -----------
    amount | integer |  required  | Amount for donation.

<!-- END_351a346643a8b21d5875d9f8200878d4 -->

<!-- START_6b5f35851d1b1b30174a8245f1dc49c8 -->
## Detail donations of task.

{stream} - stream integer id.
{task} - task integer id.
{transaction} - transaction integer id.

> Example request:

```php

$client = new \GuzzleHttp\Client();
$response = $client->get("https://api.darestreams.com/streams/1/tasks/1/transactions/1", [
    'query' => [
            "include" => "task",
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
`GET streams/{stream}/tasks/{task}/transactions/{transaction}`

#### Query Parameters

Parameter | Status | Description
--------- | ------- | ------- | -----------
    include |  optional  | string String of connections: account_sender, account_receiver, account_sender.user, account_receiver.user, task.

<!-- END_6b5f35851d1b1b30174a8245f1dc49c8 -->

#Streams tasks votes
<!-- START_6c170a051ade023cd2a440c51b13d2f3 -->
## streams/{stream}/tasks/{task}/votes
> Example request:

```php

$client = new \GuzzleHttp\Client();
$response = $client->post("https://api.darestreams.com/streams/1/tasks/1/votes", [
]);
$body = $response->getBody();
print_r(json_decode((string) $body));
```




### HTTP Request
`POST streams/{stream}/tasks/{task}/votes`


<!-- END_6c170a051ade023cd2a440c51b13d2f3 -->

#Threads
<!-- START_5cfeeb9554aa7e5d2007f1345a1475e8 -->
## Display a listing of the resource.

> Example request:

```php

$client = new \GuzzleHttp\Client();
$response = $client->get("https://api.darestreams.com/threads", [
    'query' => [
            "include" => "messages",
            "sort" => "-id",
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
`GET threads`

#### Query Parameters

Parameter | Status | Description
--------- | ------- | ------- | -----------
    include |  optional  | string String of connections: messages, participants.
    sort |  optional  | string Sort items by fields: title, id. For desc use '-' prefix.

<!-- END_5cfeeb9554aa7e5d2007f1345a1475e8 -->

<!-- START_98b0d41ddfe962a11ce14377860c8838 -->
## Display the specified resource.

> Example request:

```php

$client = new \GuzzleHttp\Client();
$response = $client->get("https://api.darestreams.com/threads/1", [
    'query' => [
            "include" => "messages",
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
`GET threads/{thread}`

#### Query Parameters

Parameter | Status | Description
--------- | ------- | ------- | -----------
    include |  optional  | string String of connections: messages, participants.

<!-- END_98b0d41ddfe962a11ce14377860c8838 -->

#Threads messages
<!-- START_8e977e54b06b68f3793162e541f8abeb -->
## Get messages of thread.

> Example request:

```php

$client = new \GuzzleHttp\Client();
$response = $client->get("https://api.darestreams.com/threads/1/messages", [
    'query' => [
            "include" => "user",
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
`GET threads/{thread}/messages`

#### Query Parameters

Parameter | Status | Description
--------- | ------- | ------- | -----------
    include |  optional  | string String of connections: user, thread.

<!-- END_8e977e54b06b68f3793162e541f8abeb -->

<!-- START_85ef3463ef256686e5f6eebeb66a9dc1 -->
## Create new message for thread.

> Example request:

```php

$client = new \GuzzleHttp\Client();
$response = $client->post("https://api.darestreams.com/threads/1/messages", [
    'headers' => [
            "Content-Type" => "application/json",
        ],
    'json' => [
            "body" => "vero",
        ],
]);
$body = $response->getBody();
print_r(json_decode((string) $body));
```




### HTTP Request
`POST threads/{thread}/messages`

#### Body Parameters

Parameter | Type | Status | Description
--------- | ------- | ------- | ------- | -----------
    body | text |  optional  | Message text.

<!-- END_85ef3463ef256686e5f6eebeb66a9dc1 -->

<!-- START_f93a5bd710b3f986e9cb2f89cdd5c283 -->
## Detail message of thread.

{thread} - thread integer id.

> Example request:

```php

$client = new \GuzzleHttp\Client();
$response = $client->get("https://api.darestreams.com/threads/1/messages/1", [
    'query' => [
            "include" => "user",
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
`GET threads/{thread}/messages/{message}`

#### Query Parameters

Parameter | Status | Description
--------- | ------- | ------- | -----------
    include |  optional  | string String of connections: user, thread.

<!-- END_f93a5bd710b3f986e9cb2f89cdd5c283 -->

<!-- START_42aa95fabe330e9a2c9a691f2a3aa578 -->
## Update message.

> Example request:

```php

$client = new \GuzzleHttp\Client();
$response = $client->put("https://api.darestreams.com/threads/1/messages/1", [
]);
$body = $response->getBody();
print_r(json_decode((string) $body));
```




### HTTP Request
`PUT threads/{thread}/messages/{message}`

`PATCH threads/{thread}/messages/{message}`


<!-- END_42aa95fabe330e9a2c9a691f2a3aa578 -->

#Threads participants
<!-- START_13ae3d6de96d1e43ada0939bb9db795b -->
## Get participant users of the chat.

> Example request:

```php

$client = new \GuzzleHttp\Client();
$response = $client->get("https://api.darestreams.com/threads/1/participants", [
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
`GET threads/{thread}/participants`


<!-- END_13ae3d6de96d1e43ada0939bb9db795b -->

#Users
<!-- START_42962fed5bc9f2908380612aadf4f09e -->
## Get authorized user.

<br><small style="padding: 1px 9px 2px;font-weight: bold;white-space: nowrap;color: #ffffff;-webkit-border-radius: 9px;-moz-border-radius: 9px;border-radius: 9px;background-color: #3a87ad;">Requires authentication</small>
> Example request:

```php

$client = new \GuzzleHttp\Client();
$response = $client->get("https://api.darestreams.com/users/me", [
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
`GET users/me`

#### Query Parameters

Parameter | Status | Description
--------- | ------- | ------- | -----------
    include |  optional  | string String of connections: tasks, streams, channel.

<!-- END_42962fed5bc9f2908380612aadf4f09e -->

<!-- START_89966bfb9ab533cc3249b91a9090d3dc -->
## Display a listing of the resource.

> Example request:

```php

$client = new \GuzzleHttp\Client();
$response = $client->get("https://api.darestreams.com/users", [
    'query' => [
            "include" => "tasks,channel",
            "sort" => "-nickname",
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
`GET users`

#### Query Parameters

Parameter | Status | Description
--------- | ------- | ------- | -----------
    include |  optional  | string String of connections: tasks, streams, channel.
    sort |  optional  | string Sort items by fields: nickname, id. For desc use '-' prefix.

<!-- END_89966bfb9ab533cc3249b91a9090d3dc -->

<!-- START_5693ac2f2e21af3ebc471cd5a6244460 -->
## Display the specified resource.

> Example request:

```php

$client = new \GuzzleHttp\Client();
$response = $client->get("https://api.darestreams.com/users/1", [
    'query' => [
            "include" => "tasks,channel",
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
`GET users/{user}`

#### Query Parameters

Parameter | Status | Description
--------- | ------- | ------- | -----------
    include |  optional  | string String of connections: tasks, streams, channel.

<!-- END_5693ac2f2e21af3ebc471cd5a6244460 -->

<!-- START_7fe085c671e1b3d51e86136538b1d63f -->
## Update user fields.

<br><small style="padding: 1px 9px 2px;font-weight: bold;white-space: nowrap;color: #ffffff;-webkit-border-radius: 9px;-moz-border-radius: 9px;border-radius: 9px;background-color: #3a87ad;">Requires authentication</small>
> Example request:

```php

$client = new \GuzzleHttp\Client();
$response = $client->put("https://api.darestreams.com/users/1", [
    'headers' => [
            "Content-Type" => "application/json",
        ],
    'json' => [
            "name" => "Archibald",
            "last_name" => "necessitatibus",
            "middle_name" => "magni",
            "nickname" => "Archi89",
            "email" => "example@example.ru",
        ],
]);
$body = $response->getBody();
print_r(json_decode((string) $body));
```




### HTTP Request
`PUT users/{user}`

`PATCH users/{user}`

#### Body Parameters

Parameter | Type | Status | Description
--------- | ------- | ------- | ------- | -----------
    name | string |  required  | User's first name.
    last_name | string |  optional  | User's last name.
    middle_name | string |  optional  | User's middle name.
    nickname | string |  required  | User's nickname.
    email | string |  required  | User's email.

<!-- END_7fe085c671e1b3d51e86136538b1d63f -->

<!-- START_139fe72f815632772695c50e011d50f6 -->
## User&#039;s account
{user} - user id integer.

<br><small style="padding: 1px 9px 2px;font-weight: bold;white-space: nowrap;color: #ffffff;-webkit-border-radius: 9px;-moz-border-radius: 9px;border-radius: 9px;background-color: #3a87ad;">Requires authentication</small>
> Example request:

```php

$client = new \GuzzleHttp\Client();
$response = $client->get("https://api.darestreams.com/users/1/account", [
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
`GET users/{user}/account`


<!-- END_139fe72f815632772695c50e011d50f6 -->

<!-- START_a5f649ddf62415a5676cf0c941c576c3 -->
## User&#039;s channel
{user} - user id integer.

<br><small style="padding: 1px 9px 2px;font-weight: bold;white-space: nowrap;color: #ffffff;-webkit-border-radius: 9px;-moz-border-radius: 9px;border-radius: 9px;background-color: #3a87ad;">Requires authentication</small>
> Example request:

```php

$client = new \GuzzleHttp\Client();
$response = $client->get("https://api.darestreams.com/users/1/channel", [
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
`GET users/{user}/channel`


<!-- END_a5f649ddf62415a5676cf0c941c576c3 -->

<!-- START_0683d066ac271332bee5e91317dae54b -->
## Update user&#039;s avatar

<br><small style="padding: 1px 9px 2px;font-weight: bold;white-space: nowrap;color: #ffffff;-webkit-border-radius: 9px;-moz-border-radius: 9px;border-radius: 9px;background-color: #3a87ad;">Requires authentication</small>
> Example request:

```php

$client = new \GuzzleHttp\Client();
$response = $client->patch("https://api.darestreams.com/users/1/avatar", [
]);
$body = $response->getBody();
print_r(json_decode((string) $body));
```




### HTTP Request
`PATCH users/{user}/avatar`


<!-- END_0683d066ac271332bee5e91317dae54b -->

<!-- START_de5d9c3bc9b04b5ce9a4f3dc1f07cc36 -->
## Update user&#039;s overlay.

<br><small style="padding: 1px 9px 2px;font-weight: bold;white-space: nowrap;color: #ffffff;-webkit-border-radius: 9px;-moz-border-radius: 9px;border-radius: 9px;background-color: #3a87ad;">Requires authentication</small>
> Example request:

```php

$client = new \GuzzleHttp\Client();
$response = $client->patch("https://api.darestreams.com/users/1/overlay", [
]);
$body = $response->getBody();
print_r(json_decode((string) $body));
```




### HTTP Request
`PATCH users/{user}/overlay`


<!-- END_de5d9c3bc9b04b5ce9a4f3dc1f07cc36 -->

<!-- START_2e0c2f1898bfc1d1a1135bea4bd77caa -->
## Update user&#039;s password.

<br><small style="padding: 1px 9px 2px;font-weight: bold;white-space: nowrap;color: #ffffff;-webkit-border-radius: 9px;-moz-border-radius: 9px;border-radius: 9px;background-color: #3a87ad;">Requires authentication</small>
> Example request:

```php

$client = new \GuzzleHttp\Client();
$response = $client->patch("https://api.darestreams.com/users/1/password", [
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
`PATCH users/{user}/password`

#### Body Parameters

Parameter | Type | Status | Description
--------- | ------- | ------- | ------- | -----------
    password | string |  required  | User's password.

<!-- END_2e0c2f1898bfc1d1a1135bea4bd77caa -->

<!-- START_e609ae5c21ee3e306171269cefcf89f7 -->
## Follow the user.

<br><small style="padding: 1px 9px 2px;font-weight: bold;white-space: nowrap;color: #ffffff;-webkit-border-radius: 9px;-moz-border-radius: 9px;border-radius: 9px;background-color: #3a87ad;">Requires authentication</small>
{user} - user id you want follow for.

> Example request:

```php

$client = new \GuzzleHttp\Client();
$response = $client->post("https://api.darestreams.com/users/1/follow", [
]);
$body = $response->getBody();
print_r(json_decode((string) $body));
```




### HTTP Request
`POST users/{user}/follow`


<!-- END_e609ae5c21ee3e306171269cefcf89f7 -->

<!-- START_52f1e2b1cbb27d0f15d754f0fce009fc -->
## Unfollow the user.

<br><small style="padding: 1px 9px 2px;font-weight: bold;white-space: nowrap;color: #ffffff;-webkit-border-radius: 9px;-moz-border-radius: 9px;border-radius: 9px;background-color: #3a87ad;">Requires authentication</small>
{user} - user id you want unfollow.

> Example request:

```php

$client = new \GuzzleHttp\Client();
$response = $client->patch("https://api.darestreams.com/users/1/unfollow", [
]);
$body = $response->getBody();
print_r(json_decode((string) $body));
```




### HTTP Request
`PATCH users/{user}/unfollow`


<!-- END_52f1e2b1cbb27d0f15d754f0fce009fc -->

<!-- START_e0b93da6a9ed6c8f885aa49fe1fcde8a -->
## User&#039;s followers

{user} - user id you want follow for.

> Example request:

```php

$client = new \GuzzleHttp\Client();
$response = $client->get("https://api.darestreams.com/users/1/followers", [
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
`GET users/{user}/followers`


<!-- END_e0b93da6a9ed6c8f885aa49fe1fcde8a -->

<!-- START_c86688026c3bf661c6deb9d9fd787b05 -->
## Users followings
{user} - user id integer.

> Example request:

```php

$client = new \GuzzleHttp\Client();
$response = $client->get("https://api.darestreams.com/users/1/followings", [
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
`GET users/{user}/followings`


<!-- END_c86688026c3bf661c6deb9d9fd787b05 -->

#Users notifications
<!-- START_0ba4788a635c7095a0a54062ecb3f7f2 -->
## Get user&#039;s unread notifications.

<br><small style="padding: 1px 9px 2px;font-weight: bold;white-space: nowrap;color: #ffffff;-webkit-border-radius: 9px;-moz-border-radius: 9px;border-radius: 9px;background-color: #3a87ad;">Requires authentication</small>
> Example request:

```php

$client = new \GuzzleHttp\Client();
$response = $client->get("https://api.darestreams.com/users/1/notifications/unread", [
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
`GET users/{user}/notifications/unread`


<!-- END_0ba4788a635c7095a0a54062ecb3f7f2 -->

<!-- START_b97207c9e8fe0de9b10a7d4c9f481b5e -->
## Set read all user&#039;s notifications.

<br><small style="padding: 1px 9px 2px;font-weight: bold;white-space: nowrap;color: #ffffff;-webkit-border-radius: 9px;-moz-border-radius: 9px;border-radius: 9px;background-color: #3a87ad;">Requires authentication</small>
> Example request:

```php

$client = new \GuzzleHttp\Client();
$response = $client->patch("https://api.darestreams.com/users/1/notifications/setReadAll", [
]);
$body = $response->getBody();
print_r(json_decode((string) $body));
```




### HTTP Request
`PATCH users/{user}/notifications/setReadAll`


<!-- END_b97207c9e8fe0de9b10a7d4c9f481b5e -->

<!-- START_340189399f7d4ed4f6ffc7c726d904f7 -->
## Get user&#039;s all notifications.

<br><small style="padding: 1px 9px 2px;font-weight: bold;white-space: nowrap;color: #ffffff;-webkit-border-radius: 9px;-moz-border-radius: 9px;border-radius: 9px;background-color: #3a87ad;">Requires authentication</small>
> Example request:

```php

$client = new \GuzzleHttp\Client();
$response = $client->get("https://api.darestreams.com/users/1/notifications", [
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
`GET users/{user}/notifications`


<!-- END_340189399f7d4ed4f6ffc7c726d904f7 -->

<!-- START_ccde0c17918b82e19dd759ddfea5f48c -->
## Display user&#039;s notification.

<br><small style="padding: 1px 9px 2px;font-weight: bold;white-space: nowrap;color: #ffffff;-webkit-border-radius: 9px;-moz-border-radius: 9px;border-radius: 9px;background-color: #3a87ad;">Requires authentication</small>
> Example request:

```php

$client = new \GuzzleHttp\Client();
$response = $client->get("https://api.darestreams.com/users/1/notifications/1", [
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
`GET users/{user}/notifications/{notification}`


<!-- END_ccde0c17918b82e19dd759ddfea5f48c -->

<!-- START_a7ab123bc8f502dcff8487722f83b9f6 -->
## Set read one user&#039;s notification.

<br><small style="padding: 1px 9px 2px;font-weight: bold;white-space: nowrap;color: #ffffff;-webkit-border-radius: 9px;-moz-border-radius: 9px;border-radius: 9px;background-color: #3a87ad;">Requires authentication</small>
> Example request:

```php

$client = new \GuzzleHttp\Client();
$response = $client->patch("https://api.darestreams.com/users/1/notifications/1/setRead", [
]);
$body = $response->getBody();
print_r(json_decode((string) $body));
```




### HTTP Request
`PATCH users/{user}/notifications/{notification}/setRead`


<!-- END_a7ab123bc8f502dcff8487722f83b9f6 -->

#Users oauth accounts
<!-- START_05064ba97ab39a4b8b6d930f5dfa4c0e -->
## Get user&#039;s all social accounts.

<br><small style="padding: 1px 9px 2px;font-weight: bold;white-space: nowrap;color: #ffffff;-webkit-border-radius: 9px;-moz-border-radius: 9px;border-radius: 9px;background-color: #3a87ad;">Requires authentication</small>
> Example request:

```php

$client = new \GuzzleHttp\Client();
$response = $client->get("https://api.darestreams.com/users/1/oauthproviders", [
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
`GET users/{user}/oauthproviders`


<!-- END_05064ba97ab39a4b8b6d930f5dfa4c0e -->

<!-- START_0c78ccec68d3acac9a61b7c7caa723e9 -->
## Get user&#039;s one social accounts.

<br><small style="padding: 1px 9px 2px;font-weight: bold;white-space: nowrap;color: #ffffff;-webkit-border-radius: 9px;-moz-border-radius: 9px;border-radius: 9px;background-color: #3a87ad;">Requires authentication</small>
> Example request:

```php

$client = new \GuzzleHttp\Client();
$response = $client->get("https://api.darestreams.com/users/1/oauthproviders/1", [
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
`GET users/{user}/oauthproviders/{oauthprovider}`


<!-- END_0c78ccec68d3acac9a61b7c7caa723e9 -->

#Users transactions
<!-- START_3d8c2ce3314eeeebd5da8c3e3ce79694 -->
## Get user&#039;s all transactions.

<br><small style="padding: 1px 9px 2px;font-weight: bold;white-space: nowrap;color: #ffffff;-webkit-border-radius: 9px;-moz-border-radius: 9px;border-radius: 9px;background-color: #3a87ad;">Requires authentication</small>
> Example request:

```php

$client = new \GuzzleHttp\Client();
$response = $client->get("https://api.darestreams.com/users/1/transactions", [
    'query' => [
            "include" => "task.",
            "page" => "7",
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
`GET users/{user}/transactions`

#### Query Parameters

Parameter | Status | Description
--------- | ------- | ------- | -----------
    include |  optional  | string String of connections: ['account_sender', 'account_receiver', 'account_sender.user', 'account_receiver.user', 'task'].
    page |  optional  | array Use as page[number]=1&page[size]=2.

<!-- END_3d8c2ce3314eeeebd5da8c3e3ce79694 -->

<!-- START_85522e03e8134ea12f35724d9a920eac -->
## Get user&#039;s one transaction.

<br><small style="padding: 1px 9px 2px;font-weight: bold;white-space: nowrap;color: #ffffff;-webkit-border-radius: 9px;-moz-border-radius: 9px;border-radius: 9px;background-color: #3a87ad;">Requires authentication</small>
> Example request:

```php

$client = new \GuzzleHttp\Client();
$response = $client->get("https://api.darestreams.com/users/1/transactions/1", [
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
`GET users/{user}/transactions/{transaction}`

#### Query Parameters

Parameter | Status | Description
--------- | ------- | ------- | -----------
    include |  optional  | string String of connections: ['account_sender', 'account_receiver', 'account_sender.user', 'account_receiver.user', 'task'].

<!-- END_85522e03e8134ea12f35724d9a920eac -->

#Votes
<!-- START_8cdc734aefde366fdeaa9cf9b6073ac4 -->
## Display a listing of the resource.

> Example request:

```php

$client = new \GuzzleHttp\Client();
$response = $client->get("https://api.darestreams.com/votes", [
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
`GET votes`


<!-- END_8cdc734aefde366fdeaa9cf9b6073ac4 -->

<!-- START_010025e3163645417fca6293cc53bd7c -->
## Display the specified resource.

> Example request:

```php

$client = new \GuzzleHttp\Client();
$response = $client->get("https://api.darestreams.com/votes/1", [
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
`GET votes/{vote}`


<!-- END_010025e3163645417fca6293cc53bd7c -->

<!-- START_736e2de7dc5087977b850f901581164c -->
## votes/{vote}
> Example request:

```php

$client = new \GuzzleHttp\Client();
$response = $client->put("https://api.darestreams.com/votes/1", [
]);
$body = $response->getBody();
print_r(json_decode((string) $body));
```




### HTTP Request
`PUT votes/{vote}`

`PATCH votes/{vote}`


<!-- END_736e2de7dc5087977b850f901581164c -->


