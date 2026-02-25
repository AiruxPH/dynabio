# I am developing a PHP/JavaScript project called DynaBio. I need help implementing three specific enhancements using my existing architecture:

## Image Compression: 
### Modify my [action_profile.php](file:///C:/Users/ASUS/Desktop/dynabio/user/action_profile.php) logic. When a user uploads a photo, use the PHP GD library to resize the image to a max width of 500px (maintaining aspect ratio) and save it as a compressed JPEG to optimize performance.

## DNS Email Validation: 
### In my [action_signup.php](file:///C:/Users/ASUS/Desktop/dynabio/auth/action_signup.php), after validating the email format, add a server-side check using checkdnsrr() to verify that the email's domain has valid MX records before sending a verification code.

## Live Theme Preview: 
### In my [dashboard.php](file:///C:/Users/ASUS/Desktop/dynabio/views/dashboard.php) and [dashboard.js](file:///C:/Users/ASUS/Desktop/dynabio/js/views/dashboard.js), implement a hover effect. When a user hovers over a .theme-option card, temporarily update the data-theme attribute on the <html> tag to match the card's data-theme-id. Revert it when the mouse leaves, unless they click to save.

# Please provide the code snippets in a way that integrates with a PDO-based backend and a glassmorphism frontend.