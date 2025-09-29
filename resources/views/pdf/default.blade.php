<table border="0" align="center" cellpadding="10px" cellspacing="0" width="100%">
    <tr align="center">
        <td align="center">
            <img style="vertical-align: middle;" src="{{public_path('assets/logo.png')}}" alt="logo de l'application PNM Church"
    class="logo">
        </td>
    </tr>
</table>
<table border="0" align="center" cellpadding="10px" cellspacing="0" width="100%" style="font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, 'Open Sans', 'Helvetica Neue', sans-serif;">
    <tr align="center">
        <td align="center">
            <h1 style="text-align: center">{{ $dataToPass['title'] }}</h1>
        </td>
    </tr>
</table>

<table border="0" align="center" width="100%" cellpadding="10px" style="font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, 'Open Sans', 'Helvetica Neue', sans-serif; ">
    <thead align="center" bgcolor="#000">
        <tr align="center">
            @foreach ($dataToPass['headers'] as $header)
                <th border="0" align="left" style="font-size: .85rem;color: white">{{ $header }}</th>
            @endforeach
        </tr>
    </thead>
    <tbody>
        @foreach ($dataToPass['datas'] as $d)
            <tr>
                @foreach ($dataToPass['headers'] as $header)
                    <td border="0" align="left">{{ $d[$header] }}</td>
                @endforeach
            </tr>
        @endforeach
    </tbody>
</table>
