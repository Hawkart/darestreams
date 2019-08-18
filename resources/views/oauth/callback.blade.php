<html>
<head>
  <meta charset="utf-8">
  <title>{{ config('app.name') }}</title>
  <script>
    document.domain="darestreams.com";

    window.addEventListener("message", function(event) {
      if(event.origin !== 'darestreams.com') {
        return false;
      }
      event.source.postMessage({ token: "{{ $token }}", expires: "{{$expires_in}}" }, event.origin);
      window.close();
    });
  </script>
</head>
<body>
</body>
</html>
