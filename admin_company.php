<?php
include 'connection.php';
include 'admin_header.php';

$company = mysqli_fetch_assoc(mysqli_query($conn,"SELECT * FROM company_info LIMIT 1"));

if(isset($_POST['update'])){
    $name = mysqli_real_escape_string($conn,$_POST['name']);
    $email = mysqli_real_escape_string($conn,$_POST['email']);
    $phone = mysqli_real_escape_string($conn,$_POST['phone']);
    $address = mysqli_real_escape_string($conn,$_POST['address']);
    mysqli_query($conn,"UPDATE company_info SET name='$name', email='$email', phone='$phone', address='$address' WHERE id=1");
    $message[]='Coordonnées mises à jour!';
}
?>
<section class="admin-content">
    <h1>Coordonnées Entreprise</h1>
    <form method="post" class="form-admin">
        <input type="text" name="name" value="<?= $company['name'] ?>" required placeholder="Nom société">
        <input type="email" name="email" value="<?= $company['email'] ?>" required placeholder="Email">
        <input type="text" name="phone" value="<?= $company['phone'] ?>" required placeholder="Téléphone">
        <input type="text" name="address" value="<?= $company['address'] ?>" required placeholder="Adresse">
        <input type="submit" name="update" value="Mettre à jour" class="btn">
    </form>
</section>
