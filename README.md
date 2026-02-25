# Dante Burba WordPress Theme
**Version:** 1.0.0  
**Author:** Dante Burba Diesel Injection Ltd.  
**Requires WordPress:** 6.0+  
**Requires PHP:** 8.0+  
**WooCommerce:** 7.0+ recommended  
**Elementor:** 3.0+ compatible  

---

## Installation

1. **Upload the theme**  
   Go to `Appearance → Themes → Add New → Upload Theme`, select `dante-burba-theme.zip` and click Install.

2. **Activate**  
   Click "Activate" on the theme listing page.

3. **Install required plugins**  
   - [WooCommerce](https://wordpress.org/plugins/woocommerce/) — for e-commerce functionality  
   - [Elementor](https://wordpress.org/plugins/elementor/) — for drag-and-drop page building  
   - [Elementor Pro](https://elementor.com/pro/) *(optional)* — for header/footer templates

4. **Set up your homepage**  
   - Create a page and set it as the static front page under `Settings → Reading`.  
   - The theme's `front-page.php` template will automatically render the full homepage.

5. **Configure navigation menus**  
   Go to `Appearance → Menus` and assign menus to:
   - **Primary Navigation** — Desktop nav
   - **Mobile Navigation** — Mobile slide-out nav
   - **Footer Links** — Footer nav

---

## Customizer Options

Go to `Appearance → Customize → Dante Burba Theme` to configure:

| Section       | Options |
|---------------|---------|
| **Brand**     | Accent colour, nav tagline |
| **Hero**      | Badge text, headline lines, subheading, background image |
| **Stats Strip** | 4 stat numbers and labels |
| **Contact Info** | Address, phone, email, hours |
| **Social Links** | Instagram, LinkedIn, Facebook URLs |

---

## WooCommerce Setup

1. Run the WooCommerce setup wizard after activation.
2. The shop page uses the theme's custom product grid automatically.
3. Product categories appear as filter tabs on the shop page.
4. The cart drawer is injected via `wp_footer` — no page builder needed.

### Product Card Features
- Diagonal cut-corner category badge
- Bottom-edge orange accent bar on hover
- Add-to-cart AJAX (no page reload)
- Quick-view modal with product description

---

## Elementor Integration

The theme is fully compatible with Elementor and Elementor Pro:

- Custom fonts (Bebas Neue, DM Sans, DM Mono) registered with Elementor
- CSS variables available globally in the editor
- Location API registered — assign Elementor templates to header/footer/archive
- If using Elementor Pro's Theme Builder, you can replace `front-page.php` with a full Elementor page

### Recommended Elementor global colors:
```
--db-orange   #FF3D00  (Accent)
--db-surface  #0A0A0B  (Background Dark)
--db-surface-2 #111113 (Surface 2)
```

---

## File Structure

```
dante-burba-theme/
├── style.css                   Theme header + all CSS
├── functions.php               Loads includes
├── index.php                   Fallback template
├── front-page.php              Homepage template
├── page.php                    Generic page
├── 404.php                     Not found
├── woocommerce.php             WooCommerce wrapper
├── header.php                  Global header + nav
├── footer.php                  Global footer
├── inc/
│   ├── theme-setup.php         Theme support declarations
│   ├── enqueue.php             Scripts & styles
│   ├── nav-menus.php           Menu registration + walker
│   ├── customizer.php          Customizer settings
│   ├── template-functions.php  Template helpers
│   ├── woocommerce.php         WooCommerce hooks
│   └── elementor.php           Elementor compatibility
├── woocommerce/
│   ├── archive-product.php     Shop grid page
│   └── content-product.php     Individual product card
└── assets/
    ├── css/
    │   ├── woocommerce.css     WooCommerce styles
    │   └── editor.css          Block editor styles
    └── js/
        └── main.js             Cursor, nav, modal, cart, animations
```

---

## Customisation Notes

### Changing the accent colour
Either use the Customizer (`Dante Burba → Brand → Accent Colour`) or override in your child theme:
```css
:root { --db-orange: #FF6600; }
```

### Adding custom product categories
Products in WooCommerce will automatically appear in filter tabs on the shop page if they have assigned categories.

### Elementor page for homepage
If you prefer full Elementor control over the homepage, create an Elementor page and set it as the front page. The theme header/footer will still wrap it correctly.

---

## Support

For theme support and customisation, contact: info@danteburba.com
