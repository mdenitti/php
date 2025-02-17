<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once 'models/User.php';
require_once 'assets/common.php';

if (!isset($_SESSION['token'])) {
    header("Location: index.php");
    exit();
}

$user = new User();

// Add debugging
try {
    $users = $user->getAll();
    if (!$users) {
        echo "No users found or error in query";
    }
    // Debug connection
    if ($user->conn->connect_error) {
        die("Connection failed: " . $user->conn->connect_error);
    }
} catch (Exception $e) {
    die("Error: " . $e->getMessage());
}

// Handle Delete Action
if(isset($_GET['delete'])) {
    $user->id = $_GET['delete'];
    if($user->delete()) {
        header("Location: dashboard.php?msg=deleted");
        exit();
    }
}

// Handle Update Action
if(isset($_POST['update'])) {
    $user->id = $_POST['id'];
    $user->name = $_POST['name'];
    $user->email = $_POST['email'];
    $user->status = $_POST['status'];
    if($user->update()) {
        header("Location: dashboard.php?msg=updated");
        exit();
    }
}

include 'assets/header.php';
?>

<div class="container mt-5">
    <div class="row">
        <div class="col-12">
            <h1 class="text-center mb-4">User Management</h1>
            <?php if(isset($_GET['msg'])): ?>
                <div class="alert alert-success">
                    User successfully <?php echo $_GET['msg']; ?>
                </div>
            <?php endif; ?>

            <?php if(isset($_GET['edit'])): ?>
                <?php 
                $user->id = $_GET['edit'];
                $editUser = $user->getOne(); 
                ?>
                <div class="card mb-4">
                    <div class="card-header">
                        Edit User
                    </div>
                    <div class="card-body">
                        <form method="POST">
                            <input type="hidden" name="id" value="<?php echo $editUser['id']; ?>">
                            <div class="mb-3">
                                <label>Name</label>
                                <input type="text" name="name" class="form-control" value="<?php echo $editUser['name']; ?>">
                            </div>
                            <div class="mb-3">
                                <label>Email</label>
                                <input type="email" name="email" class="form-control" value="<?php echo $editUser['email']; ?>">
                            </div>
                            <div class="mb-3">
                                <label>Status</label>
                                <select name="status" class="form-control">
                                    <option value="1" <?php echo $editUser['status'] == 1 ? 'selected' : ''; ?>>Active</option>
                                    <option value="0" <?php echo $editUser['status'] == 0 ? 'selected' : ''; ?>>Inactive</option>
                                </select>
                            </div>
                            <button type="submit" name="update" class="btn btn-primary">Update User</button>
                        </form>
                    </div>
                </div>
            <?php endif; ?>

            <div class="card">
                <div class="card-header">
                    Users List
                </div>
                <div class="card-body">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Status</th>
                                <th>Date</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $users = $user->getAll();
                            foreach($users as $row):
                            ?>
                            <tr>
                                <td><?php echo $row['name']; ?></td>
                                <td><?php echo $row['email']; ?></td>
                                <td><?php echo $row['status'] == 1 ? 'Active' : 'Inactive'; ?></td>
                                <td><?php echo $row['date']; ?></td>
                                <td>
                                    <a href="?edit=<?php echo $row['id']; ?>" class="btn btn-sm btn-primary">Edit</a>
                                    <a href="?delete=<?php echo $row['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">Delete</a>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="text-end mt-3">
        <a href="logout.php" class="btn btn-danger">Logout</a>
    </div>
</div>

<?php include 'assets/footer.php'; ?>