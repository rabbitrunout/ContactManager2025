<?php
session_start();
if (!isset($_SESSION["isLoggedIn"])) {
    header("Location: login_form.php");
    die();
}

require("database.php");

// JOIN contacts with contactTypes to get the contactType name
$queryContacts = '
    SELECT c.*, t.contactType
    FROM contacts c
    LEFT JOIN types t ON c.typeID = t.typeID
';
$statement1 = $db->prepare($queryContacts);
$statement1->execute();
$contacts = $statement1->fetchAll();
$statement1->closeCursor();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Manager - Home</title>
    <link rel="stylesheet" type="text/css" href="css/main.css" />
</head>
<body>
<?php include("header.php"); ?>

<main>
    <h2>Contact List</h2>
 <div class="table-responsive">
    <table>
        <thead>
        <tr>
            <th>First Name</th>
            <th>Last Name</th>
            <th>Email</th>
            <th>Phone</th>
            <th>Status</th>
            <th>Birth Date</th>
            <th>Contact Type</th>
            <th>Photo</th>
            <th colspan="3">Actions</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($contacts as $contact): ?>
            <tr>
                <td><?php echo htmlspecialchars($contact['firstName']); ?></td>
                <td><?php echo htmlspecialchars($contact['lastName']); ?></td>
                <td><a href="mailto:<?php echo htmlspecialchars($contact['emailAddress']); ?>">
                    <?php echo htmlspecialchars($contact['emailAddress']); ?></a></td>
                <td><a href="tel:<?php echo htmlspecialchars($contact['phone']); ?>">
                    <?php echo htmlspecialchars($contact['phone']); ?></a></td>
                <td><?php echo htmlspecialchars($contact['status']); ?></td>
                <td><?php echo htmlspecialchars($contact['dob']); ?></td>
                <td><?php echo htmlspecialchars($contact['contactType']); ?></td>
                <td>
                    <img src="<?php echo htmlspecialchars('./images/' . $contact['imageName']); ?>"
                         alt="<?php echo htmlspecialchars($contact['firstName'] . ' ' . $contact['lastName']); ?>"
                         style="max-width: 80px; border-radius: 6px; box-shadow: 0 2px 6px rgba(0,0,0,0.1);">
                </td>
                <td>
                    <form action="update_contact_form.php" method="post">
                        <input type="hidden" name="contact_id" value="<?php echo $contact['contactID']; ?>" />
                        <input type="submit" value="✏️ Update" />
                    </form>
                </td>
                <td>
                    <form action="delete_contact.php" method="post" onsubmit="return confirm('Are you sure you want to delete this contact?');">
                        <input type="hidden" name="contact_id" value="<?php echo $contact['contactID']; ?>" />
                        <input type="submit" value="🗑 Delete" style="background-color: #dc3545;" />
                    </form>
                </td>
                <td>
                    <form action="contact_details.php" method="post">
                        <input type="hidden" name="contact_id" value="<?php echo $contact['contactID']; ?>" />
                        <input type="submit" value="🔍 View" style="background-color: #17a2b8;" />
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>    

    <p><a href="add_contact_form.php">➕ Add Contact</a></p>
    <p><a href="logout.php">🚪 Logout</a></p>
</main>

<?php include("footer.php"); ?>
</body>
</html>
