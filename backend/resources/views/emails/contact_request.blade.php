<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>New Contact Request</title>
  <style>
    /* Overall body styling */
    body          { background:#f4f4f4; margin:0; padding:0; font-family:Arial, sans-serif; }
    /* Main container */
    .container    { max-width:600px; margin:40px auto; background:#ffffff; border-radius:5px;
                    box-shadow:0 2px 5px rgba(0,0,0,0.1); padding:20px; text-align:left; }
    /* Header */
    .header       { border-bottom:1px solid #dddddd; padding-bottom:10px; margin-bottom:20px; text-align:center; }
    .header h1    { margin:0; font-size:24px; color:#333; }
    /* Sections */
    .section      { margin-bottom:20px; }
    .section p    { margin:6px 0; font-size:16px; color:#555; line-height:1.4; }
    .section p strong { display:inline-block; margin-bottom:4px; color:#333; }
    /* Footer */
    .footer       { border-top:1px solid #dddddd; margin-top:20px; padding-top:10px;
                    font-size:12px; color:#888; text-align:center; }
  </style>
</head>
<body>
  <div class="container">
    <!-- Header -->
    <div class="header">
      <h1>Contact Details</h1>
    </div>

    <!-- Name -->
    <div class="section">
      <p><strong>Name</strong><br>{{ $data['name'] }}</p>
    </div>

    <!-- Email -->
    <div class="section">
      <p><strong>Email</strong><br>{{ $data['email'] }}</p>
    </div>

    <!-- Phone -->
    <div class="section">
      <p><strong>Phone&nbsp;Number</strong><br>{{ $data['phone'] }}</p>
    </div>

    <!-- LinkedIn (optional) -->
    @if(!empty($data['linkedinProfile']))
      <div class="section">
        <p><strong>LinkedIn&nbsp;Profile</strong><br>{{ $data['linkedinProfile'] }}</p>
      </div>
    @endif

    <!-- Selected package (optional) -->
    @if(!empty($data['selectedPackage']))
      <div class="section">
        <p><strong>Selected&nbsp;Package</strong><br>{{ $data['selectedPackage'] }}</p>
      </div>
    @endif

    <!-- Footer -->
    <div class="footer">
      This e‑mail was generated from your website contact form.
    </div>
  </div>
</body>
</html>
