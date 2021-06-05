<!DOCTYPE html>
<html lang="{{app()->getLocale()}}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <meta name="viewport" content="width=device-width", initial-scale=1>


    <title>NovellaApi</title>

    
</head>
<body>


    <form action="/upload" method="post" enctype="multipart/form-data">
        @csrf
        <input type="file" class="form-controll" name="thing">
        <input type="submit" class="btn btn-sm btn-block btn-danger" value="upload">

    </form>

</body>

</html>