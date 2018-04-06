<form action="{{route('twitter.postOAuthToken')}}" method="post">
    <input type="text" name="oauth_token" value="909751395816845312-6DgkGfwTKQTHGowomy0TlhkGOZh12Xd">
    <input type="text" name="oauth_verifier" value="    OmCNl7kppAxwTLSsN8DAi9NtsxZM0pAz5TSBecOjUZrBz">
    <input type="hidden" name="_token" value="{{csrf_token()}}">
    <button type="submit">Login With Twitter</button>
</form>