// assets/js/admin.js - Admin Dashboard Interactions

document.addEventListener('DOMContentLoaded', () => {
    // Handle Add Shipment Form
    const addShipmentForm = document.getElementById('addShipmentForm');
    if (addShipmentForm) {
        addShipmentForm.addEventListener('submit', async (e) => {
            e.preventDefault();

            const formData = new FormData(addShipmentForm);
            const saveBtn = document.getElementById('saveBtn');
            const msgBox = document.getElementById('formMsg');

            saveBtn.disabled = true;
            saveBtn.textContent = 'Saving...';

            try {
                const response = await fetch('../api/admin_actions.php?action=add_shipment', {
                    method: 'POST',
                    body: formData
                });

                const result = await response.json();

                msgBox.className = ''; // Reset
                msgBox.classList.remove('hidden');

                if (result.success) {
                    msgBox.classList.add('success-msg');
                    msgBox.textContent = result.message || 'Shipment added successfully!';
                    msgBox.style.backgroundColor = '#e8f5e9';
                    msgBox.style.color = '#2e7d32';
                    addShipmentForm.reset();
                    // Reset tracking ID placeholder
                    document.getElementById('tracking_id').value = 'Auto-generated';
                } else {
                    msgBox.classList.add('error-msg');
                    msgBox.textContent = result.error || 'An error occurred.';
                    msgBox.style.backgroundColor = '#ffebee';
                    msgBox.style.color = '#c62828';
                }
            } catch (error) {
                console.error('Error:', error);
                msgBox.classList.remove('hidden');
                msgBox.textContent = 'Network error. Please try again.';
                msgBox.style.backgroundColor = '#ffebee';
            } finally {
                saveBtn.disabled = false;
                saveBtn.textContent = 'Save Shipment';
            }
        });
    }
});
