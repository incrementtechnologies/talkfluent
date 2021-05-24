@component('mail.header')
@endcomponent

<span class="email-holder">
    <span class="logo">
        <img src= "{{$host['api']}}/storage/logo/talk.png" height="60px" width="auto"/>
    </span>
  <span class=title>
    <h1>Request to Reset Password on TalkFluent</h1>
  </span>
  <span class=content>
    <p>
        Hello! You've requested to reset your password using this email address at {{$account->email}}.
    </p>
    <p>Tap the button to continue:</p>
    <p>
      <a class="link" href="{{$host['app']}}reset_password/{{$account->username}}/{{$account->code}}">
          <button class="btn">Continue</button>
      </a>
    </p>
    <p>
      <h3>Didn't request this email?</h3>
      <p>
      No Worries! Your address may have been entered by mistake. If you ignore or delete this email, nothing further will happen.
      </p>
    </p>
  </span>
</span>
@component('mail.footer')
@endcomponent