<li class="list-group-item d-flex justify-content-between align-items-center" data-order-detail-id="<?= $card_detail['order_detail_id']; ?>">
    <div class="d-flex align-items-center">
        <a class="dropdown-toggle text-right mr-2" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown">
            <i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400 process-action"></i>
        </a>
        <div class="dropdown-menu dropdown-menu-right shadow animated--fade-in">
            <div class="dropdown-header">Tandai:</div>
            <a href="#" class="dropdown-item pending">Menunggu <?= $card_detail['status'] == 'menunggu' ? '&#10003;' : '';?></a>
            <a href="#" class="dropdown-item processed">Dikerjakan <?= $card_detail['status'] == 'dikerjakan' ? '&#10003;' : '';?></a>
            <a href="#" class="dropdown-item done">Selesai <?= $card_detail['status'] == 'selesai' ? '&#10003;' : '';?></a>
        </div>
        <div>
            <div style="color:#9aa0ac;font-size:13px">Diambil: <?= date('d-M-Y', strtotime($card_detail['required_date'])); ?></div>
            <div style="color:#495057;font-size:15px"><?= $card_detail['item_desc']; ?></div>
        </div>
    </div>
    <div class="w-50">
        <div class="clearfix">
            <div class="float-left">
                <small class="text-muted">42% (42/100pcs)</small>
            </div>
        </div>
        <div class="progress">
            <div class="progress-bar bg-yellow" role="progressbar" style="width: 42%" aria-valuenow="42" aria-valuemin="0" aria-valuemax="100"></div>
        </div>
    </div>
    <div>
        <i class="<?= $card_detail['status'] == 'dikerjakan' ? 'fas fa-circle-notch fa-lg fa-spin' : 'far fa-clock fa-lg';  ?>"></i>
    </div>
</li>