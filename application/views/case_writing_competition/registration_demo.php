<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Case Study Entry Form</title>
  <!-- Bootstrap 5.3 CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body { background: #f8f9fb; }
    .card { border: 0; border-radius: 1rem; box-shadow: 0 10px 30px rgba(0,0,0,.06); }
    .form-text { font-size: .85rem; }
  </style>
</head>
<body>
  <div class="container py-5">
    <div class="row justify-content-center">
      <div class="col-lg-8 col-xl-7">
        <div class="card p-4 p-md-5">
          <div class="d-flex align-items-center mb-3">
            <div class="flex-grow-1">
              <h1 class="h3 mb-0">Case Study Entry Form</h1>
              <small class="text-muted">Please fill in the front-page details below.</small>
            </div>
          </div>

          <form id="entryForm" class="needs-validation" novalidate>
            <!-- Title -->
            <div class="mb-3">
              <label for="title" class="form-label">Title of the Case Study <span class="text-danger">*</span></label>
              <input type="text" class="form-control" id="title" name="title" maxlength="150" placeholder="e.g., Digital Transformation at XYZ Bank" required>
              <div class="invalid-feedback">Please enter the case study title.</div>
            </div>

            <!-- Area -->
            <div class="mb-3">
              <label for="area" class="form-label">Area of the Case Study <span class="text-danger">*</span></label>
              <input class="form-control" list="areaOptions" id="area" name="area" placeholder="e.g., Operations / Strategy / Finance" required>
              <datalist id="areaOptions">
                <option value="Finance"></option>
                <option value="Human Resources"></option>
                <option value="Marketing"></option>
                <option value="Operations"></option>
                <option value="Information Systems / IT"></option>
                <option value="Strategy"></option>
                <option value="Organizational Behavior"></option>
                <option value="Economics"></option>
                <option value="Public Policy"></option>
                <option value="Other"></option>
              </datalist>
              <div class="invalid-feedback">Please specify the area.</div>
            </div>

            <!-- Scheme (I–V) -->
            <div class="mb-3">
              <label for="scheme" class="form-label">Case Study entered in the scheme (I – V) <span class="text-danger">*</span></label>
              <select class="form-select" id="scheme" name="scheme" required>
                <option value="" selected disabled>Choose…</option>
                <option value="I">I</option>
                <option value="II">II</option>
                <option value="III">III</option>
                <option value="IV">IV</option>
                <option value="V">V</option>
              </select>
              <div class="invalid-feedback">Please select a scheme (I–V).</div>
            </div>

            <!-- Author -->
            <div class="mb-3">
              <label for="authorName" class="form-label">Name of the Author <span class="text-danger">*</span></label>
              <input type="text" class="form-control" id="authorName" name="authorName" placeholder="e.g., Anil Sharma" required>
              <div class="invalid-feedback">Please enter the author’s name.</div>
            </div>

            <!-- Designation (present/former) -->
            <div class="row g-3">
              <div class="col-md-6">
                <label for="designation" class="form-label">Designation <span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="designation" name="designation" placeholder="e.g., Senior Manager" required>
                <div class="invalid-feedback">Please enter a designation.</div>
              </div>
              <div class="col-md-6">
                <label for="designationStatus" class="form-label">Status</label>
                <select class="form-select" id="designationStatus" name="designationStatus">
                  <option value="present" selected>Present</option>
                  <option value="former">Former</option>
                </select>
              </div>
            </div>

            <!-- Employer (present/former) -->
            <div class="row g-3 mt-1">
              <div class="col-md-6">
                <label for="employer" class="form-label">Employer <span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="employer" name="employer" placeholder="e.g., Fino Payments Bank" required>
                <div class="invalid-feedback">Please enter the employer.</div>
              </div>
              <div class="col-md-6">
                <label for="employerStatus" class="form-label">Status</label>
                <select class="form-select" id="employerStatus" name="employerStatus">
                  <option value="present" selected>Present</option>
                  <option value="former">Former</option>
                </select>
              </div>
            </div>

            <!-- Phone & Email -->
            <div class="row g-3 mt-1">
              <div class="col-md-6">
                <label for="phone" class="form-label">Phone / Mobile Number <span class="text-danger">*</span></label>
                <input type="tel" class="form-control" id="phone" name="phone"
                       inputmode="numeric" pattern="^[0-9]{10,15}$" placeholder="e.g., 9876543210" required>
                <div class="form-text">Enter 10–15 digits, numbers only.</div>
                <div class="invalid-feedback">Please enter a valid phone number (10–15 digits).</div>
              </div>
              <div class="col-md-6">
                <label for="email" class="form-label">Email Address <span class="text-danger">*</span></label>
                <input type="email" class="form-control" id="email" name="email" placeholder="name@example.com" required>
                <div class="invalid-feedback">Please enter a valid email address.</div>
              </div>
            </div>

            <!-- Qualifications -->
            <div class="mb-3 mt-3">
              <label for="qualifications" class="form-label">Qualifications</label>
              <textarea class="form-control" id="qualifications" name="qualifications" rows="2" placeholder="e.g., MBA (Finance), B.Tech (CSE)"></textarea>
            </div>

            <!-- Any other information -->
            <div class="mb-3">
              <label for="otherInfo" class="form-label">Any other information</label>
              <textarea class="form-control" id="otherInfo" name="otherInfo" rows="3" placeholder="Optional details, links, or notes"></textarea>
            </div>

            <!-- Confirmation -->
            <div class="form-check mb-4">
              <input class="form-check-input" type="checkbox" value="" id="confirm" required>
              <label class="form-check-label" for="confirm">
                I confirm that the information provided is accurate.
              </label>
              <div class="invalid-feedback">Please confirm the accuracy of the information.</div>
            </div>

            <!-- Actions -->
            <div class="d-flex gap-2">
              <button class="btn btn-primary px-4" type="submit">Submit</button>
              <button class="btn btn-outline-secondary" type="reset">Reset</button>
            </div>
          </form>

        </div>
      </div>
    </div>
  </div>

  <!-- Bootstrap JS (for validation styles) -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    // Bootstrap custom validation
    (function () {
      'use strict';
      const form = document.getElementById('entryForm');
      form.addEventListener('submit', function (event) {
        if (!form.checkValidity()) {
          event.preventDefault();
          event.stopPropagation();
        }
        form.classList.add('was-validated');
      }, false);
    })();
  </script>
</body>
</html>
