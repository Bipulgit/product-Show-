/**
 * Component Loader
 * Dynamically loads header, footer, and head components
 */

document.addEventListener('DOMContentLoaded', function() {
    // Load components
    loadComponent('HEAD_COMPONENT', 'components/head.html', 'head');
    loadComponent('HEADER_COMPONENT', 'components/header.html');
    loadComponent('FOOTER_COMPONENT', 'components/footer.html');
    loadComponent('ENQUIRY_POPUP_COMPONENT', 'components/enquiry-popup.html');
    
    /**
     * Load HTML component
     * @param {string} placeholder - Placeholder text in HTML
     * @param {string} componentPath - Path to component file
     * @param {string} insertLocation - Where to insert (default: replace)
     */
    function loadComponent(placeholder, componentPath, insertLocation = 'replace') {
        fetch(componentPath)
            .then(response => {
                if (!response.ok) {
                    throw new Error(`Failed to load ${componentPath}`);
                }
                return response.text();
            })
            .then(data => {
                if (insertLocation === 'head') {
                    // Insert into head
                    document.head.insertAdjacentHTML('beforeend', data);
                } else {
                    // Find and replace placeholder comment
                    const comments = document.createNodeIterator(
                        document.body,
                        NodeFilter.SHOW_COMMENT
                    );
                    
                    let currentComment;
                    while (currentComment = comments.nextNode()) {
                        if (currentComment.nodeValue.trim() === placeholder) {
                            const temp = document.createElement('div');
                            temp.innerHTML = data;
                            while (temp.firstChild) {
                                currentComment.parentNode.insertBefore(temp.firstChild, currentComment);
                            }
                            currentComment.parentNode.removeChild(currentComment);
                            break;
                        }
                    }
                }
                
                // Initialize navigation after header is loaded
                if (placeholder === 'HEADER_COMPONENT') {
                    setTimeout(() => {
                        if (typeof initializeNavigation === 'function') {
                            initializeNavigation();
                        }
                        setActiveNavLink();
                    }, 100);
                }
            })
            .catch(error => {
                console.error('Error loading component:', error);
            });
    }
    
    /**
     * Set active navigation link based on current page
     */
    function setActiveNavLink() {
        const currentPage = window.location.pathname.split('/').pop() || 'index.html';
        const navLinks = document.querySelectorAll('.nav-link');
        
        navLinks.forEach(link => {
            const href = link.getAttribute('href');
            if (href === currentPage || (currentPage === '' && href === 'index.html')) {
                link.classList.add('active');
            } else {
                link.classList.remove('active');
            }
        });
    }
});