<?php
include '../components/connect.php';
session_start();

if(!isset($_SESSION['admin_id'])){
   header('location:admin_login.php');
}

require_once('tcpdf/tcpdf.php');
require_once('phpqrcode/qrlib.php');

if(isset($_GET['order_id'])){
   $order_id = $_GET['order_id'];

   // Fetch order details
   $select_order = $conn->prepare("SELECT * FROM `orders` WHERE id = ?");
   $select_order->execute([$order_id]);
   $order = $select_order->fetch(PDO::FETCH_ASSOC);

   // Save invoice details to the invoice table
   $invoice_id = uniqid();
   $insert_invoice = $conn->prepare("INSERT INTO `invoice` (invoice_id, user_id, name, number, email, method, address, total_products, total_price, placed_on, payment_status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
   $insert_invoice->execute([$invoice_id, $order['user_id'], $order['name'], $order['number'], $order['email'], $order['method'], $order['address'], $order['total_products'], $order['total_price'], $order['placed_on'], $order['payment_status']]);

   // Generate QR code
   $qrContent = "Invoice ID: $invoice_id\nUser ID: {$order['user_id']}\nName: {$order['name']}\nTotal Price: {$order['total_price']}";
   $qrFile = "qrcodes/$invoice_id.png";
   QRcode::png($qrContent, $qrFile);

   // Generate PDF
   $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
   $pdf->SetCreator(PDF_CREATOR);
   $pdf->SetAuthor('Your Company');
   $pdf->SetTitle('Invoice');
   $pdf->SetSubject('Invoice');
   $pdf->SetKeywords('Invoice, PDF');

   $pdf->AddPage();
   $pdf->SetFont('helvetica', '', 12);

   $html = "<h1>MIM Traders (Pvt) Ltd. Invoice</h1>
            <p>Invoice ID: $invoice_id</p>
            <p>Name: {$order['name']}</p>
            <p>Number: {$order['number']}</p>
            <p>Email: {$order['email']}</p>
            <p>Address: {$order['address']}</p>
            <p>Total Products: {$order['total_products']}</p>
            <p>Total Price LKR: {$order['total_price']} .00/=</p>
            <p>Payment Method: {$order['method']}</p>
            <p>Payment Status: {$order['payment_status']}</p>
            <p>Placed On: {$order['placed_on']}</p>
            <br><br><br>
            <p>Thank you for your purchase! We appreciate your business.</p>";
            
            

   $pdf->writeHTML($html, true, false, true, false, '');
   $pdf->Image($qrFile, 150, 80, 50, 50, 'PNG');

   $pdf->Output("invoice_$invoice_id.pdf", 'D');
}
?>