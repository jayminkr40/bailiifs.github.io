<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Collect and sanitize form data
    $name = htmlspecialchars(trim($_POST['name']));
    $phone = htmlspecialchars(trim($_POST['phone']));
    $email = htmlspecialchars(trim($_POST['email']));
    $bailiff = htmlspecialchars(trim($_POST['bailiff']));
    $message = htmlspecialchars(trim($_POST['message']));
    
    // Validate required fields
    if (empty($name) || empty($phone) || empty($email)) {
        echo "error: Please fill in all required fields.";
        exit;
    }
    
    // Validate email format
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "error: Please enter a valid email address.";
        exit;
    }
    
    // Email configuration
    $to = "anskr1901@gmail.com";
    $subject = "Bailiff Advice Request from " . $name;
    
    // Email body
    $email_body = "BAILIFF ADVICE REQUEST\n";
    $email_body .= "========================\n\n";
    $email_body .= "CLIENT DETAILS:\n";
    $email_body .= "Name: " . $name . "\n";
    $email_body .= "Phone: " . $phone . "\n";
    $email_body .= "Email: " . $email . "\n\n";
    
    $email_body .= "BAILIFF INFORMATION:\n";
    $email_body .= ($bailiff ? $bailiff : "Not provided") . "\n\n";
    
    $email_body .= "ADDITIONAL MESSAGE:\n";
    $email_body .= ($message ? $message : "No additional message provided") . "\n\n";
    
    $email_body .= "========================\n";
    $email_body .= "Submitted on: " . date("F j, Y, g:i a") . "\n";
    $email_body .= "IP Address: " . $_SERVER['REMOTE_ADDR'] . "\n";
    
    // Email headers
    $headers = "From: bailiff-form@webflyin.us.com\r\n";
    $headers .= "Reply-To: " . $email . "\r\n";
    $headers .= "X-Mailer: PHP/" . phpversion();
    
    // Send email
    if (mail($to, $subject, $email_body, $headers)) {
        // Send confirmation to client
        $client_subject = "Confirmation: Your Bailiff Advice Request";
        $client_message = "Dear " . $name . ",\n\n";
        $client_message .= "Thank you for submitting your bailiff advice request.\n";
        $client_message .= "We have received your details and will review your case.\n";
        $client_message .= "Our team will respond within 24 hours.\n\n";
        $client_message .= "Summary of your submission:\n";
        $client_message .= "- Phone: " . $phone . "\n";
        $client_message .= ($bailiff ? "- Bailiff info: " . $bailiff . "\n" : "");
        $client_message .= "\nIf you need to provide additional information, please reply to this email.\n\n";
        $client_message .= "Best regards,\nBailiff Advice Team";
        
        $client_headers = "From: noreply@yourdomain.com\r\n";
        mail($email, $client_subject, $client_message, $client_headers);
        
        echo "success: Your request has been submitted successfully.";
    } else {
        echo "error: There was a problem sending your request. Please try again later.";
    }
} else {
    echo "error: Invalid request method.";
}
?>
