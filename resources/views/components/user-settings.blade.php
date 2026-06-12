{{-- 
    USER SETTINGS & PROFILE MODALS
    Include this file in your main layout (e.g., app.blade.php or admin layout)
    
    Usage: @include('components.user-settings')
--}}

<!-- Toast Notification -->
@include('components.modals.toast-notification')

<!-- Main Settings Modal -->
@include('components.modals.settings-modal')

{{-- 
    Note: Sub-modals are already included in settings-modal.blade.php:
    - change-password-modal.blade.php
    - edit-profile-modal.blade.php
    - notification-modal.blade.php
    - theme-modal.blade.php
    - privacy-modal.blade.php
--}}

<!-- Add CSRF Token Meta Tag if not exists -->
@if(!isset($csrf_token_meta))
<meta name="csrf-token" content="{{ csrf_token() }}">
@endif

<!-- Global Settings Styles -->
<style>
/* Apply theme to body based on user preference */
@if(auth()->check() && auth()->user()->theme_preference === 'dark')
body {
    background-color: #0f172a;
    color: #e2e8f0;
}

body.dark-theme {
    background-color: #0f172a;
    color: #e2e8f0;
}
@endif

/* Smooth transitions for theme switching */
body,
body * {
    transition: background-color 0.3s ease, color 0.3s ease, border-color 0.3s ease;
}

/* Prevent transition on page load */
body.no-transition,
body.no-transition * {
    transition: none !important;
}
</style>

<!-- Global Settings Scripts -->
<script>
// Initialize theme on page load
document.addEventListener('DOMContentLoaded', function() {
    // Remove no-transition class after page load
    setTimeout(() => {
        document.body.classList.remove('no-transition');
    }, 100);
    
    // Apply saved theme
    const currentTheme = '{{ auth()->check() ? auth()->user()->theme_preference : "light" }}';
    if (currentTheme === 'dark') {
        document.body.classList.add('dark-theme');
    }
});

// Global function to open settings modal from anywhere
function openUserSettings() {
    openSettingsModal();
}

// Keyboard shortcut: Ctrl+K to open settings
document.addEventListener('keydown', function(e) {
    if (e.ctrlKey && e.key === 'k') {
        e.preventDefault();
        openUserSettings();
    }
});

// Add settings button to user dropdown if not exists
document.addEventListener('DOMContentLoaded', function() {
    // This is optional - you can manually add the button in your navbar
    console.log('User Settings Loaded ✓');
    console.log('Press Ctrl+K to open settings');
});
</script>
