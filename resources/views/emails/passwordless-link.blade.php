<x-mail::message>
   # {{ __('Passwordless login') }}

   {{ __('Click the button below to automatically login without a password') }}

   <x-mail::button :url="$passwordlessLink">
      {{ __('Login') }}
   </x-mail::button>

   {{ __('Thanks,') }}<br>
   {{ config('app.name') }}

   <x-slot:subcopy>
      @lang("If you're having trouble clicking the \":actionText\" button, copy and paste the URL below\n" . 'into your web browser:', [
          'actionText' => __("Login"),
      ]) <span
         class="break-all">[{{ $passwordlessLink }}]({{ $passwordlessLink }})</span>
   </x-slot:subcopy>
</x-mail::message>
