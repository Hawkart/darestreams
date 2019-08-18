<html>
<head>
  <meta charset="utf-8">
  <title>{{ config('app.name') }}</title>
  <script>
    window.opener.postMessage({ token: "{{ $token }}", expires: "{{$expires_in}}" }, "{{  url('*',[],true)  }}");
    window.close();
  </script>
</head>
<body>
</body>
</html>
