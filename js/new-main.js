/**
 * Main JavaScript File
 * Handles all interactive features and animations
 */

// Hero Slider functionality
function initHeroSlider() {
    const slides = document.querySelectorAll('.hero-slide');
    const dots = document.querySelectorAll('.slider-dots .dot');
    const prevBtn = document.querySelector('.slider-prev');
    const nextBtn = document.querySelector('.slider-next');
    let currentSlide = 0;
    let slideInterval;

    console.log('Initializing slider. Found slides:', slides.length);
    
    if (slides.length === 0) {
        console.log('No slides found, retrying in 200ms...');
        setTimeout(initHeroSlider, 200);
        return;
    }

    function showSlide(index) {
        // Update currentSlide based on index
        currentSlide = index;
        
        // Handle wrapping
        if (currentSlide >= slides.length) currentSlide = 0;
        if (currentSlide < 0) currentSlide = slides.length - 1;
        
        // Remove active class from all slides and dots
        slides.forEach(slide => slide.classList.remove('active'));
        dots.forEach(dot => dot.classList.remove('active'));
        
        // Add active class to current slide and dot
        slides[currentSlide].classList.add('active');
        if (dots[currentSlide]) {
            dots[currentSlide].classList.add('active');
        }
    }

    function nextSlide() {
        currentSlide++;
        showSlide(currentSlide);
    }

    function prevSlide() {
        currentSlide--;
        showSlide(currentSlide);
    }

    function startAutoSlide() {
        stopAutoSlide(); // Clear any existing interval
        slideInterval = setInterval(nextSlide, 5000);
        console.log('Auto-slide started');
    }

    function stopAutoSlide() {
        if (slideInterval) {
            clearInterval(slideInterval);
        }
    }

    // Event listeners
    if (nextBtn) {
        nextBtn.addEventListener('click', () => {
            stopAutoSlide();
            nextSlide();
            startAutoSlide();
        });
    }

    if (prevBtn) {
        prevBtn.addEventListener('click', () => {
            stopAutoSlide();
            prevSlide();
            startAutoSlide();
        });
    }

    dots.forEach((dot, index) => {
        dot.addEventListener('click', () => {
            stopAutoSlide();
            showSlide(index);
            startAutoSlide();
        });
    });

    // Start auto sliding
    startAutoSlide();

    // Pause on hover
    const hero = document.querySelector('.hero');
    if (hero) {
        hero.addEventListener('mouseenter', stopAutoSlide);
        hero.addEventListener('mouseleave', startAutoSlide);
    }
    
    console.log('Slider initialized successfully');
}

// Navigation functionality
function initializeNavigation() {
    const navToggle = document.getElementById('navToggle');
    const navMenu = document.getElementById('navMenu');
    const navLinks = document.querySelectorAll('.nav-link');
    const header = document.querySelector('.header');
    const dropdowns = document.querySelectorAll('.dropdown');
    
    console.log('Initializing navigation...', {
        navToggle: navToggle,
        navMenu: navMenu,
        navLinksCount: navLinks.length
    });
    
    // Mobile menu toggle
    if (navToggle && navMenu) {
        navToggle.addEventListener('click', (e) => {
            e.preventDefault();
            console.log('Hamburger clicked!');
            navToggle.classList.toggle('active');
            navMenu.classList.toggle('active');
            document.body.style.overflow = navMenu.classList.contains('active') ? 'hidden' : '';
        });
    } else {
        console.error('Navigation elements not found!');
    }
    
    // Dropdown functionality for mobile
    dropdowns.forEach(dropdown => {
        const toggle = dropdown.querySelector('.nav-link');
        const menu = dropdown.querySelector('.dropdown-menu');
        const icon = dropdown.querySelector('.dropdown-icon');
        
        if (toggle && menu) {
            // For mobile/tablet - toggle dropdown on click
            toggle.addEventListener('click', (e) => {
                if (window.innerWidth <= 768) {
                    e.preventDefault();
                    
                    // Toggle active state
                    const isActive = dropdown.classList.contains('active');
                    
                    // Close other dropdowns
                    dropdowns.forEach(otherDropdown => {
                        otherDropdown.classList.remove('active');
                    });
                    
                    // Toggle current dropdown
                    if (!isActive) {
                        dropdown.classList.add('active');
                    }
                }
            });
        }
    });
    
    // Close mobile menu when clicking on a link (except dropdown parent links)
    navLinks.forEach(link => {
        const isDropdownParent = link.parentElement.classList.contains('dropdown');
        if (!isDropdownParent) {
            link.addEventListener('click', () => {
                if (navToggle && navMenu) {
                    navToggle.classList.remove('active');
                    navMenu.classList.remove('active');
                    document.body.style.overflow = '';
                }
            });
        }
    });
    
    // Close dropdown menu items on click (mobile)
    document.querySelectorAll('.dropdown-menu a').forEach(link => {
        link.addEventListener('click', () => {
            if (navToggle && navMenu) {
                navToggle.classList.remove('active');
                navMenu.classList.remove('active');
                document.body.style.overflow = '';
            }
            
            // Close all dropdowns
            dropdowns.forEach(dropdown => {
                dropdown.classList.remove('active');
            });
        });
    });
    
    // Header scroll effect
    if (header) {
        window.addEventListener('scroll', () => {
            if (window.scrollY > 50) {
                header.classList.add('scrolled');
            } else {
                header.classList.remove('scrolled');
            }
        });
    }
}

// Navigation functionality

// Back to top button
document.addEventListener('DOMContentLoaded', function() {
    const backToTop = document.getElementById('backToTop');
    
    if (backToTop) {
        window.addEventListener('scroll', () => {
            if (window.scrollY > 300) {
                backToTop.classList.add('show');
            } else {
                backToTop.classList.remove('show');
            }
        });
        
        backToTop.addEventListener('click', () => {
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        });
    }
});

// Animated counters for stats
function animateCounter() {
    const counters = document.querySelectorAll('.stat-number');
    const speed = 200; // Animation speed
    
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const counter = entry.target;
                const target = +counter.getAttribute('data-count');
                const increment = target / speed;
                let current = 0;
                
                const updateCounter = () => {
                    current += increment;
                    if (current < target) {
                        counter.textContent = Math.ceil(current);
                        requestAnimationFrame(updateCounter);
                    } else {
                        counter.textContent = target + '+';
                    }
                };
                
                updateCounter();
                observer.unobserve(counter);
            }
        });
    }, { threshold: 0.5 });
    
    counters.forEach(counter => observer.observe(counter));
}

// Scroll animations
function initScrollAnimations() {
    const observerOptions = {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    };
    
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('fade-in');
                observer.unobserve(entry.target);
            }
        });
    }, observerOptions);
    
    // Observe all cards and sections
    const elements = document.querySelectorAll('.feature-card, .service-card, .testimonial-card, .stat-card');
    elements.forEach(el => {
        el.style.opacity = '0';
        el.style.transform = 'translateY(20px)';
        el.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
        observer.observe(el);
    });
}

// Add fade-in class styles dynamically
const style = document.createElement('style');
style.textContent = `
    .fade-in {
        opacity: 1 !important;
        transform: translateY(0) !important;
    }
`;
document.head.appendChild(style);

// Smooth scroll for anchor links
document.addEventListener('click', function(e) {
    if (e.target.closest('a[href^="#"]')) {
        e.preventDefault();
        const targetId = e.target.closest('a').getAttribute('href');
        if (targetId === '#') return;
        
        const targetElement = document.querySelector(targetId);
        if (targetElement) {
            const headerHeight = document.querySelector('.header').offsetHeight;
            const targetPosition = targetElement.offsetTop - headerHeight - 20;
            
            window.scrollTo({
                top: targetPosition,
                behavior: 'smooth'
            });
        }
    }
});

// Form validation (for contact forms)
function initFormValidation() {
    const forms = document.querySelectorAll('form');
    
    forms.forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const inputs = form.querySelectorAll('input[required], textarea[required]');
            let isValid = true;
            
            inputs.forEach(input => {
                if (!input.value.trim()) {
                    isValid = false;
                    input.classList.add('error');
                    showError(input, 'This field is required');
                } else {
                    input.classList.remove('error');
                    removeError(input);
                    
                    // Email validation
                    if (input.type === 'email' && !isValidEmail(input.value)) {
                        isValid = false;
                        input.classList.add('error');
                        showError(input, 'Please enter a valid email address');
                    }
                }
            });
            
            if (isValid) {
                // Form is valid, submit via AJAX to backend if marked
                console.log('Form submitted successfully');
                const shouldSend = form.dataset.sendMail === 'true' || ['contactForm', 'enquiryForm', 'contactHomeForm'].includes(form.id) || form.classList.contains('contact-home-form');

                if (shouldSend) {
                    const formData = new FormData(form);
                    
                    // Show loading state
                    const submitBtn = form.querySelector('button[type="submit"], input[type="submit"]');
                    const originalBtnText = submitBtn ? submitBtn.textContent : '';
                    if (submitBtn) {
                        submitBtn.disabled = true;
                        submitBtn.textContent = 'Sending...';
                    }
                    
                    fetch('send-email.php', { method: 'POST', body: formData })
                        .then(res => {
                            if (!res.ok) {
                                throw new Error(`HTTP ${res.status}: ${res.statusText}`);
                            }
                            return res.json();
                        })
                        .then(data => {
                            // Reset button state
                            if (submitBtn) {
                                submitBtn.disabled = false;
                                submitBtn.textContent = originalBtnText;
                            }
                            
                            if (data && data.success) {
                                showSuccessMessage(data.message || 'Form submitted successfully');
                                form.reset();
                                const popup = document.getElementById('enquiryPopup');
                                if (popup && popup.classList.contains('active')) {
                                    popup.classList.remove('active');
                                    document.body.style.overflow = '';
                                }
                                // Only redirect to thank-you page if email was sent successfully
                                setTimeout(() => {
                                    window.location.href = 'thank-you.html';
                                }, 1500);
                            } else {
                                // Show debug info if available
                                let errorMsg = (data && data.message) ? data.message : 'Failed to send email.';
                                if (data && data.debug) {
                                    console.error('Email Debug Info:', data.debug);
                                    errorMsg += '\n\nDebug Info:\n' + JSON.stringify(data.debug, null, 2);
                                }
                                alert(errorMsg);
                            }
                        })
                        .catch(err => {
                            console.error('Network/Parse Error:', err);
                            
                            // Reset button state
                            if (submitBtn) {
                                submitBtn.disabled = false;
                                submitBtn.textContent = originalBtnText;
                            }
                            
                            alert('Network error: ' + err.message + '\nPlease check your internet connection and try again.');
                        });
                } else {
                    // Not a server-submitted form: redirect to the thank you page
                    window.location.href = 'thank-you.html';
                }
            }
        });
        
        // Remove error on input
        const inputs = form.querySelectorAll('input, textarea');
        inputs.forEach(input => {
            input.addEventListener('input', function() {
                this.classList.remove('error');
                removeError(this);
            });
        });
    });
}

function isValidEmail(email) {
    const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return re.test(email);
}

function showError(input, message) {
    removeError(input);
    const error = document.createElement('span');
    error.className = 'error-message';
    error.textContent = message;
    error.style.color = 'red';
    error.style.fontSize = '0.875rem';
    error.style.marginTop = '0.25rem';
    error.style.display = 'block';
    input.parentElement.appendChild(error);
}

function removeError(input) {
    const error = input.parentElement.querySelector('.error-message');
    if (error) {
        error.remove();
    }
}

function showSuccessMessage(customMessage) {
    const messageText = customMessage || 'Form submitted successfully!';
    const message = document.createElement('div');
    message.className = 'success-message';
    message.innerHTML = `
        <div style="position: fixed; top: 100px; right: 20px; background: #10b981; color: white; padding: 1rem 1.5rem; border-radius: 0.5rem; box-shadow: 0 10px 15px -3px rgba(0,0,0,0.1); z-index: 9999; animation: slideIn 0.3s ease;">
            <i class="fas fa-check-circle"></i> ${messageText}
        </div>
    `;
    document.body.appendChild(message);
    
    setTimeout(() => {
        message.remove();
    }, 3000);
}

// Lazy loading images
function initLazyLoading() {
    const images = document.querySelectorAll('img[data-src]');
    
    const imageObserver = new IntersectionObserver((entries, observer) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const img = entry.target;
                img.src = img.dataset.src;
                img.removeAttribute('data-src');
                observer.unobserve(img);
            }
        });
    });
    
    images.forEach(img => imageObserver.observe(img));
}

// Initialize all features when DOM is ready
document.addEventListener('DOMContentLoaded', function() {
    // Initialize navigation first
    initializeNavigation();
    
    // Wait for DOM to be fully ready before initializing slider
    setTimeout(() => {
        initHeroSlider();
        initEnquiryPopup();
        initCertificateLightbox();
    }, 100);
    
    // Wait a bit for components to load
    setTimeout(() => {
        animateCounter();
        initScrollAnimations();
        initFormValidation();
        initLazyLoading();
        initFAQ();
        initGallery();
    }, 500);
});

// Certificate Lightbox functionality
function initCertificateLightbox() {
    const certCards = document.querySelectorAll('.certification-card-home');
    const lightbox = document.getElementById('certLightbox');
    const lightboxImg = document.getElementById('certLightboxImg');
    const closeBtn = document.querySelector('.cert-lightbox-close');
    
    if (!lightbox || !lightboxImg) return;
    
    certCards.forEach(card => {
        card.addEventListener('click', () => {
            const imgSrc = card.getAttribute('data-cert-img');
            lightboxImg.src = imgSrc;
            lightbox.classList.add('active');
            document.body.style.overflow = 'hidden';
        });
    });
    
    function closeLightbox() {
        lightbox.classList.remove('active');
        document.body.style.overflow = '';
    }
    
    if (closeBtn) {
        closeBtn.addEventListener('click', closeLightbox);
    }
    
    lightbox.addEventListener('click', (e) => {
        if (e.target === lightbox) {
            closeLightbox();
        }
    });
    
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape' && lightbox.classList.contains('active')) {
            closeLightbox();
        }
    });
}

// Enquiry Popup functionality
function initEnquiryPopup() {
    setTimeout(() => {
        const openButton = document.getElementById('openEnquiryPopup');
        const modalOverlay = document.getElementById('enquiryPopup');
        const closeButton = document.querySelector('.modal-close');
        const modalForm = document.getElementById('enquiryForm');
        
        // Open popup
        if (openButton && modalOverlay) {
            openButton.addEventListener('click', () => {
                modalOverlay.classList.add('active');
                document.body.style.overflow = 'hidden';
            });
        }
        
        // Close popup functions
        function closePopup() {
            if (modalOverlay) {
                modalOverlay.classList.remove('active');
                document.body.style.overflow = '';
            }
        }
        
        // Close on X button
        if (closeButton) {
            closeButton.addEventListener('click', closePopup);
        }
        
        // Close on overlay click
        if (modalOverlay) {
            modalOverlay.addEventListener('click', (e) => {
                if (e.target === modalOverlay) {
                    closePopup();
                }
            });
        }
        
        // Form submission is handled centrally in initFormValidation()
        
        // Close on Escape key
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape' && modalOverlay && modalOverlay.classList.contains('active')) {
                closePopup();
            }
        });
    }, 600);
}

// FAQ Accordion functionality
function initFAQ() {
    const faqItems = document.querySelectorAll('.faq-item');
    
    faqItems.forEach(item => {
        const question = item.querySelector('.faq-question');
        
        if (question) {
            question.addEventListener('click', () => {
                // Close other items
                faqItems.forEach(otherItem => {
                    if (otherItem !== item && otherItem.classList.contains('active')) {
                        otherItem.classList.remove('active');
                    }
                });
                
                // Toggle current item
                item.classList.toggle('active');
            });
        }
    });
}

// Gallery filter and lightbox functionality
function initGallery() {
    // Filter functionality
    const filterBtns = document.querySelectorAll('.filter-btn');
    const galleryItems = document.querySelectorAll('.gallery-item');
    
    filterBtns.forEach(btn => {
        btn.addEventListener('click', () => {
            // Remove active class from all buttons
            filterBtns.forEach(b => b.classList.remove('active'));
            // Add active class to clicked button
            btn.classList.add('active');
            
            const filter = btn.getAttribute('data-filter');
            
            galleryItems.forEach(item => {
                if (filter === 'all' || item.getAttribute('data-category') === filter) {
                    item.style.display = 'block';
                    setTimeout(() => {
                        item.style.opacity = '1';
                        item.style.transform = 'scale(1)';
                    }, 10);
                } else {
                    item.style.opacity = '0';
                    item.style.transform = 'scale(0.8)';
                    setTimeout(() => {
                        item.style.display = 'none';
                    }, 300);
                }
            });
        });
    });
    
    // Lightbox functionality
    const lightbox = document.getElementById('lightbox');
    const lightboxImg = document.getElementById('lightbox-img');
    const viewBtns = document.querySelectorAll('.view-btn');
    const lightboxClose = document.querySelector('.lightbox-close');
    
    if (lightbox && lightboxImg) {
        viewBtns.forEach(btn => {
            btn.addEventListener('click', (e) => {
                e.stopPropagation();
                const imgSrc = btn.getAttribute('data-image');
                lightboxImg.src = imgSrc;
                lightbox.style.display = 'block';
                document.body.style.overflow = 'hidden';
            });
        });
        
        if (lightboxClose) {
            lightboxClose.addEventListener('click', () => {
                lightbox.style.display = 'none';
                document.body.style.overflow = '';
            });
        }
        
        lightbox.addEventListener('click', () => {
            lightbox.style.display = 'none';
            document.body.style.overflow = '';
        });
        
        lightboxImg.addEventListener('click', (e) => {
            e.stopPropagation();
        });
    }
}

// Prevent console errors for missing elements
window.addEventListener('error', function(e) {
    if (e.message.includes('Cannot read')) {
        e.preventDefault();
    }
}, true);