<?php $__env->startSection('title', 'Become a Lock Pro | Join Unloqit Marketplace'); ?>
<?php $__env->startSection('meta_description', 'Join Unloqit as a verified locksmith. Set your own schedule, earn competitive rates, and help customers in your area.'); ?>
<?php $__env->startSection('canonical', route('pro.register')); ?>

<?php $__env->startSection('content'); ?>
<section class="industrial-bg text-brand-white py-24 relative overflow-hidden">
    <div class="absolute inset-0 opacity-10">
        <div class="absolute top-0 left-0 w-full h-full" style="background: radial-gradient(circle at 30% 50%, rgba(255, 106, 58, 0.3) 0%, transparent 50%);"></div>
    </div>
    <div class="container mx-auto px-6 relative z-10">
        <div class="max-w-4xl mx-auto text-center">
            <h1 class="font-display font-black text-6xl md:text-7xl mb-6 tracking-tight text-shadow-lg animate-fade-in-up">
                Become a Lock Pro
            </h1>
            <p class="text-xl md:text-2xl text-brand-gray font-medium leading-relaxed animate-fade-in-up" style="animation-delay: 0.2s;">
                Join the on-demand locksmith marketplace. Set your schedule, earn competitive rates, help customers in your area.
            </p>
        </div>
    </div>
</section>

<section class="atmospheric-surface py-24 relative">
    <div class="container mx-auto px-6 relative z-10">
        <div class="max-w-3xl mx-auto">
            <form action="<?php echo e(route('pro.register.submit')); ?>" method="POST" class="bg-brand-white p-8 border-l-4 border-brand-accent">
                <?php echo csrf_field(); ?>
                
                <div class="space-y-6">
                    <h2 class="font-display font-bold text-2xl text-brand-dark mb-6 uppercase tracking-tight">Account Information</h2>
                    
                    <div>
                        <label for="name" class="block font-display font-bold text-lg mb-2 text-brand-dark uppercase tracking-tight">Full Name</label>
                        <input type="text" name="name" id="name" required value="<?php echo e(old('name')); ?>" class="w-full px-4 py-3 border border-brand-gray focus:border-brand-accent focus:ring-2 focus:ring-brand-accent outline-none bg-brand-white text-brand-dark">
                        <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <p class="text-brand-accent text-sm mt-1"><?php echo e($message); ?></p>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <div>
                        <label for="email" class="block font-display font-bold text-lg mb-2 text-brand-dark uppercase tracking-tight">Email</label>
                        <input type="email" name="email" id="email" required value="<?php echo e(old('email')); ?>" class="w-full px-4 py-3 border border-brand-gray focus:border-brand-accent focus:ring-2 focus:ring-brand-accent outline-none bg-brand-white text-brand-dark">
                        <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <p class="text-brand-accent text-sm mt-1"><?php echo e($message); ?></p>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <div>
                        <label for="password" class="block font-display font-bold text-lg mb-2 text-brand-dark uppercase tracking-tight">Password</label>
                        <input type="password" name="password" id="password" required class="w-full px-4 py-3 border border-brand-gray focus:border-brand-accent focus:ring-2 focus:ring-brand-accent outline-none bg-brand-white text-brand-dark">
                        <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <p class="text-brand-accent text-sm mt-1"><?php echo e($message); ?></p>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <div>
                        <label for="password_confirmation" class="block font-display font-bold text-lg mb-2 text-brand-dark uppercase tracking-tight">Confirm Password</label>
                        <input type="password" name="password_confirmation" id="password_confirmation" required class="w-full px-4 py-3 border border-brand-gray focus:border-brand-accent focus:ring-2 focus:ring-brand-accent outline-none bg-brand-white text-brand-dark">
                    </div>

                    <div>
                        <label for="phone" class="block font-display font-bold text-lg mb-2 text-brand-dark uppercase tracking-tight">Phone Number</label>
                        <input type="tel" name="phone" id="phone" required value="<?php echo e(old('phone')); ?>" class="w-full px-4 py-3 border border-brand-gray focus:border-brand-accent focus:ring-2 focus:ring-brand-accent outline-none bg-brand-white text-brand-dark" placeholder="(555) 123-4567">
                        <?php $__errorArgs = ['phone'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <p class="text-brand-accent text-sm mt-1"><?php echo e($message); ?></p>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <div>
                        <label for="license_number" class="block font-display font-bold text-lg mb-2 text-brand-dark uppercase tracking-tight">License Number *</label>
                        <input type="text" name="license_number" id="license_number" required value="<?php echo e(old('license_number')); ?>" class="w-full px-4 py-3 border border-brand-gray focus:border-brand-accent focus:ring-2 focus:ring-brand-accent outline-none bg-brand-white text-brand-dark" placeholder="Required for verification">
                        <p class="text-brand-dark-60 text-sm mt-1">Your locksmith license number. Required for verification.</p>
                        <?php $__errorArgs = ['license_number'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <p class="text-brand-accent text-sm mt-1"><?php echo e($message); ?></p>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <div>
                        <label for="bio" class="block font-display font-bold text-lg mb-2 text-brand-dark uppercase tracking-tight">Bio (Optional)</label>
                        <textarea name="bio" id="bio" rows="4" class="w-full px-4 py-3 border border-brand-gray focus:border-brand-accent focus:ring-2 focus:ring-brand-accent outline-none bg-brand-white text-brand-dark" placeholder="Tell customers about your experience..."><?php echo e(old('bio')); ?></textarea>
                        <?php $__errorArgs = ['bio'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <p class="text-brand-accent text-sm mt-1"><?php echo e($message); ?></p>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <h2 class="font-display font-bold text-2xl text-brand-dark mb-6 uppercase tracking-tight mt-8">Service Areas</h2>
                    
                    <div>
                        <label class="block font-display font-bold text-lg mb-3 text-brand-dark uppercase tracking-tight">Cities You Serve *</label>
                        <div class="grid grid-cols-2 md:grid-cols-3 gap-3 max-h-48 overflow-y-auto border border-brand-gray p-4">
                            <?php $__currentLoopData = $cities; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $city): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <label class="flex items-center space-x-2 cursor-pointer">
                                <input type="checkbox" name="service_areas[]" value="<?php echo e($city->id); ?>" <?php echo e(in_array($city->id, old('service_areas', [])) ? 'checked' : ''); ?> class="w-4 h-4 text-brand-accent border-brand-gray focus:ring-brand-accent">
                                <span class="text-brand-dark text-sm"><?php echo e($city->name); ?>, <?php echo e($city->state); ?></span>
                            </label>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                        <?php $__errorArgs = ['service_areas'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <p class="text-brand-accent text-sm mt-1"><?php echo e($message); ?></p>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <div>
                        <label class="block font-display font-bold text-lg mb-3 text-brand-dark uppercase tracking-tight">Services You Offer *</label>
                        <div class="space-y-2 border border-brand-gray p-4">
                            <?php $__currentLoopData = $services; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $service): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <label class="flex items-center space-x-2 cursor-pointer">
                                <input type="checkbox" name="service_types[]" value="<?php echo e($service->id); ?>" <?php echo e(in_array($service->id, old('service_types', [])) ? 'checked' : ''); ?> class="w-4 h-4 text-brand-accent border-brand-gray focus:ring-brand-accent">
                                <span class="text-brand-dark"><?php echo e($service->name); ?></span>
                            </label>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                        <?php $__errorArgs = ['service_types'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <p class="text-brand-accent text-sm mt-1"><?php echo e($message); ?></p>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <div class="bg-brand-light p-4 border-l-4 border-brand-accent">
                        <p class="text-brand-dark text-sm">
                            <strong>Verification Process:</strong> After registration, your account will be reviewed for verification. 
                            You'll receive an email once your account is verified and activated. This typically takes 24-48 hours.
                        </p>
                    </div>

                    <button type="submit" class="emergency-button w-full text-center">
                        Join as Lock Pro
                    </button>

                    <p class="text-center text-brand-dark-60 text-sm">
                        Already have an account? <a href="<?php echo e(route('pro.login')); ?>" class="text-brand-accent font-bold hover:text-brand-accent-80 transition">Log in</a>
                    </p>
                </div>
            </form>
        </div>
    </div>
</section>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/malware/Desktop/unloqit/resources/views/marketplace/pro/register.blade.php ENDPATH**/ ?>