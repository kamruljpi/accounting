<div class="text-center">
	<nav aria-label="Page navigation example">
		<ul class="pagination">
			@if($currentPage > 1)
				<li class="page-item">
					<a class="page-link" href="{{ Route($route_name) }}/{{ $currentPage-1 }}" aria-label="Previous">
					<span aria-hidden="true">&laquo;</span>
					<span class="sr-only">Previous</span>
					</a>
				</li>
	        @endif
	        @for($i = 1; (($i-1)*$limitPerPage)<$totalData; $i++)
        		@if($currentPage == $i)
					<li class="active page-item"><a class="page-link" href="#" onclick='return false'><strong> {{$i}}</strong></a></li>
        		@else
					<li class="page-item"><a class="page-link" href="{{ Route($route_name) }}/{{$i}}"> {{$i}}</a></li>
        		@endif
        	@endfor
        	@if($currentPage*$limitPerPage < $totalData)
	        	<li class="page-item">
					<a class="page-link" href="{{ Route($route_name) }}/{{ $currentPage+1 }}" aria-label="Next">
					<span aria-hidden="true">&raquo;</span>
					<span class="sr-only">Next</span>
					</a>
				</li>
        	@endif
		</ul>
	</nav>	
</div>

<br><br>
