{{-- @if(count($data['statistics']) > 0)
	@foreach($data['statistics'] as $segment)
  <div class="row mb-4">
		@foreach($segment as $row)
         <div class="col-md-2 col-sm-6 mt-5">
         	<div class="shadow bg-white rounded text-center p-1">
            <div class="mt-2 sky">{{ $row['value'] }}</div>
            <span style="font-size: 12px;" class="text-{{ str_replace('badge-','',$row['badge']) }}">{{ $row['name'] }}</span>
        	</div>
         </div>
         @endforeach
    </div>
    <br>
    @endforeach
@endif --}}
@if(count($data['statistics']) > 0)
    @foreach($data['statistics'] as $segment)
        <div class="row mb-4 statistics-count">
            @foreach($segment as $index => $row)
                <div class="col-md-2 col-sm-6 mt-2">
                    <div class="shadow bg-white br-20 text-center pd-1">
                        <div class="mt-2 sky fa-2x">{{ $row['value'] }}</div>
                        <span style="font-size: 12px;" class="text-{{ str_replace('badge-','',$row['badge']) }}">{{ $row['name'] }}</span>
                    </div>
                </div>
                @if(($index + 1) % 5 == 0)
                    </div><div class="row mb-4 statistics-count" >
                @endif
            @endforeach
        </div>
        <br>
    @endforeach
@endif

