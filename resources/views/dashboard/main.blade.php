<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">

    <!-- Tailwind CSS CDN link -->
    <link href="https://unpkg.com/tailwindcss@^2/dist/tailwind.min.css" rel="stylesheet">
</head>

<body class="m-4">
    <table class="table-auto table-bordered" border="1">
        <thead>
            <tr class="">
                <th class="px-4 py-2">site</th>
                <th class="px-4 py-2">link</th>
                <th class="px-4 py-2" colspan="4">Publish</th>
            </tr>
        </thead>
        <tbody>
            @foreach($newss as $user)
            <tr>
                <td class="border px-4 py-2">{{ $user['site'] }}</td>
                <td class="border px-4 py-2"><a href="{{ $user['url'] }}">{{ $user['title'] }}</a></td>
                <td class="border px-4 py-2"><a href="{{route('detail',['url'=>$user['url']])}}">Xem tin</a></td>
                <td class="border px-4 py-2"><a href="{{route('detail',['url'=>$user['url']])}}">Đăng lên NVGATE</a></td>
                <td class="border px-4 py-2"><a href="{{route('detail',['url'=>$user['url']])}}">Đăng lên Mfilm</a></td>
                <td class="border px-4 py-2"><a href="{{route('detail',['url'=>$user['url']])}}">Đăng lên Vfilm</a></td>
                <td class="border px-4 py-2"><a href="{{route('detail',['url'=>$user['url']])}}">Đăng lên Balodi</a></td>
            </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td colspan="7" class="border px-4 py-2">
                    {{$newss->links()}}
                </td>
            </tr>
    </table>
</body>

</html>