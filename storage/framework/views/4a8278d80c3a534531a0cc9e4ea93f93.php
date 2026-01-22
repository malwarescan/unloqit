<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    
    <title><?php echo $__env->yieldContent('title', 'Unloqit - 24/7 Locksmith Services'); ?></title>
    
    <meta name="description" content="<?php echo $__env->yieldContent('meta_description', 'Reliable 24/7 locksmith services in Cleveland, Ohio. Car lockouts, rekeys, key programming, residential & commercial.'); ?>">
    
    <link rel="canonical" href="<?php echo $__env->yieldContent('canonical', url('/')); ?>">
    
    
    <link rel="icon" type="image/x-icon" href="<?php echo e(asset('favicon.ico')); ?>?v=<?php echo e(filemtime(public_path('favicon.ico'))); ?>">
    <link rel="shortcut icon" type="image/x-icon" href="<?php echo e(asset('favicon.ico')); ?>?v=<?php echo e(filemtime(public_path('favicon.ico'))); ?>">
    <link rel="apple-touch-icon" href="<?php echo e(asset('favicon.ico')); ?>?v=<?php echo e(filemtime(public_path('favicon.ico'))); ?>">
    
    <?php echo $__env->yieldContent('meta_extra'); ?>
    
    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>
    
    <?php echo $__env->yieldContent('jsonld'); ?>
</head>
<body class="bg-brand-light text-brand-dark">
    <header class="industrial-bg border-b-2 border-brand-accent relative z-50">
        <nav class="container mx-auto px-6 py-5">
            <div class="flex items-center justify-between relative z-10">
                <a href="<?php echo e(route('home')); ?>" class="flex items-center group">
                    <img src="<?php echo e(asset('unloqit-logo.png')); ?>?v=<?php echo e(filemtime(public_path('unloqit-logo.png'))); ?>" alt="Unloqit" class="h-12 transition-transform duration-300 group-hover:scale-105">
                </a>
                <div class="hidden md:flex items-center space-x-8">
                    <a href="<?php echo e(route('cleveland.show')); ?>" class="text-brand-white font-display font-bold text-lg tracking-wide uppercase hover:text-brand-accent transition-colors duration-300 accent-underline">Services</a>
                    <a href="<?php echo e(route('pro.register')); ?>" class="text-brand-white font-display font-bold text-lg tracking-wide uppercase hover:text-brand-accent transition-colors duration-300 accent-underline">Become an Unloqit Pro</a>
                </div>
            </div>
        </nav>
    </header>

    <main class="relative">
        <?php echo $__env->yieldContent('content'); ?>
    </main>

    <footer class="industrial-bg border-t-2 border-brand-accent mt-20 relative">
        <div class="container mx-auto px-6 py-16 relative z-10">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-12 mb-12">
                <div>
                    <h3 class="font-display font-bold text-2xl text-brand-white mb-4 tracking-wide uppercase">Unloqit</h3>
                    <p class="text-brand-gray text-sm leading-relaxed mb-4">24/7 emergency locksmith services in Cleveland, Ohio. Precision. Speed. Reliability.</p>
                    <a href="<?php echo e(route('pro.register')); ?>" class="inline-block px-4 py-2 bg-brand-accent text-brand-white font-display font-bold text-sm uppercase tracking-wide hover:bg-brand-accent-80 transition">
                        Become an Unloqit Pro Service Provider
                    </a>
                </div>
                <div>
                    <h4 class="font-display font-bold text-lg text-brand-white mb-5 tracking-wide uppercase">Services</h4>
                    <ul class="space-y-3">
                        <li><a href="<?php echo e(route('cleveland.service.show', ['service' => 'car-lockout'])); ?>" class="text-brand-gray hover:text-brand-accent transition-colors duration-300 text-sm font-medium">Car Lockout</a></li>
                        <li><a href="<?php echo e(route('cleveland.service.show', ['service' => 'house-lockout'])); ?>" class="text-brand-gray hover:text-brand-accent transition-colors duration-300 text-sm font-medium">House Lockout</a></li>
                        <li><a href="<?php echo e(route('cleveland.service.show', ['service' => 'rekeying'])); ?>" class="text-brand-gray hover:text-brand-accent transition-colors duration-300 text-sm font-medium">Rekeying</a></li>
                        <li><a href="<?php echo e(route('cleveland.service.show', ['service' => 'commercial-locksmith'])); ?>" class="text-brand-gray hover:text-brand-accent transition-colors duration-300 text-sm font-medium">Commercial</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="font-display font-bold text-lg text-brand-white mb-5 tracking-wide uppercase">Areas We Serve</h4>
                    <ul class="space-y-3">
                        <li><a href="<?php echo e(route('cleveland.service.neighborhood.show', ['service' => 'car-lockout', 'neighborhood' => 'ohio-city'])); ?>" class="text-brand-gray hover:text-brand-accent transition-colors duration-300 text-sm font-medium">Ohio City</a></li>
                        <li><a href="<?php echo e(route('cleveland.service.neighborhood.show', ['service' => 'car-lockout', 'neighborhood' => 'tremont'])); ?>" class="text-brand-gray hover:text-brand-accent transition-colors duration-300 text-sm font-medium">Tremont</a></li>
                        <li><a href="<?php echo e(route('cleveland.service.neighborhood.show', ['service' => 'car-lockout', 'neighborhood' => 'lakewood'])); ?>" class="text-brand-gray hover:text-brand-accent transition-colors duration-300 text-sm font-medium">Lakewood</a></li>
                        <li><a href="<?php echo e(route('cleveland.service.neighborhood.show', ['service' => 'car-lockout', 'neighborhood' => 'university-circle'])); ?>" class="text-brand-gray hover:text-brand-accent transition-colors duration-300 text-sm font-medium">University Circle</a></li>
                    </ul>
                </div>
            </div>
            <div class="border-t border-brand-dark-60 pt-8">
                <p class="text-brand-gray text-sm text-center font-medium">&copy; <?php echo e(date('Y')); ?> Unloqit. All rights reserved.</p>
            </div>
        </div>
    </footer>
</body>
</html>

<?php /**PATH /Users/malware/Desktop/projects/unloqit/resources/views/layouts/app.blade.php ENDPATH**/ ?>