<?php
require_once('database.php');

$contact_id = filter_input(INPUT_POST, 'contact_id', FILTER_VALIDATE_INT);
if ($contact_id == null) {
    header("Location: index.php");
    exit();
}

// Get contact details
$query = '
    SELECT c.*, t.contactType
    FROM contacts c
    LEFT JOIN types t ON c.typeID = t.typeID
    WHERE c.contactID = :contact_id';
$statement = $db->prepare($query);
$statement->bindValue(':contact_id', $contact_id);
$statement->execute();
$contact = $statement->fetch();
$statement->closeCursor();

// Prepare image
$imageName = $contact['imageName'];
$dotPosition = strrpos($imageName, '.');
$baseName = substr($imageName, 0, $dotPosition);
$extension = substr($imageName, $dotPosition);
if (str_ends_with($baseName, '_100')) {
    $baseName = substr($baseName, 0, -4);
}
$imageName_400 = $baseName . '_400' . $extension;
$imagePath = './images/' . $imageName_400;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Contact Details | Contact Manager</title>
    <link rel="stylesheet" href="css/main.css">
</head>
<body>
<?php include("header.php"); ?>

<main>
    <section style="text-align:center;">
        <h2><?php echo htmlspecialchars($contact['firstName'] . ' ' . $contact['lastName']); ?></h2>
        <img src="<?php echo htmlspecialchars($imagePath); ?>"
             alt="Photo of <?php echo htmlspecialchars($contact['firstName']); ?>"
             style="max-width: 200px; width: 100%; border-radius: 10px; box-shadow: 0 4px 12px rgba(0,0,0,0.1); margin-bottom: 20px;">
    </section>

    <section>
        <table>
            <tbody>
                <tr>
                    <th>First Name</th>
                    <td><?php echo htmlspecialchars($contact['firstName']); ?></td>
                </tr>
                <tr>
                    <th>Last Name</th>
                    <td><?php echo htmlspecialchars($contact['lastName']); ?></td>
                </tr>
                <tr>
                    <th>Email</th>
                    <td><a href="mailto:<?php echo htmlspecialchars($contact['emailAddress']); ?>">
                        <?php echo htmlspecialchars($contact['emailAddress']); ?></a></td>
                </tr>
                <tr>
                    <th>Phone</th>
                    <td><a href="tel:<?php echo htmlspecialchars($contact['phone']); ?>">
                        <?php echo htmlspecialchars($contact['phone']); ?></a></td>
                </tr>
                <tr>
                    <th>Status</th>
                    <td><?php echo htmlspecialchars($contact['status']); ?></td>
                </tr>
                <tr>
                    <th>Birth Date</th>
                    <td><?php echo htmlspecialchars($contact['dob']); ?></td>
                </tr>
                <tr>
                    <th>Contact Type</th>
                    <td><?php echo htmlspecialchars($contact['contactType']); ?></td>
                </tr>
            </tbody>
        </table>
    </section>

    <p><a href="index.php">← Back to Contact List</a></p>
</main>

<?php include("footer.php"); ?>
</body>
</html>
