<?php
$file = "inputs.txt"; // File penyimpanan

// Tangkap input jika ada
if (!empty($_POST['input_text'])) {
    $text = trim($_POST['input_text']);
    $author = !empty($_POST['author']) ? trim($_POST['author']) : "Anonymous";
    file_put_contents($file, "$text|$author" . PHP_EOL, FILE_APPEND | LOCK_EX); // Simpan input ke file
}

// Baca semua input yang sudah disimpan
$entries = file_exists($file) ? file($file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) : [];
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quotes manual</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Indie+Flower&family=Lobster&family=Pacifico&family=Playfair+Display&family=Raleway:wght@300;700&family=Roboto:wght@400;900&display=swap');
        
        body {
            font-family: Arial, sans-serif;
            text-align: center;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
            overflow: hidden;
        }
        .container {
            margin-top: 50px;
            position: relative;
            z-index: 10;
        }
        form {
            background: rgba(255, 255, 255, 0.9);
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
            display: inline-block;
        }
        input[type="text"], button {
            padding: 10px;
            margin: 5px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
        button {
            background-color: #28a745;
            color: white;
            cursor: pointer;
            border: none;
        }
        button:hover {
            background-color: #218838;
        }
        /* Background text styling */
        .background-text {
            position: absolute;
            font-size: 30px;
            font-weight: bold;
            word-wrap: break-word;
            text-align: center;
            pointer-events: none;
            transform: rotate(calc(var(--rotation) * 1deg));
            color: var(--text-color);
        }
        .quote {
            font-family: var(--random-font-quote);
        }
        .author {
            font-family: var(--random-font-author);
            font-size: 24px;
            font-weight: normal;
            color: var(--author-color);
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Quotes of The Day</h2>
       <form method="POST">
    <div>
        <input type="text" name="input_text" placeholder="Masukkan kutipan..." required>
    </div>
    <div>
        <input type="text" name="author" placeholder="Nama penulis... (Anonymous jika kosong)">
    </div>
    <div>
        <button type="submit">Submit</button>
        <a href="/" onclick="window.location.href='/'">
            <img src="https://img.icons8.com/nolan/16/back.png" alt="back"> Back
        </a>
    </div>
</form>

    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const entries = <?php echo json_encode($entries); ?>;
            const body = document.body;
            const fonts = ['Indie Flower', 'Lobster', 'Pacifico', 'Playfair Display', 'Raleway', 'Roboto'];
            const softColors = ['#FFB6C1', '#FFD700', '#87CEEB', '#98FB98', '#DDA0DD', '#FFA07A', '#20B2AA', '#F08080', '#ADD8E6', '#FFC0CB'];

            function getRandomColor() {
                return softColors[Math.floor(Math.random() * softColors.length)];
            }

            entries.forEach(entry => {
                let [quote, author] = entry.split('|');
                let div = document.createElement("div");
                div.className = "background-text";

                let quoteSpan = document.createElement("span");
                quoteSpan.className = "quote";
                quoteSpan.textContent = quote;
                quoteSpan.style.fontFamily = fonts[Math.floor(Math.random() * fonts.length)];

                let authorSpan = document.createElement("span");
                authorSpan.className = "author";
                authorSpan.textContent = `\n- ${author} -`;
                authorSpan.style.fontFamily = fonts[Math.floor(Math.random() * fonts.length)];

                div.appendChild(quoteSpan);
                div.appendChild(document.createElement("br"));
                div.appendChild(authorSpan);

                // Posisi acak di layar
                let x = Math.random() * window.innerWidth * 0.8;
                let y = Math.random() * window.innerHeight * 0.8;
                let rotation = Math.random() * 20 - 10; // Rotasi acak antara -10° sampai 10°
                
                div.style.left = `${x}px`;
                div.style.top = `${y}px`;
                div.style.setProperty("--rotation", rotation);
                div.style.setProperty("--text-color", getRandomColor());
                div.style.setProperty("--author-color", getRandomColor());
                
                body.appendChild(div);
            });
        });
    </script>
</body>
</html>