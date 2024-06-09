<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login to YNODoc</title>
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.2/css/all.css">
    <link href="https://stackpath.bootstrapcdn.com/bootswatch/4.3.1/lux/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: url('https://images.unsplash.com/photo-1576091160550-2173dba999ef') no-repeat center center fixed; 
            -webkit-background-size: cover;
            -moz-background-size: cover;
            -o-background-size: cover;
            background-size: cover;
        }
        .container {
            background-color: rgba(255, 255, 255, 0.8);
            padding: 20px;
            border-radius: 10px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6 text-center mt-5">
                <h1>Login to YNODoc</h1>
                <div class="mt-4">
                    <a href="{{ route('patient.login')}}" class="btn btn-primary btn-lg"><i class="fas fa-user-injured"></i> Patient Login</a>
                    <a href="{{ route('professional.login')}}" class="btn btn-success btn-lg"><i class="fas fa-user-md"></i> Doctor Login</a>
                </div>
            </div>
        </div>
    </div>
</body>
</html>