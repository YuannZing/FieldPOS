@extends('layouts.master')

@section('title')
    Daftar Kategori Lapangan
@endsection

@section('breadcrumb')
    @parent
    <li class="active">Daftar Kategori Lapangan</li>
@endsection

@section('content')
    <div class="row">
        <div class="col-lg-6">
            <div class="box">
                <div class="box-header">
                    <h3 class="box-title">Lapangan 1</h3>
                </div>
                <!-- /.box-header -->
                <div class="box-body">
                    <table id="kategoriTable" class="table table-bordered table-hover">
                        <thead>
                            <tr>
                                <th width="5%">No</th>
                                <th>Jadwal Jam</th>
                                <th width="15%">Status</th>
                            </tr>
                        </thead>
                    </table>
                </div>
                <!-- /.box-body -->
            </div>
        </div>
    </div>

    @includeIf('kategori_lapangan.form')
@endsection

@push('scripts')
    <script>
        let table;

        $(function() {
            table = $('#kategoriTable').DataTable({
                responsive: true,
                processing: true,
                serverSide: true,
                autoWidth: false,
                ajax: {
                    url: '{{ route('kategori_lapangan.data') }}',
                    type: 'GET'
                },
                columns: [
                    { data: 'DT_RowIndex', searchable: false, sortable: false },
                    { data: 'nama_kategori_lapangan' },
                    { data: 'aksi', searchable: false, sortable: false }
                ],
                // Menyembunyikan fitur pencarian, paging, info, dan sorting
                searching: false, // Menyembunyikan kolom pencarian
                paging: false, // Menyembunyikan fitur paging
                info: false, // Menyembunyikan informasi tabel
                ordering: false // Menyembunyikan fitur sorting pada seluruh kolom
            });

            $('#modal-form').validator().on('submit', function(e) {
                if (!e.isDefaultPrevented()) {
                    $.post($('#modal-form form').attr('action'), $('#modal-form form').serialize())
                        .done((response) => {
                            $('#modal-form').modal('hide');
                            table.ajax.reload();
                        })
                        .fail((errors) => {
                            alert('Tidak dapat menyimpan data');
                        });
                }
            });
        });

        function addForm(url) {
            $('#modal-form').modal('show');
            $('#modal-form .modal-title').text('Tambah Kategori Lapangan');

            $('#modal-form form')[0].reset();
            $('#modal-form form').attr('action', url);
            $('#modal-form [name=_method]').val('post');
            $('#modal-form [name=nama_kategori_lapangan]').focus();
        }

        function editForm(url) {
            $('#modal-form').modal('show');
            $('#modal-form .modal-title').text('Edit Kategori Lapangan');

            $('#modal-form form')[0].reset();
            $('#modal-form form').attr('action', url);
            $('#modal-form [name=_method]').val('put');
            $('#modal-form [name=nama_kategori_lapangan]').focus();

            $.get(url)
                .done((response) => {
                    $('#modal-form [name=nama_kategori_lapangan]').val(response.nama_kategori_lapangan);
                })
                .fail((errors) => {
                    alert('Tidak dapat menampilkan data');
                });
        }

        function deleteData(url) {
            if (confirm('Yakin ingin menghapus data terpilih?')) {
                $.post(url, {
                        '_token': $('[name=csrf-token]').attr('content'),
                        '_method': 'delete'
                    })
                    .done((response) => {
                        table.ajax.reload();
                    })
                    .fail((errors) => {
                        alert('Tidak dapat menghapus data');
                    });
            }
        }
    </script>
@endpush


