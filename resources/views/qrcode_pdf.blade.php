<style type="text/css">
	div.item {
	    /*vertical-align: top;*/
	    /*display: inline-block;*/
	    text-align: center;
	    margin-bottom: 10px;
	    margin-right: 10px;
	    /*width: 150px;*/
	}
	img {
	    /*width: 125px;*/
	    /*height: 125px;*/
	    /*background-color: grey;*/
	}
	.caption {
	    display: block;
	}
</style>
<table>
	<tr>
@foreach($data as $key => $row)
	{{-- <div class="item">
		<img src="data:image/png;base64, {{$row['code']}} ">
		<p>{{$row['name']}}</p>
	</div> --}}
	{{-- <td>{{$row['name']}}</td> --}}
	@if ($key %5 ==0)
		</tr>
			<tr>
				<td>
					<div class="item">
					    <img src="data:image/png;base64, {{$row['code']}} ">
					    <span class="caption">{{$row['name']}}</span>
					</div>
				</td>
	@else
		<td>
			<div class="item">
			    <img src="data:image/png;base64, {{$row['code']}} ">
			    <span class="caption">{{$row['name']}}</span>
			</div>
		</td>
	@endif
@endforeach
	</tr>
</table>