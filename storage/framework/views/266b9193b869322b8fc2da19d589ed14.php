<?php
    // Expected variables: $href, $icon, $label, $accent (optional)
    $accent = $accent ?? 'purple';
?>
<a href="<?php echo e($href); ?>" class="qa-item qa-item--<?php echo e($accent); ?>" role="button" aria-label="<?php echo e($label); ?>">
    <div class="qa-icon"><?php echo $icon; ?></div>
    <div class="qa-label"><?php echo e($label); ?></div>
</a>
<?php /**PATH C:\wamp64\www\DAW2\0616\Projecte_05_A01_Samuel_Santi_Pol_Marc_David\resources\views/admin/components/quick-action.blade.php ENDPATH**/ ?>