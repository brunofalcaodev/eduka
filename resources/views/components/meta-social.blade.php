@if(course())
	@foreach(course()->meta_tags as $name => $content)
		<meta name="{{ $name }}" content="{{ htmlentities($content) }}"/>
	@endforeach
		{{-- Static meta tags --}}
		<meta name='og:type' content='article' />
	@if(course()->meta_image)
		<!-- Fixed social meta tags -->
		<meta name='twitter:card' content='summary_large_image' />
		<meta name='twitter:image' content="{{ url('/') . '/storage/' . course()->meta_image }}" />
		<meta name='og:image' content="{{ url('/') . '/storage/' . course()->meta_image }}" />
		<!-- /Fixed social meta tags -->
	@endif
@endif