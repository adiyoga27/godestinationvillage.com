@component('mail::message')
	{!! $message !!} <br /><br />
	<table style="width: 100%;">
	<tr>
		<td style="width: 50%; padding: 0px; vertical-align: top;">
			<strong>Order Date </strong> {{ date('d M Y', strtotime($data->created_at))}}<br />
			<strong>Order No   </strong> {{ $data->code }} <br />
		</td>
		<td style="width: 50%; padding: 0px; vertical-align: top; text-align:right">
			<strong>Status Payment   </strong><br />
		 	{{ strtoupper($data->payment_status)}}<br />
		</td>
	</tr>
</table>
<hr />
<table style="width: 100%;">
	<tr>
		<td style="width: 50%; padding: 0px; vertical-align: top;text-align:left">
		<strong>Customer </strong>  <br />
			{{$data->customer_name}}<br />
			{{$data->customer_address}} <br />
			{{$data->customer_email}} <br />
			{{$data->customer_phone}} <br />
		</td>
		<td style="width: 50%; padding: 0px; vertical-align: top;text-align:right">
		<strong>Company </strong> <br>
			Godestiantion Village<br>
			Jln Wr Supratman No. 302 Denpasar Timur, Bali <br>
		 	{{env('APP_EMAIL')}} <br>
			{{env('APP_PHONE')}}  <br>
		
	
</table>
<strong>Special Note : </strong>
<br />
{!! $data->special_note !!}    <br />
<center><strong>Transaction Detail</strong></center>
	@component('mail::table')
	|                      |      |                      |
	|----------------------|------------------------|----------------------|
	|  Name of Homestay    |                        | {{ $data->homestay_name }} |
	|  People(s)           |                        | {{ $data->pax }} Pax |
	|  Price of Homestay   |                        | Rp {{ number_format($data->homestay_price, 2,'.',',') }} |
	|  Discount            |                        | Rp {{ number_format($data->homestay_discount, 2,'.',',') }} |
	| <strong>Payment Total</strong>  |                        | <strong>Rp {{ number_format($data->total_payment, 2,'.',',') }}</strong> |
	@endcomponent



Click <a href="{{ url('invoice-homestay/'.$data->uuid) }}">this link</a> for download your 
@if($data->payment_status == 'success')
{{'voucher'}}
@else($data->payment_status == 'pending')
{{'invoice'}}
@endif 
<br />
<br />
Warmest Regards, <br />
<br />
Godevi
<br />
<br />
<strong>Note:</strong> Please do not reply this email. For more information, do not hesitate to contact us at: hello@godestinationvillage.com</a>

@endcomponent

