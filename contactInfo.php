<?php
// Contact Info Form (Name, phone, email)

// SHOULD BE DONE

function clean($key, $type = 'string', $src = INPUT_POST)
{
    $filter = FILTER_SANITIZE_SPECIAL_CHARS;
    $options = 0;

    if ($type === 'email') {
        $filter = FILTER_SANITIZE_EMAIL;
    } 
    
    $value = filter_input($src, $key, $filter, $options);

    return trim($value ?? '');
}

function esc($val)
{
    return htmlspecialchars((string)$val, ENT_QUOTES, 'UTF-8');
}

$firstName = "";
$lastName = "";
$phone = "";
$email = "";
$errors = []; 
$success = ""; 

// runs if user presses submit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $firstName = clean('firstName');
    $lastName = clean('lastName'); 
    $phone = clean('phone');
    $email = clean('email', 'email');

    // Field Validation
    if(empty($firstName))
    {
        $errors[] =  "First name is required.";
    }
    if(empty($lastName))
    {
        $errors[] =  "Last name is required.";
    }
    if (empty($phone)) {
        $errors[] = "Phone number is required.";
    } else {
        // Remove anything that isn't a digit
        $digits = preg_replace('/\D/', '', $phone);

        // Check if it has exactly 10 digits
        if (strlen($digits) !== 10) {
            $errors[] = "Phone number must be a 10-digit U.S. number.";
        } else {
            // Format it as ###-###-####
            $phone = substr($digits, 0, 3) . '-' . substr($digits, 3, 3) . '-' . substr($digits, 6, 4);
        }
    }

    if (empty($email)) {
        $errors[] = "Email is required";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email format";
    }

    if (empty($errors))
    {
        $success = "Contact Info Accepted. Name: " .esc($firstName).  " " .esc($lastName). " Phone: " .esc($phone). " Email: " .esc($email);
        
        $firstName = "";
        $lastName = ""; 
        $phone = "";
        $email = "";
    }
}
?>


<!--HTML for the form -->
<!DOCTYPE html>
<html lang="en">

<?php
// shows all errors
    if (!empty($errors)) {
        foreach ($errors as $error) {
            echo "<li style=color:red;>" .esc($error). "</li>";
    }
}

    if ($success) {
        echo "<p style=color:green;> $success </p>";
     }
?>

<form action = "" method="post"><br>
    First Name: <input type="text" name="firstName" value="<?php echo esc($firstName); ?>"><br><br>
    Last Name: <input type="text" name="lastName" value="<?php echo esc($lastName); ?>"><br><br>
    Phone Number: <input type="text" name="phone" value="<?php echo esc($phone); ?>"><br><br>
    Email: <input type="email" name="email" value="<?php echo esc($email); ?>"><br><br>

 <input type="submit" value="Submit">
 
</form>
</html>
