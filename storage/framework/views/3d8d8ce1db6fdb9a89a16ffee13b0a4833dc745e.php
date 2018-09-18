<div class="text-center">
	<nav aria-label="Page navigation example">
		<ul class="pagination">
			<?php if($currentPage > 1): ?>
				<li class="page-item">
					<a class="page-link" href="<?php echo e(Route($route_name)); ?>/<?php echo e($currentPage-1); ?>" aria-label="Previous">
					<span aria-hidden="true">&laquo;</span>
					<span class="sr-only">Previous</span>
					</a>
				</li>
	        <?php endif; ?>
	        <?php for($i = 1; (($i-1)*$limitPerPage)<$totalData; $i++): ?>
        		<?php if($currentPage == $i): ?>
					<li class="active page-item"><a class="page-link" href="#" onclick='return false'><strong> <?php echo e($i); ?></strong></a></li>
        		<?php else: ?>
					<li class="page-item"><a class="page-link" href="<?php echo e(Route($route_name)); ?>/<?php echo e($i); ?>"> <?php echo e($i); ?></a></li>
        		<?php endif; ?>
        	<?php endfor; ?>
        	<?php if($currentPage*$limitPerPage < $totalData): ?>
	        	<li class="page-item">
					<a class="page-link" href="<?php echo e(Route($route_name)); ?>/<?php echo e($currentPage+1); ?>" aria-label="Next">
					<span aria-hidden="true">&raquo;</span>
					<span class="sr-only">Next</span>
					</a>
				</li>
        	<?php endif; ?>
		</ul>
	</nav>	
</div>

<br><br>
