
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../public/styles/offer_create.css">
    <title>Document</title>
</head>
<body>
    <?php include '../includes/navbar.php';?>
    <div class="container">
        <div class="form-box">
            
            <form action="insert_offer.php" method="POST" enctype="multipart/form-data">
                
                <div class="form-group">
                    <label for="title">Nazev:</label>
                    <input type="text" id="title" name="title" required placeholder="Např. Horské kolo">
                </div>

                <div class="form-group">
                    <label for="image">Pridej obrazek:</label>
                    <input type="file" id="image" required name="image">
                </div>

                <div class="form-group">
                    <label for="price">Cena (kc):</label>
                    <input type="number" id="price" name="price" step="0.01" required placeholder="1000">
                </div>

                <div class="form-group">
                    <label for="category">Kategorie:</label>
                    <select id="category" name="category_id" required>
                        <option value="" disabled selected>Vyberte kategorii</option>
                        <option value="1">Elektronika</option>
                        <option value="2">Oblečení</option>
                        <option value="3">Sport</option>
                        <option value="4">Nábytek</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="condition">Kondice:</label>
                    <select id="condition" name="condition" required>
                        <option value="" disabled selected>Vyberte stav</option>
                        <option value="new">Nové</option>
                        <option value="used">Použité</option>
                        <option value="damaged">Poškozené</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="description">Popis:</label>
                    <textarea id="description" name="description" rows="4" required placeholder="Popište prodávaný předmět..."></textarea>
                </div>

                <div class="form-group">
                    <label for="location">Lokace:</label>
                    <input type="text" id="location" name="location" required placeholder="Praha">
                </div>

                <div class="form-footer">
                    <button type="submit" class="btn-submit">Ulozit</button>
                </div>

            </form>
        </div>
    </div>
</body>
</html>