<?php
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Sanitize inputs
    $name = strip_tags(trim($_POST["name"] ?? ""));
    $email = filter_var(trim($_POST["email"] ?? ""), FILTER_SANITIZE_EMAIL);
    $subject = strip_tags(trim($_POST["subject"] ?? ""));
    $message = strip_tags(trim($_POST["message"] ?? ""));

    // Validate inputs
    if (empty($name) || strlen($name) < 2) {
        http_response_code(400);
        echo "Please provide a valid name.";
        exit;
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        http_response_code(400);
        echo "Please provide a valid email.";
        exit;
    }
    if (empty($subject) || strlen($subject) < 3) {
        http_response_code(400);
        echo "Please provide a valid subject.";
        exit;
    }
    if (empty($message) || strlen($message) < 10) {
        http_response_code(400);
        echo "Please provide a valid message.";
        exit;
    }

    // Prepare email
    $to = "contact@mihub.org";  // Replace with MIHub official email
    $email_subject = "MIHub Contact Form: $subject";
    $email_body = "You have received a new message from the MIHub website contact form.\n\n"
        . "Name: $name\n"
        . "Email: $email\n"
        . "Subject: $subject\n\n"
        . "Message:\n$message\n";

    $headers = "From: $name <$email>\r\n";
    $headers .= "Reply-To: $email\r\n";

    // Send email
    if (mail($to, $email_subject, $email_body, $headers)) {
        // Redirect to thank you page or show success message
        header("Location: contact_thanks.html");
        exit;
    } else {
        http_response_code(500);
        echo "Oops! Something went wrong and we couldn't send your message.";
        exit;
    }
} else {
    http_response_code(403);
    echo "There was a problem with your submission, please try again.";
    exit;
}
?>
