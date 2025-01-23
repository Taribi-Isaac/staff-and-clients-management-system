<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Raslordeck Clients</title>
    <link rel="stylesheet" href="{{ asset('css/home.css') }}">
</head>

<body>
    
        <div class="homeContainer">
            <div class="centerView">
                <h3>Login</h3>

                <!-- Login Form -->
                <form action="{{ route('login') }}" method="POST" class="loginForm">
                    @csrf
                    <div>
                        <label for="email">Email</label>
                        <input type="email" id="email" name="email" value="{{ old('email') }}" required>
                    </div>
                    <br>
                    <div>
                        <label for="password">Password</label>
                        <input type="password" id="password" name="password" required>
                    </div>
                    <br>
                    <div>
                        <button type="submit" class="loginButton">Log in</button>
                    </div>
                </form>

                <!-- Display Errors -->
                @if ($errors->any())
                    <div class="errorMessages">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <p class="copyRight">Raslordeck Limited <b>&copy;</b> 2025 All Rights Reserved</p>
            </div>
        </div>
    
</body>

</html>
