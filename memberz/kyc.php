<?php 
include __DIR__."/header.php"; 
?>

<!-- SYSTEM CONTAINER FRAME -->
<div class="container bg-light p-0 h-100 mt-2" style="font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;">
  <div class="row g-0 h-100">
    <!-- LEFT SIDEBAR: Standard Structural Navigation Include -->
    <?php include __DIR__."/aside.php"; ?>

    <!-- MAIN KYC CONTENT WINDOW AREA -->
    <div class="col-md-9 col-xl-10 p-3 p-md-4">
      
      <!-- Top Breadcrumb Section -->
      <div class="d-flex align-items-center gap-2 small mb-4 <?= $theme === 'dark' ? 'text-light' : 'text-muted' ?>">
        <i class="bi bi-house-door"></i> 
        <span>Dashboard</span> 
        <i class="bi bi-chevron-right" style="font-size: 10px;"></i> 
        <span class="text-success fw-bold">Identity Verification</span>
      </div>

      <!-- Main KYC Status and Multi-Step Document Form -->
      <div class="row g-4">
        
        <!-- Left Side: Interactive Verification Processing Form -->
        <div class="col-lg-8">
          <div class="card border-0 shadow-sm bg-white rounded-4 p-4">
            <div class="mb-4 pb-2 border-bottom border-light-subtle">
              <h5 class="fw-bold text-dark mb-1">Verify Your Identity</h5>
              <p class="text-muted mb-0 small">Comply with financial regulations to fully unlock withdrawal routes and higher trading caps.</p>
            </div>
            <!-- KYC Form Post Action -->
           <form id="formSubmit" method="POST" enctype="multipart/form-data">

    <!-- Document Type -->
    <div class="mb-4">
        <label class="form-label fw-semibold text-dark small mb-2">
            Select Identification Document Type
        </label>

        <div class="row g-3">

            <div class="col-sm-4">
                <input type="radio"
                       class="btn-check"
                       name="type"
                       id="passport"
                       value="passport"
                       checked>

                <label class="btn btn-outline-light border border-light-subtle p-3 w-100 rounded-4 text-start text-dark d-flex flex-column gap-2 shadow-sm"
                       for="passport">
                    <i class="bi bi-journal-text text-success fs-4"></i>
                    <span class="fw-semibold small">
                        International Passport
                    </span>
                </label>
            </div>

            <div class="col-sm-4">
                <input type="radio"
                       class="btn-check"
                       name="type"
                       id="idcard"
                       value="national_id">

                <label class="btn btn-outline-light border border-light-subtle p-3 w-100 rounded-4 text-start text-dark d-flex flex-column gap-2 shadow-sm"
                       for="idcard">
                    <i class="bi bi-card-heading text-success fs-4"></i>
                    <span class="fw-semibold small">
                        National Identity Card
                    </span>
                </label>
            </div>

            <div class="col-sm-4">
                <input type="radio"
                       class="btn-check"
                       name="type"
                       id="license"
                       value="drivers_license">

                <label class="btn btn-outline-light border border-light-subtle p-3 w-100 rounded-4 text-start text-dark d-flex flex-column gap-2 shadow-sm"
                       for="license">
                    <i class="bi bi-car-front-fill text-success fs-4"></i>
                    <span class="fw-semibold small">
                        Driver's License
                    </span>
                </label>
            </div>

        </div>
    </div>

    <!-- Upload -->
    <div class="mb-4">

        <label class="form-label fw-semibold text-dark small mb-2">
            Upload Document Front Photo
        </label>

        <div class="border border-secondary border-opacity-25 rounded-4 p-4 text-center bg-light position-relative">

            <input
                type="file"
                id="image"
                name="image"
                class="position-absolute top-0 start-0 w-100 h-100 opacity-0"
                accept=".jpg,.jpeg,.png,.aviv"
                required>

            <i class="bi bi-cloud-arrow-up text-success display-6 mb-2 d-block"></i>

            <span class="fw-medium d-block small">
                Click to upload or drag your document here
            </span>

            <span class="text-muted small d-block">
                PNG, JPG or JPEG • Maximum 10MB
            </span>

        </div>

        <div class="mt-3 text-center">
            <img
                id="previewImage"
                src=""
                class="img-fluid rounded-3 border d-none"
                style="max-height:220px;">
        </div>

    </div>

    <!-- Details -->
    <div class="row g-3 mb-4">

        <div class="col-md-6">

            <label class="form-label text-muted small">
                Document Number
            </label>

            <input
                type="text"
                id="code"
                name="code"
                class="form-control bg-light border-light-subtle shadow-none"
                placeholder="e.g. A92401823"
                required>

        </div>

        <div class="col-md-6">

            <label class="form-label text-muted small">
                Expiration Date
            </label>

            <input
                type="date"
                id="expire"
                name="expire"
                class="form-control bg-light border-light-subtle shadow-none"
                min="<?= date('Y-m-d'); ?>"
                required>

        </div>

    </div>

    <div class="d-flex justify-content-end pt-3 border-top">

        <button
            type="submit"
            class="btn btn-success px-4">

            <i class="bi bi-cloud-upload me-2"></i>

            Submit Verification

        </button>

    </div>

</form>


          </div>
        </div>

        <!-- Right Side: Security Context Infobox & Privacy Guard Rails -->
        <div class="col-lg-4">
          <div class="card border-0 shadow-sm bg-white rounded-4 p-4 mb-4">
            <h6 class="fw-bold text-dark mb-3 d-flex align-items-center gap-2">
              <i class="bi bi-shield-lock-fill text-success fs-5"></i> Enterprise Data Privacy
            </h6>
            <p class="text-secondary small mb-3">
              Your identity materials are processed via AES-256 end-to-end operational envelope encryption layers. Data points are stored strictly for legal banking verification requirements.
            </p>
            <hr class="my-3 border-light-subtle">
            <ul class="list-unstyled d-flex flex-column gap-2.5 text-secondary small mb-0">
              <li class="d-flex gap-2 align-items-start"><i class="bi bi-check2-circle text-success mt-0.5"></i> Fully compliant with global AML regulations.</li>
              <li class="d-flex gap-2 align-items-start"><i class="bi bi-check2-circle text-success mt-0.5"></i> Automated review settles within 15 minutes.</li>
              <li class="d-flex gap-2 align-items-start"><i class="bi bi-check2-circle text-success mt-0.5"></i> Documents deleted instantly if entry errors prompt a rejection.</li>
            </ul>
          </div>

          <!-- Active Support Alert Frame -->
          <a href="/support" class="p-3 bg-success bg-opacity-10 border border-success border-opacity-10 rounded-4 text-start text-success small d-flex gap-2">
            <i class="bi bi-headset fs-5 mt-0.5"></i>
            <div>
              <span class="fw-bold d-block mb-0.5">Verification Assistance</span>
              Experiencing hardware issues with camera scans or dynamic text matching? Reach out directly to our live engineering compliance.
              <br>
            </div>
          </a>
        </div>

      </div>

    </div>
  </div>
</div>

<script>
const imageInput = document.getElementById("image");
const preview = document.getElementById("previewImage");
const form = document.getElementById("formSubmit");

// Preview Image
imageInput.addEventListener("change", function () {

    const file = this.files[0];

    if (!file) {
        preview.src = "";
        preview.classList.add("d-none");
        return;
    }

    // Validate size
    if (file.size > 10 * 1024 * 1024) {
        Swal.fire({
            icon: "error",
            title: "Image Too Large",
            text: "Maximum image size is 10MB."
        });

        this.value = "";
        preview.src = "";
        preview.classList.add("d-none");
        return;
    }

    // Validate type
    const allowed = ["image/jpeg", "image/png"];

    if (!allowed.includes(file.type)) {
        Swal.fire({
            icon: "error",
            title: "Invalid Image",
            text: "Only JPG and PNG images are allowed."
        });

        this.value = "";
        preview.src = "";
        preview.classList.add("d-none");
        return;
    }

    const reader = new FileReader();

    reader.onload = function(e){
        preview.src = e.target.result;
        preview.classList.remove("d-none");
    };

    reader.readAsDataURL(file);

});


// Submit Form
form.addEventListener("submit", async function(e){

    e.preventDefault();

    const submitBtn = form.querySelector('button[type="submit"]');

    const image = form.image.files[0];
    const code = form.code.value.trim();
    const expire = form.expire.value;
    const type = form.querySelector('input[name="type"]:checked')?.value;

    // Document Type
    if(!type){
        Swal.fire({
            icon:"warning",
            title:"Document Type",
            text:"Please select a document type."
        });
        return;
    }

    // Image
    if(!image){
        Swal.fire({
            icon:"warning",
            title:"Upload Required",
            text:"Please upload your document."
        });
        return;
    }

    if(image.size > 10 * 1024 * 1024){
        Swal.fire({
            icon:"error",
            title:"Image Too Large",
            text:"Maximum image size is 10MB."
        });
        return;
    }

    const validTypes = ["image/jpeg","image/png"];

    if(!validTypes.includes(image.type)){
        Swal.fire({
            icon:"error",
            title:"Invalid Image",
            text:"Only JPG and PNG images are allowed."
        });
        return;
    }

    // Document Number
    if(code === ""){
        Swal.fire({
            icon:"warning",
            title:"Document Number",
            text:"Enter your document number."
        });
        return;
    }

    const codePattern = /^[A-Za-z0-9\- ]+$/;

    if(!codePattern.test(code)){
        Swal.fire({
            icon:"warning",
            title:"Invalid Document Number",
            text:"Document number contains invalid characters."
        });
        return;
    }

    // Expiry Date
    if(expire === ""){
        Swal.fire({
            icon:"warning",
            title:"Expiry Date",
            text:"Please select the expiry date."
        });
        return;
    }

    const today = new Date();
    today.setHours(0,0,0,0);

    const expiryDate = new Date(expire + "T00:00:00");

    if(expiryDate < today){
        Swal.fire({
            icon:"warning",
            title:"Expired Document",
            text:"The selected document has already expired."
        });
        return;
    }

    const fd = new FormData();
    fd.append("action","/member/kyc");
    fd.append("type",type);
    fd.append("code",code);
    fd.append("expire",expire);
    fd.append("image",image);

    submitBtn.disabled = true;

    try{

        Swal.fire({
            title:"Uploading...",
            text:"Please wait while your document is being submitted.",
            allowOutsideClick:false,
            allowEscapeKey:false,
            didOpen:()=>{
                Swal.showLoading();
            }
        });

        const response = await fetch("<?= $company_info['main-server']; ?>",{
            method:"POST",
            body:fd
        });

        if(!response.ok){
            throw new Error("HTTP " + response.status);
        }

        const result = await response.json();

        Swal.close();

        if(result.success){

            await Swal.fire({
                icon:"success",
                title:"Verification Submitted",
                text:result.message || "Your KYC has been submitted successfully."
            });

            window.location.href="/member";

        }else{

            Swal.fire({
                icon:"error",
                title:"Submission Failed",
                text:result.message || "Unable to submit your KYC."
            });

        }

    }catch(error){

        Swal.close();

        Swal.fire({
            icon:"error",
            title:"Network Error",
            text:"Unable to connect to the server."
        });

        console.error(error);

    }finally{

        submitBtn.disabled = false;

    }

});
</script>