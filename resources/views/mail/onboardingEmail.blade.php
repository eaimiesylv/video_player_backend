<html>
<body style="background-color:#EDF2F7; overflow:scroll">
{{-- 	
	<div style="display: flex; align-items: center; justify-content: center; padding-top: 2em; padding-bottom: 1em;">
		<img src="{{ config('app.url') }}/logo.png" width="150px" height="150px" alt="Milan Medic" style="margin-right: 10px;" />
		<h2 style="color: black">{{$emailData['company_name']}}</h2>
		
	</div> --}}
	
	<div style="background-color:#fff;width:70%;margin:0 auto;padding:2em;color:black;">
		<h3>{{ $emailData['salutation'] }}</h3>
		
		@if(!empty($emailData['content1']))
			<p>
				{!! $emailData['content1'] !!}
			</p>
		@endif

		@if(!empty($emailData['btn_label']))
			<p style="text-align:center;">
				<a href="{{ $emailData['frontend_url'] }}" style="background-color: #4CAF50; color: white; padding: 10px 20px; text-align: center; text-decoration: none; display: inline-block; font-size: 16px; margin: 4px 2px; cursor: pointer;">{{ $emailData['btn_label'] }}</a>
			</p>
		@endif

		@if(!empty($emailData['content2']))
		<p style="word-wrap: break-word;">
				{!! $emailData['content2'] !!}
			</p>
		@endif
		
		<p>
			Best regards<br>
			{{$emailData['sender']}}
		</p>
	</div>
	<p style="font-size: 12px; text-align: center; margin:2em; padding-bottom: 2em;">
    		&copy; {{ date('Y') }} {{ config('app.name') }} {{ config('app.url') }}
	</p>
</body>
</html>
