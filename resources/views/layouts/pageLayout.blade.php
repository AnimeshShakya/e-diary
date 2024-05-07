<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title')</title>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>

    <!-- Include any necessary CSS or link to external CSS files -->
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }
        nav {
            background-color: #333;
            color: #fff;
            padding: 0.5px 0;
            text-align: center;
        }
        .container {
            max-width: 800px;
            margin: 20px auto;
            padding: 0 20px;
        }
        footer {
            background-color: #333;
            color: #fff;
            text-align: center;
            padding: 10px 0;
            position: fixed;
            width: 100%;
            bottom: 0;
        }
        h1 {
            text-align: center;
            margin-bottom: 30px;
        }
        .btn {
            background-color: #007bff;
            color: #fff;
            border: none;
            padding: 10px 20px;
            cursor: pointer;
            border-radius: 5px;
        }
        .btn:hover {
            background-color: #0056b3;
        }
        input[type="text"],
        textarea {
            width: 100%;
            padding: 2px;
            margin-bottom: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-sizing: border-box;
        }
        .time-slot {
            display: flex;
            align-items: center;
        }
        .time-slot p {
            margin: 0;
            width: 60px;
            text-align: center;
        }
        .footer {
            position: fixed;
            bottom: 10px;
            left: 10px;
            color: #666;
        }
        .days {
        display: flex;
        justify-content: flex-end;
        }
        .days button {
            background-color: #007bff;
            color: #fff;
            border: none;
            padding: 5px 10px;
            cursor: pointer;
            border-radius: 5px;
            margin: 0 5px;
        }
        .matrix-container {
            display: flex;
            justify-content: center;
            margin-top: 20px;
        }
        .matrix {
            border-collapse: collapse;
            width: 1000px; /* Adjust width as needed */
            margin-top: 20px;
            table-layout: fixed; /* Ensure fixed table layout */
        }
        .matrix th,
        .matrix td {
            border: 1px solid #ddd;
            padding: 15px;
            text-align: center;
            font-size: 18px;
        }
        .matrix th {
            background-color: #343a40; /* Dark color for headers */
            color: #fff;
            font-size: 20px; /* Increase font size */
        }
        .matrix td {
            background-color: #f4f4f4;
        }
        .matrix input {
            width: 100%;
            border: none;
            outline: none;
            text-align: center;
            font-size: 18px;
        }
    </style>
    
</head>
<body>
    
    <!-- Navigation Bar -->
    <nav>
        <h2>Navigation Bar</h2>
    </nav>

    <!-- Sidebar -->
    <div class="flex flex-col w-64 bg-white dark:bg-gray-800 h-full">
                <!-- Sidebar Content -->
                <div class="p-6">
                    <h2 class="text-lg font-semibold text-gray-800 dark:text-white">Menu</h2>
                    <ul class="mt-6">
                        <li class="mb-4"><a href="{{ route('dashboard') }}" class="text-gray-600 dark:text-gray-200 hover:text-gray-800 dark:hover:text-white">Dashboard</a></li>
                        <li class="mb-4"><a href="{{ route('diary') }}" class="text-gray-600 dark:text-gray-200 hover:text-gray-800 dark:hover:text-white">Diary</a></li>
                    </ul>
                </div>
            </div>

    <div class="container">
        <!-- Page Heading -->
        <h1>@yield('title')</h1>

        <!-- Content Section -->
        <div class="row">
            <div class="col">
                @yield('content')
            </div>
        </div>
    </div>

    <!-- Footer -->
    <div class="footer">
        <p>Animesh Shakya &copy; 2024</p>
    </div>

    <!-- CSRF Token -->
    <script>
        window.csrfToken = "{{ csrf_token() }}";
    </script>
</body>
</html>
