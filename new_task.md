[DONE]

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

### 2.4. After the user signs-up, will be redirected to a page where to set a , which the user can skip and when skipped, must generate a username according to the user's id (FORMAT: "User_"+(000000 + user_id[This is math so it must have 6 digits. Example: User with a user_id of 1 must have the Username of User_000001]))


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

#### 5.2.1. Users can change/upload a new profile picture. (NEW)

#### 5.2.2. Users can see and update their information here. We will make the structure dynamic so it would adjust if we have an addition to the database.(Keep the structure as we will yet to add a table in the database for the user profiles.(I will add it myself so make the dynamic structure first and only display the available informations from the users table for now.))

#### This is where the user can also access the forgot password page through a button.

#### 5.2.3. The user can have the ability to delete their account.(soft delete) - refer the database as i added a column 'is_archived' with a BOOLEAN value in the users table. (NEW)

## 6. General

### 6.1. Design Renovation to B/W Glassmorphism (UPGRADE)

### 6.2. Buttons and other elements will emit different colors when hovered according to the type of button/element. (UPGRADE)

# NOTE: CHECK DATABASE FIRST BEFORE DOING ANYTHING. I WANT TO SEE THE DATABASE STRUCTURE FIRST.
