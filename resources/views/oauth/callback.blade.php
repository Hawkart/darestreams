<html>
<head>
  <meta charset="utf-8">
  <title>{{ config('app.name') }}</title>
  <script>
    //document.domain="darestreams.com";
    //window.opener.postMessage({ token: "{{ $token }}", expires: "{{$expires_in}}" }, "{{  url('*',[],true)  }}");
    window.parent.postMessage({ token: "{{ $token }}", expires: "{{$expires_in}}" }, "https://darestreams.com");
    //window.close();
  </script>
</head>
<body>
</body>
</html>
