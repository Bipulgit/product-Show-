# Modern Responsive Website

A professional, fully responsive website built with HTML, CSS, and JavaScript featuring modular components and mobile-first design.

## Features

- **Fully Responsive**: Optimized for all devices (mobile, tablet, desktop)
- **Modular Components**: Reusable header, footer, and head components
- **Modern Design**: Clean, professional UI with smooth animations
- **Fast Performance**: Optimized assets and lazy loading
- **Accessible**: ARIA labels and semantic HTML
- **SEO Friendly**: Proper meta tags and structure

## Project Structure

```
project/
├── components/
│   ├── head.html       # Meta tags, favicon, fonts, CSS links
│   ├── header.html     # Navigation bar
│   └── footer.html     # Footer with scripts
├── css/
│   └── new-style.css   # Main stylesheet with responsive design
├── js/
│   ├── components.js   # Component loader
│   └── new-main.js     # Interactive features
├── img/
│   └── (images)
├── new-index.html      # Home page
└── new-contact.html    # Contact page
```

## Components

### Head Component (`components/head.html`)
- Meta tags for SEO
- Favicon links
- Google Fonts (Poppins, Roboto)
- Font Awesome icons
- CSS stylesheet link

### Header Component (`components/header.html`)
- Responsive navigation bar
- Mobile hamburger menu
- Logo and brand
- Call-to-action button
- Active page indicator

### Footer Component (`components/footer.html`)
- Company information
- Quick links
- Services list
- Contact information
- Social media links
- Copyright notice
- Back-to-top button
- JavaScript includes

## Pages

### Home Page (`new-index.html`)
- Hero section with CTA
- Features grid (6 features)
- Services showcase
- Statistics counter
- Client testimonials
- Call-to-action section

### Contact Page (`new-contact.html`)
- Page header
- Contact information
- Contact form with validation
- Google Maps integration

## CSS Features

### Responsive Breakpoints
- Desktop: > 1024px
- Tablet: 768px - 1024px
- Mobile: < 768px
- Small Mobile: < 480px

### Key Features
- CSS Variables for easy theming
- Mobile-first approach
- Flexbox and Grid layouts
- Smooth transitions and animations
- Box shadows and gradients
- Print styles

## JavaScript Features

### Components.js
- Dynamic component loading
- Active navigation link detection
- Error handling

### Main.js
- Mobile menu toggle
- Smooth scrolling
- Back-to-top button
- Animated counters
- Scroll animations
- Form validation
- Lazy loading images
- Intersection Observer for performance

## Getting Started

1. **Setup**
   ```bash
   # Clone or download the project
   # No build process required!
   ```

2. **Local Development**
   - Open `new-index.html` in a web browser
   - Or use a local server (recommended):
   ```bash
   # Python
   python -m http.server 8000
   
   # Node.js
   npx serve
   ```

3. **Customize**
   - Update colors in CSS variables
   - Replace logo and images in `img/` folder
   - Modify content in HTML files
   - Add more pages following the same structure

## Browser Support

- Chrome (latest)
- Firefox (latest)
- Safari (latest)
- Edge (latest)
- Mobile browsers (iOS Safari, Chrome Mobile)

## Performance

- Lazy loading images
- Minimal dependencies (Font Awesome via CDN)
- Optimized CSS with modern features
- Efficient JavaScript with event delegation
- No jQuery or heavy frameworks

## Customization

### Colors
Edit CSS variables in `css/new-style.css`:
```css
:root {
    --primary-color: #6366f1;
    --secondary-color: #f59e0b;
    /* ... more variables */
}
```

### Fonts
Update Google Fonts link in `components/head.html`

### Logo
Replace logo images in header and footer components

## Best Practices

- Semantic HTML5
- BEM-like CSS naming
- Mobile-first responsive design
- Accessibility features
- Performance optimization
- Clean, maintainable code

## Future Enhancements

- [ ] Add more pages (About, Services, Blog)
- [ ] Implement dark mode
- [ ] Add more animations
- [ ] Create admin dashboard
- [ ] Integrate backend API
- [ ] Add multi-language support

## License

Free to use for personal and commercial projects.

## Support

For questions or issues, contact: info@yourbrand.com

---

**Built with ❤️ using HTML, CSS, and JavaScript**