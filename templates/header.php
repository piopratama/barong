<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo isset($title) ? $title : ""; ?></title>
    <link rel="stylesheet" type="text/css" href="./assets/jquery.dataTables.min.css">
    <link rel="stylesheet" href="./assets/bootstrap3.3.7/css/bootstrap.min.css">
    <link href="https://fonts.googleapis.com/css?family=Slabo+27px" rel="stylesheet">
    <link href="./assets/select2.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="./assets/chart.css">
    <script src="./assets/pace.js"></script>
    <link rel="stylesheet" href="./assets/pace.css">

    <style>
        label, input {
            letter-spacing:1px !important;
        }
        .header {
            border-radius:0px;
            padding-left:0px;
            padding-right:0px;
        }
        .navbar-default {
            background-color: #38B593;
            border-radius:0px;
            height:80px;
        }

        .navbar-brand {
            font-size: 40px;
            margin-top:15px; 
            color:black !important;   
        }

        .btn {
            border: none;
        }
    </style>

    <script>
        function isNumberKey(evt){
					var charCode = (evt.which) ? evt.which : event.keyCode
					console.log(charCode);
					if ((charCode <= 57 && charCode >= 48) || charCode == 46)
						return true;
					return false;
				}

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
            var d = new Date(); // for now
            return day + ' ' + monthNames[monthIndex] + ' ' + year+' '+d.getHours()+':'+d.getMinutes()+':'+d.getSeconds();
        }

       
    </script>
    <?php
        function rupiah($angka){
	
            $hasil_rupiah = number_format($angka,0,'','.');
            return $hasil_rupiah;
         
        }
    ?>
</head>