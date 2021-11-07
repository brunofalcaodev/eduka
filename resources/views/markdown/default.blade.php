@component('mail::message', ['data' => $data])

{!! $data['markdown'] !!}

@if(data_get($data, 'button'))
@component('mail::button', ['url' => data_get($data, 'button.url')])
{{ data_get($data, 'button.text') }}
@endcomponent
@endif

Best,<br>
{{ htmlentities(course()->from['name']) }}
@endcomponent
