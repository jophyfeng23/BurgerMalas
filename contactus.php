<?php include 'partials/header.php'; ?>



    <main class="mainContent mt-5 pb-5">
        <div class="container pt-4">
            <!-- Headline -->
            <div class="text-center mb-5">
                <h1 class="fw-bold text-dark mb-3">Let's Talk</h1>
                <p class="mt-3 subText">Have questions about joining Burger Malas? Want to invest or just hungry for
                    a
                    burger? We're just a click away.</p>
            </div>

            <!-- Contact Form -->
            <div class="row justify-content-center">
                <div class="col-12 col-md-8">
                    <div class="card shadow-sm p-4">
                        <h5 class="mb-4 text-center">Send us a message and our team will try to get back within 24 hours.
                        </h5>
                        <form action="contact_process.php" method="POST" enctype="multipart/form-data" class="contactForm" id="contactForm">
                            <div class="mb-3">
                                <label for="name" class="form-label">Full Name</label>
                                <input type="text" class="form-control" id="name" name="name" placeholder="Your full name" required>
                            </div>
                            <div class="mb-3">
                                <label for="email" class="form-label">Email Address</label>
                                <input type="email" class="form-control" id="email" name="email" placeholder="burger@example.com" required>
                            </div>
                            <div class="mb-3">
                                <label for="subject" class="form-label">Subject</label>
                                <input type="text" class="form-control" id="subject" name="subject" placeholder="Subject" required>
                            </div>
                            <div class="mb-3">
                                <label for="message" class="form-label">Message</label>
                                <textarea class="form-control" id="message" name="message" rows="5" placeholder="Your message" required></textarea>
                            </div>
                            <div class="mb-3">
                                <label for="attachment" class="form-label">Attachment (optional)</label>
                                <input class="form-control" type="file" id="attachment" name="attachment" accept=".jpg,.png,.pdf,.docx">
                            </div>
                            <div class="mb-3">
                                <div class="g-recaptcha" data-sitekey="6LfFc3MsAAAAAFk0GJKYWsB12Gcp4Yv533RD5BMv"></div>
                            </div>
                            <button type="submit" class="btn btn-primary w-100 submitbtn">Submit & Send</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </main>

    

    <?php include 'partials/footer.php'; ?>

    
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
   
    <script>
        document.getElementById("contactForm").addEventListener("submit", function (e) {
            e.preventDefault(); 

            // 1. Frontend validation: Check if ReCAPTCHA is completed
            let recaptchaResponse = grecaptcha.getResponse();
            if (recaptchaResponse.length === 0) {
                alert("Please complete the reCAPTCHA challenge before submitting.");
                return; // Stop the function here
            }

            const btn = document.querySelector(".submitbtn");
            const originalText = btn.innerHTML;

            // 2. UI Loading State (Optional but recommended)
            btn.disabled = true;
            btn.innerHTML = 'Sending...';

            let formData = new FormData(this);

            fetch("contact_process.php", {
                method: "POST",
                body: formData
            })
            .then(response => response.text())
            .then(result => {
                alert(result);  // Show success message from PHP
                
                // Clear the form and reset recaptcha if successful
                if(result.includes("Thank you")) {
                    this.reset();
                    grecaptcha.reset(); 
                }
            })
            .catch(error => {
                alert("Error: " + error);
            })
            .finally(() => {
                // Restore Button State
                btn.disabled = false;
                btn.innerHTML = originalText;
            });
        });
    </script>



</body>


</html>










