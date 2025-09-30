<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>Catering Inquiry</title>
  <style>
    /* Overall body styling */
    body {
      background-color: #f4f4f4;
      margin: 0;
      padding: 0;
      font-family: Arial, sans-serif;
    }

    /* Main container to center content with a white background */
    .container {
      max-width: 600px;
      margin: 40px auto;
      background-color: #ffffff;
      border-radius: 5px;
      box-shadow: 0 2px 5px rgba(0,0,0,0.1);
      padding: 20px;
      text-align: left; /* left-align for a more “form-like” look */
    }

    /* Header styling */
    .header {
      border-bottom: 1px solid #dddddd;
      padding-bottom: 10px;
      margin-bottom: 20px;
      text-align: center; /* keep the header centered */
    }
    .header h1 {
      margin: 0;
      font-size: 24px;
      color: #333;
    }

    /* Content area */
    .section {
      margin-bottom: 20px;
    }
    /* Label & data paragraphs */
    .section p {
      margin: 6px 0;
      font-size: 16px;
      color: #555;
      line-height: 1.4;
    }
    .section p strong {
      display: inline-block;
      margin-bottom: 4px;
      color: #333;
    }

    /* List styling for multiple options */
    ul {
      margin: 4px 0 0 20px;
      padding: 0;
    }
    ul li {
      list-style-type: disc;
      margin-left: 15px;
      font-size: 16px;
      color: #555;
      line-height: 1.4;
    }

    /* Footer section */
    .footer {
      border-top: 1px solid #dddddd;
      margin-top: 20px;
      padding-top: 10px;
      font-size: 12px;
      color: #888;
      text-align: center;
    }
  </style>
</head>
<body>
  <div class="container">
    <!-- Header -->
    <div class="header">
      <h1>Contact Details</h1>
    </div>

    <!-- Content Sections -->

    <div class="section">
      <p><strong>Name</strong><br>
      {{ $data['name'] }}</p>
    </div>

    <div class="section">
      <p><strong>Email</strong><br>
      {{ $data['email'] }}</p>
    </div>

    <div class="section">
      <p><strong>Message</strong><br>
      {{ $data['message'] }}</p>
    </div>

    <!-- Footer -->
    <div class="footer">
      This email was generated from your Contact inquiry form.
    </div>
  </div>
</body>
</html>
