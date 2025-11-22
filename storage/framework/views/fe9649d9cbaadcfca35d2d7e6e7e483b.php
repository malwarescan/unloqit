<?php $__env->startSection('title', $service->name . ' in ' . $city->name . ' | ' . $city->name . ' Locksmith | Unloqit'); ?>
<?php $__env->startSection('meta_description', 'Professional ' . $service->name . ' services in ' . $city->name . ', ' . $city->state . '. Fast, reliable, and available 24/7.'); ?>
<?php $__env->startSection('canonical', $serviceUrl ?? ($city->slug === 'cleveland' ? route('cleveland.service.show', ['service' => $service->slug]) : route('city.service.show', ['city' => $city->slug, 'service' => $service->slug]))); ?>

<?php $__env->startSection('meta_extra'); ?>
<meta property="og:title" content="<?php echo e($service->name); ?> in <?php echo e($city->name); ?> | Unloqit">
<meta property="og:description" content="Professional <?php echo e($service->name); ?> services in <?php echo e($city->name); ?>, <?php echo e($city->state); ?>. Fast, reliable, and available 24/7.">
<meta property="og:url" content="<?php echo e($serviceUrl ?? ($city->slug === 'cleveland' ? route('cleveland.service.show', ['service' => $service->slug]) : route('city.service.show', ['city' => $city->slug, 'service' => $service->slug]))); ?>">
<meta property="og:type" content="website">
<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:title" content="<?php echo e($service->name); ?> in <?php echo e($city->name); ?> | Unloqit">
<meta name="twitter:description" content="Professional <?php echo e($service->name); ?> services in <?php echo e($city->name); ?>, <?php echo e($city->state); ?>.">
<?php $__env->stopSection(); ?>

<?php $__env->startSection('jsonld'); ?>
<script type="application/ld+json"><?php echo $jsonld; ?></script>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<!-- Breadcrumbs -->
<nav class="atmospheric-surface py-4 border-b border-brand-gray">
    <div class="container mx-auto px-6">
        <ol class="flex items-center space-x-3 text-sm">
            <?php $__currentLoopData = $breadcrumbs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $crumb): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <li>
                <a href="<?php echo e($crumb['url']); ?>" class="text-brand-accent hover:text-brand-dark font-medium transition-colors duration-300"><?php echo e($crumb['name']); ?></a>
            </li>
            <?php if(!$loop->last): ?>
            <li class="text-brand-gray">/</li>
            <?php endif; ?>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </ol>
    </div>
</nav>

<!-- Hero -->
<section class="industrial-bg text-brand-white py-24 relative overflow-hidden">
    <div class="absolute inset-0 opacity-10">
        <div class="absolute top-0 left-0 w-full h-full" style="background: radial-gradient(circle at 30% 50%, rgba(255, 106, 58, 0.3) 0%, transparent 50%);"></div>
    </div>
    <div class="container mx-auto px-6 relative z-10">
        <div class="max-w-4xl">
            <div class="mb-4 animate-fade-in-down">
                <span class="inline-block px-4 py-2 bg-brand-accent text-brand-white font-display font-bold text-sm tracking-widest uppercase">24/7 Emergency Service</span>
            </div>
            <h1 class="font-display font-black text-6xl md:text-7xl mb-6 tracking-tight text-shadow-lg animate-fade-in-up">
                <?php echo e($service->name); ?><br>in <?php echo e($city->name); ?>

            </h1>
            <p class="text-xl md:text-2xl text-brand-gray font-medium leading-relaxed animate-fade-in-up" style="animation-delay: 0.2s;">
                Professional locksmith services available 24/7
            </p>
        </div>
    </div>
</section>

<!-- Service Content -->
<section class="atmospheric-surface py-24 relative">
    <div class="container mx-auto px-6 relative z-10">
        <div class="max-w-4xl mx-auto section-reveal">
            <div class="space-y-8">
                <div>
                    <h2 class="font-display font-black text-4xl md:text-5xl mb-6 text-brand-dark tracking-tight">
                        About <?php echo e($service->name); ?> Services
                    </h2>
                    <p class="text-lg text-brand-dark-60 mb-8 leading-relaxed">
                        <?php echo e($service->description); ?>

                    </p>
                </div>
                
                <div class="bg-brand-dark border-l-4 border-brand-accent p-8">
                    <h3 class="font-display font-bold text-2xl mb-6 text-brand-white tracking-tight uppercase">
                        Why Choose Unloqit for <?php echo e($service->name); ?>?
                    </h3>
                    <ul class="space-y-4 text-brand-gray text-lg">
                        <li class="flex items-start">
                            <span class="text-brand-accent mr-3 font-bold">•</span>
                            <span>24/7 availability for emergency situations</span>
                        </li>
                        <li class="flex items-start">
                            <span class="text-brand-accent mr-3 font-bold">•</span>
                            <span>Fast response times in <?php echo e($city->name); ?></span>
                        </li>
                        <li class="flex items-start">
                            <span class="text-brand-accent mr-3 font-bold">•</span>
                            <span>Licensed and insured professionals</span>
                        </li>
                        <li class="flex items-start">
                            <span class="text-brand-accent mr-3 font-bold">•</span>
                            <span>Competitive pricing with no hidden fees</span>
                        </li>
                    </ul>
                </div>

                <div>
                    <h3 class="font-display font-bold text-3xl mb-4 text-brand-dark tracking-tight uppercase">
                        Serving <?php echo e($city->name); ?> Neighborhoods
                    </h3>
                    <p class="text-lg text-brand-dark-60 leading-relaxed">
                        We provide <?php echo e(strtolower($service->name)); ?> services throughout <?php echo e($city->name); ?> and surrounding areas. 
                        Our locksmiths are familiar with the local area and can provide fast service.
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/malware/Desktop/unloqit/resources/views/pages/city-service.blade.php ENDPATH**/ ?>