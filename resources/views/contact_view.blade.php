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
              <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Dashboard /</span>Contacts</h4>

              <hr class="my-1" />
              <div  style="display: flex; justify-content:end;padding:20px">
                <a href="{{ route('create-contact') }}" class="menu-link">
                <button type="button" class="btn btn-primary" >Add Contact</button>
                </a>
              </div>
              <div class="card">
                <div style="display: flex; justify-content: space-between; align-items: center; padding-top: 20px; padding-left:25px;padding-right:25px">
                  <!-- Buttons -->
                  <div style="display: flex; gap: 10px;">
                    <button type="button" class="btn btn-primary">Print</button>
                    <button type="button" class="btn btn-primary">Excel</button>
                    <button type="button" class="btn btn-primary">PDF</button>
                  </div>
                  <!-- Search Field -->
                  <input
                    type="text"
                    id="searchInput"
                    placeholder="Search..."
                    style="padding: 5px; border: 1px solid #ccc; border-radius: 4px; width: 200px;"
                    oninput="filterTable()"
                  />
                </div>

                <div class="card-body">
                  <div class="table-responsive text-nowrap">
                    <table class="table table-bordered" id="projectTable">
                      <thead >
                        <tr>
                          <th><strong>Name</strong></th>
                          <th><strong>Email</strong></th>
                          <th><strong>Phone number</strong></th>
                          <th><strong>Address</strong></th>
                          <th><strong>Remarks</strong></th>
                          <th><strong>Created Date</strong></th>
                          <th><strong>Action</strong></th>
                        </tr>
                      </thead>
                      <tbody>
                        <!-- Example rows for the table -->
                        <tr>
                          <td>User1</td>
                          <td>user1@gmail.com</td>
                          <td>1234567890</td>
                          <td>Basic</td>
                          <td>Plumber</td>
                          <td>2024-11-20</td>
                          <td>
                            <a href="{{ route('edit-contact') }}">
                              <button class="btn btn-sm btn-primary">Edit</button>
                            </a>

                            <button class="btn btn-sm btn-danger">Delete</button>
                          </td>
                        </tr>

                      </tbody>
                    </table>
                  </div>
                </div>
              </div>
              <script>
                function filterTable() {
                  const input = document.getElementById("searchInput").value.toLowerCase();
                  const table = document.getElementById("projectTable");
                  const rows = table.getElementsByTagName("tr");

                  for (let i = 1; i < rows.length; i++) {
                    const cells = rows[i].getElementsByTagName("td");
                    let rowMatches = false;

                    for (let j = 0; j < cells.length; j++) {
                      if (cells[j].innerText.toLowerCase().includes(input)) {
                        rowMatches = true;
                        break;
                      }
                    }

                    rows[i].style.display = rowMatches ? "" : "none";
                  }
                }
              </script>


<!-- Footer -->
<footer class="content-footer footer bg-footer-theme">
  <div class="container-xxl d-flex justify-content-between align-items-center py-2">
    <div id="entries-info">Showing 1 to 5 of 6 entries</div>
    <nav aria-label="Page navigation">
      <ul class="pagination" id="pagination">
        <li class="page-item">
          <a class="page-link" href="javascript:void(0);" onclick="changePage('prev')">&laquo;</a>
        </li>
        <li class="page-item"><a class="page-link" href="javascript:void(0);" onclick="changePage(1)">1</a></li>
        <li class="page-item"><a class="page-link" href="javascript:void(0);" onclick="changePage(2)">2</a></li>
        <li class="page-item">
          <a class="page-link" href="javascript:void(0);" onclick="changePage('next')">&raquo;</a>
        </li>
      </ul>
    </nav>
  </div>
</footer>
<!-- / Footer -->
<script>
  const rowsPerPage = 5; // Number of rows to display per page
  let currentPage = 1; // Current page
  const table = document.getElementById("projectTable");
  const rows = table.getElementsByTagName("tr");
  const totalRows = rows.length - 1; // Excluding header row
  const totalPages = Math.ceil(totalRows / rowsPerPage);

  function updateTable() {
    const startRow = (currentPage - 1) * rowsPerPage + 1; // First row to display
    const endRow = startRow + rowsPerPage - 1; // Last row to display

    // Hide all rows
    for (let i = 1; i < rows.length; i++) {
      rows[i].style.display = "none";
    }

    // Display rows for the current page
    for (let i = startRow; i <= endRow && i < rows.length; i++) {
      rows[i].style.display = "";
    }

    // Update footer information
    const info = document.getElementById("entries-info");
    info.textContent = `Showing ${Math.min(startRow, totalRows)} to ${Math.min(endRow, totalRows)} of ${totalRows} entries`;

    // Update active page in pagination
    const pagination = document.getElementById("pagination");
    Array.from(pagination.getElementsByClassName("page-item")).forEach((item, index) => {
      if (index > 0 && index < pagination.children.length - 1) {
        item.classList.remove("active");
      }
    });
    if (currentPage > 0 && currentPage <= totalPages) {
      pagination.children[currentPage].classList.add("active");
    }
  }

  function changePage(page) {
    if (page === "prev") {
      currentPage = Math.max(1, currentPage - 1);
    } else if (page === "next") {
      currentPage = Math.min(totalPages, currentPage + 1);
    } else {
      currentPage = page;
    }
    updateTable();
  }

  // Initialize table
  updateTable();


</script>
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
    <script src="{{ asset('../vendor/libs/jquery/jquery.js') }}"></script>
    <script src="{{ asset('../vendor/libs/popper/popper.js') }}"></script>
    <script src="{{ asset('../vendor/js/bootstrap.js') }}"></script>
    <script src="{{ asset('../vendor/libs/perfect-scrollbar/perfect-scrollbar.js') }}"></script>

    <script src="{{ asset('../vendor/js/menu.js') }}"></script>
    <!-- endbuild -->

    <!-- Vendors JS -->

    <!-- Main JS -->
    <script src="{{ asset('../js/main.js') }}"></script>

    <!-- Page JS -->

    <!-- Place this tag in your head or just before your close body tag. -->
    <script async defer src="https://buttons.github.io/buttons.js') }}"></script>
  </body>
