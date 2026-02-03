<div class="page-header">
    <h2>Item Details</h2>
    <div>
        <a href="/Inventory/item" class="btn btn-secondary">Back to List</a>
        <a href="/Inventory/item/edit/<?= $book['id'] ?>" class="btn btn-warning">Edit Book</a>
    </div>
</div>

<div class="book-details">
    <div class="detail-group">
        <label>ID:</label>
        <span><?= htmlspecialchars($book['id']) ?></span>
    </div>
    
    <div class="detail-group">
        <label>Title:</label>
        <span><?= htmlspecialchars($book['title']) ?></span>
    </div>
    
    <div class="detail-group">
        <label>Author:</label>
        <span><?= htmlspecialchars($book['author']) ?></span>
    </div>
    
    <div class="detail-group">
        <label>ISBN:</label>
        <span><?= htmlspecialchars($book['isbn']) ?></span>
    </div>
    
    <div class="detail-group">
        <label>Published Year:</label>
        <span><?= htmlspecialchars($book['published_year']) ?></span>
    </div>
    
    <div class="detail-group">
        <label>Status:</label>
        <span class="badge <?= $book['available'] ? 'badge-success' : 'badge-danger' ?>">
            <?= $book['available'] ? 'Available' : 'Borrowed' ?>
        </span>
    </div>
    
    <div class="detail-group">
        <label>Added On:</label>
        <span><?= date('F j, Y, g:i a', strtotime($book['created_at'])) ?></span>
    </div>
</div>