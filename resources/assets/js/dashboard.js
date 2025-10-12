document.addEventListener('DOMContentLoaded', function() {
    // CSRF Token Setup for AJAX
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
    
    // Sidebar Toggle
    const menuBtn = document.getElementById('menuBtn');
    const closeSidebar = document.getElementById('closeSidebar');
    const sidebar = document.getElementById('sidebar');
    
    if (menuBtn) {
        menuBtn.addEventListener('click', () => {
            sidebar.classList.remove('sidebar-closed');
            sidebar.classList.add('sidebar-open');
        });
    }
    
    if (closeSidebar) {
        closeSidebar.addEventListener('click', () => {
            sidebar.classList.remove('sidebar-open');
            sidebar.classList.add('sidebar-closed');
        });
    }
    
    // Dark Mode Toggle
    const darkModeBtn = document.getElementById('darkModeBtn');
    const moonIcon = document.getElementById('moonIcon');
    const sunIcon = document.getElementById('sunIcon');
    
    if (darkModeBtn) {
        // Check for saved dark mode preference
        if (localStorage.getItem('darkMode') === 'enabled') {
            document.body.classList.add('dark');
            moonIcon?.classList.add('hidden');
            sunIcon?.classList.remove('hidden');
        }
        
        darkModeBtn.addEventListener('click', () => {
            document.body.classList.toggle('dark');
            moonIcon?.classList.toggle('hidden');
            sunIcon?.classList.toggle('hidden');
            
            // Save preference
            if (document.body.classList.contains('dark')) {
                localStorage.setItem('darkMode', 'enabled');
            } else {
                localStorage.setItem('darkMode', 'disabled');
            }
        });
    }
    
    // Color Pickers
    const primaryColor = document.getElementById('primaryColor');
    const primaryText = document.getElementById('primaryText');
    const secondaryColor = document.getElementById('secondaryColor');
    const secondaryText = document.getElementById('secondaryText');
    const accentColor = document.getElementById('accentColor');
    const accentText = document.getElementById('accentText');
    
    if (primaryColor && primaryText) {
        primaryColor.addEventListener('input', (e) => {
            primaryText.value = e.target.value;
        });
        
        primaryText.addEventListener('input', (e) => {
            if (e.target.value.match(/^#[0-9A-F]{6}$/i)) {
                primaryColor.value = e.target.value;
            }
        });
    }
    
    if (secondaryColor && secondaryText) {
        secondaryColor.addEventListener('input', (e) => {
            secondaryText.value = e.target.value;
        });
        
        secondaryText.addEventListener('input', (e) => {
            if (e.target.value.match(/^#[0-9A-F]{6}$/i)) {
                secondaryColor.value = e.target.value;
            }
        });
    }
    
    if (accentColor && accentText) {
        accentColor.addEventListener('input', (e) => {
            accentText.value = e.target.value;
        });
        
        accentText.addEventListener('input', (e) => {
            if (e.target.value.match(/^#[0-9A-F]{6}$/i)) {
                accentColor.value = e.target.value;
            }
        });
    }
    
    // Apply Theme
    const applyTheme = document.getElementById('applyTheme');
    const resetTheme = document.getElementById('resetTheme');
    const fontFamily = document.getElementById('fontFamily');
    
    if (applyTheme) {
        applyTheme.addEventListener('click', async () => {
            const root = document.documentElement;
            const primary = primaryText?.value || '#1A685B';
            const secondary = secondaryText?.value || '#FF5528';
            const accent = accentText?.value || '#FFAC00';
            const font = fontFamily?.value || "'Poppins', sans-serif";
            const fontSize = document.querySelector('.font-size-btn.active')?.dataset.size || 'md';
            
            root.style.setProperty('--primary', primary);
            root.style.setProperty('--secondary', secondary);
            root.style.setProperty('--accent', accent);
            document.body.style.fontFamily = font;
            
            // Save to server via AJAX
            try {
                const response = await fetch('/admin/settings/theme', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    },
                    body: JSON.stringify({
                        primary_color: primary,
                        secondary_color: secondary,
                        accent_color: accent,
                        font_family: font,
                        font_size: fontSize
                    })
                });
                
                const data = await response.json();
                if (data.success) {
                    alert('✅ Theme applied successfully!');
                }
            } catch (error) {
                console.error('Error saving theme:', error);
                alert('✅ Theme applied locally!');
            }
        });
    }
    
    if (resetTheme) {
        resetTheme.addEventListener('click', () => {
            const root = document.documentElement;
            root.style.setProperty('--primary', '#1A685B');
            root.style.setProperty('--secondary', '#FF5528');
            root.style.setProperty('--accent', '#FFAC00');
            document.body.style.fontFamily = "'Poppins', sans-serif";
            
            if (primaryColor) primaryColor.value = '#1A685B';
            if (primaryText) primaryText.value = '#1A685B';
            if (secondaryColor) secondaryColor.value = '#FF5528';
            if (secondaryText) secondaryText.value = '#FF5528';
            if (accentColor) accentColor.value = '#FFAC00';
            if (accentText) accentText.value = '#FFAC00';
            if (fontFamily) fontFamily.value = "'Poppins', sans-serif";
            
            document.querySelectorAll('.font-size-btn').forEach(btn => {
                btn.classList.remove('active');
                if (btn.dataset.size === 'md') btn.classList.add('active');
            });
            
            alert('🔄 Theme has been reset!');
        });
    }
    
    // Toggle Switches
    const toggles = document.querySelectorAll('.toggle-switch');
    toggles.forEach(toggle => {
        toggle.addEventListener('click', async () => {
            toggle.classList.toggle('active');
            
            // Save notification settings via AJAX
            const toggleType = toggle.dataset.toggle;
            const isActive = toggle.classList.contains('active');
            
            try {
                await fetch('/admin/settings/notifications', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    },
                    body: JSON.stringify({
                        [toggleType]: isActive
                    })
                });
            } catch (error) {
                console.error('Error saving notification settings:', error);
            }
        });
    });
    
    // Skeleton Loader & Lazy Loading Simulation
    const skeleton = document.getElementById('skeletonLoader');
    const content = document.getElementById('actualContent');
    
    if (skeleton && content) {
        setTimeout(() => {
            skeleton.classList.add('hidden');
            content.classList.remove('hidden');
        }, 1500);
    }
    
    // Keyboard Navigation
    document.addEventListener('keydown', (e) => {
        // Close sidebar with Escape key
        if (e.key === 'Escape' && sidebar?.classList.contains('sidebar-open')) {
            sidebar.classList.remove('sidebar-open');
            sidebar.classList.add('sidebar-closed');
        }
        
        // Trigger click on focusable elements with Enter or Space
        if (e.key === 'Enter' || e.key === ' ') {
            if (document.activeElement.matches('[tabindex="0"], button')) {
                e.preventDefault();
                document.activeElement.click();
            }
        }
    });
    
    // Font Size Adjustment
    const fontSizeControls = document.getElementById('fontSizeControls');
    if (fontSizeControls) {
        const sizeButtons = fontSizeControls.querySelectorAll('.font-size-btn');
        fontSizeControls.addEventListener('click', (e) => {
            if (e.target.matches('.font-size-btn')) {
                const size = e.target.dataset.size;
                document.documentElement.classList.remove('font-size-sm', 'font-size-md', 'font-size-lg');
                document.documentElement.classList.add(`font-size-${size}`);
                
                sizeButtons.forEach(btn => btn.classList.remove('active'));
                e.target.classList.add('active');
            }
        });
    }
    
    // Dynamic Font Loading
    if (fontFamily) {
        fontFamily.addEventListener('change', (e) => {
            const fontName = e.target.value.split(',')[0].replace(/'/g, '').trim();
            const fontUrl = `https://fonts.googleapis.com/css2?family=${fontName.replace(' ', '+')}:wght@300;400;500;600;700&display=swap`;
            
            // Check if font is already loaded
            if (!document.querySelector(`link[href="${fontUrl}"]`)) {
                const link = document.createElement('link');
                link.rel = 'stylesheet';
                link.href = fontUrl;
                document.head.appendChild(link);
            }
        });
    }
    
    // Profile Image Cropper
    const profileImageInput = document.getElementById('profileImageInput');
    const profileImagePreview = document.getElementById('profileImagePreview');
    const cropperModal = document.getElementById('cropperModal');
    const imageToCrop = document.getElementById('imageToCrop');
    const cropImageBtn = document.getElementById('cropImageBtn');
    const cancelCropBtn = document.getElementById('cancelCrop');
    
    let cropper = null;
    
    if (profileImageInput) {
        profileImageInput.addEventListener('change', (e) => {
            const files = e.target.files;
            if (files && files.length > 0) {
                const reader = new FileReader();
                reader.onload = () => {
                    imageToCrop.src = reader.result;
                    cropperModal.classList.remove('hidden');
                    
                    if (cropper) {
                        cropper.destroy();
                    }
                    
                    cropper = new Cropper(imageToCrop, {
                        aspectRatio: 1,
                        viewMode: 1,
                        background: false,
                        responsive: true,
                        restore: true,
                    });
                };
                reader.readAsDataURL(files[0]);
            }
        });
    }
    
    if (cropImageBtn) {
        cropImageBtn.addEventListener('click', async () => {
            if (!cropper) return;
            
            const canvas = cropper.getCroppedCanvas({
                width: 256,
                height: 256,
            });
            
            profileImagePreview.src = canvas.toDataURL();
            
            // Upload to server
            canvas.toBlob(async (blob) => {
                const formData = new FormData();
                formData.append('profile_image', blob, 'profile.jpg');
                
                try {
                    const response = await fetch('/admin/settings/profile-image', {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': csrfToken
                        },
                        body: formData
                    });
                    
                    const data = await response.json();
                    if (data.success) {
                        alert('Profile image updated successfully!');
                    }
                } catch (error) {
                    console.error('Error uploading image:', error);
                    alert('Image cropped locally!');
                }
            }, 'image/jpeg');
            
            hideModalAndDestroyCropper();
        });
    }
    
    if (cancelCropBtn) {
        cancelCropBtn.addEventListener('click', hideModalAndDestroyCropper);
    }
    
    function hideModalAndDestroyCropper() {
        cropperModal?.classList.add('hidden');
        if (cropper) {
            cropper.destroy();
            cropper = null;
        }
        if (profileImageInput) {
            profileImageInput.value = '';
        }
    }
});