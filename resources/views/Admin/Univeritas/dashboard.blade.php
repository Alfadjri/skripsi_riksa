@extends('Admin.Template.app')
@section('title')
Univeritas > Dashboard
@endsection
@section('content')
<div class="container-fluid">
    <div class="row mb-3">
        <div class="col-10">
            <form action="{{route('admin.univeritas.dashboard', ['limit_per_page' => 8])}}" method="get">
                @csrf
                @method('GET')
                <div class="input-group">
                    <input type="text" class="form-control" name="search" placeholder="Masukan nama kampus yang mau di cari "
                        aria-label="Recipient's username" aria-describedby="button-addon2">
                    <input type="hidden" name="limit_per_page" value="8" />
                    <button class="btn btn-outline-secondary" type="submit" id="button-addon2"><i
                            class="fa-solid fa-magnifying-glass"></i></button>
                </div>
            </form>

        </div>
        <div class="col-2 d-flex justify-content-end align-items-start">
            <a href="{{route('admin.univeritas.dashboard.create.page')}}" type="button"
                class="btn btn-primary w-100">Create Univeritas</a>
        </div>
    </div>
    <div class="row row-cols-1 row-cols-md-4 g-4">
        @if($universitas->total() != 0)
            @foreach ($universitas as $value)
                <div class="col">
                    <div class="card" style="width: 18rem;" data-id="{{ $value['id'] }}">
                        <div class="card-body">
                            <div class="row mb-3">
                                <div class="col">
                                    @if($value['image_logo'] == null)
                                        <img src="https://stmik-amikbandung.ac.id/wp-content/uploads/2021/05/putih-300x285.png"
                                            class="card-img-top" alt="logo_universitas" width="30">
                                    @else
                                        <img src="{{ $value['image_logo'] }}" class="card-img-top" alt="logo_universitas"
                                            width="30">
                                    @endif
                                </div>
                                <div class="col d-flex align-items-center justify-content-center">
                                    <h5>{{ $value['nama_kampus'] }}</h5>
                                </div>
                            </div>
                            <div class="row mb-1">
                                <div class="col text-start">
                                    <p class="truncatedText">{{ $value['alamat'] }}</p>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col text-start">
                                    <p>Akreditas : <b class="akreditasi">{{ $value['akreditasi'] }}</b></p>
                                </div>
                            </div>
                            <input type="hidden" class="image" value="{{$value['image_logo']}}">
                            <input type="hidden" class="jurusan"
                                value="{{$value->jurusan()->pluck('jurusan_universitas.nama_jurusan')}}">
                            <button type="button" class="rounded rounded-pill btn btn-primary w-100 mb-3">Detail</button>
                        </div>
                    </div>
                </div>
            @endforeach
        @endif

        <!-- Repeat for other cards -->
    </div>
    <div class="col d-flex justify-content-center ">
        {{$universitas->links('Layout.Pagination.pagination_card')}}
    </div>

    <!-- modal  -->
    <div class="modal fade" id="modal_create_jurusan" data-backdrop="static" tabindex="-1" role="dialog"
        aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="staticBackdropLabel">Detail Universitas</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col">
                            <img id="modal_image" class="card-img-top img-fluid" alt="logo_universitas">
                        </div>
                        <div class="col">
                            <div class="row mb-1">
                                <div class="col">
                                    <h6 class="mb-1"><strong>Nama Universitas:</strong></h6>
                                    <p id="modal_nama"></p>
                                </div>
                            </div>
                            <div class="row mb-1">
                                <div class="col">
                                    <h6><strong>Akreditas: </strong><span id="modal_akreditasi"></span></h6>
                                </div>
                            </div>
                            <div class="row mb-1">
                                <div class="col">
                                    <h6 class="mb-1"><strong>Jurusan:</strong></h6>
                                    <p id="modal_jurusan"></p>
                                </div>
                            </div>
                            <div class="row mb-1">
                                <div class="col">
                                    <h6 class="mb-1"><strong>Alamat:</strong></h6>
                                    <p id="modal_alamat"></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <div class="col d-flex justify-content-between">
                        <form action="{{route('admin.univeritas.dashboard.update.page')}}" method="get">
                            <input type="hidden" name="id" id="modal_id">
                            <button type="submit" class="btn btn-primary" data-bs-dismiss="modal">Update</button>
                        </form>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection
@section('script')
<script>
    function truncateText(text, wordLimit) {
        const words = text.split(' ');
        if (words.length <= wordLimit) {
            return text;
        }

        return words.slice(0, wordLimit).join(' ') + '...';
    }
    const elements = document.querySelectorAll('.truncatedText');
    elements.forEach(element => {
        const fullText = element.textContent;
        element.textContent = truncateText(fullText, 15);
    });
    document.addEventListener('DOMContentLoaded', function () {
        var detailButtons = document.querySelectorAll('.btn-primary');

        detailButtons.forEach(function (button) {
            button.addEventListener('click', function () {
                var card = this.closest('.card');
                var id = card.getAttribute('data-id');
                var nama = card.querySelector('.card-body h5').textContent;
                var akreditasi = card.querySelector('.akreditasi').textContent;
                var alamat = card.querySelector('.truncatedText').textContent;
                var image = card.querySelector('.image').value;
                var jurusan = card.querySelector('.jurusan').value;
                var jurusanArray = JSON.parse(jurusan);
                var jurusanText = jurusanArray.join(', ');

                document.getElementById('modal_id').value = id;
                document.getElementById('modal_nama').textContent = nama;
                document.getElementById('modal_akreditasi').textContent = akreditasi;
                document.getElementById('modal_alamat').textContent = alamat;
                document.getElementById('modal_image').src = image;
                document.getElementById('modal_jurusan').textContent = jurusanText;
                var modal = new bootstrap.Modal(document.getElementById('modal_create_jurusan'));
                modal.show();
            });
        });
    });
</script>
@endsection