<script type='text/javascript'>
    var rank = document.getElementsByClassName('info');
    var selectDebug = document.getElementById('debugRole');
    if (rank[1].innerHTML == 'Admin') {
        selectDebug.selectedIndex = "1";
    } else if (rank[1].innerHTML == 'Keuangan') {
        selectDebug.selectedIndex = "2";
    } else if (rank[1].innerHTML == 'Gudang') {
        selectDebug.selectedIndex = "3";
    } else {
        selectDebug.selectedIndex = "0";
    }
</script>

</html>