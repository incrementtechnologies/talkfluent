@component('mail.header')
@endcomponent
<span class="email-holder">
    <span class="logo">
        <img src= "{{$host['api']}}/storage/logo/talk.png" height="60px" width="auto"/>
    </span>
    <span class="title">
        <h1>Confirm your Email Address on TalkFluent</h1>
    </span>
    <span class="content">
        <p>
            Hello! We just need to verify that {{$account->email}} is your email address.
        </p>
        <p>Tap the button to confirm:</p>
        <p>
            <a class="link" href="{{$host['app']}}/account_verification/{{$account->username}}/{{$account->code}}">
                <button class="btn">Confirm Email Address</button>
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