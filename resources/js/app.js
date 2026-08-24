import axios from 'axios';

window.axios = axios;
window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

// Initialize Laravel Echo + Pusher when PUSHER_APP_KEY is exposed on window (set via Blade layout)
if (window.PUSHER_APP_KEY) {
    // Dynamic import so build doesn't break when packages are missing during development
    Promise.all([import('pusher-js'), import('laravel-echo')])
        .then(([PusherModule, EchoModule]) => {
            const Pusher = PusherModule.default ?? PusherModule;
            const Echo = EchoModule.default ?? EchoModule;

            window.Pusher = Pusher;
            window.Echo = new Echo({
                broadcaster: 'pusher',
                key: window.PUSHER_APP_KEY,
                cluster: window.PUSHER_APP_CLUSTER || undefined,
                wsHost: window.PUSHER_APP_HOST || undefined,
                wsPort: window.PUSHER_APP_PORT ? Number(window.PUSHER_APP_PORT) : undefined,
                forceTLS: window.PUSHER_APP_PORT ? false : true,
                encrypted: true,
                enabledTransports: ['ws','wss'],
            });
        })
        .catch(err => console.warn('Echo/Pusher initialization failed:', err));
}


document.querySelectorAll('[data-suggest-endpoint]').forEach((input) => {
    const endpoint = input.getAttribute('data-suggest-endpoint');
    const target = document.querySelector(input.getAttribute('data-target'));

    if (!target) {
        return;
    }

    input.addEventListener('input', async () => {
        if (input.value.trim().length < 2) {
            target.innerHTML = '';
            return;
        }

        try {
            const { data } = await axios.get(`${endpoint}?q=${encodeURIComponent(input.value)}`);
            const items = data.data ?? data ?? [];
 
            // Filter out suggestions without a slug to avoid generating invalid URLs
            const valid = items.filter(i => i && i.slug);
 
            target.innerHTML = valid.slice(0, 5).map((item) => `
               <a href="/products/${item.slug}" class="search-result-item">
                   <strong>${item.name}</strong>
                   <span>Rs. ${Number(item.price).toLocaleString('en-IN')}</span>
               </a>
            `).join('');
        } catch (error) {
            target.innerHTML = '';
        }
    });

    document.addEventListener('click', (event) => {
        if (!input.contains(event.target) && !target.contains(event.target)) {
            target.innerHTML = '';
        }
    });
});

document.querySelectorAll('[data-countdown]').forEach((node) => {
    const targetDate = new Date(node.getAttribute('data-countdown'));

    const render = () => {
        const diff = targetDate.getTime() - Date.now();

        if (diff <= 0) {
            node.textContent = 'Offer refreshed';
            return;
        }

        const hours = Math.floor(diff / (1000 * 60 * 60));
        const minutes = Math.floor((diff / (1000 * 60)) % 60);
        const seconds = Math.floor((diff / 1000) % 60);
        node.textContent = `${hours}h ${minutes}m ${seconds}s`;
    };

    render();
    setInterval(render, 1000);
});

document.querySelectorAll('[data-gallery-thumb]').forEach((thumb) => {
    thumb.addEventListener('click', () => {
        const mainTarget = document.querySelector(thumb.getAttribute('data-gallery-target'));
        if (!mainTarget) return;

        // The thumbnail is a button wrapping an <img>. Use the nested image's src.
        const img = thumb.querySelector('img');
        // Prefer an explicit full-size URL from data-full-src, otherwise use the nested img src.
        const full = thumb.dataset && thumb.dataset.fullSrc ? thumb.dataset.fullSrc : null;
        const newSrc = full || (img ? img.getAttribute('src') : thumb.getAttribute('src'));
        if (!newSrc) return; // guard against null/undefined

        mainTarget.src = newSrc;
    });
});

document.querySelectorAll('[data-pincode-check]').forEach((button) => {
    button.addEventListener('click', () => {
        const input = document.querySelector(button.getAttribute('data-input'));
        const target = document.querySelector(button.getAttribute('data-target'));

        if (!input || !target) {
            return;
        }

        const code = input.value.trim();
        const supported = {
            '560001': 'Delivery by tomorrow. Installation slot available.',
            '110001': 'Get it in 24 hours with express shipping.',
            '400001': 'Dispatch today, delivery in 2 business days.',
        };

        target.textContent = supported[code] || 'Delivery available in 3-5 business days.';
    });
});
 function showNotification(message, type = 'success') {
            const notification = document.createElement('div');
            notification.className = `notification notification-${type}`;
            notification.textContent = message;
            document.body.appendChild(notification);
            
            setTimeout(() => {
                notification.style.animation = 'slideOutRight 0.3s ease-out';
                setTimeout(() => notification.remove(), 300);
            }, 3000);
        }
        
        // Profile Picture Validation
        document.getElementById('profile_picture')?.addEventListener('change', async function(e) {
            const file = e.target.files[0];
            if (!file) return;
            
            const maxSize = 2 * 1024 * 1024; // 2MB
            const allowedTypes = ['image/jpeg', 'image/png', 'image/jpg', 'image/gif', 'image/webp'];
            
            // Validate file type
            if (!allowedTypes.includes(file.type)) {
                showNotification('Please upload a valid image file (JPG, PNG, GIF, or WEBP)', 'error');
                this.value = '';
                return;
            }
            
            // Validate file size
            if (file.size > maxSize) {
                showNotification('File size must be less than 2MB', 'error');
                this.value = '';
                return;
            }
            
            // Validate dimensions
            const img = new Image();
            const reader = new FileReader();
            
            reader.onload = function(e) {
                img.src = e.target.result;
                img.onload = function() {
                    if (img.width < 100 || img.height < 100) {
                        showNotification('Image dimensions should be at least 100x100 pixels', 'error');
                        document.getElementById('profile_picture').value = '';
                        return;
                    }
                    document.getElementById('profile-picture-form').submit();
                };
            };
            reader.readAsDataURL(file);
        });
        
        // Confirm remove picture
        function confirmRemovePicture() {
            if (confirm('Are you sure you want to remove your profile picture?')) {
                document.getElementById('remove-picture-form').submit();
            }
        }
        
        // Profile Form Validation
        document.getElementById('profile-form')?.addEventListener('submit', function(e) {
            const name = document.getElementById('name').value.trim();
            const email = document.getElementById('email').value.trim();
            const phone = document.getElementById('phone').value.trim();
            const errors = {};
            
            // Name validation
            if (!name) {
                errors.name = 'Full name is required';
            } else if (name.length < 2) {
                errors.name = 'Name must be at least 2 characters long';
            } else if (name.length > 100) {
                errors.name = 'Name cannot exceed 100 characters';
            } else if (!/^[\p{L}\s\-]+$/u.test(name)) {
                errors.name = 'Name can only contain letters, spaces, and hyphens';
            }
            
            // Email validation
            const emailRegex = /^[^\s@]+@([^\s@]+\.)+[^\s@]+$/;
            if (!email) {
                errors.email = 'Email address is required';
            } else if (!emailRegex.test(email)) {
                errors.email = 'Please enter a valid email address';
            } else if (email.length > 255) {
                errors.email = 'Email cannot exceed 255 characters';
            }
            
            // Phone validation (optional)
            if (phone && !/^[0-9+\-\s\(\)]{8,20}$/.test(phone)) {
                errors.phone = 'Please enter a valid phone number (8-20 digits)';
            }
            
            if (Object.keys(errors).length > 0) {
                e.preventDefault();
                // Clear existing errors
                document.querySelectorAll('#profile-form .error-msg').forEach(el => el.remove());
                document.querySelectorAll('#profile-form .is-invalid').forEach(el => {
                    el.classList.remove('is-invalid');
                });
                
                // Display new errors
                for (const [field, message] of Object.entries(errors)) {
                    const input = document.getElementById(field);
                    if (input) {
                        input.classList.add('is-invalid');
                        const errorSpan = document.createElement('span');
                        errorSpan.className = 'error-msg';
                        errorSpan.style.cssText = 'color: #EF4444; font-size: 12px; margin-top: 4px; display: block;';
                        errorSpan.textContent = message;
                        input.parentNode.appendChild(errorSpan);
                    }
                }
                
                showNotification(Object.values(errors)[0], 'error');
                return false;
            }
        });
        
        // Logout Form Validation
        document.getElementById('logout-form')?.addEventListener('submit', function(e) {
            const password = document.getElementById('logout_password').value;
            
            if (!password) {
                e.preventDefault();
                showNotification('Password is required', 'error');
                return false;
            }
            
            if (password.length < 6) {
                e.preventDefault();
                showNotification('Password must be at least 6 characters long', 'error');
                return false;
            }
            
            if (!confirm('Are you sure you want to logout from all other devices? You will remain logged in on this device.')) {
                e.preventDefault();
                return false;
            }
        });
        
        // Real-time validation for profile form
        const validateField = (field, value) => {
            switch(field) {
                case 'name':
                    if (!value) return 'Full name is required';
                    if (value.length < 2) return 'Name must be at least 2 characters';
                    if (value.length > 100) return 'Name cannot exceed 100 characters';
                    if (!/^[\p{L}\s\-]+$/u.test(value)) return 'Name can only contain letters, spaces, and hyphens';
                    return null;
                case 'email':
                    const emailRegex = /^[^\s@]+@([^\s@]+\.)+[^\s@]+$/;
                    if (!value) return 'Email is required';
                    if (!emailRegex.test(value)) return 'Please enter a valid email address';
                    if (value.length > 255) return 'Email cannot exceed 255 characters';
                    return null;
                case 'phone':
                    if (value && !/^[0-9+\-\s\(\)]{8,20}$/.test(value)) {
                        return 'Please enter a valid phone number (8-20 digits)';
                    }
                    return null;
                default:
                    return null;
            }
        };
        
        ['name', 'email', 'phone'].forEach(field => {
            const input = document.getElementById(field);
            if (input) {
                input.addEventListener('blur', function() {
                    const error = validateField(field, this.value.trim());
                    const existingError = this.parentNode.querySelector('.error-msg');
                    
                    if (error) {
                        this.classList.add('is-invalid');
                        if (!existingError) {
                            const errorSpan = document.createElement('span');
                            errorSpan.className = 'error-msg';
                            errorSpan.style.cssText = 'color: #EF4444; font-size: 12px; margin-top: 4px; display: block;';
                            errorSpan.textContent = error;
                            this.parentNode.appendChild(errorSpan);
                        } else {
                            existingError.textContent = error;
                        }
                    } else {
                        this.classList.remove('is-invalid');
                        if (existingError) {
                            existingError.remove();
                        }
                    }
                });
                
                input.addEventListener('focus', function() {
                    this.classList.remove('is-invalid');
                    const existingError = this.parentNode.querySelector('.error-msg');
                    if (existingError) existingError.remove();
                });
            }
        });
        
        // Delete address confirmation
        document.querySelectorAll('.delete-address-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                if (confirm('Are you sure you want to delete this address?')) {
                    this.closest('.delete-address-form').submit();
                }
            });
        });
        
        // Remove wishlist item confirmation
        document.querySelectorAll('.remove-wishlist-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                if (confirm('Remove this item from your wishlist?')) {
                    this.closest('.remove-wishlist-form').submit();
                }
            });
        });
        
        // Edit address function
        function editAddress(addressId) {
            window.location.href = '/address/' + addressId + '/edit';
        }
        
        // Auto-hide alerts after 5 seconds
        setTimeout(() => {
            document.querySelectorAll('.alert-success, .alert-danger').forEach(alert => {
                alert.style.animation = 'slideDown 0.3s ease-out reverse';
                setTimeout(() => alert.remove(), 300);
            });
        }, 5000);

        /* Live rider tracking integration
         * - Rider devices should authenticate (sanctum) and call POST /api/v1/riders/me/location
         * - Customers subscribe to Echo.channel('rider.{id}') and render markers on a Leaflet map
         */

        // Driver device: send periodic location updates when an element with data-driver-tracker exists
        if (document.querySelector('[data-driver-tracker]')) {
            if ('geolocation' in navigator) {
                navigator.geolocation.watchPosition(async (pos) => {
                    const payload = {
                        latitude: pos.coords.latitude,
                        longitude: pos.coords.longitude,
                        heading: pos.coords.heading ?? null,
                        speed: pos.coords.speed ?? null,
                        accuracy: pos.coords.accuracy ?? null,
                    };

                    try {
                        // Using Axios and Sanctum cookie auth (ensure session is authenticated or provide token)
                        await axios.post('/api/v1/riders/me/location', payload);
                    } catch (err) {
                        console.error('Location update failed', err);
                    }
                }, (err) => {
                    console.error('Geolocation error', err);
                }, { enableHighAccuracy: true, maximumAge: 5000, timeout: 10000 });
            } else {
                console.warn('Geolocation not supported in this browser');
            }
        }

        // Customer/Viewer: subscribe to rider.{id} channel and update a Leaflet map
        if (typeof Echo !== 'undefined' && document.getElementById('live-tracking-map')) {
            const mapEl = document.getElementById('live-tracking-map');
            const riderId = mapEl.dataset.riderId;

            // Ensure Leaflet (L) is loaded
            if (typeof L !== 'undefined') {
                // initialize map centered at India by default
                const map = L.map('live-tracking-map').setView([20.5937, 78.9629], 6);
                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    maxZoom: 19,
                    attribution: '&copy; OpenStreetMap contributors'
                }).addTo(map);

                const riderMarker = L.marker([0,0]).addTo(map);

                if (riderId) {
                    Echo.channel('rider.' + riderId).listen('.RiderLocationUpdated', (e) => {
                        const lat = e.latitude ?? e.location?.latitude;
                        const lng = e.longitude ?? e.location?.longitude;
                        if (!lat || !lng) return;

                        riderMarker.setLatLng([lat, lng]);
                        map.setView([lat, lng], 13);
                    });
                }
            } else {
                console.warn('Leaflet not loaded: include leaflet.js and leaflet.css in the page to render maps');
            }
        }
