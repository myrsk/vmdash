<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <title>{{ config('app.name', 'Laravel') }} Installer</title>

    <style>
        #requirements {
            list-style-type: none;
            padding-left: 20px;
        }

        #requirements li {
            line-height: 40px;
        }

        #requirements li svg {
            vertical-align: middle;
            margin-right: 5px;
        }

        #requirements i {
            margin-right: 5px;
        }

        #errors ul {
            margin-bottom: 0px;
        }

        .card {
            margin-bottom: 25px;
        }
    </style>

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.0/css/bootstrap.min.css" integrity="sha384-9gVQ4dYFwwWSjIDZnLEWnxCjeSWFphJiwGPXr1jddIhOegiu1FwO5qRGvFXOdJZ4" crossorigin="anonymous">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.1.1/css/all.css" integrity="sha384-O8whS3fhG2OnA5Kas0Y9l3cfpmYjapjI0E4theH4iuMD+pLhbf6JI0jIMfYcK3yZ" crossorigin="anonymous">
</head>
<body class="bg-light">
    <div class="container">
        <div class="py-5 text-center">
            <img class="d-block mx-auto mb-3" src="{{ asset('images/vmdash_logo.png') }}" width="72" alt="">
            <h2>vmDash Installer</h2>
        </div>

        <div class="card">
            <h5 id="installTitle" class="card-header">@yield('title')</h5>
            <div id="installBody" class="card-body">
                @yield('content')
            </div>
        </div>

        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.2/js/bootstrap.min.js" integrity="sha384-o+RDsa0aLu++PJvFqy8fFScvbHFLtbvScb8AjopnFD+iEQ7wo/CG0xlczd+2O/em" crossorigin="anonymous"></script>
        <script>
            function maskPassword()
            {
                var passwordField = document.getElementById('password');
                var passwordIcon = document.getElementById('passwordIcon');

                if (passwordField.type == 'password')
                {
                    passwordField.type = 'text';
                    passwordIcon.className = 'fas fa-eye-slash';
                }
                else
                {
                    passwordField.type = 'password';
                    passwordIcon.className = 'fas fa-eye';
                }
            }
        </script>
</body>
</html>
