<!DOCTYPE html>
<html>

<head>
    <title>Professional Registration</title>
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.2/css/all.css">
    <link href="https://stackpath.bootstrapcdn.com/bootswatch/4.3.1/lux/bootstrap.min.css" rel="stylesheet">
    <style type="text/css">
        body {
            background: url('https://images.unsplash.com/photo-1576091160550-2173dba999ef') no-repeat center center fixed;
            -webkit-background-size: cover;
            -moz-background-size: cover;
            -o-background-size: cover;
            background-size: cover;
            z-index: 0;
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
            <div class="col-md-6">
                <h2 class="text-center mt-5">Professional Registration</h2>
                <form method="POST" action="{{ route('professional.register') }}" class="mt-4">
                    @csrf
                    <div class="form-group">
                        <label for="titre">Title:</label>
                        <select id="titre" name="titre" class="form-control" required>
                            <option value="Dr.">Dr.</option>
                            <option value="Pr.">Pr.</option>
                            <option value="-">-</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="name">Name:</label>
                        <input type="text" id="name" name="name" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="persoPhone">Personal Phone:</label>
                        <input type="text" id="persoPhone" name="persoPhone" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="email">Email:</label>
                        <input type="email" id="email" name="email" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="address">Address:</label>
                        <input type="text" id="address" name="address" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="proPhone">Professional Phone:</label>
                        <input type="text" id="proPhone" name="proPhone" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="password">Password:</label>
                        <input type="password" id="password" name="password" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="specialization">Specialization:</label>
                        <input type="text" id="specialization" name="specialization" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="city">City:</label>
                        <input type="text" id="city" name="city" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="location">Location:</label>
                        <select id="location" name="location" class="form-control" required>
                            <option value="clinic">Clinic</option>
                            <option value="atHome">Home</option>
                            <option value="cabinet">Cabinet</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary btn-block">Register</button>
                </form>
            </div>
        </div>
    </div>
</body>

</html>