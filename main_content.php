<!-- Main Content -->
<div class="main-content" x-data="{ isSaving: false, showModal: false }">
    <h1 class="text-3xl font-bold text-center text-gray-800 mb-6">Croco Notes</h1>

    <!-- Tombol untuk membuka modal -->
    <button @click="showModal = true" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg mb-6 transition duration-300 flex items-center">
        <i class="fas fa-plus mr-2"></i>
        Add New Note
    </button>

    <!-- Fitur Search -->
    <div class="mb-6">
        <form action="tasking.php" method="GET" class="flex items-center">
            <input type="text" name="search" value="<?= htmlspecialchars($search) ?>" placeholder="Search by title, author, or link..." class="w-full px-4 py-2 border border-gray-300 rounded-l-lg focus:outline-none focus:ring-blue-500 focus:border-blue-500">
            <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-r-lg">Search</button>
        </form>
    </div>

    <!-- Tabel untuk menampilkan data -->
    <div class="bg-white shadow-md rounded-lg overflow-hidden">
        <div class="overflow-x-auto"> <!-- Hanya satu div untuk overflow -->
            <table class="min-w-full w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Number</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Title</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Author</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Link</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Created At</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    <?php
                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            echo "<tr class='hover:bg-gray-50 transition duration-200'>
                                    <td class='px-6 py-4'>{$row['number']}</td>
                                    <td class='px-6 py-4'>{$row['title']}</td>
                                    <td class='px-6 py-4'>{$row['author']}</td>
                                    <td class='px-6 py-4'><a href='{$row['link']}' target='_blank' class='text-blue-500 hover:text-blue-600'>{$row['link']}</a></td>
                                    <td class='px-6 py-4'>{$row['created_at']}</td>
                                    <td class='px-6 py-4'>
                                        <a href='process_tasking.php?delete={$row['id']}' class='text-red-500 hover:text-red-600'>Delete</a>
                                    </td>
                                  </tr>";
                        }
                    } else {
                        echo "<tr><td colspan='6' class='px-6 py-4 text-center text-gray-500'>No data found</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Pagination -->
    <div class="flex justify-center mt-6">
        <nav class="inline-flex rounded-md shadow-sm">
            <?php if ($page > 1): ?>
                <a href="tasking.php?page=<?= $page - 1 ?>&search=<?= urlencode($search) ?>" class="px-4 py-2 text-sm font-medium text-gray-500 bg-white border border-gray-300 rounded-l-lg hover:bg-gray-100 pagination-button">Previous</a>
            <?php endif; ?>

            <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                <a href="tasking.php?page=<?= $i ?>&search=<?= urlencode($search) ?>" class="px-4 py-2 text-sm font-medium text-gray-500 bg-white border border-gray-300 hover:bg-gray-100 pagination-button <?= ($i == $page) ? 'bg-blue-500 text-white' : '' ?>"><?= $i ?></a>
            <?php endfor; ?>

            <?php if ($page < $total_pages): ?>
                <a href="tasking.php?page=<?= $page + 1 ?>&search=<?= urlencode($search) ?>" class="px-4 py-2 text-sm font-medium text-gray-500 bg-white border border-gray-300 rounded-r-lg hover:bg-gray-100 pagination-button">Next</a>
            <?php endif; ?>
        </nav>
    </div>

    <!-- Modal untuk menambah data -->
    <div x-show="showModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-4 modal" @click.away="showModal = false">
        <div class="bg-white rounded-lg shadow-lg w-full max-w-md p-6">
            <h2 class="text-xl font-bold text-gray-800 mb-4">Add New Note</h2>
            <form action="process_tasking.php" method="POST" @submit="isSaving = true">
                <div class="mb-4">
                    <label for="title" class="block text-sm font-medium text-gray-700">Title</label>
                    <input type="text" id="title" name="title" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500" required>
                </div>
                <div class="mb-4">
                    <label for="author" class="block text-sm font-medium text-gray-700">Author</label>
                    <input type="text" id="author" name="author" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500" required>
                </div>
                <div class="mb-4">
                    <label for="link" class="block text-sm font-medium text-gray-700">Link</label>
                    <input type="url" id="link" name="link" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500" required>
                </div>
                <div class="flex justify-end">
                    <button type="button" @click="showModal = false" class="mr-2 px-4 py-2 text-gray-600 hover:text-gray-800">Cancel</button>
                    <button type="submit" name="add_note" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg flex items-center transition duration-300">
                        <span x-show="!isSaving">Save</span>
                        <span x-show="isSaving" class="flex items-center">
                            <svg class="animate-spin h-5 w-5 mr-2 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            Saving...
                        </span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>