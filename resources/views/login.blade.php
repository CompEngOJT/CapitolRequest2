@extends('layout')

@section('title', 'Login')

@section('content')
<style>
    /* General Styles */
    body, html {
        margin: 0;
        padding: 0;
        width: 100%;
        height: 100%;
        background: linear-gradient(to bottom, #E9F2F9, #9ECFF6);
        overflow: hidden;
    }

    /* Left Section */
    #left {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
    }

    #left img {
        width: 67%;
        height: 100%;
        object-fit: cover;
        background: rgba(48, 120, 203, 0.5);
        pointer-events: none;
    }

    /* Logo Container */
    #logo-container {
        position: fixed;
        top: 20vh;
        left: 33.5%;
        transform: translateX(-50%);
        z-index: 1000;
        text-align: center;
        min-height: 20vh;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
    }

    #logo {
        width: clamp(150px, 20vw, 200px);
        height: auto;
    }

    #logo-heading {
        font-size: clamp(28px, 3.5vw, 42px);
        font-weight: bold;
        color: white;
        margin-top: 2vh;
        margin-bottom: 1vh;
        font-family: 'Poppins', sans-serif;
    }

    #logo-subtext {
        font-size: clamp(20px, 2.5vw, 30px);
        color: white;
        font-family: 'Poppins', sans-serif;
    }

    /* Container */
    #container {
        position: fixed;
        top: 0;
        right: 0;
        width: 33%;
        min-height: 600px;
        height: 100vh;
        background: linear-gradient(to bottom, #E9F2F9, #9ECFF6);
        display: flex;
        justify-content: flex-start;
        align-items: flex-start;
        padding-top: 0px;
        border-right: 5px solid #446F95;
    }

    /* Admin Badge */
    .admin-badge {
        background: #2C66A8;
        color: white;
        font-size: 1em;
        font-weight: bold;
        padding: 10px 30px;
        border-radius: 50px;
        text-align: center;
        width: auto;
        min-width: 120px;
        height: 40px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-top: 5px;
        box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.2);
    }

    /* Profile Container */
    .profile-container {
        display: flex;
        justify-content: center;
        align-items: center;
        margin-bottom: 20px;
    }

    .profile-image {
        width: 100px;
        height: 100px;
        border-radius: 50%;
        object-fit: cover;
        border: 3px solid #2C66A8;
    }

    /* Form Styles */
    .form-title {
        font-size: clamp(18px, 2vw, 32px);
        font-weight: bold;
        color: #2C66A8;
        text-align: center;
        margin-bottom: 10px;
        font-family: 'Poppins', sans-serif;
        margin-top: 20px;
    }

    .form-subtext {
        font-size: clamp(14px, 1.2vw, 22px);
        color: gray;
        text-align: center;
        margin-bottom: 50px;
        font-family: 'Poppins', sans-serif;
    }

    #form-wrapper {
        position: relative;
        top: 5%;
        width: 100%;
        display: flex;
        flex-direction: column;
        align-items: center;
    }

    .form-group {
        position: relative;
        top: 10%;
        margin-bottom: 30px;
        width: 80%;
    }

    .form-control {
        width: 100%;
        padding: 18px 12px 8px;
        font-size: 0.8em;
        border-radius: 5px;
        border: 1px solid #ccc;
        height: 60px;
        outline: none;
        background: white;
        line-height: 1.5;
    }

    .form-group label {
        position: absolute;
        left: 12px;
        top: 50%;
        transform: translateY(-50%);
        color: gray;
        font-size: 1em;
        transition: 0.3s ease-in-out;
        background: white;
        padding: 0 5px;
        pointer-events: none;
    }

    .form-control:focus + label,
    .form-control:not(:placeholder-shown) + label {
        top: 10px;
        font-size: 0.8em;
        color: grey;
        background: transparent;
        padding: 0 5px;
        font-family: 'Poppins', sans-serif;
    }

    button {
        padding: 12px 20px;
        font-size: 1em;
        border-radius: 5px;
        width: 80%;
        margin-top: 0px;
        height: 50px;
        background: #2C66A8;
        transition: background 0.3s ease-in-out;
        font-family: 'Poppins', sans-serif;
        font-weight: bold;
        color: white;
        border: none;
    }

    button:hover {
        background-color: #0056b3;
    }

    #footer-text {
        font-size: clamp(10px, 1.2vw, 14px);
        color: gray;
        text-align: center;
        margin-top: 20px;
        font-family: 'Poppins', sans-serif;
        opacity: 0.8;
    }

    .error-message {
        color: #ff4444;
        font-size: 14px;
        text-align: center;
        margin-bottom: 20px;
        padding: 10px;
        background-color: #ffe6e6;
        border: 1px solid #ff4444;
        border-radius: 5px;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 3px;
        opacity: 0;
        transform: translateY(-20px);
        transition: opacity 0.3s ease, transform 0.3s ease;
    }

    .error-message.visible {
        opacity: 1;
        transform: translateY(0);
    }

    .error-message i {
        font-size: 16px;
        color: #ff4444;
    }

    /* Responsive Styles */
    @media (max-width: 991px) {
        body, html {
            background: rgba(48, 120, 203, 0.2);
            height: 100%;
            margin: 0;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        #left img, #logo, #logo-heading, #logo-subtext {
            display: none;
        }

        @media (max-height: 720px) {
            body, html {
                background: rgba(48, 120, 203, 0.2);
                height: 100%;
                margin: 0;
                display: flex;
                justify-content: center;
                align-items: flex-start;
                overflow: hidden;
            }
        }

        #container {
            position: relative;
            width: 450px;
            height: 720px;
            margin: 0;
            padding: 0px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.5);
            background: rgba(233, 242, 249, 0.8);
            border-radius: 10px;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        #form-wrapper {
            top: 0;
            padding: 10px;
        }

        .form-title {
            font-size: clamp(24px, 2vw, 32px);
        }

        .form-subtext {
            font-size: clamp(16px, 1.2vw, 22px);
            color: gray;
            text-align: center;
            margin-bottom: 8px;
            font-family: 'Poppins', sans-serif;
        }

        #footer-text {
            font-size: clamp(14px, 1.2vw, 18px);
        }

        .form-group {
            width: 90%;
            margin-bottom: 15px;
        }

        button {
            width: 90%;
            margin-top: 60px;
        }

        .error-message {
            margin-top: 5px;
            margin-bottom: 5px;
            font-size: 12px;
            padding: 8px;
            width: 90%;
            margin-left: auto;
            margin-right: auto;
        }

        .error-message i {
            font-size: 14px;
        }
    }
</style>

<div id="left">
    <img src="{{ asset('images/background.png') }}">
</div>

<div id="logo-container">
    <img src="images/logo.png" alt="Logo" id="logo">
    <h1 id="logo-heading">Online Request System</h1>
    <p id="logo-subtext">Hassle-free, anytime</p>
</div>

<form id="container" method="POST" action="{{ url('/login') }}">
    @csrf
    <div id="form-wrapper">
        <div class="profile-container">
            <img src="{{ asset('images/profile.png') }}" alt="Profile" class="profile-image">
        </div>

        <div class="admin-badge">ADMIN</div>
        <h2 class="form-title">LOG IN YOUR ACCOUNT</h2>
        <p class="form-subtext">Effortlessly request online</p>

        @if($errors->has('login'))
            <p class="error-message visible">
                <i class="fas fa-exclamation-circle"></i>
                {{ $errors->first('login') }}
            </p>
        @else
            <p class="error-message">&nbsp;</p>
        @endif

        <div class="form-group">
            <input type="text" class="form-control" id="username" name="username" value="{{ old('username') }}" placeholder=" " required>
            <label for="username">Username</label>
        </div>

        <div class="form-group">
            <input type="password" class="form-control" id="password" name="password" placeholder=" " required>
            <label for="password">Password</label>
        </div>

        <button type="submit">LOGIN</button>
        <p id="footer-text">By Provincial Government of Misamis Oriental</p>
    </div>
</form>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        const form = document.getElementById("container");

        form.addEventListener("submit", function (event) {
            event.preventDefault(); // Prevent default form submission

            // Fade out effect
            form.style.transition = "opacity 0.5s ease-out";
            form.style.opacity = "0";

            // Delay the form submission for the fade-out effect
            setTimeout(() => {
                form.submit(); // Submit the form after the fade-out effect
            }, 200);
        });
    });
</script>
@endsection