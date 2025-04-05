<?php
session_start();
include '../config/db_connect.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$user_role = $_SESSION['role'];

// Handle New Post
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['post_submit'])) {
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $content = mysqli_real_escape_string($conn, $_POST['content']);

    $query = "INSERT INTO community_posts (user_id, role, title, content) VALUES (?, ?, ?, ?)";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "isss", $user_id, $user_role, $title, $content);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    header("Location: community_forum.php");
    exit();
}

// Handle New Comment
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['comment_submit'])) {
    $post_id = $_POST['post_id'];
    $comment = mysqli_real_escape_string($conn, $_POST['comment']);

    $query = "INSERT INTO community_comments (post_id, user_id, role, comment) VALUES (?, ?, ?, ?)";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "iiss", $post_id, $user_id, $user_role, $comment);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    header("Location: community_forum.php");
    exit();
}

// Get name by role and ID
function getUserName($conn, $role, $id) {
    $tableMap = [
        'farmer' => 'farmers',
        'buyer' => 'buyers',
        'insurance_agent' => 'insurance_agents',
        'admin' => 'admins'
    ];

    if (!array_key_exists($role, $tableMap)) return 'Unknown';

    $table = $tableMap[$role];
    $result = mysqli_query($conn, "SELECT name FROM $table WHERE id = $id");

    if ($row = mysqli_fetch_assoc($result)) {
        return $row['name'];
    } else {
        return 'Unknown';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Community Forum</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<?php
$home_link = "#";
if ($user_role == 'farmer') {
    $home_link = '../dashboard/farmer_dashboard.php';
} elseif ($user_role == 'buyer') {
    $home_link = '../dashboard/buyer_dashboard.php';
}
?>
<nav class="navbar navbar-expand-lg navbar-dark bg-success">
  <div class="container-fluid">
    <a class="navbar-brand" href="<?= $home_link ?>">AgriConnect</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav ms-auto">
        <li class="nav-item">
          <a class="nav-link active" href="<?= $home_link ?>">Home</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="community_forum.php">Community Forum</a>
        </li>
        <li class="nav-item">
          <span class="nav-link text-white"><?= ucfirst($user_role) ?> | <?= getUserName($conn, $user_role, $user_id) ?></span>
        </li>
        <li class="nav-item">
          <a class="nav-link text-white" href="../auth/logout.php">Logout</a>
        </li>
      </ul>
    </div>
  </div>
</nav>

<div class="container mt-4">
    <h2 class="text-success">Community Forum</h2>

    <form method="post" class="card p-3 mb-4 shadow-sm">
        <h5>Ask a Question / Share Info</h5>
        <input type="text" name="title" class="form-control mb-2" placeholder="Title" required>
        <textarea name="content" class="form-control mb-2" placeholder="Content" rows="3" required></textarea>
        <button name="post_submit" class="btn btn-success">Post</button>
    </form>

    <?php
    $query = "SELECT * FROM community_posts ORDER BY created_at DESC";
    $posts = mysqli_query($conn, $query);

    while ($post = mysqli_fetch_assoc($posts)) {
        $poster_name = getUserName($conn, $post['role'], $post['user_id']);
    ?>
        <div class="card mb-3 shadow-sm">
            <div class="card-body">
                <h5><?= htmlspecialchars($post['title']) ?></h5>
                <p><?= nl2br(htmlspecialchars($post['content'])) ?></p>
                <small class="text-muted">Posted by <?= ucfirst($post['role']) ?>: <strong><?= htmlspecialchars($poster_name) ?></strong> on <?= $post['created_at'] ?></small>

                <?php if ($post['user_id'] == $user_id && $post['role'] == $user_role): ?>
                    <div class="mt-2">
                        <a href="edit_post.php?id=<?= $post['id'] ?>" class="btn btn-warning btn-sm">Edit</a>
                        <a href="delete_post.php?id=<?= $post['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this post?')">Delete</a>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Comment Section -->
            <div class="card-footer bg-light">
                <form method="post" class="mb-3">
                    <input type="hidden" name="post_id" value="<?= $post['id'] ?>">
                    <textarea name="comment" class="form-control mb-2" rows="2" placeholder="Write a comment..." required></textarea>
                    <button name="comment_submit" class="btn btn-primary btn-sm">Comment</button>
                </form>

                <?php
                $post_id = $post['id'];
                $comment_query = "SELECT * FROM community_comments WHERE post_id = $post_id ORDER BY created_at ASC";
                $comments = mysqli_query($conn, $comment_query);

                while ($comment = mysqli_fetch_assoc($comments)) {
                    $commenter_name = getUserName($conn, $comment['role'], $comment['user_id']);
                ?>
                    <div class="mb-2">
                        <strong><?= ucfirst($comment['role']) ?> <?= htmlspecialchars($commenter_name) ?>:</strong>
                        <?= nl2br(htmlspecialchars($comment['comment'])) ?><br>
                        <small class="text-muted"><?= $comment['created_at'] ?></small>

                        <?php if ($comment['user_id'] == $user_id && $comment['role'] == $user_role): ?>
                            <div class="mt-1">
                                <a href="edit_comment.php?id=<?= $comment['id'] ?>" class="btn btn-warning btn-sm">Edit</a>
                                <a href="delete_comment.php?id=<?= $comment['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this comment?')">Delete</a>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php } ?>
            </div>
        </div>
    <?php } ?>
</div>
</body>
</html>
