<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'farmer') {
    header("Location: ../auth/login.php");
    exit();
}
include '../config/db_connect.php';
include '../farmer/header.php';

$farmer_id = $_SESSION['user_id'];

// Handle New Post Submission
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['post_submit'])) {
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $content = mysqli_real_escape_string($conn, $_POST['content']);

    if (!empty($title) && !empty($content)) {
        $query = "INSERT INTO community_posts (farmer_id, title, content) VALUES (?, ?, ?)";
        $stmt = mysqli_prepare($conn, $query);
        mysqli_stmt_bind_param($stmt, "iss", $farmer_id, $title, $content);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
    }
    header("Location: community_forum.php");
    exit();
}

// Handle New Comment Submission
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['comment_submit'])) {
    $post_id = $_POST['post_id'];
    $comment = mysqli_real_escape_string($conn, $_POST['comment']);

    if (!empty($comment)) {
        $query = "INSERT INTO community_comments (post_id, farmer_id, comment) VALUES (?, ?, ?)";
        $stmt = mysqli_prepare($conn, $query);
        mysqli_stmt_bind_param($stmt, "iis", $post_id, $farmer_id, $comment);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
    }
    header("Location: community_forum.php");
    exit();
}

// Fetch All Posts
$query = "SELECT p.id, p.title, p.content, p.created_at, u.name 
          FROM community_posts p 
          JOIN users u ON p.farmer_id = u.id 
          ORDER BY p.created_at DESC";
$posts = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Community Forum | AgriCycle</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container mt-4">
    <h2 class="text-success">Community Forum</h2>
    <p class="text-muted">Share insights, ask questions, and engage with other farmers.</p>

    <!-- Post Creation Form -->
    <div class="card shadow-lg border-0 mb-4">
        <div class="card-body">
            <h5 class="card-title">Create a Post</h5>
            <form method="post">
                <div class="mb-3">
                    <label for="title" class="form-label">Title:</label>
                    <input type="text" class="form-control" name="title" required>
                </div>
                <div class="mb-3">
                    <label for="content" class="form-label">Content:</label>
                    <textarea class="form-control" name="content" rows="3" required></textarea>
                </div>
                <button type="submit" name="post_submit" class="btn btn-success">Post</button>
            </form>
        </div>
    </div>

    <!-- Display Posts -->
    <?php while ($post = mysqli_fetch_assoc($posts)) : ?>
        <div class="card shadow-lg border-0 mb-3">
            <div class="card-body">
                <h5 class="card-title"><?= htmlspecialchars($post['title']) ?></h5>
                <p class="card-text"><?= nl2br(htmlspecialchars($post['content'])) ?></p>
                <small class="text-muted">Posted by <?= htmlspecialchars($post['name']) ?> on <?= $post['created_at'] ?></small>
            </div>

            <!-- Fetch Comments -->
            <?php
            $post_id = $post['id'];
            $comment_query = "SELECT c.comment, c.created_at, u.name 
                              FROM community_comments c 
                              JOIN users u ON c.farmer_id = u.id 
                              WHERE c.post_id = ? 
                              ORDER BY c.created_at ASC";
            $comment_stmt = mysqli_prepare($conn, $comment_query);
            mysqli_stmt_bind_param($comment_stmt, "i", $post_id);
            mysqli_stmt_execute($comment_stmt);
            $comments = mysqli_stmt_get_result($comment_stmt);
            ?>

            <div class="card-footer bg-light">
                <h6>Comments:</h6>
                <?php while ($comment = mysqli_fetch_assoc($comments)) : ?>
                    <p><strong><?= htmlspecialchars($comment['name']) ?>:</strong> <?= nl2br(htmlspecialchars($comment['comment'])) ?></p>
                    <small class="text-muted"><?= $comment['created_at'] ?></small>
                    <hr>
                <?php endwhile; ?>
                
                <!-- Comment Form -->
                <form method="post">
                    <input type="hidden" name="post_id" value="<?= $post_id ?>">
                    <div class="mb-2">
                        <textarea class="form-control" name="comment" rows="2" placeholder="Write a comment..." required></textarea>
                    </div>
                    <button type="submit" name="comment_submit" class="btn btn-sm btn-primary">Comment</button>
                </form>
            </div>
        </div>
    <?php endwhile; ?>

</div>

</body>
</html>
