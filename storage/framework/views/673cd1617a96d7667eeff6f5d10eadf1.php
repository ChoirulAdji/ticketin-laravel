<?php if($paginator->hasPages()): ?>
<nav class="flex justify-center gap-2 mt-8" aria-label="Pagination">
  <?php if($paginator->onFirstPage()): ?>
    <span class="px-3 py-2 rounded-lg bg-gray-100 text-gray-400 text-sm cursor-not-allowed">←</span>
  <?php else: ?>
    <a href="<?php echo e($paginator->previousPageUrl()); ?>" class="px-3 py-2 rounded-lg bg-white border border-gray-200 hover:bg-gold hover:border-gold hover:text-navy-deep text-gray-600 text-sm transition-all">←</a>
  <?php endif; ?>

  <?php $__currentLoopData = $elements; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $element): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <?php if(is_string($element)): ?>
      <span class="px-3 py-2 text-gray-400 text-sm"><?php echo e($element); ?></span>
    <?php endif; ?>
    <?php if(is_array($element)): ?>
      <?php $__currentLoopData = $element; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $page => $url): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <?php if($page == $paginator->currentPage()): ?>
          <span class="px-3 py-2 rounded-lg bg-navy-mid text-white font-bold text-sm"><?php echo e($page); ?></span>
        <?php else: ?>
          <a href="<?php echo e($url); ?>" class="px-3 py-2 rounded-lg bg-white border border-gray-200 hover:bg-gold hover:border-gold hover:text-navy-deep text-gray-600 text-sm transition-all"><?php echo e($page); ?></a>
        <?php endif; ?>
      <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    <?php endif; ?>
  <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

  <?php if($paginator->hasMorePages()): ?>
    <a href="<?php echo e($paginator->nextPageUrl()); ?>" class="px-3 py-2 rounded-lg bg-white border border-gray-200 hover:bg-gold hover:border-gold hover:text-navy-deep text-gray-600 text-sm transition-all">→</a>
  <?php else: ?>
    <span class="px-3 py-2 rounded-lg bg-gray-100 text-gray-400 text-sm cursor-not-allowed">→</span>
  <?php endif; ?>
</nav>
<?php endif; ?>
<?php /**PATH C:\xampp\htdocs\ticketin-laravel\resources\views/vendor/pagination/tailwind.blade.php ENDPATH**/ ?>