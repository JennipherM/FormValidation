<?php
include "helper.php";

$firstName = $lastName = $phone = $email = $address = $city = $zip = $quantity = $item = "";
$startDate = $endDate = "";
$errors = []; 
$success = ""; 

// runs if user presses submit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $firstName = clean('firstName');
    $lastName = clean('lastName'); 
    $phone = clean('phone');
    $email = clean('email', 'email');
    $address = clean('address');
    $city = clean('city'); 
    $zip = clean('zip');
    $item = clean('item');
    $today = date('Y-m-d');
    $startDate = clean('startDate', 'date');
    $endDate = clean('endDate', 'date');
    $quantity = clean('quantity'); 

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

    if(empty($address))
    {
        $errors[] =  "Address is required.";
    }
    if(empty($city))
    {
        $errors[] =  "City is required.";
    }
    if (empty($zip)) {
        $errors[] = "Zip code is required.";
    } 
    if (empty($item)) {
        $errors[] = "Item is required.";
    } 

    if (empty($startDate)) {
        $errors[] = "Start date is required.";
    } elseif ($startDate < $today) {
        $errors[] = "Start date cannot be in the past";
    }

    if (empty($endDate)) {
        $errors[] = "End date is required.";
    } elseif ($endDate <= $startDate) {
        $errors[] = "End date must be at least 1 day after start date.";
    }
    
    if(empty($quantity))
    {
        $errors[] =  "Quantity is required.";
    }
    // will need to check if quantity is greater than the database stock 
    // elseif($quantity > DBstock)
    // {
    //     $errors[] =  "$item only has ($stock) in stock.";
    // }

    if (empty($errors))
    {
        $success = "Contact Info Accepted. Name: " .esc($firstName).  " " .esc($lastName). " Phone: " .esc($phone). " Email: " .esc($email). "<br><br>RSV Info Accepted. Address: " .esc($address).
          " City: " .esc($city). " Zip Code: " .esc($zip). " Dates: " . esc($startDate). " - " . esc($endDate). " Item: " .esc($item);
        
        $firstName = $lastName = $phone = $email = $address = $city = $zip = "";
        $startDate = $endDate = "";
        $quantity = "";
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
        <div class="grid">
            First Name: <input type="text" name="firstName" value="<?php echo esc($firstName); ?>"><br><br>
            Last Name: <input type="text" name="lastName" value="<?php echo esc($lastName); ?>"><br><br>
            Phone Number: <input type="text" name="phone" value="<?php echo esc($phone); ?>"><br><br>
            Email: <input type="text" name="email" value="<?php echo esc($email); ?>"><br><br>
            Street Address: <input type = "text" name="address" value = "<?php echo esc($address); ?>"><br><br>
            City: <input type = "text" name="city" value = "<?php echo esc($city); ?>"><br><br>
            Zipcode: <input type = "text" name="zip" value = "<?php echo esc($zip); ?>"><br><br>
            Start Date: <input type="date" name="startDate" value="<?= esc($startDate) ?>"><br><br>
            End Date: <input type="date" name="endDate" value="<?= esc($endDate) ?>"><br><br>

            Item: <select name="item"><br><br>
                <option value="">Select...</option>
                <option value="Royal Bounce Castle" <?php if ($item === 'Royal Bounce Castle') echo 'selected'; ?>>Royal Bounce Castle</option>
                <option value="Majestic Water Slide" <?php if ($item === 'Majestic Water Slide') echo 'selected'; ?>>Majestic Water Slide</option>
                <option value="Crown Obstacle Course" <?php if ($item === 'Crown Obstacle Course') echo 'selected'; ?>>Crown Obstacle Course</option>
            </select><br><br>
          Quantity: <input name = "quantity" value = "<?php echo esc($quantity); ?>"><br><br>
        </div>
        <div class="admin-actions">
          <button type="submit" class="btn btn-primary">Create</button>
          <button type="reset" class="btn btn-secondary">Reset</button>
        </div>
      </form>
</html>
