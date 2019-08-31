<li class="list-group-item d-flex justify-content-between align-items-center" data-order-detail-id="<?= $card_detail['order_detail_id']; ?>">

    <div class="d-flex align-items-center">
        <a class="dropdown-toggle mr-3" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown">
            <img style="width:33px;style:33px" src="<?= base_url("assets/icon/{$card_detail['icon']}"); ?>.png" alt="">
        </a>
        <div class="dropdown-menu dropdown-menu-right shadow animated--fade-in">
            <div class="dropdown-header">Tandai:</div>
            <a href="#" class="dropdown-item pending">Menunggu <?= $card_detail['status'] == 'menunggu' ? '&#10003;' : ''; ?></a>
            <a href="#" class="dropdown-item processed">Dikerjakan <?= $card_detail['status'] == 'dikerjakan' ? '&#10003;' : ''; ?></a>
            <a href="#" class="dropdown-item done">Selesai <?= $card_detail['status'] == 'selesai' ? '&#10003;' : ''; ?></a>
        </div>
        <div>
            <div style="color:#9aa0ac;font-size:13px">Diambil: <?= date('d-M-Y', strtotime($card_detail['required_date'])); ?></div>
            <div style="color:#495057;font-size:15px"><?= $card_detail['item_desc']; ?></div>
        </div>
    </div>

    <?php if (strtolower($title) == 'bordir') : ?>
        <div class="w-50">
            <div style="color:#9aa0ac;font-size:13px" class="pb-1">42% (42/100pcs)</div>
            <div class="progress">
                <div class="progress-bar" role="progressbar" style="width:42%"></div>
            </div>
        </div>
    <?php endif; ?>

    <div>
        <i class="<?= $card_detail['status'] == 'dikerjakan' ? 'fas fa-circle-notch fa-lg fa-spin' : 'far fa-clock fa-lg';  ?>"></i>
    </div>

</li>