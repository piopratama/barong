<script src="https://cdn.jsdelivr.net/npm/recta/dist/recta.js"></script>
<script src="./assets/jquery.js"></script>
<!-- Latest compiled and minified JavaScript -->
<!--<script src="./assets/bootstrap3.3.7/js/bootstrap.min.js"></script>-->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/js/bootstrap.min.js"></script>0
<script src="./assets/select2.min.js"></script>
<script src="./assets/jquery.dataTables.min.js"></script>
<script src="./assets/chart.js"></script>
<script src="./assets/chart.js"></script>
<script src="./assets/jquery.scannerdetection.compatibility.js"></script>
<script src="./assets/jquery.scannerdetection.js"></script>
<script>
    $(document).ajaxStart(function() { Pace.restart(); });
    
    function formatDate(date) {
            var monthNames = [
                "January", "February", "March",
                "April", "May", "June", "July",
                "August", "September", "October",
                "November", "December"
            ];

            var day = date.getDate();
            var monthIndex = date.getMonth();
            var year = date.getFullYear();

            return day + ' ' + monthNames[monthIndex] + ' ' + year;
        }   
    /*var rupiah = document.getElementById("rupiah");
    rupiah.addEventListener("keyup", function(e) {
    // tambahkan 'Rp.' pada saat form di ketik
    // gunakan fungsi formatRupiah() untuk mengubah angka yang di ketik menjadi format angka
    rupiah.value = formatRupiah(this.value, "Rp. ");
    });

    function formatRupiah(angka, prefix) {
    var number_string = angka.replace(/[^,\d]/g, "").toString(),
        split = number_string.split(","),
        sisa = split[0].length % 3,
        rupiah = split[0].substr(0, sisa),
        ribuan = split[0].substr(sisa).match(/\d{3}/gi);

    // tambahkan titik jika yang di input sudah menjadi angka ribuan
    if (ribuan) {
        separator = sisa ? "." : "";
        rupiah += separator + ribuan.join(".");
    }

    rupiah = split[1] != undefined ? rupiah + "," + split[1] : rupiah;
    return prefix == undefined ? rupiah : rupiah ? "Rp. " + rupiah : "";
    }*/

</script>