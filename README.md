# EJ LiteAccordion (Joomla 4/5/6 Module)

A Joomla module that displays articles as a horizontal **LiteAccordion** image slider (jQuery-based), using Nicola Hibbert’s **liteAccordion v2.2.0**. Images, titles and optional caption elements (date, author, intro, readmore, thumbnail) are pulled from Joomla content.  

Ported and modernized for Joomla 4/5/6 by **Andreas Kontarinis** (original Joomla 3 module by **Element J**).

---

## Features

- Pulls items from **com_content** (Articles) with **category filtering** and **ordering**
- Displays a horizontal accordion slider powered by **liteAccordion 2.2.0**
- Configurable:
  - number of items
  - ordering (publish date / create date / ordering / random)
  - theme (dark/light/stitch/basic) or custom class
  - autoplay, pause on hover, speeds, easing, rounded corners, numbering
- Captions (optional, per element):
  - image caption (from article image JSON caption)
  - title (optionally linked)
  - date (language-aware Joomla date formats + custom format)
  - author
  - intro text (with optional fallback to fulltext)
  - read more (with optional “always show”)
  - thumbnail inside caption (optional fallback)
- **Placeholder support** when an item has no image (background color / css / image)
- Responsive handling: recalculates width only on **real width changes** (prevents mobile scroll “jump”)

---

## Requirements

- Joomla **4.x / 5.x / 6.x**
- PHP **8.0+** recommended
- A template that loads Bootstrap is fine, but not required
- jQuery:
  - The module can use Joomla’s built-in jQuery (`jquery.framework`) if enabled
  - liteAccordion depends on jQuery (and easing file provided)

---

## Installation

### Option A — Install as ZIP (recommended)
1. Download or build the installable ZIP from this repository.
2. In Joomla Administrator: **System → Install → Extensions**
3. Upload the ZIP.
4. Go to **Content → Site Modules**, create a new module instance: **EJ LiteAccordion**.

### Option B — Manual install (development)
Copy files into:
- `modules/mod_ej_liteaccordion/`
- `media/mod_ej_liteaccordion/`

Then install via Joomla Extension Manager (or use a packaged ZIP).

---

## Files & Structure

- `mod_ej_liteaccordion.php` — module entry point (loads assets, gets list, calls layout)
- `helper.php` — retrieves articles list
- `tmpl/default.php` — rendering + JS init code
- `media/mod_ej_liteaccordion/css/liteaccordion.css` — core slider styles
- `media/mod_ej_liteaccordion/js/liteaccordion.jquery.min.js` — liteAccordion plugin
- `media/mod_ej_liteaccordion/js/easing.js` — easing functions
- Language files:
  - `language/en-GB/en-GB.mod_ej_liteaccordion.ini`
  - `language/en-GB/en-GB.mod_ej_liteaccordion.sys.ini`
  - `language/el-GR/el-GR.mod_ej_liteaccordion.ini`
  - `language/el-GR/el-GR.mod_ej_liteaccordion.sys.ini`

---

## Configuration (Module Options)

### Basic
- **Count**: how many articles to show
- **Ordering**: publish date / create date / ordering / random
- **Categories**: select one or more article categories
- Slider behavior: autoplay, pause on hover, speeds, easing, rounded, header width, numbering
- Theme: predefined theme or **custom class**

### Captions
Enable captions and then control each part:
- image caption, title (linked or not), date format, author, intro, thumbnail, read more

### Placeholder (when no image)
Used when an article has no image:
- placeholder background color / css
- placeholder image file (optional)

### Advanced
- Optional “load jQuery” toggle (use Joomla’s framework)

---

## Notes / Known Limitations

- liteAccordion **2.2.0** is an older library (2013). It works well, but it’s not a modern touch-optimized slider.
- If you need true mobile swipe support, consider migrating to a modern slider library.
- Styling is intentionally split:
  - static CSS in `media/.../liteaccordion.css`
  - admin-entered per-instance CSS via module params (inline output)

---

## Credits

- **liteAccordion v2.2.0** by **Nicola Hibbert** (MIT License)  
  Original project: http://nicolahibbert.com/liteaccordion-v2/ (historical)
- Original Joomla 3 module by **Element J**
- Joomla 4/5/6 port and modernization by **Andreas Kontarinis**

---

## License

This module is released under the **GNU GPL v2 or later** (as per Joomla extension norms).  
Included third-party JS (liteAccordion) is MIT licensed—see header in the JS file.

---

## Support / Issues

If you find a bug or want an improvement:
- Open a GitHub issue with:
  - Joomla version
  - PHP version
  - template name
  - screenshots / console errors
  - module settings export (if possible)

---
