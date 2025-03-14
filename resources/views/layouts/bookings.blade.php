<!DOCTYPE html>
<html lang="id">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>{{ $title }}</title>
	<script src="https://cdn.tailwindcss.com"></script>
     <meta name="csrf-token" content="{{ csrf_token() }}">
</head>

<body class="bg-gray-100 p-5">
	<!-- SweetAlert -->
	<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

	@yield('content')
</body>

</html>
