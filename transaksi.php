<?php
require 'ceklogin.php';

if(!isset($_SESSION['transaksi'])){
    //sudah login
} else {
    //belum login
    header('location:transaksi.php');
}

?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <meta name="description" content="" />
        <meta name="author" content="" />
        <title>kasir SRC Mutiara</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
        <link href="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/style.min.css" rel="stylesheet" />
        <link href="css/styles.css" rel="stylesheet" />
        <link href="https://cdn.datatables.net/1.10.20/css/dataTables.bootstrap4.min.css" rel="stylesheet" crossorigin="anonymous" />
        <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
    </head>
    <body class="sb-nav-fixed">
        <nav class="sb-topnav navbar navbar-expand navbar-dark bg-dark">
        <!-- Navbar Brand-->
            <a class="navbar-brand ps-3" href="index.php">KASIR SRC MUTIARA</a>
        <!-- Sidebar Toggle-->
            <button class="btn btn-link btn-sm order-1 order-lg-0 me-4 me-lg-0" id="sidebarToggle" href="#!"><i class="fas fa-bars"></i></button>
        <!-- Navbar-->
        </nav>
        <div id="layoutSidenav">
            <div id="layoutSidenav_nav">
                <nav class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion">
                    <div class="sb-sidenav-menu">
                        <div class="nav">
                            <div class="sb-sidenav-menu-heading">MENU</div>
                            <a class="nav-link" href="index.php">
                                <div class="sb-nav-link-icon"><i class="fa-solid fa-basket-shopping" style="color: #f00030;"></i></div>
                                Pesanan
                            </a> 
                            <a class="nav-link" href="stok.php">
                                <div class="sb-nav-link-icon"><i class="far fa-clipboard"  style="color: #f00030;"></i></div>
                                Stok Barang
                            </a>    
                            <a class="nav-link" href="masuk.php">
                                <div class="sb-nav-link-icon"><i class="fas fa-cart-plus" style="color: #f00030;"></i></div>
                                Barang Masuk
                            </a>    
                            <a class="nav-link" href="pelanggan.php">
                                <div class="sb-nav-link-icon"><i class="fa-solid fa-user" style="color: #f00030;"></i></div>
                                Kelola Pelanggan
                            </a>    
                            <a class="nav-link" href="transaksi.php">
                                <div class="sb-nav-link-icon"><i class="fa-solid fa-money-bills" style="color: #f00030;"></i></div>
                                Transaksi
                            </a>    
                            <a class="nav-link" href="logout.php">
                                <div class="sb-nav-link-icon"><i class="fa-solid fa-arrow-up-from-bracket" style="color: #e0002d;"></i></div>
                                Logout
                            </a>       
                        </div>
                    </div>
                    <div class="sb-sidenav-footer">
                        <div class="small">SRC Mutiara</div>
                        Duji
                    </div>
                </nav>
            </div>
            <div id="layoutSidenav_content">
                <main>
                    <div class="container-fluid px-4">
                        <h1 class="mt-4">TRANSAKSI</h1>
                        <ol class="breadcrumb mb-4">
                            <li class="breadcrumb-item active">Transaksi Pembelian : </li>
                        </ol>

                        <tbody>
                                    <?php
                                    $get = mysqli_query($c,"select * from detailpesanan p, produk pr where p.idproduk=pr.idproduk and idpesanan='$idp'");
                                    $i = 1;
                                    $subtotals = []; // Array untuk menyimpan subtotal

                                    while($p = mysqli_fetch_array($get)){
                                    $idpr= $p['idproduk'];
                                    $iddp = $p['iddetailpesanan'];
                                    $harga = $p['harga'];
                                    $deskripsi = $p['deskripsi'];
                                    $namaproduk = $p['namaproduk'];
                                    $qty = $p['qty'];
                                    $subtotal = $qty*$harga;
                                    $subtotals[] = $subtotal; 
                                    
                                    ?>
                                  
                                    <?php
                                    }; //end of while
                                    $total = array_sum($subtotals);
                                    ?>
                                       
                                <tr>
                                    <h2 colspan="5">Total : Rp<?=number_format($total);?></h2>
                                </tr>
                                    </tbody>
                        <div class="card mb-4">
                            <div class="card-header">
                                <i class="fas fa-table me-1"></i>
                                Data Table Transaksi
                            </div>
                            <div class="card-body">
                                <table id="datatablesSimple">
                        
                                <thead>
                                        <tr>
                                            <th>ID Transaksi</th>
                                            <th>ID Pesanan</th>
                                            <th>Tanggal</th>
                                            <th>Nama Pelanggan</th>
                                            <th>Jumlah</th>
                                            <th>Total</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                            
                                    <tbody>
                                    <?php
                                    $get = mysqli_query($c,"select * from transaksi p, transaksi pr where p.idtransaksi=pr.idtransaksi and iddetailpesanan='$iddetailpesanan' ");
                                    $i = 1;

                                    while($p = mysqli_fetch_array($get)){
                                    
                                        $idtransaksi = $_POST['idtransaksi'];
                                        $namapelanggan = $_POST['namapelanggan'];
                                        $iddetailpesanan = $_POST['iddetailpesanan'];
                                        $alamat = $_POST['alamat'];
                                        $qty = $p['qty'];
                                        $tanggal = $p['tanggal'];
                                        $total = $p['total'];
                                        
                                        
                                        

                                    ?>

                                        <tr>
                                            <td><?=$i++;?></td>
                                            <td><?=$iddetailpesanan;?></td>
                                            <td><?=$tanggal;?></td>
                                            <td><?=$namapelanggan;?> - <?=$alamat;?></td>
                                            <td><?=number_format($qty);?></td>
                                            <td>Rp<?=number_format($total);?></td>
                                            <td>
                                            <button type="button" class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#edit<?=$idpr;?>">
                                                Edit
                                            </button>
                                            <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#delete<?=$idpr;?>">
                                                Delete
                                            </button>
                                            </td>
                      
                                        </tr>

                                        <!-- The Modal Delete -->
                                        <div class="modal" id="delete<?=$idpesanan;?>">
                                        <div class="modal-dialog">
                                            <div class="modal-content">

                                            <!-- Modal Header -->
                                            <div class="modal-header">
                                                <h4 class="modal-title">Hapus data pesanan</h4>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>

                                            <form method="post">
                                                <!-- Modal body -->
                                                <div class="modal-body">
                                                    Apakah Anda yakin ingin menghapus Pesanan ini?
                                                    <input type="hidden" name="idp" value="<?=$idproduk;?>">
                                                </div>

                                                <!-- Modal footer -->
                                                <div class="modal-footer">
                                                    <button type="submit" class="btn btn-success" name="hapuspesanan"> Ya</button>
                                                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
                                                </div>

                                            </form>


                                            </div>
                                        </div>
                                        </div>
                                
                                    <?php
                                            }
                                            ?>
                                    </tbody>

                                                        
                                                </div>
                                                    </div>
                                                </div>
                                            </main>
                                <!-- <script>
                                window.console = window.console || function(t) {};
                                </script>
                            
                                <script>
                                if (document.location.search.match(/type=embed/gi)) {
                                    window.parent.postMessage("resize", "*");
                                }
                                </script>
                            
                            </head>
                            
                            <body translate="no" >
                            
                            
                            <div id="invoice-POS">
                            
                                <center id="top">
                                <div class="info"> 
                                    <h5>SRC MUTIARA</h5>
                                    <p>By Duji</p>
                                </div>End Info -->
                                <!-- </center>End InvoiceTop -->
                            
                                <!-- <div id="mid">
                                <div class="info">
                                    <h2>Transaksi</h2>
                                    <p>ID Pesanan      :</p>
                                    <p>Nama Pelanggan  :</p>
                                    <p>Alamat          :</p>
                                    <p>No Telepon      :</p>
                                    <p>Tanggal         :</p>
                                </div>
                                </div>End Invoice Mid -->
                            
                                <!-- <div id="bot">
 
                    <div id="table">
                        <table>
                            <tr class="tabletitle">
                                <td class="item"><h2>Nama Produk</h2></td>
                                <td class="item"><h2>Deaskripsi</h2></td>
                                <td class="Hours"><h2>Jumlah</h2></td>
                                <td class="Rate"><h2>Sub Total</h2></td>
                            </tr>
 
                            <tr class="service">
                                <td class="tableitem"><p class="itemtext">Roti Panggang</p></td>
                                <td class="tableitem"><p class="itemtext">Roti</p></td>
                                <td class="tableitem"><p class="itemtext">5</p></td>
                                <td class="tableitem"><p class="itemtext">Rp5.000,-</p></td>
                            </tr>
 
 
                            <tr class="tabletitle">
                                <td></td>
                                <td class="Rate"><h2>Total</h2></td>
                                <td class="Rate"><h2></h2></td>
                                <td class="payment"><h2>Rp37.500,-</h2></td>
                            </tr>
 
                        </table>
                    </div>End Table -->
<!--  
                    <div id="legalcopy">
                        <p class="legal"><strong>Terimakasih Telah Berbelanja!</strong>  Barang yang sudah dibeli tidak dapat dikembalikan. Jangan lupa berkunjung kembali
                        </p>
                    </div>
 
                </div>End InvoiceBot-->
  <!-- </div>End Invoice -->

  
</body>
                
                
            </div>
        </div>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
        <script src="js/scripts.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.min.js" crossorigin="anonymous"></script>
        <script src="assets/demo/chart-area-demo.js"></script>
        <script src="assets/demo/chart-bar-demo.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/umd/simple-datatables.min.js" crossorigin="anonymous"></script>
        <script src="js/datatables-simple-demo.js"></script>

        
    </body>
</html>

 
 
 
