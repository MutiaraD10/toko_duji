<?php

session_start();


//bikin koneksi
$c = mysqli_connect('localhost','root','','toko_duji');

//login
if(isset($_POST['login'])){
    //initiate variable
    $username = $_POST['username'];
    $password = $_POST['password'];

    $check = mysqli_query($c,"SELECT * FROM login_user WHERE username='$username' and password='$password' ");
    $hitung = mysqli_num_rows($check);

    if($hitung > 0){
        //jika datanya ditemukan
        //berhasil login

        $_SESSION['login'] = 'True';
        header('location:index.php');
    }
    else{
        //data tak ditemukan
        //gagal login
        echo'
        <script>alert("Username atau Password salah, Mohon Masukan Kembali");
        window.location.href="login.php"
        </script>
        ';
    }
}


if(isset($_POST['tambahbarang'])){
    $namaproduk = $_POST['namaproduk'];
    $deskripsi = $_POST['deskripsi'];
    $stok = $_POST['stok'];
    $harga = $_POST['harga'];
   

    $insert = mysqli_query($c,"insert into produk (namaproduk,deskripsi,harga,stok) values ('$namaproduk','$deskripsi','$harga','$stok')");

    if($insert){
        header('location:stok.php');
    }
    else{
        //data tak ditemukan
        //gagal menambah produk
        echo'
        <script>alert("Gagal Menambah Produk Baru, Mohon Masukan Kembali");
        window.location.href="stok.php"
        </script>
        ';
    }
    
}

if(isset($_POST['tambahpelanggan'])){
    $namapelanggan = $_POST['namapelanggan'];
    $notelepon = $_POST['notelepon'];
    $alamat = $_POST['alamat'];
   
    $insert = mysqli_query($c,"insert into pelanggan (namapelanggan,notelepon,alamat) values ('$namapelanggan','$notelepon','$alamat')");

    if($insert){
        header('location:pelanggan.php');
    }
    else{
        //data tak ditemukan
        //gagal menambah produk
        echo'
        <script>alert("Gagal Menambah Pelanggan Baru, Mohon Masukan Kembali");
        window.location.href="pelanggan.php"
        </script>
        ';
    }
    
}

if(isset($_POST['tambahpesanan'])){
    $idpelanggan = $_POST['idpelanggan'];
   
    $insert = mysqli_query($c,"insert into pesanan (idpelanggan) values ('$idpelanggan')");

    if($insert){
        header('location:index.php');
    }
    else{
        //data tak ditemukan
        //gagal menambah pesanan
        echo'
        <script>alert("Gagal Menambah Pesanan Baru, Mohon Masukan Kembali");
        window.location.href="index.php"
        </script>
        ';
    }
    
}

    //produk dipilih dipesanan
if(isset($_POST['addproduk'])){
    $idproduk = $_POST['idproduk'];
    $idp = $_POST['idp'];
    $qty = $_POST['qty']; //jumlah

    //hitung stok yang ada
    $hitung1 = mysqli_query($c, "select * from produk where idproduk='$idproduk'");
    $hitung2 = mysqli_fetch_array($hitung1);
    $stoksekarang = $hitung2['stok']; //stok barang saat ini

    if($stoksekarang>=$qty){

        //kurangi stok dengan jumlah barang yang akan dikeluarkan
        $selisih = $stoksekarang-$qty;

        //stok cukup
        $insert = mysqli_query($c,"insert into detailpesanan (idproduk,idpesanan,qty) values ('$idproduk','$idp','$qty')");
        $update =mysqli_query($c,"update produk set stok='$selisih' where idproduk='$idproduk'");

    if($insert&&$update){
        header('location:view.php?idp='.$idp);
    }
    
    else{
        echo'
        <script>alert("Gagal Menambah Pelanggan Baru, Mohon Masukan Kembali");
        window.location.href="view.php?idp='.$idp.'"
        </script>
        ';
    }
    }
    else{
        //stok barang tidak cukup
        echo'
        <script>alert("Stok barang tidak cukup");
        window.location.href="view.php?idp='.$idp.'"
        </script>
        ';
    }
   
    
    
}

//menambah barang masuk
if(isset($_POST['barangmasuk'])){
    $idproduk = $_POST['idproduk'];
    $qty = $_POST['qty'];

    //caritahu stok sekarang
    $caristok = mysqli_query($c, "select * from produk where idproduk='$idproduk'");
    $caristok2 = mysqli_fetch_array($caristok);
    $stoksekarang = $caristok2['stok'];

    //hitung
    $newstok = $stoksekarang+$qty;

    $insertbarangmasuk = mysqli_query($c, "insert into masuk (idproduk,qty) values ('$idproduk','$qty')");
    $update = mysqli_query($c,"update produk set stok='$newstok' where idproduk='$idproduk'");

    if($insertbarangmasuk&&$update){
        header('location:masuk.php');
    }
    else{
        echo'
        <script>alert("Gagal");
        window.location.href="masuk.php"
        </script>
        ';
    }
}


//hapus produk pesanan
if(isset($_POST['hapusprodukpesanan'])){
    $iddp = $_POST['iddp']; //iddetail pesanan
    $idpr = $_POST['idpr'];
    $idpesanan = $_POST['idpesanan'];

    //cek qty sekarang
    $cek1 = mysqli_query($c, "select * from detailpesanan where iddetailpesanan='$iddp'");
    $cek2 = mysqli_fetch_array($cek1);
    $qtysekarang = $cek2['qty'];

    //cek stok sekarang
    $cek3 = mysqli_query($c, "select * from produk where idproduk='$idpr'");
    $cek4 = mysqli_fetch_array($cek3);
    $stoksekarang = $cek4['stok'];

    $hitung = $stoksekarang+$qtysekarang;

    $update = mysqli_query($c,"update produk set stok='$hitung' where idproduk='$idpr'"); //update stok
    $hapus = mysqli_query($c,"delete from detailpesanan where idproduk='$idpr' and iddetailpesanan='$iddp'");

    if($update&&$hapus){
        header('location:view.php?idp'.$idpesanan);
    }
    else{
        echo'
        <script>alert("Gagal menghapus barang");
        window.location.href="view.php?idp='.$idpesanan.'"
        </script>
        ';
    }
}


//edit barang
if(isset($_POST['editbarang'])){
    $np = $_POST['namaproduk'];
    $desc = $_POST['deskripsi'];
    $harga = $_POST['harga'];
    $idp = $_POST['idp']; //idproduk

    $query = mysqli_query($c,"update produk set namaproduk='$np', deskripsi='$desc', harga='$harga' where idproduk='$idp' ");

    if($query){
        header('location:stok.php');
    }
    else{
        echo'
        <script>alert("Gagal");
        window.location.href="stok.php"
        </script>
        ';
    }
}

//hapus barang
if(isset($_POST['hapusbarang'])){
    $idp = $_POST['idp'];

    $query = mysqli_query($c,"delete from produk where idproduk='$idp'");

    if($query){
        header('location:stok.php');
    }
    else{
        echo'
        <script>alert("Gagal");
        window.location.href="stok.php"
        </script>
        ';
    }
}


//edit pelanggan
if(isset($_POST['editpelanggan'])){
    $npel = $_POST['namapelanggan'];
    $nt = $_POST['notelepon'];
    $a = $_POST['alamat'];
    $id = $_POST['idpl']; //idpelanggan

    $query = mysqli_query($c,"update pelanggan set namapelanggan='$npel', notelepon='$nt', alamat='$a' where idpelanggan='$id' ");

    if($query){
        header('location:pelanggan.php');
    }  
    else{
        echo'
        <script>alert("Gagal");
        window.location.href="pelanggan.php"
        </script>
        ';
    }
}

//hapus pelanggan
if(isset($_POST['hapuspelanggan'])){
    $idpl = $_POST['idpl'];

    $query = mysqli_query($c,"delete from pelanggan where idpelanggan='$idpl'");

    if($query){
        header('location:pelanggan.php');
    }
    else{
        echo'
        <script>alert("Gagal");
        window.location.href="pelanggan.php"
        </script>
        ';
    }
}

//edit data barang masuk
if(isset($_POST['editdatabarangmasuk'])){
    $qty = $_POST['qty'];
    $idp = $_POST ['idp']; //id produk
    $idm = $_POST['idm'];//id masuk

    //cari tahu qty sekarang
    $caritahu1 = mysqli_query($c,"select * from masuk where idmasuk='$idm'");
    $caritahu2 = mysqli_fetch_array($caritahu1);
    $qtysekarang = $caritahu2['qty'];

    //caritahu stok sekarang
    $caristok = mysqli_query($c, "select * from produk where idproduk='$idp'");
    $caristok2 = mysqli_fetch_array($caristok);
    $stoksekarang = $caristok2['stok'];


    if($qty >= $qtysekarang){
        //inputan user lebih besar daripada qty yang tercatat
        //hitung selisih
        $selisih = $qty-$qtysekarang; 
        $newstok = $stoksekarang+$selisih;

        $query1 = mysqli_query($c,"update masuk set qty='$qty' where idmasuk='$idm'");
        $query2 = mysqli_query($c,"update produk set stok='$newstok' where idproduk='$idp'");

        if($query1&&$query2){
            header('location:masuk.php');
        }
        else{
            echo'
            <script>alert("Gagal");
            window.location.href="masuk.php"
            </script>
            ';
        }
    }
    
    else{
        //kalau lebih kecil
        //hitung selisih
        $selisih = $qtysekarang-$qty;
        $newstok = $stoksekarang-$selisih;

        $query1 = mysqli_query($c,"update masuk set qty='$qty' where idmasuk='$idm'");
        $query2 = mysqli_query($c,"update produk set stok='$newstok' where idproduk='$idp'");

        if($query1&&$query2){
            header('location:masuk.php');
        }
        else{
            echo'
            <script>alert("Gagal");
            window.location.href="masuk.php"
            </script>
            ';
        }
    }
}

// //delete data barang masuk
if(isset($_POST['hapusdatabarangmasuk'])){
    $idm = $_POST['idm'];
    $idp = $_POST['idp'];

     //cari tahu qty sekarang
     $caritahu = mysqli_query($c,"select * from masuk where idmasuk='$idm'");
     $caritahu2 = mysqli_fetch_array($caritahu);
     $qtysekarang = $caritahu2['qty'];
 
     //caritahu stok sekarang
     $caristok = mysqli_query($c, "select * from produk where idproduk='$idp'");
     $caristok2 = mysqli_fetch_array($caristok);
     $stoksekarang = $caristok2['stok'];
    

        //hitung selisih
        $newstok = $stoksekarang-$qtysekarang;

        $query1 = mysqli_query($c,"delete from masuk where idmasuk='$idm'");
        $query2 = mysqli_query($c,"update produk set stok='$newstok' where idproduk='$idp'");

    

        if($query1&&$query2){
            header('location:masuk.php');
        }
        else{
            echo'
            <script>alert("Gagal");
            window.location.href="masuk.php"
            </script>
            ';
        }

}


//hapus pesanan
if(isset($_POST['hapuspesanan'])){
    $idp = $_POST['idp'];

    $cekdata = mysqli_query($c, "select * from detailpesanan dp where idpesanan='$idp'");

    while($ok=mysqli_fetch_array($cekdata)){
        //balikin stok
        $qty = $ok['qty'];
        $idproduk = $ok['idproduk'];
        $iddp = $ok['iddetailpesanan'];
        
        //cari tahu stok sekarang berapa
        $caristok = mysqli_query($c, "select * from produk where idproduk='$idproduk'");
        $caristok2 = mysqli_fetch_array($caristok);
        $stoksekarang = $caristok2['stok'];

        $newstok = $stoksekarang+$qty;

        $queryupdate = mysqli_query($c,"update produk set stok='$newstok' where idproduk='$idproduk'");

        //hapus data
        $querydelete = mysqli_query($c,"delete from detailpesanan where iddetailpesanan='$iddp'");

    }

    $query = mysqli_query($c,"delete from pesanan where idpesanan='$idp'");

    if($queryupdate && $querydelete && $query){
        header('location:index.php');
    }
    else{
        echo'
        <script>alert("Gagal");
        window.location.href="index.php"
        </script>
        ';
    }
}


//edit data detail pesanan
if(isset($_POST['editdetailpesanan'])){
    $qty = $_POST['qty'];
    $iddp = $_POST ['iddp']; //id detailpesanan
    $idpr = $_POST['idpr'];//id produk
    $idp = $_POST['idp'];


    //cari tahu qty sekarang
    $caritahu1 = mysqli_query($c,"select * from detailpesanan where iddetailpesanan='$iddp'");
    $caritahu2 = mysqli_fetch_array($caritahu1);
    $qtysekarang = $caritahu2['qty'];

    //caritahu stok sekarang
    $caristok = mysqli_query($c, "select * from produk where idproduk='$idpr'");
    $caristok2 = mysqli_fetch_array($caristok);
    $stoksekarang = $caristok2['stok'];


    if($qty >= $qtysekarang){
        //inputan user lebih besar daripada qty yang tercatat
        //hitung selisih
        $selisih = $qty-$qtysekarang; 
        $newstok = $stoksekarang+$selisih;

        $query1 = mysqli_query($c,"update detailpesanan set qty='$qty' where iddetailpesanan='$iddp'");
        $query2 = mysqli_query($c,"update produk set stok='$newstok' where idproduk='$idpr'");

        if($query1&&$query2){
            header('location:view.php?idp='.$idp);
        }
        else{
            echo'
            <script>alert("Gagal");
            window.location.href="view.php?idp='.$idp.'"
            </script>
            ';
        }
    }
    
        else{
        //kalau lebih kecil
        //hitung selisih
        $selisih = $qtysekarang-$qty;
        $newstok = $stoksekarang+$selisih;

        $query1 = mysqli_query($c,"update detailpesanan set qty='$qty' where iddetailpesanan='$iddp'");
        $query2 = mysqli_query($c,"update produk set stok='$newstok' where idproduk='$idpr'");

        if($query1&&$query2){
            header('location:view.php?idp='.$idp);
        }
        else{
            echo'
            <script>alert("Gagal");
            window.location.href="view.php?idp='.$idp.'"
            </script>
            ';
        }
    }
}

if(isset($_POST['transaksi'])){
    $idtransaksi = $_POST['idtransaksi'];
    $namapelanggan = $_POST['namapelanggan'];
    $iddetailpesanan = $_POST['iddetailpesanan'];
    $alamat = $_POST['alamat'];
    $qty = $p['qty'];
    $tanggal = $p['tanggal'];
    $total = $p['total'];
   
    $insert = mysqli_query($c,"insert into transaksi (idtransaksi,iddetailpesanan,idpelanggan,namapelanggan,alamat,qty,tanggal,total) values ('$idtransaksi','$idpesanan','$namapelanggan','$alamat','$qty','$tanggal','$total',)");

    if($insert){
        header('location:transaksi.php');
    }
    else{
        //data tak ditemukan
        //gagal menambah produk
        echo'
        <script>alert("Gagal Menambah Pelanggan Baru, Mohon Masukan Kembali");
        window.location.href="view.php"
        </script>
        ';
    }
    
}




?>