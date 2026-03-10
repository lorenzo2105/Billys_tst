/**
 * Billy's Fast Food - Admin Dashboard JavaScript
 */

'use strict';

document.addEventListener('DOMContentLoaded', () => {
    // Image preview on file input
    document.querySelectorAll('input[type="file"][accept="image/*"]').forEach(input => {
        input.addEventListener('change', (e) => {
            const file = e.target.files[0];
            if (!file) return;

            // Check file size (5MB max)
            if (file.size > 5 * 1024 * 1024) {
                alert('Image trop lourde (max 5 Mo)');
                input.value = '';
                return;
            }

            // Show preview
            let preview = input.parentElement.querySelector('.form-preview');
            if (!preview) {
                preview = document.createElement('img');
                preview.className = 'form-preview';
                preview.style.maxWidth = '150px';
                preview.style.borderRadius = '8px';
                preview.style.marginTop = '0.5rem';
                input.parentElement.appendChild(preview);
            }

            const reader = new FileReader();
            reader.onload = (ev) => { preview.src = ev.target.result; };
            reader.readAsDataURL(file);
        });
    });

    // Confirm delete actions
    document.querySelectorAll('form[onsubmit*="confirm"]').forEach(form => {
        // Already handled via inline onsubmit
    });

    // Auto-hide alerts
    document.querySelectorAll('.alert').forEach(alert => {
        setTimeout(() => {
            alert.style.transition = 'opacity .3s';
            alert.style.opacity = '0';
            setTimeout(() => alert.remove(), 300);
        }, 5000);
    });
});
