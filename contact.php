<?php
// if ($_SERVER["REQUEST_METHOD"] == "POST") {
//     $name = strip_tags(trim($_POST["name"]));
//     $name = str_replace(["\r","\n"], [" ", " "], $name);
//     $email = filter_var(trim($_POST["email"]), FILTER_SANITIZE_EMAIL);
//     $message = trim($_POST["message"]);

//     if (empty($name) || empty($message) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
//         http_response_code(400);
//         echo "Please complete the form and use a valid email address.";
//         exit;
//     }

//     $recipient = "biplovkhatri36@gmail.com";
//     $subject = "New Contact Form Submission from ratedisco.com";

//     $content = "Name: $name\n";
//     $content .= "Email: $email\n";
//     $content .= "Message:\n$message\n";

//     $headers = "From: $name <$email>\r\n";
//     $headers .= "Reply-To: $email\r\n";

//     if (mail($recipient, $subject, $content, $headers)) {
//         http_response_code(200);
//         echo "Thank you! Your message has been sent.";
//     } else {
//         http_response_code(500);
//         echo "Oops! Something went wrong and we couldn't send your message.";
//     }
// } else {
//     http_response_code(403);
//     echo "Invalid request.";
// }

if ($_SERVER['REQUEST_METHOD']==='POST') {
    // … (sanitize $name, $email, $phone, $subject, $message as before)
    
    $recipient = 'nemwang.soooman@gmail.com';          // ← your real address
    $subject   = 'New contact from ratedisco.com';
    $body      = "Name: $name\n"
               . "Email: $email\n"
               . "Phone: $phone\n"
               . "Subject: $subjectSelect\n\n"
               . "Message:\n$message\n";
    
    $headers  = "From: $name <$email>\r\n"
              . "Reply-To: $email\r\n";
    
    if ( mail($recipient, $subject, $body, $headers) ) {
        http_response_code(200);
        echo "Thank you! Your message has been sent.";
    } else {
        http_response_code(500);
        echo "Oops! Something went wrong sending your message.";
    }
} else {
    http_response_code(403);
    echo "Invalid request.";
}

?>
