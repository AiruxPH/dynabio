# NEW  (UPGRADE/FIX)

## 1. LOGIN PAGE

### 1.1. Input fields should not do an auto-fill. (FIX)

### 1.2. I noticed that the input fields when auto-filled, the border-color is not the same as the border-color of the input fields when not auto-filled. (FIX)

### 1.3. The password field should be hidden by default. (FIX)

### 1.4. Add a custom eye icon to the password field to show and hide the password. Please use font awesome for the icon. (UPGRADE)

### 1.5. Remove the native eye icon from the password field. We already have a custom one prior to the upgrade. (FIX)

### 1.6. When the user do not click the Remember me checkbox, the user's session should be terminated once the user closes the browser. (FIX)

## 2. SIGNUP PAGE

### 2.1. If the user is already logged in, the user should not be able to access the signup page. (FIX)

### 2.2. During the email verification:

#### 2.2.1. The user can be able to resend the verification within a set time limit. (UPGRADE)

#### 2.2.2. The user can be able to request for a new verification email if the previous one was not received or if the user submitted a wrong email address. (UPGRADE)

### 2.3. During the password field:

#### 2.3.1. The password field should be hidden by default. (FIX)

#### 2.3.2. Add a custom eye icon to the password field to show and hide the password. Please use font awesome for the icon. (UPGRADE)

#### 2.3.3. Remove the native eye icon from the password field. We already have a custom one prior to the upgrade. (FIX)

#### 2.3.4. There should a strength indicator for the password field. (UPGRADE)

- The strength indicator should be a bar that changes color based on the strength of the password. (UPGRADE)
- The standard requirement of a password is at least 8 characters long, with at least one uppercase letter, one lowercase letter, one number, and one special character. (UPGRADE)
- The strength indicator should be hidden by default. (UPGRADE)
- The strength indicator should be visible when the user is typing in the password field. (UPGRADE)
- The strength indicator should update the user's password strength as they type. (UPGRADE)
- There should be a message that tells the user the requirements of a password. (UPGRADE)
- The message should be hidden by default. (UPGRADE)
- The message should be visible when the user is typing in the password field. (UPGRADE)
- The message should update the user's password strength as they type. (UPGRADE)

## 3. Forgot Password Page

### 3.1. Same as the signup page's 2.2 and 2.3. (UPGRADE)

### 3.2. If the entered email is not found in the database, it should display an error message saying that the email is not found or does not have an account affiliated with it. (FIX)

## 4. During verification page (auth/verify.php)

### 4.1. Add a timer for the resend verification email button. (UPGRADE)

### 4.2. The user should be able to request for a new verification email if the previous one was not received or if the user submitted a wrong email address. (UPGRADE)

### 4.3. Add a Back to login page button. (UPGRADE)

## 5. PROFILE PAGE [profile.php] (NEW)

### 5.1. This is where the user can manage their profile. (NEW)

### 5.2. Features:

#### 5.2.1. Users can change/upload a new profile picture.

#### 5.2.2. Users 
