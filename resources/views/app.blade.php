<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
   <meta charset="utf-8">
   <meta name="viewport"
      content="width=device-width, initial-scale=1">

   <title inertia>{{ config('app.name', 'Laravel') }}</title>

   <!-- Fonts -->
   <link rel="stylesheet"
      href="https://fonts.bunny.net/css2?family=Nunito:wght@400;600;700&display=swap">

   <!-- Scripts -->
   @routes
   @vite(['resources/js/app.js', "resources/js/Pages/{$page['component']}.vue"])
   @inertiaHead

   <script src="https://cdn.yousign.tech/iframe-sdk-1.1.0.min.js"></script>

   <link href="https://assets.calendly.com/assets/external/widget.css"
      rel="stylesheet">
   <script src="https://assets.calendly.com/assets/external/widget.js"
      type="text/javascript"
      async></script>
</head>

<body class="font-sans antialiased">
   @inertia

   @javascript([
       '_baseUrl' => url(''),
       '_appName' => config('app.name'),
       '_locale' => app()->getLocale(),
       '_supportedLocales' => config('app.supported_locales'),
       '_translations' => cache('translations'),
   ])
</body>

</html>
