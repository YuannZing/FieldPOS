<div class="modal fade" id="modal-lapangan" tabindex="-1" role="dialog" aria-labelledby="modal-lapangan">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Pilih Lapangan</h4>
            </div>
            <div class="modal-body">
                <table class="table table-striped table-bordered table-lapangan">
                    <thead>
                        <th width="5%">No</th>
                        <th>Nama</th>
                        <th>Harga Sewa</th>
                        <th><i class="fa fa-cog"></i></th>
                    </thead>
                    <tbody>
                        @foreach ($lapangan as $key => $item)
                            <tr>
                                <td width="5%">{{ $key+1 }}</td>
                                <td>{{ $item->nama_lapangan }}</td>
                                <td>{{ $item->harga_sewa }}</td>
                                <td>
                                    <a href="#" class="btn btn-primary btn-xs btn-flat"
                                        onclick="pilihLapangan('{{ $item->id_lapangan }}', '{{ $item->nama_lapangan }}')">
                                        <i class="fa fa-check-circle"></i>
                                        Pilih
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>