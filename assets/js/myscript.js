const flashdata = $('.flashdata').data('flashdata');
console.log(flashdata);

if (flashdata) {
    Swal.fire({
        icon: 'success',
        title: 'Anda',
        text: 'Berhasil ' + flashdata

    });
}

$('.tombol-hapus').on('click', function(e) {

    e.preventDefault();
    const href = $(this).attr('href');

    Swal.fire({
        title: 'Yakin Hapus?',
        text: "Data akan dihapus!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Ya, hapus!'
    }).then((result) => {
        if (result.value) {
            document.location.href = href;
        }
    })
})