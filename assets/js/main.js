document.addEventListener('DOMContentLoaded', () => {
    
    // 1. Toast Notification Animation
    const alerts = document.querySelectorAll('.alert');
    alerts.forEach(alert => {
        // Slide in effect
        alert.style.opacity = '0';
        alert.style.transform = 'translateY(-10px)';
        alert.style.transition = 'all 0.4s cubic-bezier(0.16, 1, 0.3, 1)';
        
        requestAnimationFrame(() => {
            alert.style.opacity = '1';
            alert.style.transform = 'translateY(0)';
        });

        // Auto dismiss after 5 seconds if safe
        setTimeout(() => {
            alert.style.opacity = '0';
            setTimeout(() => alert.remove(), 400);
        }, 5000);
    });

    // 2. Input Field Interaction (Material Logic)
    const inputs = document.querySelectorAll('.form-control');
    inputs.forEach(input => {
        input.addEventListener('focus', () => {
            input.parentElement.classList.add('focused');
        });
        input.addEventListener('blur', () => {
            input.parentElement.classList.remove('focused');
        });
    });

    // 3. Form Validation (Client Side)
    const forms = document.querySelectorAll('form');
    forms.forEach(form => {
        form.addEventListener('submit', (e) => {
            const required = form.querySelectorAll('[required]');
            let valid = true;
            required.forEach(field => {
                if (!field.value.trim()) {
                    valid = false;
                    field.style.borderColor = '#de350b';
                } else {
                    field.style.borderColor = '';
                }
            });

            if (!valid) {
                e.preventDefault();
                // Shake animation logic could go here
                alert("Please fill in all required fields.");
            }
        });
    });

});
