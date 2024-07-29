<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form inputs
    $name = $_POST['name'];
    $email = $_POST['email'];
    $message = $_POST['message'];

    // Recipient email
    $to = "dylhyrkas@gmail.com";
    
    // Subject
    $subject = "New Contact Form Submission";

    // Create email body
    $body = "Hello, $name.\n\n$message";

    // Headers
    $headers = "From: $email";

    // Boundary for MIME
    $boundary = md5(uniqid(time()));

    // Initialize the content
    $content = "";

    // Handle file upload
    if (isset($_FILES['file']) && $_FILES['file']['error'] == 0) {
        $file = $_FILES['file'];
        $file_name = $file['name'];
        $file_tmp_name = $file['tmp_name'];
        $file_type = $file['type'];

        // Read the file content
        $file_content = chunk_split(base64_encode(file_get_contents($file_tmp_name)));

        // Prepare the attachment part
        $attachment = "--$boundary\r\n";
        $attachment .= "Content-Type: $file_type; name=\"$file_name\"\r\n";
        $attachment .= "Content-Transfer-Encoding: base64\r\n";
        $attachment .= "Content-Disposition: attachment; filename=\"$file_name\"\r\n\r\n";
        $attachment .= $file_content . "\r\n";
        $attachment .= "--$boundary--\r\n";

        // Add MIME headers
        $headers .= "\r\nMIME-Version: 1.0\r\n";
        $headers .= "Content-Type: multipart/mixed; boundary=\"$boundary\"";

        // Prepare the email content with attachment
        $content = "--$boundary\r\n";
        $content .= "Content-Type: text/plain; charset=UTF-8\r\n";
        $content .= "Content-Transfer-Encoding: 7bit\r\n\r\n";
        $content .= "$body\r\n";
        $content .= $attachment;
    } else {
        // Add plain text content if no file is attached
        $headers .= "\r\nContent-Type: text/plain; charset=UTF-8";
        $content = $body;
    }

    // Send email
    if (mail($to, $subject, $content, $headers)) {
        echo "Email successfully sent to $to.";
    } else {
        echo "Email sending failed.";
    }
} else {
    echo "Invalid request.";
}
?>
