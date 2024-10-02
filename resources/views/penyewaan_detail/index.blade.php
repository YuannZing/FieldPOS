@extends('layouts.master')

@section('title')
    Transaksi Penyewaan
@endsection

@push('css')
<style>
    .tampil-bayar {
        font-size: 5em;
        text-align: center;
        height: 100px;
    }

    .tampil-terbilang {
        padding: 10px;
        background: #f0f0f0;
    }

    .table-penyewaan tbody tr:last-child {
        display: none;
    }

    @media(max-width: 768px) {
        .tampil-bayar {
            font-size: 3em;
            height: 70px;
            padding-top: 5px;
        }
    }
</style>
@endpush

@section('breadcrumb')
    @parent
    <li class="active">Transaksi Penyewaan</li>
@endsection

@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="box">
            <div class="box-body">
                    
                <form class="form-lapangan">
                    @csrf
                    <div class="form-group row">
                        <label for="nama1_lapangan" class="col-lg-2">Nama Lapangan</label>
                        <div class="col-lg-5">
                            <div class="input-group">
                                <input type="hidden" name="id_penyewaan" id="id_penyewaan" value="{{ $id_penyewaan }}">
                                <input type="hidden" name="id_lapangan" id="id_lapangan">
                                <input type="text" class="form-control" name="nama_lapangan" id="nama_lapangan">
                                <span class="input-group-btn">
                                    <button onclick="tampilLapangan()" class="btn btn-info btn-flat" type="button"><i class="fa fa-arrow-right"></i></button>
                                </span>
                            </div>
                        </div>
                    </div>
                </form>

                <table class="table table-stiped table-bordered table-penyewaan">
                    <thead>
                        <th width="5%">No</th>
                        <th>Nama</th>
                        <th>Harga Sewa</th>
                        <th width="15%">Durasi</th>
                        <th>Subtotal</th>
                        <th width="15%"><i class="fa fa-cog"></i></th>
                    </thead>
                </table>

                <div class="row">
                    <div class="col-lg-8">
                        <div class="tampil-bayar bg-primary"></div>
                        <div class="tampil-terbilang"></div>
                    </div>
                    <div class="col-lg-4">
                        <form action="{{ route('transaksi_penyewaan.simpan') }}" class="form-penyewaan" method="post">
                            @csrf
                            <input type="hidden" name="id_penyewaan" value="{{ $id_penyewaan }}">
                            <input type="hidden" name="total" id="total">
                            <input type="hidden" name="total_durasi" id="total_durasi">
                            <input type="hidden" name="bayar" id="bayar">
                            <input type="hidden" name="id_member" id="id_member" value="{{ $memberSelected->id_member }}">
                        
                            <div class="form-group row">
                                <label for="totalrp" class="col-lg-2 control-label">Total</label>
                                <div class="col-lg-8">
                                    <input type="text" id="totalrp" class="form-control" readonly>
                                </div>
                            </div>
                            
                            <div class="form-group row">
                                <label for="kode_member" class="col-lg-2 control-label">Member</label>
                                <div class="col-lg-8">
                                    <div class="input-group">
                                        <input type="text" class="form-control" id="kode_member" value="{{ $memberSelected->kode_member }}">
                                        <span class="input-group-btn">
                                            <button onclick="tampilMember()" class="btn btn-info btn-flat" type="button"><i class="fa fa-arrow-right"></i></button>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="form-group row">
                                <label for="diskon" class="col-lg-2 control-label">Diskon (%)</label>
                                <div class="col-lg-8">
                                    <input type="number" id="diskon" name="diskon" class="form-control" value="0">
                                </div>
                            </div>
                        
                            <div class="form-group row">
                                <label for="bayar" class="col-lg-2 control-label">Bayar</label>
                                <div class="col-lg-8">
                                    <input type="text" id="bayarrp" class="form-control" readonly>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="diterima" class="col-lg-2 control-label">Diterima</label>
                                <div class="col-lg-8">
                                    <input type="number" id="diterima" class="form-control" name="diterima" value="{{ $penyewaan->diterima ?? 0 }}">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="kembali" class="col-lg-2 control-label">Kembali</label>
                                <div class="col-lg-8">
                                    <input type="text" id="kembali" name="kembali" class="form-control" value="0" readonly>
                                </div>
                            </div>
                        </form>
                        
                    </div>
                </div>
            </div>

            <div class="box-footer">
                <button type="submit" class="btn btn-primary btn-sm btn-flat pull-right btn-simpan"><i class="fa fa-floppy-o"></i> Simpan Transaksi</button>
            </div>
        </div>
    </div>
</div>

@includeIf('penyewaan_detail.lapangan')
@includeIf('penyewaan_detail.member')
@endsection

@push('scripts')
{{-- <script>
    let table;

    $(function () {
        $('body').addClass('sidebar-collapse');

        table = $('.table-penyewaan').DataTable({
            responsive: true,
            processing: true,
            serverSide: true,
            autoWidth: false,
            ajax: {
                url: '{{ route('transaksi-penyewaan.data', $id_penyewaan) }}',
            },
            columns: [
                {data: 'DT_RowIndex', searchable: false, sortable: false},
                {data: 'nama_lapangan'},
                {data: 'harga_sewa'},
                {data: 'durasi'},
                {data: 'subtotal'},
                {data: 'aksi', searchable: false, sortable: false},
            ],
            dom: 'Brt',
            bSort: false,
            paginate: false
        })
        .on('draw.dt', function () {
            loadForm();
            setTimeout(() => {
                $('#diterima').trigger('input');
            }, 300);
        });

        $('.btn-simpan').on('click', function () {
            $('.form-penyewaan').submit();
        });

        $('#diterima').on('input', function () {
            if ($(this).val() == "") {
                $(this).val(0).select();
            }

            loadForm($(this).val());
        }).focus(function () {
            $(this).select();
        });
        $(document).on('input', '#diskon', function () {
            if ($(this).val() == "") {
                $(this).val(0).select();
            }

            loadForm($(this).val());
        });

        $('#diterima').on('input', function () {
            if ($(this).val() == "") {
                $(this).val(0).select();
            }

            loadForm($('#diskon').val(), $(this).val());
        }).focus(function () {
            $(this).select();
        });

        $('.btn-simpan').on('click', function () {
            $('.form-penjualan').submit();
        });
    });

    function tampilLapangan() {
        $('#modal-lapangan').modal('show');
    }

    function hideLapangan() {
        $('#modal-lapangan').modal('hide');
    }

    function pilihLapangan(id, nama) {
        $('#id_lapangan').val(id);
        $('#nama_lapangan').val(nama);
        hideLapangan();
        tambahLapangan();
    }

    function tambahLapangan() {
        $.post('{{ route('transaksi-penyewaan.store') }}', $('.form-lapangan').serialize())
            .done(response => {
                $('#nama_lapangan').focus();
                table.ajax.reload(() => loadForm());
            })
            .fail(errors => {
                alert('Tidak dapat menyimpan data');
                return;
            });
    }

    function tampilMember() {
        $('#modal-member').modal('show');
    }

    function pilihMember(id, kode) {
        $('#id_member').val(id);
        $('#kode_member').val(kode);
        loadForm();
        $('#diterima').val(0).focus().select();
        hideMember();
    }

    function hideMember() {
        $('#modal-member').modal('hide');
    }

    function deleteData(url) {
        if (confirm('Yakin ingin menghapus data terpilih?')) {
            $.post(url, {
                    '_token': $('[name=csrf-token]').attr('content'),
                    '_method': 'delete'
                })
                .done((response) => {
                    table.ajax.reload(() => loadForm());
                })
                .fail((errors) => {
                    alert('Tidak dapat menghapus data');
                    return;
                });
        }
    }

    function loadForm(diskon = 0, diterima = 0) {
        $('#total').val($('.total').text());
        $('#total_durasi').val($('.total_durasi').text());

    // Panggil API untuk mendapatkan perhitungan
    $.get(`{{ url('/transaksi-penyewaan/loadform') }}/${diskon}/${$('.total').text()}/${diterima}`)
        .done(response => {
            $('#totalrp').val('Rp. '+ response.totalrp);
            $('#bayarrp').val('Rp. '+ response.bayarrp);
            $('#bayar').val(response.bayar);
            $('.tampil-bayar').text('Bayar: Rp. '+ response.bayarrp);
            $('.tampil-terbilang').text(response.terbilang);

            $('#kembali').val('Rp.'+ response.kembalirp);
            if ($('#diterima').val() != 0) {
                $('.tampil-bayar').text('Kembali: Rp. '+ response.kembalirp);
                $('.tampil-terbilang').text(response.kembali_terbilang);
            }
        })
        .fail(errors => {
            alert('Tidak dapat menampilkan data');
            return;
        });
}
</script> --}}
<script>
    let table, table2;

    $(function () {
        $('body').addClass('sidebar-collapse');

        // Inisialisasi DataTable untuk penyewaan
        table = $('.table-penyewaan').DataTable({
            responsive: true,
            processing: true,
            serverSide: true,
            autoWidth: false,
            ajax: {
                url: '{{ route('transaksi_penyewaan.data', $id_penyewaan) }}',
            },
            columns: [
                {data: 'DT_RowIndex', searchable: false, sortable: false},
                {data: 'nama_lapangan'},
                {data: 'harga_sewa'},
                {data: 'durasi'},
                {data: 'subtotal'},
                {data: 'aksi', searchable: false, sortable: false},
            ],
            dom: 'Brt',
            bSort: false,
            paginate: false
        })
        .on('draw.dt', function () {
            loadForm();
            setTimeout(() => {
                $('#diterima').trigger('input');
            }, 300);
        });
        table2 = $('.table-lapangan').DataTable();


        // Input durasi durasi (atau item sewa) yang dapat diubah langsung
        $(document).on('input', '.quantity', function () {
            let id = $(this).data('id');
            let durasi = parseInt($(this).val());

            if (durasi < 1) {
                $(this).val(1);
                alert('Durasi tidak boleh kurang dari 1');
                return;
            }
            if (durasi > 10000) {
                $(this).val(10000);
                alert('Durasi tidak boleh lebih dari 10000');
                return;
            }

            $.post(`{{ url('/transaksi_penyewaan') }}/${id}`, {
                    '_token': $('[name=csrf-token]').attr('content'),
                    '_method': 'put',
                    'durasi': durasi
                })
                .done(response => {
                    table.ajax.reload(() => loadForm($('#diskon').val()));
                })
                .fail(errors => {
                    console.log(errors)
                    alert('Tidak dapat menyimpan data');
                });
        });

        // Input diskon untuk penyewaan
        $(document).on('input', '#diskon', function () {
            if ($(this).val() == "") {
                $(this).val(0).select();
            }

            loadForm($(this).val());
        });

        // Input diterima, untuk perhitungan kembalian
        $('#diterima').on('input', function () {
            if ($(this).val() == "") {
                $(this).val(0).select();
            }

            loadForm($('#diskon').val(), $(this).val());
        }).focus(function () {
            $(this).select();
        });

        // Tombol simpan untuk menyelesaikan transaksi
        $('.btn-simpan').on('click', function () {
            $('.form-penyewaan').submit();
        });
    });

    // Fungsi untuk menampilkan modal lapangan
    function tampilLapangan() {
        $('#modal-lapangan').modal('show');
    }

    function hideLapangan() {
        $('#modal-lapangan').modal('hide');
    }

    // Pilih lapangan saat ditambahkan
    function pilihLapangan(id, nama) {
        $('#id_lapangan').val(id);
        $('#nama_lapangan').val(nama);
        hideLapangan();
        tambahLapangan();
    }

        function tambahLapangan() {
        // if ($('#id_lapangan').val() === '' || $('#nama_lapangan').val() === '') {
        //     alert('Silakan pilih lapangan terlebih dahulu');
        //     return;
        // }
        
        $.post('{{ route('transaksi_penyewaan.store') }}', $('.form-lapangan').serialize())
            .done(response => {
                console.log(response);
                $('#nama_lapangan').focus();
                table.ajax.reload(() => loadForm($('#diskon').val()));
            })
            .fail(errors => {
                console.log(errors);  // Untuk debugging error dari server
                alert('Tidak dapat menyimpan data');
            });
    }


    // Fungsi untuk menampilkan modal member
    function tampilMember() {
        $('#modal-member').modal('show');
    }

    function pilihMember(id, kode) {
        $('#id_member').val(id);
        $('#kode_member').val(kode);
        $('#diskon').val('{{ $diskon }}'); // Set diskon dari member jika ada
        loadForm($('#diskon').val());
        $('#diterima').val(0).focus().select();
        hideMember();
    }

    function hideMember() {
        $('#modal-member').modal('hide');
    }

    // Fungsi untuk menghapus data penyewaan
    function deleteData(url) {
        if (confirm('Yakin ingin menghapus data terpilih?')) {
            $.post(url, {
                    '_token': $('[name=csrf-token]').attr('content'),
                    '_method': 'delete'
                })
                .done((response) => {
                    table.ajax.reload(() => loadForm($('#diskon').val()));
                })
                .fail((errors) => {
                    console.log(errors)
                    alert('Tidak dapat menghapus data');
                    return;
                });
        }
    }

    // Fungsi untuk menghitung total dan diskon
    function loadForm(diskon = 0, diterima = 0) {
        let total = $('.total').text() || 0;  // Default ke 0 jika kosong
        let totalItem = $('.total_item').text() || 0;

        $('#total').val($('.total').text());
        $('#total_durasi').val($('.total_durasi').text());

    $.get(`{{ url('/transaksi_penyewaan/loadform') }}/${diskon}/${total}/${diterima}`)
        .done(response => {
            $('#totalrp').val('Rp. '+ response.totalrp);
            $('#bayarrp').val('Rp. '+ response.bayarrp);
            $('#bayar').val(response.bayar);
            $('.tampil-bayar').text('Bayar: Rp. '+ response.bayarrp);
            $('.tampil-terbilang').text(response.terbilang);

            $('#kembali').val('Rp.'+ response.kembalirp);
            if ($('#diterima').val() != 0) {
                $('.tampil-bayar').text('Kembali: Rp. '+ response.kembalirp);
                $('.tampil-terbilang').text(response.kembali_terbilang);
            }
        })
        .fail(errors => {
            console.log(errors)
            alert('Tidak dapat menampilkan data');
            return;
        });
}

</script>

@endpush
