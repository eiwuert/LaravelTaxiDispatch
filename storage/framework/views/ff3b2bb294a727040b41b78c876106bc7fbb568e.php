;

<?php $__env->startSection('title'); ?>

GO Cabs - Dashboard

<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>

<?php if(Session::get('user_role')==1): ?>
	<?php echo $__env->make('dashboard.index', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
<?php elseif(Session::get('user_role')==3): ?>
	<?php echo $__env->make('dashboard.franchise-index', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
<?php endif; ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layout.master', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>