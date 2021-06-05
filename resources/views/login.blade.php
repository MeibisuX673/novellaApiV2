<!DOCTYPE html>
<html lang="{{app()->getLocale()}}">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">

	<meta name="viewport" content="width=device-width", initial-scale=1>

	<meta name="csrf-token" content="{{ csrf_token() }}">
	<title>NovellaApi</title>

	
</head>
<body>

<h1>Авторизация</h1>
<form class="col-3 offset-4 border rounded" method="POST" action="{{ route('login') }}">
	<input type="hidden" name="_token" value="{{csrf_token()}}" />
	
	<div class="form-group">
		<label for="email" class="col-form-label-lg">Ваш email</label>
		<input class="form-controll" id="email" name="email" type="text" placeholder="Email">

	</div>
	<div class="form-group">
		<label for="password" class="col-form-label-lg">Пароль</label>
		<input class="form-controll" id="password" name="password" type="password" value="" placeholder="Пароль">
		
	</div>
	<div class="form-group">
		<button class="btn btn-lg btn-primary" type="submit" name="sendMe" value="1">Получить токен</button>
	</div>

</form>

</body>

</html>