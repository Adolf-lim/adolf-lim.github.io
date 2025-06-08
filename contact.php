<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = strip_tags(trim($_POST["name"]));
    $name = str_replace(array("\r","\n"),array(" "," "),$name);
    $email = filter_var(trim($_POST["email"]), FILTER_SANITIZE_EMAIL);
    $message = trim($_POST["message"]);

    $recipient = "biplovkhatri36@gmail.com";
    $email_subject = "New Contact Form Submission";

    $email_content = "Name: $name\n";
    $email_content .= "Email: $email\n";
    $email_content .= "Message:\n$message\n";

    $email_headers = "From: $name <$email>\r\n";
    $email_headers .= "Reply-To: $email\r\n";

    if (mail($recipient, $email_subject, $email_content, $email_headers)) {
        // Option 1: Redirect to thank-you page
        header("Location: thank-you.html");
        exit;
        
        // Option 2: Uncomment to show an alert instead
        // echo "<script>alert('Message sent successfully!'); window.location.href='contact.html';</script>";
    } else {
        echo "Oops! Something went wrong and we couldn't send your message.";
    }

} else {
    http_response_code(403);
    echo "There was a problem with your submission, please try again.";
}
?>
