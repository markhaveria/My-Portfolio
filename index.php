<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header('Location: login.php?error=' . urlencode('Please login to access this page'));
    //header('Location: auth/login.php?error=' . urlencode('Please login to access this page'));
    exit;
}

// User is logged in, continue with page
$user_name = $_SESSION['user_name'];
$user_email = $_SESSION['user_email'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home Page</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        *{
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="user-info">
    <p>Welcome, <strong><?php echo htmlspecialchars($user_name); ?></strong>!</p>
    <p>Email: <?php echo htmlspecialchars($user_email); ?></p>
    <a href="logout.php" class="btn btn-danger">Logout</a>
</div>
        <div class="row">
            <div class="col-md-12">
                <h1 class="text-center p-5 bg-dark text-white mb-3">CRUD APPLICATION USING PHP AND MYSQL</h1>

                <?php
                // Display success message
                if (isset($_GET['success'])) {
                    echo '<div class="alert alert-success alert-dismissible fade show" role="alert">';
                    echo htmlspecialchars($_GET['success']);
                    echo '<button type="button" class="btn-close" data-bs-dismiss="alert"></button>';
                    echo '</div>';
                }

                // Display error message
                if (isset($_GET['error'])) {
                    echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">';
                    echo htmlspecialchars($_GET['error']);
                    echo '<button type="button" class="btn-close" data-bs-dismiss="alert"></button>';
                    echo '</div>';
                }
                ?>

                <div class="d-flex justify-content-between mb-3">
                    <h3 class="text-left">All Students</h3>
                    <a href="#" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#studentModal">Create</a>
                </div>
                <div class="table-responsive">
                    <table class="table table-bordered table-striped table-hover">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Age</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                                include 'db_config/dbconn.php';
                                $sql = "SELECT * FROM students";
                                $result = mysqli_query($connection, $sql);

                                if(!$result){
                                    die("Query failed: " . mysqli_error($connection));
                                }

                                while($row = mysqli_fetch_assoc($result)){
                            ?>
                                <tr>
                                    <td><?php echo $row['id']; ?></td>
                                    <td><?php echo $row['first_name'] . ' ' . $row['last_name']; ?></td>
                                    <td><?php echo $row['age']; ?></td>
                                    <td>
                                    <a href="crud/pages/edit_page.php?id=<?php echo $row['id']; ?>"
                                      class="btn btn-sm btn-warning">Edit</a>
                                      <button onclick="confirmDelete(<?php echo $row['id']; ?>, '<?php echo htmlspecialchars($row['first_name'] . ' ' . $row['last_name']); ?>')"
                                    class="btn btn-sm btn-danger">Delete</button>
                                    </td>
                                </tr>
                            <?php
                                }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    
    <?php include 'component/modal.php'; ?>
    <!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title" id="deleteModalLabel">Confirm Delete</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete this student?</p>
                <p><strong id="studentName"></strong></p>
                <p class="text-danger"><small>This action cannot be undone.</small></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <form id="deleteForm" action="crud/delete_student.php" method="POST" style="display: inline;">
                    <input type="hidden" name="id" id="deleteStudentId">
                    <button type="submit" class="btn btn-danger">Delete</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    function confirmDelete(id, name) {
        document.getElementById('deleteStudentId').value = id;
        document.getElementById('studentName').textContent = name;
        var deleteModal = new bootstrap.Modal(document.getElementById('deleteModal'));
        deleteModal.show();
    }
</script>
</body>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
</html>