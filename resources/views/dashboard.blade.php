<!DOCTYPE html>
<html>
<head>
    <title>Dashboard</title>
</head>
<body>
    <h2>Welcome, {{ session('user')['email'] }}</h2>
    <a href="/logout">Logout</a>
</body>
</html>