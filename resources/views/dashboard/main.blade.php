<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">

    <!-- Tailwind CSS CDN link -->
    <link href="https://unpkg.com/tailwindcss@^2/dist/tailwind.min.css" rel="stylesheet">
</head>

<body class="m-4">
<form action="{{route('home')}}" method="get" class="">
    <input type="text" name="q" value="{{request()->get('q')}}">
    <button type="submit">Search</button>
</form>
<table class="table-auto table-bordered" border="1">
    <thead>
    <tr class="">
        <th class="px-4 py-2">ID</th>
        <th class="px-4 py-2">Nguồn</th>
        <th class="px-4 py-2">link</th>
        <th class="px-4 py-2">ảnh</th>
        <th class="px-4 py-2">.</th>
        <th class="px-4 py-2">.</th>
        <th class="px-4 py-2">Balodi</th>
        <th class="px-4 py-2">Nvgate</th>
        <th class="px-4 py-2">Mfilm</th>
        <th class="px-4 py-2">Vfilm</th>
    </tr>
    </thead>
    <tbody>
    @foreach($newss as $url)
        <tr>
            <td class="border px-4 py-2">{{ $url['id'] }}</td>
            <td class="border px-4 py-2">{{ $url['site'] }}</td>
            <td class="border px-4 py-2"><a href="{{ $url['url'] }}">{{ $url['title'] }}</a></td>
            <td class="border px-4 py-2"><img src="{{ $url['thumbnail'] }}" style="height: 110px" alt=""></td>
            <td class="border px-4 py-2">{{!empty($url['html'])?'Xem':''}}</td>
            <td class="border px-4 py-2"><a href="{{route('hide',['url'=>$url['url']])}}">Ẩn</a></td>
            <td class="border px-4 py-2">
                @if($url['balodi_id'])
                    ✔ Đã lên bài
                    @else
                        <a href="{{route('push',['url'=>$url['url']])}}" class="underline">Đăng bài</a>
                @endif
                @if($url['balodi_category_id'])
                    @php
                        echo \App\Enum\BalodiCategoryIdEnum::from($url['balodi_category_id'])->name;
                    @endphp
                @endif
                <br>
            <td class="border px-4 py-2">
                @if($url['nvgate_publish_at'])
                    ✔ Đã lên bài
                @else
                    <a href="{{route('publish-2-nvgate',['id'=>$url['id']])}}" class="underline">Đăng</a>
                @endif</td>
            <td class="border px-4 py-2"></td>
            <td class="border px-4 py-2"></td>
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