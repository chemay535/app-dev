document.addEventListener('DOMContentLoaded', () => {
    const form = document.getElementById('registrationForm');
    const roleSelect = document.getElementById('role');
    const studentGroup = document.getElementById('studentNumberGroup');
    const employeeGroup = document.getElementById('employeeNumberGroup');
    const studentInput = document.getElementById('studentNumber');
    const employeeInput = document.getElementById('employeeNumber');
    const messageBox = document.getElementById('formMessage');

    const resetRoleFields = () => {
        studentGroup.classList.add('hidden');
        studentInput.disabled = true;
        studentInput.required = false;
        studentInput.value = '';

        employeeGroup.classList.add('hidden');
        employeeInput.disabled = true;
        employeeInput.required = false;
        employeeInput.value = '';
    };

    const toggleRoleFields = () => {
        resetRoleFields();
        const role = roleSelect.value;

        if (role === 'student') {
            studentGroup.classList.remove('hidden');
            studentInput.disabled = false;
            studentInput.required = true;
        } else if (role === 'admin') {
            employeeGroup.classList.remove('hidden');
            employeeInput.disabled = false;
            employeeInput.required = true;
        }
    };

    const setMessage = (status, text) => {
        messageBox.textContent = text;
        messageBox.classList.remove('hidden', 'success', 'error', 'pending');
        if (status) {
            messageBox.classList.add(status);
        } else {
            messageBox.classList.add('hidden');
        }
    };

    roleSelect.addEventListener('change', toggleRoleFields);
    toggleRoleFields();

    form.addEventListener('submit', (event) => {
        event.preventDefault();
        setMessage('pending', 'Submitting registration...');

        const formData = new FormData(form);

        fetch('register.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                setMessage('success', data.message || 'Registration successful!');
                form.reset();
                toggleRoleFields();
            } else {
                setMessage('error', data.message || 'Registration failed.');
            }
        })
        .catch(() => {
            setMessage('error', 'Server error. Please try again later.');
        });
    });
});
