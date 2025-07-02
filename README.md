# Jocelyne Saab WordPress Theme

A custom WordPress theme for the Association Jocelyne Saab website (jocelynesaab.org), designed to showcase the legacy and work of the pioneering Lebanese filmmaker and artist.

## 🎯 Features

### Core Functionality
- **Custom Post Types**: Film, Workshop, and Screening with comprehensive meta fields
- **Multilingual Support**: Full Polylang/WPML compatibility with RTL language support
- **Mobile-First Design**: Responsive layout optimized for all devices
- **Accessibility**: WCAG 2.1 AA compliant with ARIA labels and keyboard navigation
- **Performance Optimized**: Lazy loading, optimized images, and efficient code structure

### Design & UX
- **Hero Section**: Fullscreen hero with video/background image support and animated tagline "Protect. Restore. Promote."
- **Navigation**: Fullscreen mobile-first overlay menu with dynamic sections
- **Typography**: IBM Plex Sans Arabic for RTL languages, Epilogue for headings, Lora for body text
- **Color Scheme**: Professional palette inspired by Prince Claus Fund design principles

### Custom Post Types

#### Films
- Comprehensive film metadata (year, duration, director, DOP, editor, synopsis)
- Film stills gallery with Swiper.js carousel
- Video embedding (YouTube, Vimeo, direct uploads)
- Credits management
- Featured films functionality

#### Workshops
- Workshop details (date, location, duration, trainers, capacity)
- Learning objectives and schedule
- Registration system integration
- Workshop gallery
- Upcoming/past filtering

#### Screenings
- Screening information (date, time, location, film, type)
- Upcoming and past screenings with year grouping
- Ticket purchasing integration
- Related screenings sidebar

### Archive Logic
- **Screenings**: Split into upcoming (future dates) and past (grouped by year)
- **Workshops**: Filterable by upcoming/past with interactive JavaScript filtering
- **Films**: Featured films on homepage with fallback to latest films

## 🛠 Technical Implementation

### File Structure
```
jocelynesaabdesign/
├── assets/
│   ├── css/
│   │   └── main.css          # Complete responsive CSS with RTL support
│   └── js/
│       └── main.js           # Modern ES6+ JavaScript with accessibility
├── template-parts/
│   ├── film-card.php         # Film display component
│   ├── screening-card.php    # Screening display component
│   ├── workshop-card.php     # Workshop display component
│   ├── news-card.php         # News display component
│   ├── partner-card.php      # Partner display component
│   └── hero.php              # Hero section component
├── functions.php             # Theme setup, CPTs, meta boxes
├── functions-seo.php         # SEO optimization and structured data
├── header.php                # Header with navigation and language switcher
├── footer.php                # Footer with newsletter and social links
├── index.php                 # Homepage with hero and featured content
├── single-film.php           # Individual film template
├── single-workshop.php       # Individual workshop template
├── single-screening.php      # Individual screening template
├── archive-screening.php     # Screenings archive with tabs
├── archive-workshop.php      # Workshops archive with filters
├── archive-film.php          # Films archive
└── package.json              # Build tools configuration
```

### Custom Post Types & Meta Fields

#### Film CPT
- `_saab_film_year` - Film production year
- `_saab_film_duration` - Film duration
- `_saab_film_director` - Director name
- `_saab_film_dop` - Director of Photography
- `_saab_film_editor` - Editor name
- `_saab_film_synopsis` - Film synopsis
- `_saab_film_video_url` - Video URL (YouTube/Vimeo/direct)
- `_saab_film_stills` - Film stills gallery
- `_saab_film_credits` - Credits array
- `_saab_film_format` - Film format
- `_saab_featured` - Featured film flag

#### Workshop CPT
- `_saab_training_workshop_date` - Workshop date
- `_saab_training_workshop_location` - Workshop location
- `_saab_training_workshop_duration` - Workshop duration
- `_saab_training_workshop_trainers` - Trainer names
- `_saab_training_workshop_type` - Workshop type
- `_saab_training_workshop_capacity` - Participant capacity
- `_saab_training_workshop_objectives` - Learning objectives
- `_saab_training_workshop_schedule` - Workshop schedule
- `_saab_training_workshop_materials` - Required materials
- `_saab_training_workshop_gallery` - Workshop gallery
- `_saab_training_workshop_registration` - Registration URL
- `_saab_training_workshop_contact` - Contact information
- `_saab_training_workshop_project_manager` - Project manager

#### Screening CPT
- `_saab_screening_date` - Screening date
- `_saab_screening_time` - Screening time
- `_saab_screening_location` - Screening location
- `_saab_screening_film` - Film being screened
- `_saab_screening_type` - Screening type
- `_saab_screening_duration` - Event duration
- `_saab_screening_tickets` - Ticket purchasing URL
- `_saab_screening_contact` - Contact information
- `_saab_screening_additional_info` - Additional information

### Multilingual Support
- `load_theme_textdomain('saab', get_template_directory() . '/languages')`
- Polylang/WPML language switcher in header
- RTL language support with IBM Plex Sans Arabic font
- Proper text domain usage throughout theme

### Accessibility Features
- ARIA labels for navigation, forms, and interactive elements
- Keyboard navigation support
- Skip to content link
- Focus management for modals and overlays
- Screen reader friendly markup
- High contrast mode support
- Reduced motion preferences respected

### Performance Optimizations
- Lazy loading for images
- Optimized image sizes for different contexts
- Efficient CSS with CSS custom properties
- Modern JavaScript with debouncing and throttling
- Structured data for SEO
- Optimized font loading

## 🚀 Installation & Setup

1. **Upload Theme**: Upload the theme files to `/wp-content/themes/jocelyne-saab/`
2. **Activate Theme**: Activate the theme in WordPress admin
3. **Install Dependencies**: Run `npm install` for build tools
4. **Build Assets**: Run `npm run build` to compile CSS and JS
5. **Configure Customizer**: Set up hero content, colors, and typography
6. **Create Content**: Add films, workshops, and screenings
7. **Set Up Menus**: Configure primary, footer, and social menus

## 🎨 Customization

### Theme Customizer Options
- Hero video/image URL
- Hero title and subtitle
- Rotating text for hero section
- Footer about text and links
- Social media links
- Copyright information

### CSS Custom Properties
```css
:root {
  --brand-cream: #fcefbd;
  --brand-yellow: #ffe54b;
  --brand-gold: #f0cc00;
  --brand-blue: #3269ff;
  --brand-indigo: #6476d1;
  --brand-navy: #1c2f7c;
  --brand-midnight: #10284a;
  --font-heading: 'Epilogue', sans-serif;
  --font-body: 'Lora', serif;
  --font-arabic: 'IBM Plex Sans Arabic', sans-serif;
}
```

### Build Process
```bash
# Development
npm run dev

# Production build
npm run build

# CSS only
npm run sass:build

# JavaScript only
npm run js:build

# Linting
npm run lint:css
npm run lint:js
```

## 📱 Responsive Breakpoints
- **Mobile**: < 480px
- **Tablet**: 481px - 768px
- **Desktop**: 769px - 1024px
- **Large Desktop**: > 1024px

## 🌐 Browser Support
- Chrome (latest 2 versions)
- Firefox (latest 2 versions)
- Safari (latest 2 versions)
- Edge (latest 2 versions)
- Internet Explorer 11+

## 📄 License
GPL v2 or later

## 👨‍💻 Developer
**Karl Serag** - [GitHub](https://github.com/karlserag)

## 🔄 Version History
- **v1.0.1** - Fixed meta box errors, improved accessibility
- **v1.0.0** - Initial release with core functionality

---

*This theme is designed to honor the legacy of Jocelyne Saab and provide a modern, accessible platform for showcasing her work and the Association's activities.*

