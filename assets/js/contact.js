document.addEventListener('DOMContentLoaded', function() {
    const contactForm = document.getElementById('contactForm');
    const formMessage = document.getElementById('formMessage');

    function validateEmail(email) {
        const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return re.test(email);
    }

    function showError(fieldId, message) {
        const errorElement = document.getElementById(fieldId + 'Error');
        const inputElement = document.getElementById(fieldId);

        if (errorElement && inputElement) {
            errorElement.textContent = message;
            inputElement.classList.add('error');
        }
    }

    function clearError(fieldId) {
        const errorElement = document.getElementById(fieldId + 'Error');
        const inputElement = document.getElementById(fieldId);

        if (errorElement && inputElement) {
            errorElement.textContent = '';
            inputElement.classList.remove('error');
        }
    }

    function clearAllErrors() {
        ['name', 'email', 'subject', 'message'].forEach(clearError);
    }

    function validateForm() {
        let isValid = true;
        clearAllErrors();

        const name = document.getElementById('name').value.trim();
        const email = document.getElementById('email').value.trim();
        const subject = document.getElementById('subject').value.trim();
        const message = document.getElementById('message').value.trim();

        if (name.length < 2) {
            showError('name', 'Name must be at least 2 characters long');
            isValid = false;
        }

        if (!validateEmail(email)) {
            showError('email', 'Please enter a valid email address');
            isValid = false;
        }

        if (subject.length < 3) {
            showError('subject', 'Subject must be at least 3 characters long');
            isValid = false;
        }

        if (message.length < 10) {
            showError('message', 'Message must be at least 10 characters long');
            isValid = false;
        }

        return isValid;
    }

    ['name', 'email', 'subject', 'message'].forEach(fieldId => {
        const field = document.getElementById(fieldId);
        if (field) {
            field.addEventListener('input', function() {
                if (this.value.trim()) {
                    clearError(fieldId);
                }
            });
        }
    });

    contactForm.addEventListener('submit', async function(e) {
        e.preventDefault();

        if (!validateForm()) {
            return;
        }

        const submitBtn = contactForm.querySelector('.submit-btn');
        const btnText = submitBtn.querySelector('.btn-text');
        const btnLoader = submitBtn.querySelector('.btn-loader');

        submitBtn.disabled = true;
        btnText.style.display = 'none';
        btnLoader.style.display = 'inline-block';
        formMessage.style.display = 'none';

        const formData = new FormData(contactForm);

        try {
            const response = await fetch('php/send-mail.php', {
                method: 'POST',
                body: formData
            });

            const result = await response.json();

            if (result.success) {
                formMessage.className = 'form-message success';
                formMessage.textContent = result.message || 'Thank you! Your message has been sent successfully. We will get back to you soon.';
                formMessage.style.display = 'block';
                contactForm.reset();
                clearAllErrors();
            } else {
                formMessage.className = 'form-message error';
                formMessage.textContent = result.message || 'Sorry, there was an error sending your message. Please try again.';
                formMessage.style.display = 'block';
            }
        } catch (error) {
            console.error('Error:', error);
            formMessage.className = 'form-message error';
            formMessage.textContent = 'Sorry, there was an error sending your message. Please try again.';
            formMessage.style.display = 'block';
        } finally {
            submitBtn.disabled = false;
            btnText.style.display = 'inline-block';
            btnLoader.style.display = 'none';

            setTimeout(() => {
                formMessage.style.display = 'none';
            }, 5000);
        }
    });
});
