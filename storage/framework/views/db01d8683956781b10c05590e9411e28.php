<?php
    // Expected variables: $href, $label, $number, $icon, $alert (optional), $accent (optional)
    $accent = $accent ?? 'purple';
?>
<a href="<?php echo e($href); ?>" class="stat-card stat-card--<?php echo e($accent); ?> <?php echo e(isset($alert) && $alert ? 'stat-card--alert' : ''); ?>">
    <div class="stat-card-body">
        <div class="stat-label"><?php echo e($label); ?></div>
        <div class="stat-number"><?php echo e($number); ?></div>
    </div>
    <div class="stat-icon"><?php echo $icon; ?></div>
</a>
<?php /**PATH C:\wamp64\www\DAW2\0616\Projecte_05_A01_Samuel_Santi_Pol_Marc_David\resources\views/admin/components/stat-card.blade.php ENDPATH**/ ?>