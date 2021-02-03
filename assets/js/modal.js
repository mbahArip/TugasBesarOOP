function openModal(modalID) {
    var modalContainer = document.querySelector('.modal');
    var modal = document.getElementById(modalID);

    modalContainer.style.display = 'flex';
    modal.style.display = 'flex';
}

function openEdit(modalID, buttonID, tableID, areaID, i) {
    var modalContainer = document.querySelector('.modal');
    var modal = document.getElementById(modalID);
    var table = document.getElementById(tableID);

    modalContainer.style.display = 'flex';
    modal.style.display = 'flex';

    var index = buttonID.parentNode.parentNode.rowIndex;
    document.getElementById(areaID).innerHTML = table.rows[index].cells[i].innerHTML;
    document.getElementById('edit-notesID').value = table.rows[index].cells[0].innerHTML;
}
function openDelete(modalID, buttonID, tableID) {
    var modalContainer = document.querySelector('.modal');
    var modal = document.getElementById(modalID);
    var table = document.getElementById(tableID);

    modalContainer.style.display = 'flex';
    modal.style.display = 'flex';

    var index = buttonID.parentNode.parentNode.rowIndex;
    document.getElementById('delete-notesID').value = table.rows[index].cells[0].innerHTML;
}

function employeeEdit(modalID, buttonID, tableID) {
    var modalContainer = document.querySelector('.modal');
    var modal = document.getElementById(modalID);
    var table = document.getElementById(tableID);

    modalContainer.style.display = 'flex';
    modal.style.display = 'flex';

    var index = buttonID.parentNode.parentNode.rowIndex;
    document.getElementById('edit-id').value = table.rows[index].cells[0].innerHTML; 
    document.getElementById('edit-nama').value = table.rows[index].cells[1].innerHTML; 
    document.getElementById('edit-email').value = table.rows[index].cells[3].innerHTML; 
    document.getElementById('edit-alamat').value = table.rows[index].cells[4].innerHTML; 
    document.getElementById('edit-telp').value = table.rows[index].cells[5].innerHTML; 
    if (table.rows[index].cells[2].innerHTML == 'admin' || table.rows[index].cells[2].innerHTML == 'superAdmin') {
        document.getElementById('edit-posisi').selectedIndex = 0;
    } else if (table.rows[index].cells[2].innerHTML == 'keuangan') {
        document.getElementById('edit-posisi').selectedIndex = 1;
    } else if (table.rows[index].cells[2].innerHTML == 'gudang') {
        document.getElementById('edit-posisi').selectedIndex = 2;
    } else {
        document.getElementById('edit-posisi').selectedIndex = 0;
    }
}
function employeeDelete(modalID, buttonID, tableID) {
    var modalContainer = document.querySelector('.modal');
    var modal = document.getElementById(modalID);
    var table = document.getElementById(tableID);

    modalContainer.style.display = 'flex';
    modal.style.display = 'flex';

    var index = buttonID.parentNode.parentNode.rowIndex;
    document.getElementById('delete-id').value = table.rows[index].cells[0].innerHTML;
}
function employeeSalary(modalID, buttonID, tableID) {
    var modalContainer = document.querySelector('.modal');
    var modal = document.getElementById(modalID);
    var table = document.getElementById(tableID);

    modalContainer.style.display = 'flex';
    modal.style.display = 'flex';

    var index = buttonID.parentNode.parentNode.rowIndex;
    document.getElementById('salary-id').value = table.rows[index].cells[0].innerHTML;
    document.getElementById('salary-number').value = table.rows[index].cells[6].innerHTML.substring(4); 
}

function barangEdit(modalID, buttonID, tableID) {
    var modalContainer = document.querySelector('.modal');
    var modal = document.getElementById(modalID);
    var table = document.getElementById(tableID);

    modalContainer.style.display = 'flex';
    modal.style.display = 'flex';

    var index = buttonID.parentNode.parentNode.rowIndex;
    document.getElementById('edit-id').value = table.rows[index].cells[0].innerHTML;
    document.getElementById('edit-nama').value = table.rows[index].cells[1].innerHTML;
    document.getElementById('edit-harga').value = table.rows[index].cells[2].innerHTML;
    document.getElementById('edit-stok').value = table.rows[index].cells[3].innerHTML;
}
function barangDelete(modalID, buttonID, tableID){
    var modalContainer = document.querySelector('.modal');
    var modal = document.getElementById(modalID);
    var table = document.getElementById(tableID);

    modalContainer.style.display = 'flex';
    modal.style.display = 'flex';

    var index = buttonID.parentNode.parentNode.rowIndex;
    document.getElementById('delete-id').value = table.rows[index].cells[0].innerHTML;
}
function barangReq(modalID, buttonID, tableID) {
    var modalContainer = document.querySelector('.modal');
    var modal = document.getElementById(modalID);
    var table = document.getElementById(tableID);

    modalContainer.style.display = 'flex';
    modal.style.display = 'flex';

    var index = buttonID.parentNode.parentNode.rowIndex;
    document.getElementById('req-id').value = table.rows[index].cells[0].innerHTML;
    document.getElementById('req-nama').value = table.rows[index].cells[1].innerHTML;
    document.getElementById('req-harga').value = table.rows[index].cells[2].innerHTML;
}

function closeModal(modalID) {
    var modal = document.getElementById(modalID);
    var modalContainer = document.querySelector('.modal');

    modalContainer.style.display = "none";
    modal.style.display = "none";
}