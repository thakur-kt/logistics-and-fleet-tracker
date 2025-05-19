<!DOCTYPE html>
<html>
<head>
<meta name="csrf-token" content="{{ csrf_token() }}">
  <title>Fleet Tracker</title>
  @vite('resources/js/app.js') <!-- Or mix() if using Laravel Mix -->
</head>
<body>
  <div id="app"></div>
</body>
</html>