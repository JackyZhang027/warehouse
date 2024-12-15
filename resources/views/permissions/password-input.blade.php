<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Enter Password</title>
</head>
<body>
    <h1>Enter Password to Continue</h1>
    @if($errors->any())
        <div style="color: red;">
            {{ $errors->first('password') }}
        </div>
    @endif
    <form action="{{ route('password.validate') }}" method="POST">
        @csrf
        <input type="password" name="password" placeholder="Enter password" required>
        <button type="submit">Submit</button>
    </form>
</body>
</html>