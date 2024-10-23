<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">

    <!-- Tailwind CSS CDN link -->
    <link href="https://unpkg.com/tailwindcss@^2/dist/tailwind.min.css" rel="stylesheet">
</head>

<body class="m-4">
    <table class="table-auto" border="1">
        <thead>
            <tr>
                <th class="px-4 py-2">ID</th>
                <th class="px-4 py-2">Name</th>
                <th class="px-4 py-2">Email</th>
                <th class="px-4 py-2">Created At</th>
                <th class="px-4 py-2">Updated At</th>
            </tr>
        </thead>
        <tbody>
            @foreach($newss as $user)
            <tr>
                <td class="border px-4 py-2">{{ $user['site'] }}</td>
                <td class="border px-4 py-2">{{ $user['url'] }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>

</html>