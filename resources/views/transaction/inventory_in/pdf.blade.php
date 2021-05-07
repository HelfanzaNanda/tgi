<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>In Bound</title>
    <style>
        body{
            margin:0 60px;
        }
        .content{
            width: 100%;
            margin: 10px 0;
        }
        .footer {
            width: 100%;
        }
        .desc-2{
            margin-bottom: 90px;
        }
        .desc-3{
            margin-bottom: 80px;
        }
        .footer tr:first-child td {
            padding-bottom:70px !important;
        }
    </style>
</head>
<body>
    <h4><center>BERITA ANCARA SERAH TERIMA BARANG</center></h4>
    <p>Pada hari ini {{ $result['date'] }} telah dilakukan serah terima barang sebagai berikut : </p>
    @foreach ($result['items'] as $item)
        <table class="content">
            <tr> <td style="width: 30%">Product </td> <td>: {{ $item['product_name'] }}</td> </tr>
            <tr> <td style="width: 30%">Batch Number </td> <td>: {{ $item['batch_number'] }}</td> </tr>
            <tr> <td style="width: 30%">Product Description </td> <td>: {{ $item['product_desc'] }}</td> </tr>
            <tr> <td style="width: 30%">Material Code </td> <td>: {{ $item['product_code'] }}</td> </tr>
            <tr> <td style="width: 30%">Color </td> <td>: {{ $item['color'] }}</td> </tr>
            <tr> <td style="width: 30%">Quantity </td> <td>: {{ $item['qty'] }}</td> </tr>
        </table>    
    @endforeach
    <p class="desc-2">
        Dari  <i>PT. Trans Geo Indonesia</i> kepada <i>{{ $result['to'] }}</i> dengan kondisi barang sebagai berikut :
    </p>
    <p class="desc-3">Demikian kami buat berita acara serah terima barang untuk di pergunakan sebagaimana mestinya.</p>

    <table class="footer">
        <tr> <td >Yang Menyerahkan,</td> <td  style="width: 20%">Yang Menerima,</td> </tr>
        <tr> <td>Ohing Abdul Rahim</td> <td style="width: 20%">.........................</td> </tr>
        <tr> <td>PT. Trans Geo Indonesia</td> <td style="width: 20%">{{ $result['to'] }}</td> </tr>
    </table>
    
</body>
</html>