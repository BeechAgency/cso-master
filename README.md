# CSO Master WordPress Theme

CSO Master is a premium WordPress theme designed specifically for the Catholic Schools Office of the Maitland-Newcastle Diocese. Built on the `_s` table boilerplate, it provides a robust foundation for diocese websites with a focus on performance and extensibility.

---

## 🚀 Requirements

- **PHP**: 8.1 or higher
- **Plugins**:
  - [Advanced Custom Fields PRO](https://www.advancedcustomfields.com/)
  - [Gravity Forms](https://www.gravityforms.com/)
  - [Classic Editor](https://wordpress.org/plugins/classic-editor/)

---

## 🔄 Theme Updater & Release Process

The theme features a built-in automated updater that connects to GitHub. This allows managed WordPress sites to receive updates directly through the WordPress dashboard.

### How it Works
The updater logic resides in `inc/template-updater.php`. It uses the `CatholicSchoolsMN_Master_Theme_Updater` class to:
1. Poll the GitHub API for the **latest release**.
2. Compare the `tag_name` from GitHub with the local `Version` in `style.css`.
3. Provide a secure download link via a GitHub Personal Access Token (PAT).
4. Automate the installation and folder cleanup.

### 🛠 The Release Workflow
To deploy a new version of the theme, follow these steps:

1. **Update Version**: Open `style.css` and update the `Version:` header (e.g., `1.0.5`).
2. **Commit Changes**: Commit your code changes and the updated `style.css`.
   ```bash
   git add .
   git commit -m "Brief description of changes"
   git push origin master
   ```
3. **Tag the Release**: Create a new git tag matching the version in `style.css`.
   ```bash
   git tag 1.0.5
   git push origin 1.0.5
   ```
4. **Automated Packaging**: Pushing the tag triggers a GitHub Action (`.github/workflows/package-theme.yml`) which:
   - Zips the theme files (excluding `.git`, etc.).
   - Creates a GitHub Release.
   - Attaches the `cso-master.zip` as a release asset.

Once the Action completes, the update will appear in the WordPress "Updates" dashboard for all sites using the theme.

### 🔑 Configuration
For the updater to work on private repositories, an update key must be configured:
1. Go to **Settings > General** in the WordPress Admin.
2. Locate the **CSMN Master Theme Update Key** field.
3. Enter a GitHub Personal Access Token (PAT) with `repo` scope.

> [!NOTE]
> **Security Note:** The current implementation relies on a shared GitHub PAT stored in the WordPress database. While functional for the current scale, it is recommended to transition to a more secure authentication method (like a dedicated proxy or GitHub App) in the future to avoid sharing personal tokens.

---

## 📦 Content Distribution (Distributor Helper)

The theme includes specialized logic in `inc/distributor-helper.php` to streamline content sharing via the [Distributor](https://distributor.it/) plugin. This is particularly important for posts originating from the **St Nicks** site.

### Key Logic & Automations
- **Content Merging**: Automatically detects ACF "Content Builder" fields from St Nicks and merges them into the main `post_content`. This ensures that distributed posts display all content correctly even if the receiving site doesn't have the same ACF structure.
- **Media Migration**: 
    - Downloads remote images (Galleries, Header Images, Featured Images) via the WordPress REST API.
    - Stores the original `dt_original_media_id` locally to prevent duplicate downloads.
    - **ID Remapping**: Dynamically scans post content to update gallery shortcode IDs to match the new local attachment IDs.
- **Automatic Styling**: Distributed posts are automatically assigned default header styling (Primary Dark background, White text, and Gradient enabled) via `update_field` during the distribution process.
- **Header Image Remapping**: Uses a delayed remap strategy (hooking into `acf/save_post`) to ensure the `header_image` field is correctly linked to the local media asset after it has been fully imported.

---

## 🎨 Styling & JavaScript

The theme follows a modular approach to frontend development, prioritizing performance and maintainability.

### Styling Architecture
- **CSS Strategy**: Styles are organized into functional modules within the `css/` directory and aggregated in the root `style.css` using `@import`.
- **Responsive Design**: Dedicated files (`_mobile.css`, `_tablet.css`) handle breakpoints using standard media queries. 
- **Typography**: Primary font is **Work Sans**, loaded via Google Fonts.

### JavaScript Development
- **Vanilla JS**: The theme avoids jQuery where possible, utilizing modern ES6+ vanilla JavaScript for components like FAQs, Tabs, and the custom Lightbox.
- **Performance Optimizations**:
    - **Deferring**: The main `blocks.js` script is deferred to improve the Critical Rendering Path.
    - **Lazy Loading**: Integrated with `lozad.min.js` for high-performance lazy loading of images and background assets.
- **Analytics & Tracking**: A global `AnalyticsHandler()` helper is available to push events to the `dataLayer` (GTM/GA4). Interaction tracking is built-in for sliders, FAQs, and tabs.
- **Sliders**: Powered by [Flickity](https://flickity.metafizzy.co/). Options are passed via `data-flickity-options` as JSON strings in the HTML markup.

---

## ⚓ Hooks & Extensibility

Developers can extend the theme without modifying core files using the following hooks:

| Hook Name | Description |
|-----------|-------------|
| `csomaster_header` | Renders the main theme header |
| `csomaster_before_header` | Content before the header tag |
| `csomaster_after_header` | Content after the header tag |
| `csomaster_set_custom_header_data` | Filter for modifying header data |

Check `inc/template-hooks.php` for the full implementation of these hooks.

---

## 🛠 Development Setup

The theme includes a "Development Mode" to assist with styling and debugging.

- **Enable Dev Mode**: Go to **Settings > General** and check **Enable Development Mode**.
- **Styles**: Main styles are located in `style.css` and the `css/` directory.
- **Scripts**: Custom logic is in `js/blocks.js` and `js/navigation.js`.

---

*Thank you for contributing to the CSO Master project!*
