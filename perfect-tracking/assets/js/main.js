/* assets/js/main.js - Frontend Logic */

document.addEventListener('DOMContentLoaded', () => {
    const trackingForm = document.getElementById('trackingForm');
    const trackingInput = document.getElementById('trackingInput');
    const trackSubmitBtn = document.getElementById('trackSubmitBtn');
    const errorContainer = document.getElementById('trackingError');
    const resultsContainer = document.getElementById('trackingResults');

    if (trackingForm) {
        trackingForm.addEventListener('submit', async (e) => {
            e.preventDefault();

            const trackingId = trackingInput.value.trim();
            if (!trackingId) {
                showError('Please enter a valid tracking number.');
                return;
            }

            // Reset UI state
            errorContainer.classList.add('hidden');
            resultsContainer.classList.add('hidden');
            trackSubmitBtn.disabled = true;
            trackSubmitBtn.textContent = 'Tracking...';

            try {
                const response = await fetch(`api/track.php?id=${encodeURIComponent(trackingId)}`);
                const data = await response.json();

                if (!response.ok) {
                    throw new Error(data.error || 'Failed to fetch tracking details.');
                }

                renderResults(data);

            } catch (error) {
                showError(error.message);
            } finally {
                trackSubmitBtn.disabled = false;
                trackSubmitBtn.textContent = 'Track Package';
            }
        });
    }

    function showError(message) {
        errorContainer.textContent = message;
        errorContainer.classList.remove('hidden');
    }

    function renderResults(data) {
        const { shipment, timeline } = data;
        const container = resultsContainer.querySelector('.container');

        container.innerHTML = `
            <div class="card shipment-summary fade-in">
                <div class="summary-header">
                    <span class="status-badge status-${shipment.status.toLowerCase().replace(' ', '-')}">${shipment.status}</span>
                    <h3>Tracking ID: ${shipment.tracking_id}</h3>
                </div>
                <div class="summary-details">
                    <div class="detail-item">
                        <label>Sender</label>
                        <p>${shipment.sender_name}</p>
                    </div>
                    <div class="detail-item">
                        <label>Receiver</label>
                        <p>${shipment.receiver_name}</p>
                    </div>
                    <div class="detail-item">
                        <label>Destination</label>
                        <p>${shipment.destination_pincode}</p>
                    </div>
                </div>
            </div>
            
            <div class="timeline-card fade-in" style="animation-delay: 0.1s">
                <h4>Journey Progress</h4>
                ${timeline.length > 0 ? buildTimelineHtml(timeline) : '<p class="text-slate">Initial processing started...</p>'}
            </div>
        `;

        resultsContainer.classList.remove('hidden');
    }

    function buildTimelineHtml(events) {
        let html = '<ul class="tracking-timeline">';
        events.forEach((event, index) => {
            const date = new Date(event.timestamp).toLocaleDateString('en-US', {
                month: 'short', day: 'numeric', hour: '2-digit', minute: '2-digit'
            });
            html += `
                <li class="timeline-event" style="animation-delay: ${index * 0.1}s">
                    <span class="timeline-date">${date}</span>
                    <div class="timeline-content">
                        <strong>${event.status}</strong>
                        <span class="location">${event.location}</span>
                        ${event.description ? `<p>${event.description}</p>` : ''}
                    </div>
                </li>
            `;
        });
        html += '</ul>';
        return html;
    }

    function renderPincodeResult(data) {
        const details = data.details || {};
        pincodeResult.innerHTML = `
            <div class="result-box ${data.serviceable ? 'success' : 'error'} fade-in">
                <div class="result-header">
                    <span class="result-icon">${data.serviceable ? '✓' : '✕'}</span>
                    <strong>${data.message}</strong>
                </div>
                ${details.city ? `<p class="location-detail">Available in <strong>${details.city}, ${details.state}</strong></p>` : ''}
            </div>
        `;
        pincodeResult.classList.remove('hidden');
    }

    // Handle Pincode Check
    const pincodeForm = document.getElementById('pincodeForm');
    const pincodeInput = document.getElementById('pincodeInput');
    const pincodeResult = document.getElementById('pincodeResult');
    const pincodeSubmitBtn = document.getElementById('pincodeSubmitBtn');

    if (pincodeForm) {
        // Handle URL parameters for automatic checking
        const urlParams = new URLSearchParams(window.location.search);
        const queryPincode = urlParams.get('pincode');
        if (queryPincode) {
            pincodeInput.value = queryPincode;
            checkPincode(queryPincode);
        }

        pincodeForm.addEventListener('submit', async (e) => {
            e.preventDefault();
            const pincode = pincodeInput.value.trim();
            if (!pincode) return;
            checkPincode(pincode);
        });
    }

    async function checkPincode(pincode) {
        pincodeSubmitBtn.disabled = true;
        pincodeSubmitBtn.textContent = 'Checking...';
        pincodeResult.classList.add('hidden');

        try {
            const response = await fetch(`api/pincode.php?pincode=${encodeURIComponent(pincode)}`);
            const data = await response.json();

            if (!response.ok) throw new Error(data.error || 'Check failed.');
            renderPincodeResult(data);

        } catch (error) {
            pincodeResult.innerHTML = `<div class="result-box error"><strong>${error.message}</strong></div>`;
            pincodeResult.classList.remove('hidden');
        } finally {
            pincodeSubmitBtn.disabled = false;
            pincodeSubmitBtn.textContent = 'Check Area';
        }
    }

    // Handle Contact Form Mockup
    const contactForm = document.getElementById('contactForm');
    const formSuccess = document.getElementById('formSuccess');

    if (contactForm) {
        contactForm.addEventListener('submit', (e) => {
            e.preventDefault();

            const submitBtn = contactForm.querySelector('button[type="submit"]');
            submitBtn.disabled = true;
            submitBtn.textContent = 'Sending...';

            // Simulate network delay
            setTimeout(() => {
                contactForm.classList.add('hidden');
                formSuccess.classList.remove('hidden');
                formSuccess.classList.add('fade-in');
            }, 1000);
        });
    }
});
