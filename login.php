<!DOCTYPE html>
<html lang="en">
<head>
  <script src="https://accounts.google.com/gsi/client" async defer></script>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>SHC Office Supply</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@docsearch/css@3">
  <link href="https://getbootstrap.com/docs/5.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
  <meta name="theme-color" content="#09a223">
  <link rel="icon" type="image/svg+xml" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'><rect width='100' height='100' fill='%2309a223'/><text x='50%25' y='50%25' text-anchor='middle' dy='.3em' font-size='50' fill='white' font-family='Arial'>SHC</text></svg>">

  <style>
    :root {
      --main-color: #09a223;
      --main-color-dark: #078a1d;
      --main-color-light: #e8f5ea;
      --main-color-lighter: #f5fdf6;
    }
    
    body {
      height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      background-color: var(--main-color-lighter);
    }
    
    .login-container {
      width: 100%;
      max-width: 360px;
      padding: 2.5rem;
      border-radius: 12px;
      box-shadow: 0 10px 25px rgba(0,0,0,0.05);
      background-color: white;
      text-align: center;
    }
    
    .logo-container {
      margin-bottom: 1.5rem;
      text-align: center;
    }
    
    .login-header {
      color: var(--main-color);
      font-weight: 600;
      margin-bottom: 1.5rem;
      text-align: center;
      font-size: 1.5rem;
    }
    
    .card-decoration {
      position: absolute;
      height: 140px;
      width: 140px;
      background-color: var(--main-color);
      opacity: 0.1;
      border-radius: 50%;
      top: -70px;
      right: -70px;
      z-index: 0;
    }
    
    .footer {
      margin-top: 2rem;
      text-align: center;
      color: #777;
      font-size: 0.85rem;
    }
    
    .g_id_signin {
      display: flex;
      justify-content: center;
      margin-top: 1rem;
    }
    
    .signin-container {
      margin: 2rem 0;
    }
  </style>
</head>

<body>
  <div class="login-container position-relative">
    <div class="card-decoration"></div>
    
    <div class="logo-container">
      <img src="https://saharahomecare.com/wp-content/uploads/2024/03/Sahara-Homecare-Logo-01.png" alt="Sahara Homecare Logo" height="70" class="img-fluid">
    </div>
    
    <h1 class="login-header">Welcome to SHC Office Supply</h1>
    
    <div class="signin-container">
      <div id="g_id_onload"
           data-client_id="202090025871-j2d3kr382d7cconpi8k5jdub1sdabl11.apps.googleusercontent.com"
           data-login_uri="/authenticate.php"
           data-auto_prompt="false">
      </div>
      
      <div class="g_id_signin"
           data-type="standard"
           data-shape="rectangular"
           data-theme="outline"
           data-text="signin_with"
           data-size="large"
           data-locale="en"
           data-logo_alignment="center">
      </div>
    </div>
    
    <div class="footer">
      U & F Sons, Inc. DBA Sahara Home Care &copy; 2010–2024
    </div>
  </div>

  <script src="https://getbootstrap.com/docs/5.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>