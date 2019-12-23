<html>
<head>
  <meta charset="utf-8">
  <title>{{ config('app.name') }}</title>
  <script>
    window.opener.postMessage({ result: "{{ $result }}", error: "{{$error}}" }, "{{  url('*',[],true)  }}");
    window.location.href="/success";
    //window.close();
  </script>
</head>
<body>
</body>
</html>