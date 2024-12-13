@include('head')
<body>
    <!-- Layout wrapper -->
    <div class="layout-wrapper layout-content-navbar">
      <div class="layout-container">
        @include('admin_menu')
        <!-- Layout container -->
        <div class="layout-page">
        @include('nav_bar')

          <!-- Content wrapper -->
          <div class="content-wrapper">
            <!-- Content -->



            <div class="container-xxl flex-grow-1 container-p-y">
              <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">User Management /</span> Create User</h4>

              <div class="row">

                <div class="col-xl-12">
                    <!-- HTML5 Inputs -->
                    <div class="card mb-4">
                      <h5 class="card-header">User Information</h5>
                      <div class="card-body">
                        <div class="mb-3 row">
                          <label for="html5-text-input" class="col-md-2 col-form-label">UserName</label>
                          <div class="col-md-10">
                            <input class="form-control" type="text" id="html5-text-input" />
                          </div>
                        </div>
                        <div class="mb-3 row">
                          <label for="html5-text-input" class="col-md-2 col-form-label">Name</label>
                          <div class="col-md-10">
                            <input class="form-control" type="text" id="html5-text-input" />
                          </div>
                        </div>

                        <div class="mb-3 row">
                          <label for="html5-email-input" class="col-md-2 col-form-label">Email</label>
                          <div class="col-md-10">
                            <input class="form-control" type="email"  id="html5-email-input" />
                          </div>
                        </div>
                        <div class="mb-3 row">

                        <div class="mb-3 row">
                          <label for="html5-tel-input" class="col-md-2 col-form-label">Phone</label>
                          <div class="col-md-10">
                            <input class="form-control" type="tel" value="eg: 60-123-4567" id="html5-tel-input" />
                          </div>
                        </div>
                        <div class="mb-3 row">
                          {{-- <label for="defaultSelect" class="form-label">Default select</label> --}}
                          <label for="html5-tel-input" class="col-md-2 col-form-label">Package Selection</label>
                          <div class="col-md-10">
                            <select id="defaultSelect" class="form-select">
                              <option>Default select</option>
                              <option value="1">One</option>
                              <option value="2">Two</option>
                              <option value="3">Three</option>
                            </select>
                          </div>

                        </div>
                        <div class="mb-3 row">
                          <label for="html5-datetime-local-input" class="col-md-2 col-form-label">Package Due Date</label>
                          <div class="col-md-10">
                            <input
                              class="form-control"
                              type="date"

                              id="html5-datetime-local-input"
                            />
                          </div>
                        </div>

                          </div>

                      </div>
                    </div>

                    <button type="submit" class="btn btn-primary">Confirm</button>
                    <a href="{{ route('user-view') }}" >
                        <button  class="btn btn-warning">Back</button>
                    </a>

              </div>
            </div>
            <!-- / Content -->


            <div class="content-backdrop fade"></div>
          </div>
          <!-- Content wrapper -->
        </div>
        <!-- / Layout page -->
      </div>

      <!-- Overlay -->
      <div class="layout-overlay layout-menu-toggle"></div>
    </div>
    <!-- / Layout wrapper -->



    <!-- Core JS -->
    <!-- build:js vendor/js/core.js -->
    <script src="../vendor/libs/jquery/jquery.js"></script>
    <script src="../vendor/libs/popper/popper.js"></script>
    <script src="../vendor/js/bootstrap.js"></script>
    <script src="../vendor/libs/perfect-scrollbar/perfect-scrollbar.js"></script>

    <script src="../vendor/js/menu.js"></script>
    <!-- endbuild -->

    <!-- Vendors JS -->

    <!-- Main JS -->
    <script src="../js/main.js"></script>

    <!-- Page JS -->

    <script src="../js/form-basic-inputs.js"></script>

    <!-- Place this tag in your head or just before your close body tag. -->
    <script async defer src="https://buttons.github.io/buttons.js"></script>
  </body>