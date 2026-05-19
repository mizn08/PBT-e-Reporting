<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style4.css">
    <title>eComplaint | Connect with Your Community</title>
    <style>
body {
    font-family: Arial, sans-serif;
    margin: 0;
    padding: 0;
}

nav {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 15px 30px;
    background: #00796B;
    color: white;
}

nav .nav-logo img {
    width: 150px;
}

nav .nav-links {
    display: flex;
    list-style: none;
    gap: 20px;
}

nav .nav-links li {
    margin: 0;
}

nav .nav-links a {
    color: white;
    text-decoration: none;
    font-weight: bold;
}

nav .btn {
    padding: 8px 20px;
    background: white;
    color: #00796B;
    border-radius: 5px;
    text-decoration: none;
    font-weight: bold;
}

header.container {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 40px;
    background: url('images/header-bg.jpg') no-repeat center/cover;
    color: white;
}

header .content {
    max-width: 50%;
}

header .content h1 {
    font-size: 2.5em;
    color: black;
}

header .content p {
    margin: 20px 0;
    color: black; /* Set paragraph text to black */
}

header .image img {
    width: 350px;
}

section.container {
    text-align: center;
    padding: 40px 20px;
    background: #F4F4F4;
}

section h2.header {
    font-size: 2.5em;
    margin-bottom: 20px;
    color: #00796B;
}

.features {
    display: flex;
    justify-content: center;
    gap: 40px;
}

.features .card {
    background: white;
    padding: 20px;
    border-radius: 10px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    width: 300px;
    text-align: left;
}

.features .card h4 {
    font-size: 1.5em;
    color: #00796B;
}

.features .card p {
    margin: 15px 0;
    color: black; /* Set paragraph text to black */
}

.features .btn-submit {
    display: inline-block;
    margin-top: 10px;
    padding: 10px 15px;
    background: #00796B;
    color: white;
    border-radius: 5px;
    text-decoration: none;
    font-weight: bold;
}

.features .btn-submit:hover {
    background: #005a4f;
}

section p {
    color: black; /* Set all paragraphs in sections to black */
}

section {
    margin-bottom: 40px;
}

.copyright {
    text-align: center;
    padding: 15px;
    background: #00796B;
    color: white;
    margin-top: 20px;
}

    </style>
</head>

<body>

    <nav>
        <div class="nav-logo">
            <a href="#">
                <img src="images/citylogo2.png" alt="eComplaint Logo">
            </a>
        </div>

        <ul class="nav-links">
            <li class="link"><a href="#">Home</a></li>
            <li class="link"><a href="#">About Us</a></li>
            <li class="link"><a href="#">Contact</a></li>
        </ul>
        <a href="login.php" class="btn">Sign In</a>
    </nav>

    <header class="container">
        <div class="content">
            <span class="blur"></span>
            <span class="blur"></span>
            <h4>Empowering Citizens, Connecting Communities</h4>
            <h1>Welcome to <span>eComplaint</span></h1>
            <p>
                Join us in improving our community by reporting issues, tracking complaints, and working together for
                positive change. Your voice matters, and with eComplaint, it’s heard.
            </p>
        </div>
        <div class="image">
            <img src="images/header.png" alt="Community Illustration">
        </div>
    </header>

    <section class="container">
        <h2 class="header">Why Choose eComplaint?</h2>
        <div class="features">
            <div class="card left">
                <h4>Submit Your Complaint</h4>
                <p>
                    Facing a community issue? Submit your complaint easily with our platform and let us take it from
                    there!
                </p>
                <a href="login.php" class="btn-submit">Submit Now</a>
            </div>

            <div class="card right">
                <h4>Track Complaint Progress</h4>
                <p>
                    Keep an eye on the status of your complaint. Transparency and accountability are our promises to
                    you.
                </p>
                <a href="login.php" class="btn-submit">Track Now</a>
            </div>
        </div>
    </section>

    <section class="container">
        <h2 class="header">Our Mission</h2>
        <p>
            At eComplaint, our mission is to bridge the gap between citizens and city officials by providing a
            transparent, efficient, and user-friendly complaint management system. Together, let’s build a better
            tomorrow.
        </p>
    </section>

    <div class="copyright">
        Copyright © 2024 eComplaint. All Rights Reserved.
    </div>

</body>

</html>
