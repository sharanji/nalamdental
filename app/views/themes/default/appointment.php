
    <body data-mobile-nav-style="classic">
        <div class="box-layout">  
         
            <!-- start page title -->
            <section class="top-space-margin page-title-big-typography cover-background overflow-hidden position-relative p-0 border-radius-10px lg-no-border-radius" style="background-image: url(<?php echo base_url();?>assets/frontend/img/demo-medical-contact-title-bg.jpg)"> 
                <div class="container">
                    <div class="row small-screen">
                        <div class="col-xl-5 col-lg-6 col-md-7 position-relative page-title-extra-large align-self-center" data-anime='{ "el": "childs", "translateY": [30, 0], "opacity": [0,1], "duration": 600, "delay": 0, "staggervalue": 300, "easing": "easeOutQuad" }'>
                            <h2 class="fw-500 text-dark-gray mb-15px d-block"><span class="w-40px h-2px bg-base-color d-inline-block align-middle me-15px"></span>We provide world class facility</h2>
                            <h1 class="text-dark-gray fw-800 ls-minus-3px sm-ls-minus-1px d-block mb-0">Book Appointment</h1>
                        </div>
                        <div class="col-xl-7 col-lg-6 col-md-5 position-relative d-none d-md-block">
                            <div class="w-85px h-85px border-radius-100 d-flex align-items-center justify-content-center position-absolute right-30px xxl-right-120px xl-right-100px lg-right-30px md-right-150px md-me-50px top-100px md-top-130px mt-4 translate-middle-y">
                                <div class="bg-red video-icon-box video-icon-medium feature-box-icon-rounded w-80px h-80px rounded-circle d-flex align-items-center justify-content-center">
                                    <span>
                                        <span class="video-icon">
                                            <i class="fa-solid fa-user-nurse icon-very-medium text-white position-relative top-minus-2px m-0"></i>
                                            <span class="video-icon-sonar">
                                                <span class="video-icon-sonar-bfr border border-1 border-color-red"></span>
                                            </span>
                                        </span> 
                                    </span>
                                </div>
                            </div>
                            <div class="blur-box bg-white-transparent position-absolute border-radius-6px top-50 left-150px lg-left-30px md-left-5px w-250px p-25px text-center last-paragraph-no-margin animation-float">
                                <!-- start features box item -->
                                <div class="icon-with-text-style-08">
                                    <div class="feature-box feature-box-left-icon-middle overflow-hidden">
                                        <div class="feature-box-icon me-15px">
                                            <i class="bi bi-bandaid icon-medium text-base-color"></i>
                                        </div>
                                        <div class="feature-box-content last-paragraph-no-margin">
                                            <span class="d-inline-block fs-17 fw-700 text-dark-gray">Best treatment</span>
                                            <p class="lh-20 fs-15">Specialist doctor</p>
                                        </div>
                                    </div>
                                </div>
                                <!-- end features box item -->
                            </div>
                        </div>
                    </div>
                </div>
            </section>
            <!-- end page title -->
            <!-- start section --> 
            
               
            <section class="position-relative bg-light-turquoise-blue" id="appointment">
    <div class="container">
        <div class="row mb-3">
            <div class="col text-center">
                <h2 class="fw-800 text-dark-gray ls-minus-2px">Book an appointment</h2>
            </div>
        </div>
        <div class="row justify-content-center">
            <div class="col-xl-12 col-lg-12">
                <div class="contact-form-style-05">
                    <form id="appointmentForm" onsubmit="event.preventDefault();">
                        <div class="row justify-content-center">
                            <div class="col-md-6 sm-mb-25px">
                                <input class="mb-25px form-control required" type="text" name="name" placeholder="Patient's full name*" required />
                                <input class="mb-25px form-control" type="email" name="email" placeholder="Patient's email" />

                                <div class="select">
                                    <select class="form-control border-radius-4px border-color-white box-shadow-double-large" name="select" id="doctor" onchange="updateDoctorDetails()" required>
                                        <option value="">Select doctor</option>
                                        <option value="Dr.N.Nisanth">Dr.N.Nisanth</option>
                                        <option value="P.K.Hari haran">P.K.Hari haran</option>
                                        <option value="B. Chandra udhayan">B. Chandra udhayan</option>
                                        <option value="G. Anbuselvan">G. Anbuselvan</option>
                                        <option value="U. Tharani">U. Tharani</option>
                                        <option value="Dr.J.Jayaindhraeswaran">Dr.J.Jayaindhraeswaran</option>
                                        <option value="Dr.Pavithra">Dr.Pavithra</option>
                                        <option value="Dr.Shivangi">Dr.Shivangi</option>
                                        <option value="Dr. Deepak ravindran">Dr. Deepak ravindran</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-25px select">
                                    <select class="form-control" name="branch" id="branch" onchange="updateLocation()" required>
                                        <option value="">Select branch</option>
                                        <option value="Hosur">Hosur</option>
                                        <option value="Shoolagiri">Shoolagiri</option>
                                        <option value="Kaveripattinam">Kaveripattinam</option>
                                        <option value="Santhur x road">Santhur x road</option>
                                        <option value="Meera Multi speciality Hospital, Hosur">Meera Multi speciality Hospital, Hosur</option>
                                    </select>
                                </div>

                                <input class="mb-25px form-control" type="text" name="location" id="location" placeholder="Location" readonly required />

                                <div class="date-time row gutter-very-small">
                                    <div class="date-icon col-xl-6 lg-mb-25px">
                                        <input class="form-control" type="date" name="date" value="2025-11-01" min="2023-01-01" max="2099-12-31" required />
                                    </div>
                                    <div class="time-icon col-xl-6">
                                        <input class="form-control" type="time" name="time" value="09:12" min="09:00" max="12:00" required />
                                    </div>
                                </div>

                                <div class="pt-4">
                                    <textarea class="form-control" cols="20" rows="4" name="comment" placeholder="Your message"></textarea>
                                </div>
                            </div>

                            <div class="col-md-12 text-center text-md-end mt-25px sm-mt-20px d-flex justify-content-end gap-3 flex-wrap">
                                <button type="button" class="btn btn-medium btn-success btn-round-edge btn-box-shadow" onclick="sendToWhatsApp()">
                                    <i class="bi bi-whatsapp"></i> Send on WhatsApp
                                </button>
                                <button type="button" class="btn btn-medium btn-primary btn-round-edge btn-box-shadow" onclick="sendAsSMS()">
                                    <i class="bi bi-chat-dots"></i> Send as SMS
                                </button>
                            </div>

                            <div class="col-12">
                                <div class="form-results mt-20px d-none text-center"></div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="position-absolute bottom-minus-15px right-100px lg-right-0px z-index-minus-1 opacity-1 w-300px lg-w-200px">
            <img src="images/demo-medical-home-08.png" alt="best dental hospial hosur">
        </div>
    </div>

    <script>
    const doctorData = {
        "Dr.N.Nisanth": { branch: "Kaveripattinam", location: "Opp. Bus Stand" },
        "P.K.Hari haran": { branch: "Santhur x road", location: "Santhur Main" },
        "B. Chandra udhayan": { branch: "Shoolagiri", location: "Opp. Bus Stand" },
        "U. Tharani": { branch: "Hosur", location: "Opp. GH" },
        "G. Anbuselvan": { branch: "", location: "" },
        "Dr.J.Jayaindhraeswaran": { branch: "", location: "" },
        "Dr.Pavithra": { branch: "", location: "" },
        "Dr.Shivangi": { branch: "", location: "" },
        "Dr. Deepak ravindran": { branch: "", location: "" }
    };

    const branchLocations = {
        "Hosur": "GH opposite",
        "Shoolagiri": "Opp. Bus Stand",
        "Kaveripattinam": "Opp. Bus Stand",
        "Santhur x road": "Opp. Bus Stand",
        "Meera Multi speciality Hospital, Hosur": "Rayakottai Road, Hosur"
    };

    function updateDoctorDetails() {
        const selectedDoctor = document.getElementById("doctor").value;
        const branchField = document.getElementById("branch");
        const locationField = document.getElementById("location");

        if (doctorData[selectedDoctor] && doctorData[selectedDoctor].branch) {
            const { branch, location } = doctorData[selectedDoctor];
            branchField.value = branch;
            branchField.disabled = true;
            locationField.value = location || branchLocations[branch] || "";
            locationField.readOnly = true;
        } else {
            branchField.disabled = false;
            branchField.value = "";
            locationField.readOnly = false;
            locationField.value = "";
        }
    }

    function updateLocation() {
        const branch = document.getElementById("branch").value;
        const locationField = document.getElementById("location");
        locationField.value = branchLocations[branch] || "";
    }

    function getFormData() {
        const name = document.querySelector('input[name="name"]').value;
        const email = document.querySelector('input[name="email"]').value;
        const doctor = document.getElementById('doctor').value;
        const branch = document.getElementById("branch").value;
        const location = document.getElementById("location").value;
        const date = document.querySelector('input[name="date"]').value;
        const time = document.querySelector('input[name="time"]').value;
        const comment = document.querySelector('textarea[name="comment"]').value;

        return `Appointment Request:\nDoctor: ${doctor}\nName: ${name}\nEmail: ${email}\nBranch: ${branch}\nLocation: ${location}\nDate: ${date}\nTime: ${time}\nMessage: ${comment}`;
    }

    function validateForm() {
        const form = document.getElementById("appointmentForm");
        if (!form.checkValidity()) {
            form.reportValidity();
            return false;
        }
        return true;
    }

    function sendToWhatsApp() {
        if (!validateForm()) return;
        const message = getFormData();
        const phoneNumber = "9159671189";
        const whatsappURL = `https://wa.me/91${phoneNumber}?text=${encodeURIComponent(message)}`;
        window.open(whatsappURL, '_blank');
    }

    function sendAsSMS() {
        if (!validateForm()) return;
        const message = getFormData();
        const phoneNumber = "9159671189";
        const smsURL = `sms:+91${phoneNumber}?body=${encodeURIComponent(message)}`;
        window.open(smsURL, '_blank');
    }
</script>

</section>

            <!-- end section -->
        
            <!-- start sticky column -->
            <div class="sticky-wrap d-none d-xl-inline-block" data-animation-delay="100" data-shadow-animation="true">
                <span class="fs-15 fw-500 d-flex align-items-center"><i class="bi bi-envelope icon-small me-10px align-middle"></i>Arrange your appointment — <a href="contact-us.html" class="text-decoration-line-bottom fw-700 lh-22">Book appointment</a></span>
            </div>
            <!-- end sticky column -->
        </div>
        <!-- start scroll progress -->
        <div class="scroll-progress d-none d-xxl-block">
            <a href="#" class="scroll-top" aria-label="scroll">
                <span class="scroll-text">Scroll</span><span class="scroll-line"><span class="scroll-point"></span></span>
            </a>
        </div>
        <!-- end scroll progress -->

    </body>
