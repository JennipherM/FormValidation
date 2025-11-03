<?php
// RSV Info Form (Address, City, Zip, Dates)

// NEEDS TO HAVE THE DATES VALIDATED

function clean($key, $type = 'string', $src = INPUT_POST)
{
    $filter = FILTER_SANITIZE_SPECIAL_CHARS;
    $options = 0;

    // check for things like...   
    
    //     if ($type === 'email') {
    //     $filter = FILTER_SANITIZE_EMAIL;
    // }
    
    $value = filter_input($src, $key, $filter, $options);

    return trim($value ?? '');
}

function esc($val)
{
    return htmlspecialchars((string)$val, ENT_QUOTES, 'UTF-8');
}

$address = "";
$city = "";
$zip = "";
$startDate = "";
$endDate = "";
$errors = []; 
$success = ""; 

// runs if user presses submit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $address = clean('address');
    $city = clean('city'); 
    $zip = clean('zip');
    $startDate = clean('startDate');
    $endDate = clean('endDate');

    // Field Validation
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

    if (empty($startDate)) {
        $errors[] = "Start date is required";
    } 
    if (empty($endDate)) {
        $errors[] = "End date is required";
    }

    if (empty($errors))
    {
        $success = "RSV Info Accepted.<br> Address: " .esc($address).  " City: " .esc($city). " Zip Code: " .esc($zip). " Dates: " .esc($startDate). " - " .esc($endDate);
        
        $address = "";
        $city = ""; 
        $zip = "";
        $startDate = "";
        $endDate = "";
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
            Street Address <input type = "text" name="address" value = "<?php echo esc($address); ?>"><br><br>
            City <input type = "text" name="city" value = "<?php echo esc($city); ?>"><br><br>
            Zipcode <input type = "text" name="zip" value = "<?php echo esc($zip); ?>"><br><br>
            Start Date <input type = "text" name="startDate" type="date" value = "<?php echo esc($startDate); ?>"><br><br>
            End Date <input type = "text" name="endDate" type="date" value = "<?php echo esc($endDate); ?>"><br><br>

          
            <button class="btn btn-secondary check-availability" type="button" data-check-dates>Check Date Availability</button><br><br>
            
              <button class="btn btn-primary" type="submit" data-checkout-now>Check Out Now</button>
              <button class="btn btn-secondary" type="submit" data-add-to-cart>Add to Cart</button>
            
         
        </form>
</html>

