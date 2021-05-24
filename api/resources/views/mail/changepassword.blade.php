@component('mail.header')
@endcomponent
<span class="email-holder">
  <span class="logo">
      <img src= "{{$host['api']}}/storage/logo/talk.png" height="60px" width="auto"/>
  </span>
  <span class=title>
    <h1>Password was successfully changed!</h1>
  </span>
  <span class=content>
    <p>
      Hello {{$account->username}}!
    </p>
    <p>
      You've have successfully changed your password.
    </p>
    <p>
      Your password was changed on {{$date}} using {{$host['browser']}}.
    </p>
  </span>
</span>
@component('mail.footer')
@endcomponent