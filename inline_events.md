# Phase 8: Inline JavaScript Events Documentation

As part of adhering to the academic requirement, 10 distinct, native inline JavaScript fallback event listeners (`on[event]="function()"`) were implemented across the DynaBio frontend. This documentation breaks down the exact file locations, elements, and functions assigned to each of the 10 events.

## 1. Dashboard View (`views/dashboard.php`)
The dashboard implements three interactive visual events on the header objects.

### Event 1 & 2: `onmouseover` / `onmouseout`
- **Location:** `<img alt="Profile" onmouseover="zoomProfile(this)" onmouseout="unzoomProfile(this)">`
- **Functionality:** Zooms the user's profile picture by exactly 10% smoothly when the mouse hovers over it, and snaps it back to normal dimensions when the mouse leaves.

### Event 3: `ondblclick`
- **Location:** `<h1 ondblclick="highlightWelcome(this)">Welcome, ...</h1>`
- **Functionality:** Captures a double-click on the giant Welcome header text. Triggers a diagnostic `alert` and forces the text shadow and font color to instantly adopt the underlying `<html data-theme="xyz">` primary CSS variable color.

## 2. Editor View (`views/editor.php`)
The editor implements four interactive form validation events native to typical inputs.

### Event 4 & 5: `onfocus` / `onblur`
- **Location:** `<input type="text" id="tagline" onfocus="highlightInput(this)" onblur="removeHighlight(this)">`
- **Functionality:** Listens for the user explicitly clicking into the Professional Tagline input field, shading the entire background a translucent blue. Leaving the field (`onblur`) instantly resets the background style securely.

### Event 6: `onkeyup`
- **Location:** `<textarea id="about_me" onkeyup="countChars(this)">`
- **Functionality:** Fires every single time a keyboard key is released while typing the story. It reads the length of the string and manually updates the `div#charCount` node with live counting statistics.

### Event 7: `onmousedown`
- **Location:** `<button type="submit" id="saveDraftBtn" onmousedown="showProcessing(this)">`
- **Functionality:** Intercepts exactly when the mouse click is *depressed* on the Global UI Save button. Instantly swaps out the floppy disk icon for a spinning FontAwesome loader and changes the text to "Processing...".

## 3. Public Portfolio View (`views/public.php`)
The portfolio view implements three passive browser-level utility hooks on the HTML body.

### Event 8: `onload`
- **Location:** `<body onload="logGreeting()">`
- **Functionality:** Fires immediately once the entire portfolio DOM successfully resolves mounting to the screen, logging "Portfolio Successfully Loaded! Welcome to DynaBio Engine" silently to the developer console.

### Event 9: `oncopy`
- **Location:** `<body ... oncopy="warnCopy()">`
- **Functionality:** Identifies if a viewer uses `CTRL+C` (or right-click -> Copy) anywhere inside the public bio, raising a defensive browser-enforced `alert()` requesting them to respect intellectual property.

### Event 10: `oncontextmenu`
- **Location:** `<body ... oncontextmenu="protectContent(event)">`
- **Functionality:** Completely overrides the native browser right-click context menu by wrapping `e.preventDefault()`, halting "Save Image As" capabilities directly over the user's portfolio and throwing a privacy dialogue.

## 4. Extended Technical Events
The following 5 events were added to demonstrate more complex, programmatic inline logic dealing with file inputs, scroll depth, and string manipulation.

### Event 11: `onpaste`
- **Location:** `views/editor.php` -> `<input id="github_username" onpaste="sanitizeGithubPaste(event, this)">`
- **Functionality:** Intercepts clipboard paste directly. If a user tries to paste a full URL (e.g., `https://github.com/torvalds`), it waits 10ms for the paste to resolve in the DOM, then automatically strips the URL string, leaving only the raw username `"torvalds"`.

### Event 12: `oninput`
- **Location:** `views/editor.php` -> `<input id="full_name" oninput="autoCapitalize(this)">`
- **Functionality:** Fires on every single keystroke. Uses a Regex replacement algorithm (`/\b\w/g`) to dynamically force pure Title Case formatting across the user's Full Name string while they type.

### Event 13: `onchange`
- **Location:** `views/profile.php` -> `<input type="file" id="photoInput" onchange="previewUpload(this)">`
- **Functionality:** Immediately triggers when the user selects an image from their OS dialog box. Instead of blindly submitting, it invokes the browser's `FileReader` API natively to instantly swap the `<img src>` attribute with a live base64 client-side preview of their new avatar.

### Event 14: `onerror`
- **Location:** `views/public.php` -> `<img class="avatar" src="..." onerror="fallbackImage(this)">`
- **Functionality:** Advanced fault tolerance. If the user's `$photo` MySQL database string points to a corrupted or deleted image file, the browser `<img onerror>` listener catches the 404 response natively and hot-swaps the `src` to `user-placeholder.png` rather than showing a broken layout.

### Event 15: `onscroll`
- **Location:** `views/public.php` -> `<body onscroll="handlePublicScroll()">`
- **Functionality:** A low-level performance listener attached to the viewport body. Reads `window.scrollY` and directly manipulates the CSS transform attribute of the User's Avatar image, creating a programmatic parallax rotation effect as visitors scroll down the public portfolio.

### Event 16: `onkeydown`
- **Location:** `views/editor.php` -> `<input id="weight" onkeydown="preventLetters(event)">`
- **Functionality:** Form guard mechanism. Intercepts raw keyboard scan codes before they print to the `<input>`. If the key pressed corresponds to an alphabet letter or special symbol, it fires `e.preventDefault()`, mathematically blocking the user from entering anything except numeric values for their weight.

### Event 17: `onfocusout`
- **Location:** `views/editor.php` -> `<input id="height" onfocusout="appendCm(this)">`
- **Functionality:** Triggers the moment the user tabs away or clicks out of the focal point of the height input. If it detects raw numbers, it artificially injects the string "cm" onto the end to enforce metric standardization.

### Event 18: `ondragstart`
- **Location:** `views/editor.php` -> `<div class="icon-option" draggable="true" ondragstart="dragIcon(event)">`
- **Functionality:** Binds native HTML5 drag-and-drop mechanics to the Journey Timeline icons. When the user clicks and pulls an icon, it registers the custom data attribute inside `event.dataTransfer` and lowers the node's visual opacity to ghost it.

### Event 19: `ondrop` (and `ondragover`)
- **Location:** `views/editor.php` -> `<div class="icon-selector" ondrop="dropIcon(event)" ondragover="allowDropIcon(event)">`
- **Functionality:** Defines a valid dropzone for the dragged icons. Releasing the mouse block fires `ondrop`, intercepts the payload from `dataTransfer`, and natively alerts the UI that an element payload was successfully sorted via the browser engine.

### Event 20: `onresize`
- **Location:** `views/dashboard.php` -> `<body onresize="logResize()">`
- **Functionality:** Continuously streams real-time client viewport dimensions width x height into the developer console whenever the browser window boundaries are dragged. Useful for responsive layout diagnostics.
